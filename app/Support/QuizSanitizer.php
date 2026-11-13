<?php

namespace App\Support;

/**
 * Strips answer keys from quiz questions before they are sent to a student's
 * browser, and fills in contract defaults (type, points, required).
 */
class QuizSanitizer
{
    /** Question-level keys that must never reach the student. */
    private const HIDDEN_QUESTION_KEYS = [
        'accepted_answers', 'expected_number', 'grading_guidance',
        'answer', 'answers', 'correct_answer', 'correct_answers', 'answer_key',
    ];

    /** Choice-level keys that must never reach the student. */
    private const HIDDEN_CHOICE_KEYS = ['is_correct', 'correct'];

    public static function contents($contents): array
    {
        if (! is_array($contents)) {
            return [];
        }

        return array_values(array_map([self::class, 'question'], $contents));
    }

    public static function question($question): array
    {
        $q = is_array($question) ? $question : [];

        foreach (self::HIDDEN_QUESTION_KEYS as $key) {
            unset($q[$key]);
        }

        $q['type'] = isset($q['type']) && is_string($q['type']) && $q['type'] !== '' ? $q['type'] : 'single_choice';
        $q['points'] = isset($q['points']) && is_numeric($q['points']) && (int) $q['points'] >= 1 ? (int) $q['points'] : 1;
        $required = $q['required'] ?? true;
        $q['required'] = is_bool($required)
            ? $required
            : (filter_var($required, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true);

        if (isset($q['choices']) && is_array($q['choices'])) {
            $q['choices'] = array_values(array_map(function ($choice) {
                if (! is_array($choice)) {
                    return ['text' => is_scalar($choice) ? (string) $choice : ''];
                }
                foreach (self::HIDDEN_CHOICE_KEYS as $key) {
                    unset($choice[$key]);
                }

                return $choice;
            }, $q['choices']));
        } else {
            $q['choices'] = [];
        }

        return $q;
    }
}
