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
            <h3 id="question-text" class="text-xl font-bold text-gray-900 text-center leading-relaxed min-h-[60px]"></h3>
        </div>

        <!-- Choice buttons -->
        <div id="choices-container" class="grid grid-cols-1 gap-4">
            <!-- Dynamic Choices Buttons -->
        </div>

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
    // Load data from PHP
    const questions = @json($quiz->contens ?? []);
    const quizId = @json($quiz->id);
    const submitUrl = @json(route('quizzes.submit', $quiz->id));

    let currentIndex = 0;
    const totalQuestions = questions.length;
    let selectedAnswerIdx = null;
    
    // Store user's picked answers: key is question Index, value is choice object { text, is_correct }
    const answersRecord = {};
    let correctAnswersCount = 0;

    // Initialize View on Page Load
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('total-questions-num').textContent = totalQuestions;
        document.getElementById('total-questions-footer-num').textContent = totalQuestions;
        renderQuestion();
    });

    // Render current question
    function renderQuestion() {
        if (currentIndex >= totalQuestions) return;
        
        selectedAnswerIdx = null;
        document.getElementById('next-btn').disabled = true;
        
        const currentData = questions[currentIndex];
        
        // Update question text
        document.getElementById('current-question-num').textContent = currentIndex + 1;
        document.getElementById('question-text').textContent = currentData.question;
        
        // Update progress bar
        const progressPercent = Math.round(((currentIndex) / totalQuestions) * 100);
        document.getElementById('progress-bar-fill').style.width = `${progressPercent}%`;
        
        // Update answered status count
        const answeredCount = Object.keys(answersRecord).length;
        document.getElementById('answered-count-text').textContent = answeredCount;

        // Render choice buttons
        const container = document.getElementById('choices-container');
        container.innerHTML = '';
        
        if (currentData.choices && Array.isArray(currentData.choices)) {
            currentData.choices.forEach((choice, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = "choice-btn rounded-xl border border-gray-200 p-4 text-left text-gray-700 shadow-sm transition hover:shadow-md hover:border-blue-400 focus:outline-none bg-white font-semibold text-sm cursor-pointer flex items-center justify-between";
                
                btn.innerHTML = `
                    <span>${choice.text}</span>
                    <span class="circle-indicator w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center shrink-0 ml-4 group-hover:border-blue-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 hidden"></span>
                    </span>
                `;
                
                // Highlight if already selected
                if (answersRecord[currentIndex] && answersRecord[currentIndex].text === choice.text) {
                    btn.classList.add('border-blue-600', 'bg-blue-50/50', 'text-blue-800');
                    btn.querySelector('.circle-indicator').classList.add('border-blue-600');
                    btn.querySelector('.circle-indicator span').classList.remove('hidden');
                    selectedAnswerIdx = index;
                    document.getElementById('next-btn').disabled = false;
                }
                
                btn.onclick = () => selectChoice(index, btn);
                container.appendChild(btn);
            });
        }
        
        // Update Next button label
        const nextBtn = document.getElementById('next-btn');
        if (currentIndex === totalQuestions - 1) {
            nextBtn.textContent = 'Submit Test';
        } else {
            nextBtn.textContent = 'Next Question';
        }
    }

    // Handle Choice Selection
    function selectChoice(index, element) {
        selectedAnswerIdx = index;
        
        // Clear active styles from all choice buttons
        const buttons = document.querySelectorAll('.choice-btn');
        buttons.forEach(btn => {
            btn.classList.remove('border-blue-600', 'bg-blue-50/50', 'text-blue-800');
            btn.querySelector('.circle-indicator').classList.remove('border-blue-600');
            btn.querySelector('.circle-indicator span').classList.add('hidden');
        });
        
        // Add active styles to clicked button
        element.classList.add('border-blue-600', 'bg-blue-50/50', 'text-blue-800');
        element.querySelector('.circle-indicator').classList.add('border-blue-600');
        element.querySelector('.circle-indicator span').classList.remove('hidden');
        
        // Enable Next / Submit button
        document.getElementById('next-btn').disabled = false;

        // Record Answer
        const currentData = questions[currentIndex];
        const pickedChoice = currentData.choices[index];
        
        answersRecord[currentIndex] = {
            text: pickedChoice.text,
            is_correct: !!pickedChoice.is_correct
        };
    }

    // Handle Next Click
    function handleNext() {
        if (selectedAnswerIdx === null) return;
        
        if (currentIndex < totalQuestions - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            // Fill final progress bar to 100%
            document.getElementById('progress-bar-fill').style.width = '100%';
            submitQuiz();
        }
    }

    // Submit Quiz Results Asynchronously
    async function submitQuiz() {
        let scoreSum = 0;
        const answersList = [];
        
        Object.keys(answersRecord).forEach(key => {
            const val = answersRecord[key];
            answersList.push(val);
            if (val.is_correct) {
                scoreSum++;
            }
        });
        
        const finalScorePercentage = Math.round((scoreSum / totalQuestions) * 100);

        try {
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    score: finalScorePercentage,
                    answers: answersList
                })
            });

            if (response.ok) {
                // Show results view
                document.getElementById('quiz-view').classList.add('hidden');
                
                const scoreText = `You scored ${finalScorePercentage} points.`;
                document.getElementById('score-text').textContent = scoreText;
                
                let msg = '';
                if (finalScorePercentage === 100) {
                    msg = "Perfect score! Excellent work! You have shown complete mastery over these topics.";
                } else if (finalScorePercentage >= 70) {
                    msg = "Great job! You passed the test and demonstrate robust comprehension.";
                } else {
                    msg = "Keep practicing. Read the materials again and you can do better next time.";
                }
                
                document.getElementById('message-text').textContent = msg;
                document.getElementById('results-view').classList.remove('hidden');
            } else {
                alert('Submission failed. Please try again.');
            }
        } catch (err) {
            console.error('Error submitting quiz:', err);
            alert('Something went wrong during submission.');
        }
    }
</script>
@endsection
