<?php

/**
 * Coverage threshold checker.
 *
 * Parses a Clover coverage report and fails (non-zero exit) when the
 * line coverage is below the required minimum. PHPUnit has no built-in
 * "fail under" option, so this script enforces it for CI/local runs.
 *
 * Usage: php tests/coverage-check.php [clover.xml path] [min percent]
 */

$cloverPath = $argv[1] ?? __DIR__ . '/../coverage/clover.xml';
$minimum = (float) ($argv[2] ?? 80);

if (!file_exists($cloverPath)) {
    fwrite(STDERR, "Coverage file not found: {$cloverPath}\n");
    fwrite(STDERR, "Run the test suite with coverage first.\n");
    exit(1);
}

$xml = simplexml_load_file($cloverPath);
$metrics = $xml->project->metrics;

$statements = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];

if ($statements === 0) {
    fwrite(STDERR, "No statements found in coverage report.\n");
    exit(1);
}

$coverage = round(($covered / $statements) * 100, 2);

printf("Line coverage: %.2f%% (%d/%d statements)\n", $coverage, $covered, $statements);
printf("Minimum required: %.2f%%\n", $minimum);

if ($coverage < $minimum) {
    fwrite(STDERR, sprintf("\n❌ FAILED: coverage %.2f%% is below the minimum of %.2f%%\n", $coverage, $minimum));
    exit(1);
}

printf("\n✅ PASSED: coverage meets the minimum threshold.\n");
exit(0);
