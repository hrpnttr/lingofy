@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <!-- Results View (Hidden initially) -->
    <div id="results-view" class="hidden bg-white border border-gray-150 rounded-2xl shadow-sm p-8 text-center max-w-xl mx-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Test Completed!</h1>
        <p id="score-text" class="text-2xl font-bold text-blue-650 mt-2"></p>
        <p id="message-text" class="text-sm mt-4 text-gray-500 max-w-sm mx-auto leading-relaxed"></p>
        <p id="pending-note" class="hidden text-xs mt-4 text-amber-700 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 max-w-sm mx-auto leading-relaxed">
            Some of your answers are awaiting teacher review. The score shown is the automatic subtotal and may increase once they are graded.
        </p>
        
        <div class="mt-8">
            <a href="{{ route('placement-tests') }}" class="inline-block bg-blue-600 hover:bg-blue-750 text-white px-8 py-3.5 rounded-xl shadow-md font-bold transition text-sm cursor-pointer">
                Back to Tests
            </a>
        </div>
    </div>

    <!-- Quiz View -->
    <div id="quiz-view" class="bg-white border border-gray-150 rounded-2xl shadow-sm p-8 max-w-2xl mx-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
        
        <!-- Header: Progress and Back button -->
        <div class="flex items-center justify-between pb-6 border-b border-gray-50">
            <h2 id="quiz-title" class="text-lg font-bold text-gray-900 truncate max-w-[70%]">
                {{ $quiz->title }}
            </h2>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold">
                <span id="current-question-num">1</span> / <span id="total-questions-num">0</span>
            </div>
        </div>

        <!-- Progress bar -->
        <div class="w-full bg-gray-100 h-1 mt-0">
            <div id="progress-bar-fill" class="bg-blue-600 h-1 transition-all duration-300" style="width: 0%;"></div>
        </div>

        <!-- Question text -->
        <div class="py-8">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span id="question-points" class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 text-xs font-bold"></span>
                <span id="question-optional" class="hidden inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-50 text-gray-500 border border-gray-150 text-xs font-bold">Optional</span>
            </div>
            <h3 id="question-text" class="text-xl font-bold text-gray-900 text-center leading-relaxed min-h-[60px]"></h3>
            <p id="question-hint" class="hidden text-xs font-semibold text-gray-400 text-center mt-2"></p>
        </div>

        <!-- Answer area (rendered per question type) -->
        <div id="choices-container" class="grid grid-cols-1 gap-4">
            <!-- Dynamic answer controls -->
        </div>
        <p id="question-error" class="hidden text-sm font-semibold text-red-600 mt-4" role="alert"></p>

        <!-- Footer actions -->
        <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between">
            <div class="text-xs font-semibold text-gray-400">
                <span id="answered-count-text">0</span> of <span id="total-questions-footer-num">0</span> answered
            </div>
            
            <button
                id="next-btn"
                type="button"
                onclick="handleNext()"
                disabled
                class="bg-blue-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-blue-750 disabled:opacity-50 disabled:cursor-not-allowed font-bold transition duration-200 cursor-pointer text-sm"
            >
                Next
            </button>
        </div>
    </div>
</div>

