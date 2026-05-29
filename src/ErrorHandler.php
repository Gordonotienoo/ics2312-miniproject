<?php

declare(strict_types=1);

namespace App;

/**
 * ErrorHandler — Phase 4: Error Handling & Debugging (Week 13)
 * --------------------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma | Platform: Kioto iLMS
 *
 * Throws RuntimeException on all failures instead of silent crashes.
 */
class ErrorHandler
{
    /**
     * Safely read entire file contents.
     * Throws RuntimeException if file missing, unreadable, or read fails.
     */
    public function safeReadFile(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException(
                "File not found: '{$filePath}'. Verify the path is correct before reading."
            );
        }
        if (!is_readable($filePath)) {
            throw new \RuntimeException(
                "File is not readable: '{$filePath}'. Check file permissions."
            );
        }
        $contents = file_get_contents($filePath);
        if ($contents === false) {
            throw new \RuntimeException(
                "Failed to read file: '{$filePath}'. An unexpected I/O error occurred."
            );
        }
        return $contents;
    }

    /**
     * Safely write content to a file.
     * Throws RuntimeException if directory or file is not writable.
     * Returns number of bytes written.
     */
    public function safeWriteFile(string $filePath, string $content): int
    {
        $dir = dirname($filePath);
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \RuntimeException(
                "Directory is not writable: '{$dir}'. Check directory permissions."
            );
        }
        if (file_exists($filePath) && !is_writable($filePath)) {
            throw new \RuntimeException(
                "File exists but is not writable: '{$filePath}'. Check file permissions."
            );
        }
        $bytes = file_put_contents($filePath, $content);
        if ($bytes === false) {
            throw new \RuntimeException(
                "Failed to write to file: '{$filePath}'. An unexpected I/O error occurred."
            );
        }
        return $bytes;
    }

    /**
     * Safely divide two numbers.
     * Throws RuntimeException if divisor is zero.
     */
    public function safeDivide(int|float $dividend, int|float $divisor): float
    {
        if ($divisor == 0) {
            throw new \RuntimeException(
                "Division by zero is not allowed. The divisor must be non-zero; received: {$divisor}."
            );
        }
        return (float)($dividend / $divisor);
    }
}
