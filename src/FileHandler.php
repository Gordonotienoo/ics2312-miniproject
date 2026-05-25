<?php
/**
 * FileHandler — Phase 1: File Handling (Week 10)
 * ------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Converts structured data between CSV text and associative PHP arrays.
 * Uses fopen(), fgetcsv(), and fputcsv() as required — never explode(',').
 * All file handles are closed in finally blocks to prevent resource leaks.
 */
class FileHandler
{
    /**
     * CSV column order as specified by the project brief.
     * Every row written must follow this exact header sequence.
     */
    private const HEADERS = [
        'name', 'reg_no', 'age', 'email',
        'mark1', 'mark2', 'mark3', 'mark4',
    ];

    // -------------------------------------------------------------------------
    // writeRecord()
    // -------------------------------------------------------------------------

    /**
     * Create or overwrite a CSV file, write the header row, then write
     * exactly one data row.
     *
     * @param string $filePath Absolute or relative path to the target CSV file.
     * @param array  $record   Associative array whose keys match the CSV headers.
     *                         Example:
     *                         [
     *                           'name'   => 'Grace Wanjiku',
     *                           'reg_no' => 'EN271-0001-2022',
     *                           'age'    => '21',
     *                           'email'  => 'grace@example.com',
     *                           'mark1'  => '78', 'mark2' => '82',
     *                           'mark3'  => '80', 'mark4' => '75',
     *                         ]
     * @return bool TRUE on success, FALSE if the file cannot be opened.
     */
    public function writeRecord(string $filePath, array $record): bool
    {
        // Mode 'w' creates the file or truncates an existing one.
        $handle = fopen($filePath, 'w');
        if ($handle === false) {
            return false;
        }

        try {
            // 1. Write the header row in the prescribed column order.
            fputcsv($handle, self::HEADERS);

            // 2. Build and write exactly one data row in the same column order.
            $row = [];
            foreach (self::HEADERS as $col) {
                $row[] = $record[$col] ?? '';
            }
            fputcsv($handle, $row);

            return true;
        } finally {
            // Always close the handle, even if an error occurs above.
            fclose($handle);
        }
    }

    // -------------------------------------------------------------------------
    // readAllRecords()
    // -------------------------------------------------------------------------

    /**
     * Read a CSV file and return every data row as an associative array.
     * The first row of the file is treated as the header definition.
     *
     * @param string $filePath Path to the CSV file to read.
     * @return array           Indexed array of associative arrays (one per data
     *                         row).  Returns an empty array if the file does not
     *                         exist, cannot be opened, or contains only headers.
     */
    public function readAllRecords(string $filePath): array
    {
        // Gracefully handle a missing file.
        if (!file_exists($filePath)) {
            return [];
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return [];
        }

        $records = [];

        try {
            // Read the header row first.
            $headers = fgetcsv($handle);

            // Guard against an empty file (fgetcsv returns false on EOF).
            if ($headers === false || $headers === null) {
                return [];
            }

            // Map each subsequent row onto the header keys.
            while (($row = fgetcsv($handle)) !== false) {
                // Skip rows whose column count does not match the headers
                // (e.g. blank lines that produce ['']).
                if (count($row) === count($headers)) {
                    $records[] = array_combine($headers, $row);
                }
            }
        } finally {
            fclose($handle);
        }

        return $records;
    }

    // -------------------------------------------------------------------------
    // appendRecord()
    // -------------------------------------------------------------------------

    /**
     * Append one data row to a CSV file.
     * If the file does not yet exist or is empty, the header row is written
     * first so the file always starts with a valid header.
     *
     * @param string $filePath Path to the CSV file.
     * @param array  $record   Associative array with keys matching the CSV headers.
     * @return bool            TRUE on success, FALSE if the file cannot be opened.
     */
    public function appendRecord(string $filePath, array $record): bool
    {
        // Determine whether we need to prepend headers before the data row.
        $needsHeader = !file_exists($filePath) || filesize($filePath) === 0;

        // Mode 'a' opens for appending; creates the file if it does not exist.
        $handle = fopen($filePath, 'a');
        if ($handle === false) {
            return false;
        }

        try {
            // Write the header row only for new or empty files.
            if ($needsHeader) {
                fputcsv($handle, self::HEADERS);
            }

            // Build and append the data row in the prescribed column order.
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
