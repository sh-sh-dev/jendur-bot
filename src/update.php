<?php
require_once 'config.php';
global $databasePathPrefix;

$prohibitedWords = $databasePathPrefix . 'prohibitedWords.json';
$currentRegexFile = $databasePathPrefix . 'currentRegex.txt';

$currentRegex = file_get_contents($currentRegexFile);
$newRegex = '/(?:' . implode('|', json_decode(file_get_contents($prohibitedWords))) . ')/';

if ($currentRegex !== $newRegex) {
    // Use new regex in the main source
    $text = file_get_contents('config.php');
    $pattern = '/([$]prohibitedWordsRegex = )(?:.+);/';
    $array = '${1}\'' . $newRegex . '\';';
    file_put_contents('config.php', preg_replace($pattern, $array, $text));

    // Save new regex for next check
    $update = file_put_contents($currentRegexFile, $newRegex) ? 'ok' : 'failed';
}

echo $update ?? 'not changed';
