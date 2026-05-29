<?php

declare(strict_types=1);

namespace App;

/**
 * FormValidator — Phase 3: Web Form (Week 12)
 * ---------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma | Platform: Kioto iLMS
 *
 * Returns NULL when valid, error string when invalid.
 */
class FormValidator
{
    /**
     * Validate full name.
     * Rules: min 2 chars, only letters/spaces/hyphens/apostrophes.
     */
    public function validateName(string $name): ?string
    {
        $name = trim($name);
        if (strlen($name) < 2) {
            return 'Name is too short. Please enter at least 2 characters.';
        }
        if (!preg_match("/^[A-Za-z\s'\-]+$/", $name)) {
            return 'Name contains invalid characters. Only letters, spaces, hyphens and apostrophes are allowed.';
        }
        return null;
    }

    /**
     * Validate email address using filter_var.
     */
    public function validateEmail(string $email): ?string
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'The email address format is invalid. Please enter a valid address (e.g. name@domain.com).';
        }
        return null;
    }

    /**
     * Validate age — must be between 18 and 100 inclusive.
     */
    public function validateAge(int $age): ?string
    {
        if ($age < 18) {
            return "Age is below the minimum allowed value of 18. You entered {$age}.";
        }
        if ($age > 100) {
            return "Age is above the maximum allowed value of 100. You entered {$age}.";
        }
        return null;
    }

    /**
     * Validate all fields at once.
     * Returns empty array if all valid, or associative array of errors.
     */
    public function validateAll(array $input): array
    {
        $errors = [];
        $nameError = $this->validateName((string)($input['name'] ?? ''));
        if ($nameError !== null) {
            $errors['name'] = $nameError;
        }
        $emailError = $this->validateEmail((string)($input['email'] ?? ''));
        if ($emailError !== null) {
            $errors['email'] = $emailError;
        }
        $ageError = $this->validateAge((int)($input['age'] ?? 0));
        if ($ageError !== null) {
            $errors['age'] = $ageError;
        }
        return $errors;
    }
}
