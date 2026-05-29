<?php

declare(strict_types=1);

namespace App;

/**
 * FileHandler — Phase 1: File Handling (Week 10)
 * ------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma | Platform: Kioto iLMS
 */
class FileHandler
{
    private const HEADERS = [
        'name', 'reg_no', 'age', 'email',
        'mark1', 'mark2', 'mark3', 'mark4',
    ];

    /**
     * Create or overwrite a CSV file, write header row, then write exactly one data row.
     */
    public function writeRecord(string $filePath, array $record): bool
    {
        $handle = fopen($filePath, 'w');
        if ($handle === false) {
            return false;
        }
        try {
            fputcsv($handle, self::HEADERS);
            $row = [];
            foreach (self::HEADERS as $col) {
                $row[] = $record[$col] ?? '';
            }
            fputcsv($handle, $row);
            return true;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Return all rows as associative arrays using the first row as the header definition.
     */
    public function readAllRecords(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return [];
        }
        $records = [];
        try {
            $headers = fgetcsv($handle);
            if ($headers === false || $headers === null) {
                return [];
            }
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($headers)) {
                    $records[] = array_combine($headers, $row);
                }
            }
        } finally {
            fclose($handle);
        }
        return $records;
    }

    /**
     * Add a new row at the end; write headers first if the file is new or empty.
     */
    public function appendRecord(string $filePath, array $record): bool
    {
        $needsHeader = !file_exists($filePath) || filesize($filePath) === 0;
        $handle = fopen($filePath, 'a');
        if ($handle === false) {
            return false;
        }
        try {
            if ($needsHeader) {
                fputcsv($handle, self::HEADERS);
            }
            $row = [];
            foreach (self::HEADERS as $col) {
                $row[] = $record[$col] ?? '';
            }
            fputcsv($handle, $row);
            return true;
        } finally {
            fclose($handle);
        }
    }
}
