<?php
/**
 * ErrorHandler — Phase 4: Error Handling & Debugging (Week 13)
 * --------------------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Defensive programming: detect unsafe situations early and raise clear
 * RuntimeExceptions so failures are visible and debuggable immediately —
 * rather than causing silent corruption or cryptic PHP warnings downstream.
 *
 * Exception hierarchy used in this class:
 *   \Exception                   (base throwable type)
 *     └── \RuntimeException      (used for all failures in this project:
 *                                 missing files, unreadable files,
 *                                 unwritable paths, zero-divisor)
 *
 * Usage pattern (as shown in the project brief):
 *
 *   $handler = new ErrorHandler();
 *   try {
 *       $contents = $handler->safeReadFile('students.csv');
 *       echo $contents;
 *   } catch (\RuntimeException $e) {
 *       echo 'Error: ' . $e->getMessage();
 *   } finally {
 *       echo 'Operation finished.';
 *   }
 */
class ErrorHandler
{
    // =========================================================================
    // safeReadFile()
    // =========================================================================

    /**
     * Safely read the entire contents of a file and return them as a string.
     *
     * Checks:
     *   1. File exists       — throws if not.
     *   2. File is readable  — throws if not (permission problem).
     *   3. file_get_contents succeeds — throws on unexpected I/O failure.
     *
     * @param string $filePath Path to the file to read.
     * @return string          The raw file contents.
     * @throws \RuntimeException If the file is missing, unreadable, or the
     *                           read operation fails.
     */
    public function safeReadFile(string $filePath): string
    {
        // Check 1: Does the file exist at all?
        if (!file_exists($filePath)) {
            throw new \RuntimeException(
                "File not found: '{$filePath}'. "
                . "Verify the path is correct before attempting to read."
            );
        }

        // Check 2: Is the file readable by the current process?
        if (!is_readable($filePath)) {
            throw new \RuntimeException(
                "File is not readable: '{$filePath}'. "
                . "Check that the file permissions allow read access."
            );
        }

        // Attempt the actual read.
        $contents = file_get_contents($filePath);

        // Check 3: file_get_contents returns false on failure.
        if ($contents === false) {
            throw new \RuntimeException(
                "Failed to read file: '{$filePath}'. "
                . "An unexpected I/O error occurred during the read operation."
            );
        }

        return $contents;
    }

    // =========================================================================
    // safeWriteFile()
    // =========================================================================

    /**
     * Safely write a string to a file and return the number of bytes written.
     *
     * Checks:
     *   1. Parent directory is writable — throws if not.
     *   2. If the file already exists, it is writable — throws if not.
     *   3. file_put_contents succeeds — throws on unexpected I/O failure.
     *
     * @param string $filePath Path to the file to write (will be created or
     *                         overwritten).
     * @param string $content  The string content to write.
     * @return int             Number of bytes written.
     * @throws \RuntimeException If the directory or file is not writable, or
     *                           the write operation fails.
     */
    public function safeWriteFile(string $filePath, string $content): int
    {
        $dir = dirname($filePath);

        // Check 1: Is the parent directory writable?
        if (!is_writable($dir)) {
            throw new \RuntimeException(
                "Directory is not writable: '{$dir}'. "
                . "Check that the directory permissions allow write access."
            );
        }

        // Check 2: If the file already exists, is it writable?
        if (file_exists($filePath) && !is_writable($filePath)) {
            throw new \RuntimeException(
                "File exists but is not writable: '{$filePath}'. "
                . "Check the file permissions before attempting to overwrite it."
            );
        }

        // Attempt the actual write.
        $bytes = file_put_contents($filePath, $content);

        // Check 3: file_put_contents returns false on failure.
        if ($bytes === false) {
            throw new \RuntimeException(
                "Failed to write to file: '{$filePath}'. "
                . "An unexpected I/O error occurred during the write operation."
            );
        }

        return $bytes;
    }

    // =========================================================================
    // safeDivide()
    // =========================================================================

    /**
     * Safely divide two numbers, explicitly rejecting a zero divisor.
     *
     * Without this guard, PHP would raise a DivisionByZeroError (PHP 8) or
     * return INF / NAN for float division — both are silent failures that make
     * downstream bugs hard to trace.  By throwing a RuntimeException here we
     * surface the problem at its source.
     *
     * @param int|float $dividend The number to be divided.
     * @param int|float $divisor  The number to divide by; must not be zero.
     * @return float              The result of ($dividend / $divisor).
     * @throws \RuntimeException  If $divisor is zero.
     */
    public function safeDivide(int|float $dividend, int|float $divisor): float
    {
        // Strict zero check covers both integer 0 and float 0.0.
        if ($divisor == 0) {
            throw new \RuntimeException(
                "Division by zero is not allowed. "
                . "The divisor must be a non-zero value; received: {$divisor}."
            );
        }

        return (float)($dividend / $divisor);
    }
}
