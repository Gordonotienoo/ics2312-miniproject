<?php
/**
 * SearchSorter — Phase 2: Search & Sort (Week 11)
 * -------------------------------------------------
 * Course  : ICS/ECE 2312 — JKUAT ECE Year 3 Semester 2
 * Lecturer: Maxwell Ouma
 * Platform: Kioto iLMS
 *
 * Implements two search algorithms and three sort algorithms.
 * Every sort method returns ['sorted' => [...], 'iterations' => int]
 * so the autograder can verify both the sorted output and the exact
 * iteration (comparison) count.
 */
class SearchSorter
{
    // =========================================================================
    // SEARCH ALGORITHMS
    // =========================================================================

    /**
     * Linear Search
     * -------------
     * Scans every element from left to right until the target is found.
     *
     * Time complexity:
     *   Best    O(1)  — target is the first element
     *   Average O(n)
     *   Worst   O(n)  — target is the last element or absent
     *
     * @param array          $items  Indexed array of values to search.
     * @param int|string     $target The value to find.
     * @return int                   Zero-based index of the target, or -1 if
     *                               the target is not present.
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
     * Binary Search
     * -------------
     * Repeatedly halves the search range.  The input array MUST already be
     * sorted in ascending order for this algorithm to produce correct results.
     *
     * Time complexity:
     *   Best    O(1)     — target is the middle element on the first probe
     *   Average O(log n)
     *   Worst   O(log n)
     *
     * @param array          $items  Ascending-sorted indexed array.
     * @param int|string     $target The value to find.
     * @return int                   Zero-based index of the target, or -1 if
     *                               the target is not present.
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
                $low = $mid + 1;   // Target is in the right half.
            } else {
                $high = $mid - 1;  // Target is in the left half.
            }
        }

        return -1;
    }

    // =========================================================================
    // SORT ALGORITHMS
    // =========================================================================

    /**
     * Bubble Sort
     * -----------
     * Repeatedly compares adjacent elements and swaps them if they are in the
     * wrong order.  Includes an early-termination optimisation: if a full pass
     * produces no swaps the array is already sorted and the loop exits early,
     * giving O(n) best-case performance.
     *
     * Time complexity:
     *   Best    O(n)  — already sorted (with optimisation)
     *   Average O(n²)
     *   Worst   O(n²)
     *
     * Iteration contract: 'iterations' counts every element comparison made
     * inside the inner loop, matching the autograder's expectation.
     *
     * Trace example — bubbleSort([5, 3, 1, 4, 2]):
     *   Pass 1: [5,3,1,4,2] → [3,1,4,2,5]   (4 comparisons)
     *   Pass 2: [3,1,4,2,5] → [1,3,2,4,5]   (3 comparisons)
     *   Pass 3: [1,3,2,4,5] → [1,2,3,4,5]   (2 comparisons)
     *   Pass 4: [1,2,3,4,5] → no swap, exit  (1 comparison)
     *
     * @param array $items Indexed array of values to sort.
     * @return array       ['sorted' => [...], 'iterations' => int]
     */
    public function bubbleSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;

        for ($i = 0; $i < $n - 1; $i++) {
            $swapped = false;

            for ($j = 0; $j < $n - $i - 1; $j++) {
                $iterations++;                            // Count each comparison.
                if ($items[$j] > $items[$j + 1]) {
                    [$items[$j], $items[$j + 1]] = [$items[$j + 1], $items[$j]];
                    $swapped = true;
                }
            }

            // Optimisation: stop if the array is already fully sorted.
            if (!$swapped) {
                break;
            }
        }

        return ['sorted' => $items, 'iterations' => $iterations];
    }

    /**
     * Selection Sort
     * --------------
     * Divides the array into a sorted left portion and an unsorted right
     * portion.  On each pass it finds the minimum element in the unsorted
     * portion and moves it to the boundary of the sorted portion.
     *
     * Time complexity:
     *   Best    O(n²)
     *   Average O(n²)
     *   Worst   O(n²)
     *
     * Note: selection sort always makes the same number of comparisons
     * regardless of input order — it does not short-circuit.
     *
     * @param array $items Indexed array of values to sort.
     * @return array       ['sorted' => [...], 'iterations' => int]
     */
    public function selectionSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;

        for ($i = 0; $i < $n - 1; $i++) {
            $minIdx = $i;

            for ($j = $i + 1; $j < $n; $j++) {
                $iterations++;                            // Count each comparison.
                if ($items[$j] < $items[$minIdx]) {
                    $minIdx = $j;
                }
            }

            // Swap the found minimum into position only when necessary.
            if ($minIdx !== $i) {
                [$items[$i], $items[$minIdx]] = [$items[$minIdx], $items[$i]];
            }
        }

        return ['sorted' => $items, 'iterations' => $iterations];
    }

    /**
     * Insertion Sort
     * --------------
     * Builds a sorted sub-array one element at a time.  Each unsorted element
     * is inserted into its correct position by shifting larger sorted elements
     * one place to the right.
     *
     * Time complexity:
     *   Best    O(n)  — already sorted (inner loop never executes)
     *   Average O(n²)
     *   Worst   O(n²)
     *
     * Nearly-sorted insight: because insertion sort stops shifting as soon as
     * it finds a smaller element, it typically makes fewer comparisons than
     * bubble sort on nearly-sorted input.  This is why the autograder compares
     * iteration counts on a nearly-sorted test case.
     *
     * @param array $items Indexed array of values to sort.
     * @return array       ['sorted' => [...], 'iterations' => int]
     */
    public function insertionSort(array $items): array
    {
        $n          = count($items);
        $iterations = 0;

        for ($i = 1; $i < $n; $i++) {
            $key = $items[$i];
            $j   = $i - 1;

            // Shift elements that are greater than $key one position to the right.
            while ($j >= 0) {
                $iterations++;                            // Count each comparison.
                if ($items[$j] > $key) {
                    $items[$j + 1] = $items[$j];
                    $j--;
                } else {
                    break;                                // Found the insertion point.
                }
            }

            $items[$j + 1] = $key;
        }

        return ['sorted' => $items, 'iterations' => $iterations];
    }
}
