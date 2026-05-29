<?php

declare(strict_types=1);

namespace App;

/**
 * SearchSorter — Phase 2: Search & Sort (Week 11)
 * -------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma | Platform: Kioto iLMS
 */
class SearchSorter
{
    /**
     * Linear Search — scan every element from left to right.
     * Returns index of target or -1 if not found.
     */
    public function linearSearch(array $items, int|string $target): int
    {
        foreach ($items as $index => $value) {
            if ($value === $target) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * Binary Search — requires ascending-sorted input.
     * Returns index of target or -1 if not found.
     */
    public function binarySearch(array $items, int|string $target): int
    {
        $low  = 0;
        $high = count($items) - 1;
        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            if ($items[$mid] === $target) {
                return $mid;
            } elseif ($items[$mid] < $target) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        return -1;
    }

    /**
     * Bubble Sort — repeatedly swap adjacent elements in wrong order.
     * Returns ['sorted' => [...], 'iterations' => int]
     */
    public function bubbleSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;
        for ($i = 0; $i < $n - 1; $i++) {
            $swapped = false;
            for ($j = 0; $j < $n - $i - 1; $j++) {
                $iterations++;
                if ($items[$j] > $items[$j + 1]) {
                    [$items[$j], $items[$j + 1]] = [$items[$j + 1], $items[$j]];
                    $swapped = true;
                }
            }
            if (!$swapped) {
                break;
            }
        }
        return ['sorted' => $items, 'iterations' => $iterations];
    }

    /**
     * Selection Sort — find minimum in unsorted portion and place it at front.
     * Returns ['sorted' => [...], 'iterations' => int]
     */
    public function selectionSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;
        for ($i = 0; $i < $n - 1; $i++) {
            $minIdx = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                $iterations++;
                if ($items[$j] < $items[$minIdx]) {
                    $minIdx = $j;
                }
            }
            if ($minIdx !== $i) {
                [$items[$i], $items[$minIdx]] = [$items[$minIdx], $items[$i]];
            }
        }
        return ['sorted' => $items, 'iterations' => $iterations];
    }

    /**
     * Insertion Sort — build sorted sub-array one element at a time.
     * Fewer iterations than bubble sort on nearly-sorted input.
     * Returns ['sorted' => [...], 'iterations' => int]
     */
    public function insertionSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;
        for ($i = 1; $i < $n; $i++) {
            $key = $items[$i];
            $j   = $i - 1;
            while ($j >= 0) {
                $iterations++;
                if ($items[$j] > $key) {
                    $items[$j + 1] = $items[$j];
                    $j--;
                } else {
                    break;
                }
            }
            $items[$j + 1] = $key;
        }
        return ['sorted' => $items, 'iterations' => $iterations];
    }
}
