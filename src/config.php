<?php
$botToken = '123:abc';
$webhookSecret = 'aRandomLongText';
$databasePathPrefix = '../db/';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    die('method');

else if ($_GET['secret'] !== $webhookSecret)
    die('secret');

// Do not edit below variable manually. Run update.php Instead
$prohibitedWordsRegex = '';