<script>
    // Load data from PHP (answer keys are stripped server-side)
    const questions = @json($quiz->contens ?? []);
    const quizId = @json($quiz->id);
    const submitUrl = @json(route('quizzes.submit', $quiz->id));

    let currentIndex = 0;
    const totalQuestions = questions.length;
    let submitting = false;

    // Student responses keyed by question index. Value shape depends on type:
    // choice index (single_choice/dropdown/true_false), array of indexes (multiple_select),
    // string (short_text/long_answer/number input text).
    const responses = {};

    const ACTIVE_CHOICE = ['border-blue-600', 'bg-blue-50/50', 'text-blue-800'];
    const INPUT_CLASS = "w-full rounded-xl border border-gray-200 p-4 text-gray-700 shadow-sm transition focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 bg-white font-semibold text-sm";

    function questionType(q) {
        return (q && q.type) ? q.type : 'single_choice';
    }

    function questionPoints(q) {
        const p = parseInt(q && q.points, 10);
        return p >= 1 ? p : 1;
    }

    function isRequired(q) {
        return !(q && q.required === false);
    }

    function validationOf(q) {
        return (q && q.validation && typeof q.validation === 'object') ? q.validation : {};
    }

    function isAnswered(index) {
        const v = responses[index];
        if (v === undefined || v === null) return false;
        if (Array.isArray(v)) return v.length > 0;
        if (typeof v === 'string') return v.trim() !== '';
        return true;
    }

    // Initialize View on Page Load
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('total-questions-num').textContent = totalQuestions;
        document.getElementById('total-questions-footer-num').textContent = totalQuestions;
        renderQuestion();
    });

    function updateAnsweredCount() {
        let count = 0;
        for (let i = 0; i < totalQuestions; i++) {
            if (isAnswered(i)) count++;
        }
        document.getElementById('answered-count-text').textContent = count;
    }

    function setError(message) {
        const el = document.getElementById('question-error');
        el.textContent = message || '';
        el.classList.toggle('hidden', !message);
    }

    // Client-side checks are only for UX; the server re-validates everything.
    function currentInputError() {
        const q = questions[currentIndex];
        const type = questionType(q);
        const control = document.getElementById('answer-input');
        if (type === 'number' && control) {
            if (control.validity && control.validity.badInput) return 'Please enter a valid number.';
            if (control.value !== '' && !control.checkValidity()) return control.validationMessage || 'Please enter a valid number.';
        }
        return '';
    }

    function refreshNextState() {
        const q = questions[currentIndex];
        const ok = !submitting && (isAnswered(currentIndex) || !isRequired(q)) && currentInputError() === '';
        document.getElementById('next-btn').disabled = !ok;
        updateAnsweredCount();
    }

    // Render current question
    function renderQuestion() {
        if (currentIndex >= totalQuestions) return;

        const currentData = questions[currentIndex] || {};
        const type = questionType(currentData);
        const points = questionPoints(currentData);
        const validation = validationOf(currentData);

        setError('');

        document.getElementById('current-question-num').textContent = currentIndex + 1;
        document.getElementById('question-text').textContent = currentData.question || '';
        document.getElementById('question-points').textContent = points === 1 ? '1 pt' : `${points} pts`;
        document.getElementById('question-optional').classList.toggle('hidden', isRequired(currentData));

        const hint = document.getElementById('question-hint');
        hint.textContent = type === 'multiple_select' ? 'Select all that apply.' : '';
        hint.classList.toggle('hidden', hint.textContent === '');

        // Update progress bar
        const progressPercent = Math.round(((currentIndex) / totalQuestions) * 100);
        document.getElementById('progress-bar-fill').style.width = `${progressPercent}%`;

        const container = document.getElementById('choices-container');
        container.replaceChildren();
        container.removeAttribute('role');
        container.setAttribute('aria-labelledby', 'question-text');

        const choices = Array.isArray(currentData.choices) ? currentData.choices : [];

        if (type === 'multiple_select') {
            container.setAttribute('role', 'group');
            choices.forEach((choice, index) => {
                container.appendChild(buildChoiceButton(choice, index, true));
            });
        } else if (type === 'dropdown') {
            container.appendChild(buildDropdown(choices));
        } else if (type === 'short_text' || type === 'long_answer' || type === 'number') {
            container.appendChild(buildTextInput(type, validation));
        } else {
            // single_choice, true_false, and legacy questions
            container.setAttribute('role', 'radiogroup');
            choices.forEach((choice, index) => {
                container.appendChild(buildChoiceButton(choice, index, false));
            });
        }

        // Update Next button label
        const nextBtn = document.getElementById('next-btn');
        nextBtn.textContent = currentIndex === totalQuestions - 1 ? 'Submit Test' : 'Next Question';

        refreshNextState();
    }

    function choiceIsSelected(index, multi) {
        const v = responses[currentIndex];
        return multi ? (Array.isArray(v) && v.includes(index)) : v === index;
    }

    function styleChoiceButton(btn, selected) {
        ACTIVE_CHOICE.forEach(c => btn.classList.toggle(c, selected));
        const indicator = btn.querySelector('.circle-indicator');
        indicator.classList.toggle('border-blue-600', selected);
        indicator.querySelector('span').classList.toggle('hidden', !selected);
        btn.setAttribute('aria-checked', selected ? 'true' : 'false');
    }

    function buildChoiceButton(choice, index, multi) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = "choice-btn rounded-xl border border-gray-200 p-4 text-left text-gray-700 shadow-sm transition hover:shadow-md hover:border-blue-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-300 bg-white font-semibold text-sm cursor-pointer flex items-center justify-between";
        btn.setAttribute('role', multi ? 'checkbox' : 'radio');

        const label = document.createElement('span');
        label.textContent = (choice && choice.text != null) ? String(choice.text) : '';

        const indicator = document.createElement('span');
        indicator.setAttribute('aria-hidden', 'true');
        indicator.className = multi
            ? "circle-indicator w-5 h-5 rounded-md border border-gray-300 flex items-center justify-center shrink-0 ml-4"
            : "circle-indicator w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center shrink-0 ml-4";
        const dot = document.createElement('span');
        dot.className = multi ? "w-2.5 h-2.5 rounded-sm bg-blue-600 hidden" : "w-2.5 h-2.5 rounded-full bg-blue-600 hidden";
        indicator.appendChild(dot);

        btn.append(label, indicator);
        styleChoiceButton(btn, choiceIsSelected(index, multi));

        btn.onclick = () => multi ? toggleChoice(index, btn) : selectChoice(index, btn);
        return btn;
    }

    // Single-answer selection (radio style)
    function selectChoice(index, element) {
        const current = responses[currentIndex];
        // Allow clearing an optional question by clicking the selected choice again.
        if (current === index && !isRequired(questions[currentIndex])) {
            responses[currentIndex] = null;
        } else {
            responses[currentIndex] = index;
        }
        document.querySelectorAll('#choices-container .choice-btn').forEach((btn, i) => {
            styleChoiceButton(btn, responses[currentIndex] === i);
        });
        refreshNextState();
    }

    // Multi-answer toggle (checkbox style)
    function toggleChoice(index, element) {
        const current = Array.isArray(responses[currentIndex]) ? responses[currentIndex].slice() : [];
        const pos = current.indexOf(index);
        if (pos === -1) current.push(index); else current.splice(pos, 1);
        current.sort((a, b) => a - b);
        responses[currentIndex] = current;
        styleChoiceButton(element, pos === -1);
        refreshNextState();
    }

    function buildDropdown(choices) {
        const wrap = document.createElement('div');
        const label = document.createElement('label');
        label.htmlFor = 'answer-input';
        label.className = 'block text-xs font-bold text-gray-500 mb-2';
        label.textContent = 'Choose an answer';

        const select = document.createElement('select');
        select.id = 'answer-input';
        select.className = INPUT_CLASS + ' cursor-pointer';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select an answer…';
        select.appendChild(placeholder);

        choices.forEach((choice, index) => {
            const opt = document.createElement('option');
            opt.value = String(index);
            opt.textContent = (choice && choice.text != null) ? String(choice.text) : '';
            select.appendChild(opt);
        });

        const current = responses[currentIndex];
        select.value = Number.isInteger(current) ? String(current) : '';
        select.addEventListener('change', () => {
            responses[currentIndex] = select.value === '' ? null : parseInt(select.value, 10);
            refreshNextState();
        });

        wrap.append(label, select);
        return wrap;
    }

    function buildTextInput(type, validation) {
        const wrap = document.createElement('div');
        const label = document.createElement('label');
        label.htmlFor = 'answer-input';
        label.className = 'block text-xs font-bold text-gray-500 mb-2';
        label.textContent = 'Your answer';

        let control;
        if (type === 'long_answer') {
            control = document.createElement('textarea');
            control.rows = 6;
            control.className = INPUT_CLASS + ' resize-y';
        } else {
            control = document.createElement('input');
            control.type = type === 'number' ? 'number' : 'text';
            control.className = INPUT_CLASS;
            if (type === 'short_text') control.autocomplete = 'off';
        }
        control.id = 'answer-input';
        control.placeholder = type === 'number' ? 'Enter a number' : 'Type your answer';

        const maxLength = parseInt(validation.max_length, 10);
        let counter = null;
        if (type !== 'number' && maxLength > 0) {
            control.maxLength = maxLength;
            counter = document.createElement('p');
            counter.className = 'text-xs font-semibold text-gray-400 mt-2 text-right';
            counter.setAttribute('aria-live', 'polite');
        }
        if (type === 'number') {
            control.inputMode = validation.integer ? 'numeric' : 'decimal';
            control.step = validation.integer ? '1' : 'any';
            if (validation.min !== undefined && validation.min !== null && validation.min !== '') control.min = String(validation.min);
            if (validation.max !== undefined && validation.max !== null && validation.max !== '') control.max = String(validation.max);
        }

        const current = responses[currentIndex];
        control.value = typeof current === 'string' ? current : '';

        const updateCounter = () => {
            if (counter) counter.textContent = `${control.value.length} / ${maxLength}`;
        };
        updateCounter();

        control.addEventListener('input', () => {
            responses[currentIndex] = control.value;
            updateCounter();
            setError(currentInputError());
            refreshNextState();
        });

        wrap.append(label, control);
        if (counter) wrap.appendChild(counter);
        return wrap;
    }

    // Handle Next Click
    function handleNext() {
        const q = questions[currentIndex];
        const inputError = currentInputError();
        if (inputError) { setError(inputError); return; }
        if (isRequired(q) && !isAnswered(currentIndex)) return;

        if (currentIndex < totalQuestions - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            // Fill final progress bar to 100%
            document.getElementById('progress-bar-fill').style.width = '100%';
            submitQuiz();
        }
    }

    // Convert stored responses into the submission payload (no correctness computed here).
    function buildResponsesPayload() {
        const list = [];
        for (let i = 0; i < totalQuestions; i++) {
            const type = questionType(questions[i]);
            const raw = responses[i];
            let value = null;
            if (isAnswered(i)) {
                if (type === 'multiple_select') {
                    value = raw.slice();
                } else if (type === 'number') {
                    const n = Number(String(raw).trim());
                    value = Number.isFinite(n) ? n : String(raw).trim();
                } else if (type === 'short_text' || type === 'long_answer') {
                    value = String(raw);
                } else {
                    value = raw;
                }
            }
            list.push({ index: i, value: value });
        }
        return list;
    }

    // Submit Quiz Responses; scoring happens on the server
    async function submitQuiz() {
        if (submitting) return;
        submitting = true;
        refreshNextState();

        try {
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ responses: buildResponsesPayload() })
            });

            let data = {};
            try { data = await response.json(); } catch (e) { data = {}; }

            if (response.ok && data.success) {
                showResults(data);
                return;
            }

            submitting = false;
            if (response.status === 422) {
                // Jump to the first question the server rejected, if it told us which.
                const keys = Object.keys(data.errors || {});
                const match = keys.map(k => /^responses\.(\d+)/.exec(k)).find(Boolean);
                if (match) {
                    const idx = parseInt(match[1], 10);
                    if (idx >= 0 && idx < totalQuestions) {
                        currentIndex = idx;
                        renderQuestion();
                    }
                }
                setError(data.error || data.message || 'Some answers are not valid. Please review them.');
                refreshNextState();
            } else {
                refreshNextState();
                alert('Submission failed. Please try again.');
            }
        } catch (err) {
            submitting = false;
            refreshNextState();
            console.error('Error submitting quiz:', err);
            alert('Something went wrong during submission.');
        }
    }

    function showResults(data) {
        document.getElementById('quiz-view').classList.add('hidden');

        const earned = Number(data.points_earned) || 0;
        const max = Number(data.points_max) || 0;
        const percent = Number.isFinite(Number(data.score)) && data.score !== null
            ? Number(data.score)
            : (max > 0 ? Math.round(earned / max * 100) : 0);

        document.getElementById('score-text').textContent = `${earned} / ${max} points · ${percent}%`;

        let msg = '';
        if (data.pending_review) {
            msg = "Thanks for submitting! Your automatically graded answers have been scored.";
        } else if (percent === 100) {
            msg = "Perfect score! Excellent work! You have shown complete mastery over these topics.";
        } else if (percent >= 70) {
            msg = "Great job! You passed the test and demonstrate robust comprehension.";
        } else {
            msg = "Keep practicing. Read the materials again and you can do better next time.";
        }

        document.getElementById('message-text').textContent = msg;
        document.getElementById('pending-note').classList.toggle('hidden', !data.pending_review);
        document.getElementById('results-view').classList.remove('hidden');
    }
</script>
@endsection
