<?php
/**
 * FormValidator — Phase 3: Web Form (Week 12)
 * ---------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Reusable, server-side validation rules for the student registration form.
 * Validation contract:
 *   — Returns NULL  when the supplied value is valid.
 *   — Returns a human-readable string when the value is invalid.
 *
 * This class is intentionally free of any HTML or $_POST references so it
 * can be unit-tested independently and reused across multiple form pages.
 */
class FormValidator
{
    // =========================================================================
    // Individual field validators
    // =========================================================================

    /**
     * Validate a student's full name.
     *
     * Rules
     *   • Minimum 2 characters (after trimming whitespace).
     *   • Only letters (A–Z, a–z), spaces, hyphens, and apostrophes.
     *   • Regex: /^[A-Za-z\s'-]+$/
     *
     * @param string $name Raw name string from user input.
     * @return string|null NULL if valid; error message string if invalid.
     */
    public function validateName(string $name): ?string
    {
        $name = trim($name);

        if (strlen($name) < 2) {
            return 'Name is too short. Please enter at least 2 characters.';
        }

        if (!preg_match("/^[A-Za-z\s'\-]+$/", $name)) {
            return 'Name contains invalid characters. '
                 . "Only letters, spaces, hyphens (-), and apostrophes (') are allowed.";
        }

        return null; // Valid.
    }

    /**
     * Validate an email address.
     *
     * Uses PHP's built-in filter_var() with FILTER_VALIDATE_EMAIL, which
     * checks the structure of the address without sending any network request.
     *
     * @param string $email Raw email string from user input.
     * @return string|null  NULL if valid; error message string if invalid.
     */
    public function validateEmail(string $email): ?string
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'The email address format is invalid. '
                 . 'Please enter a valid address (e.g. name@domain.com).';
        }

        return null; // Valid.
    }

    /**
     * Validate a student's age.
     *
     * Rules
     *   • Minimum: 18 (inclusive).
     *   • Maximum: 100 (inclusive).
     *
     * @param int $age Integer age value from user input.
     * @return string|null NULL if valid; error message string if invalid.
     */
    public function validateAge(int $age): ?string
    {
        if ($age < 18) {
            return "Age is below the minimum allowed value of 18. "
                 . "You entered {$age}.";
        }

        if ($age > 100) {
            return "Age is above the maximum allowed value of 100. "
                 . "You entered {$age}.";
        }

        return null; // Valid.
    }

    // =========================================================================
    // Aggregate validator
    // =========================================================================

    /**
     * Validate all registration fields in a single call.
     *
     * Runs validateName(), validateEmail(), and validateAge() on the
     * corresponding values from $input and collects all errors.
     *
     * @param array $input Associative array with keys 'name', 'email', 'age'.
     *                     Example:
     *                     ['name' => 'Grace Wanjiku',
     *                      'email' => 'grace@students.jkuat.ac.ke',
     *                      'age'   => 21]
     * @return array       Associative array of field-keyed error messages.
     *                     An EMPTY array means all fields passed validation.
     *                     Example on failure:
     *                     ['name'  => 'Name is too short ...',
     *                      'email' => 'The email address format is invalid ...']
     */
    public function validateAll(array $input): array
    {
        $errors = [];

        // Validate name.
        $nameError = $this->validateName((string)($input['name'] ?? ''));
        if ($nameError !== null) {
            $errors['name'] = $nameError;
        }

        // Validate email.
        $emailError = $this->validateEmail((string)($input['email'] ?? ''));
        if ($emailError !== null) {
            $errors['email'] = $emailError;
        }

        // Validate age — cast to int so validateAge() receives the right type.
        $ageError = $this->validateAge((int)($input['age'] ?? 0));
        if ($ageError !== null) {
            $errors['age'] = $ageError;
        }

        return $errors; // Empty array = all valid.
    }
}
