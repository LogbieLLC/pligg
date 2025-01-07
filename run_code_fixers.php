<?php

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
$toolsDir = __DIR__ . '/tools';
$outputFile = __DIR__ . '/code_fixer_results.txt';
$phpcsPath = $toolsDir . '/phpcs.phar';
$phpcbfPath = $toolsDir . '/phpcbf.phar';

// Function to run a command and return output
function runCommand($command)
{
    $output = [];
    $returnVar = 0;
    exec($command . " 2>&1", $output, $returnVar);
    return [
        'output' => implode("\n", $output),
        'return_code' => $returnVar
    ];
}

// Open file for writing
$file = fopen($outputFile, 'w') or die("Unable to open output file!");

// Write header
fwrite($file, "Code Fixer Results - " . date('Y-m-d H:i:s') . "\n");
fwrite($file, str_repeat("=", 80) . "\n\n");

// Run PHPCS
fwrite($file, "PHP CodeSniffer Results:\n");
fwrite($file, str_repeat("-", 80) . "\n");
$phpcsResult = runCommand("php {$phpcsPath} --standard=PSR2 .");
fwrite($file, $phpcsResult['output'] . "\n\n");

// Run PHPCBF
fwrite($file, "PHP Code Beautifier Results:\n");
fwrite($file, str_repeat("-", 80) . "\n");
$phpcbfResult = runCommand("php {$phpcbfPath} --standard=PSR2 .");
fwrite($file, $phpcbfResult['output'] . "\n");

// Close file
fclose($file);

echo "Code fixer analysis complete. Results written to: code_fixer_results.txt\n";
