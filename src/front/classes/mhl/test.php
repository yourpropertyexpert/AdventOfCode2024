<?php

/**
 * test class
 *
 * @copyright Mark Harrison Ltd 2024
 * @package mhl
 */

namespace mhl;

/**
 * Test class
 */

class test
{
    public function __construct()
    {
    }

    public function calculateTotalDistance(array $leftList, array $rightList)
    {
        // Sort both lists
        sort($leftList);
        sort($rightList);

        $totalDistance = 0;

        // Calculate the total distance
        for ($i = 0; $i < count($leftList); $i++) {
            $totalDistance += abs($leftList[$i] - $rightList[$i]);
        }

        return $totalDistance;
    }

    public function calculateSimilarityScore(array $leftList, array $rightList)
    {
        $similarityScore = 0;
        $rightCount = array_count_values($rightList); // Count occurrences in the right list

        foreach ($leftList as $number) {
            if (isset($rightCount[$number])) {
                $similarityScore += $number * $rightCount[$number]; // Multiply by occurrences
            }
        }

        return $similarityScore;
    }

    public function analyzeReports(array $reports)
    {
        $safeCount = 0;

        foreach ($reports as $report) {
            $levels = explode(' ', $report);
            $isIncreasing = true;
            $isDecreasing = true;

            for ($i = 1; $i < count($levels); $i++) {
                $diff = abs($levels[$i] - $levels[$i - 1]);

                if ($diff < 1 || $diff > 3) {
                    $isIncreasing = false;
                    $isDecreasing = false;
                    break;
                }

                if ($levels[$i] <= $levels[$i - 1]) {
                    $isIncreasing = false;
                }
                if ($levels[$i] >= $levels[$i - 1]) {
                    $isDecreasing = false;
                }
            }

            // Check if the report is safe or can be made safe by removing one level
            if ($isIncreasing || $isDecreasing || $this->canBeMadeSafe($levels)) {
                $safeCount++;
            }
        }

        return $safeCount;
    }

    private function canBeMadeSafe(array $levels)
    {
        // Check if removing one level can make the report safe
        for ($i = 0; $i < count($levels); $i++) {
            $tempLevels = $levels;
            unset($tempLevels[$i]); // Remove one level
            $tempLevels = array_values($tempLevels); // Re-index the array

            // Check if the remaining levels are safe
            if ($this->isSafe($tempLevels)) {
                return true;
            }
        }
        return false;
    }

    private function isSafe(array $levels)
    {
        // Check if the levels are either increasing or decreasing
        $isIncreasing = true;
        $isDecreasing = true;

        for ($i = 1; $i < count($levels); $i++) {
            $diff = abs($levels[$i] - $levels[$i - 1]);

            if ($diff < 1 || $diff > 3) {
                $isIncreasing = false;
                $isDecreasing = false;
                break;
            }

            if ($levels[$i] <= $levels[$i - 1]) {
                $isIncreasing = false;
            }
            if ($levels[$i] >= $levels[$i - 1]) {
                $isDecreasing = false;
            }
        }

        return $isIncreasing || $isDecreasing;
    }

    public function calculateTotalFromCorruptedMemory(string $corruptedMemory)
    {
        $total = 0;

        // Use regex to find valid mul instructions
        preg_match_all('/mul\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/', $corruptedMemory, $matches);

        // Calculate the total from valid instructions
        foreach ($matches[0] as $index => $match) {
            $x = (int)$matches[1][$index];
            $y = (int)$matches[2][$index];
            $total += $x * $y;
        }

        return $total;
    }

    public function calculateTotalWithConditionals(string $corruptedMemory)
    {
        $total = 0;
        $enabled = true; // Start with mul instructions enabled

        // Use regex to find all relevant instructions
        preg_match_all('/(mul\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)|do\(\)|don\'t\(\))/', $corruptedMemory, $matches);

        foreach ($matches[0] as $match) {
            if ($match === 'do()') {
                $enabled = true; // Enable mul instructions
            } elseif ($match === "don't()") {
                $enabled = false; // Disable mul instructions
            } elseif ($enabled) {
                // If mul instructions are enabled, calculate the total
                preg_match('/mul\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/', $match, $mulMatches);
                if (isset($mulMatches[1]) && isset($mulMatches[2])) {
                    $x = (int)$mulMatches[1];
                    $y = (int)$mulMatches[2];
                    $total += $x * $y; // Add to total
                }
            }
        }

        return $total;
    }

    public function countXMASOccurrences(array $grid)
    {
        $word = "XMAS";
        $count = 0;
        $rows = count($grid);
        $cols = count($grid[0]);

        // Directions: right, down, diagonal down-right, diagonal up-right
        $directions = [
            [0, 1],   // right
            [1, 0],   // down
            [1, 1],   // diagonal down-right
            [-1, 1],  // diagonal up-right
            [0, -1],  // left
            [-1, 0],  // up
            [-1, -1], // diagonal up-left
            [1, -1],  // diagonal down-left
        ];

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                // Check in all directions
                foreach ($directions as $direction) {
                    $count += $this->searchInDirection($grid, $row, $col, $direction, $word);
                }
            }
        }

        return $count;
    }

    private function searchInDirection(array $grid, int $startRow, int $startCol, array $direction, string $word)
    {
        $wordLength = strlen($word);
        $row = $startRow;
        $col = $startCol;

        for ($i = 0; $i < $wordLength; $i++) {
            // Calculate the current position
            $currentRow = $row + $i * $direction[0];
            $currentCol = $col + $i * $direction[1];

            // Check if the current position is out of bounds
            if ($currentRow < 0 || $currentRow >= count($grid) || $currentCol < 0 || $currentCol >= count($grid[0])) {
                return 0; // Out of bounds
            }

            // Check if the character matches
            if ($grid[$currentRow][$currentCol] !== $word[$i]) {
                return 0; // Mismatch
            }
        }

        return 1; // Found the word
    }

    public function FormatXMASInput(string $input): array
    {
        // Split the input string by CR or LF characters
        $lines = preg_split('/\r\n|\r|\n/', $input);
        $result = [];

        // Convert each line into an array of characters
        foreach ($lines as $line) {
            $result[] = str_split(trim($line)); // Trim to remove any extra spaces
        }

        return $result;
    }

    public function countXMASOccurrencesInXShape(array $grid)
    {
        $word = "MAS";
        $count = 0;
        $rows = count($grid);
        $cols = count($grid[0]);

        // Check for the X-MAS pattern in all possible orientations
        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                // Check if we can form an X shape
                $count += $this->searchXShape($grid, $row, $col);
            }
        }
        return $count;
    }

    private function searchXShape(array $grid, int $startRow, int $startCol)
    {
        $count = 0;

        // Check if the X shape can be formed
        if ($startRow - 1 >= 0 && $startRow + 1 < count($grid) && $startCol - 1 >= 0 && $startCol + 1 < count($grid[0])) {
            // Check for the forward X-MAS
            if ($grid[$startRow - 1][$startCol - 1] === 'M' &&
                $grid[$startRow][$startCol] === 'A' &&
                $grid[$startRow + 1][$startCol + 1] === 'S') {
                $count++;
            }
            // Check for the backward X-MAS
            if ($grid[$startRow - 1][$startCol + 1] === 'M' &&
                $grid[$startRow][$startCol] === 'A' &&
                $grid[$startRow + 1][$startCol - 1] === 'S') {
                $count++;
            }
        }
        return $count;
    }
}
