<?php
require 'config.php';
require 'functions.php';
global $botToken, $prohibitedWordsRegex;

$event = json_decode(file_get_contents('php://input'))->chat_member;

// User kicked, left, permissions changed, etc.
if ($event->new_chat_member->status !== 'member')
    die('none my business');

// User added by someone
if (!isset($event->invite_link))
    die('I only check users came by link');

// Initialize variables
$kickReason = null;
$chatId = $event->chat->id;
$event = $event->new_chat_member;
$event->user->name = $event->user->first_name . ($event->user->last_name ? ' ' . $event->user->last_name : null);

if (preg_match($prohibitedWordsRegex, $event->user->name, $matchedWords))
    $kickReason = 'name';

else if (preg_match($prohibitedWordsRegex, $userBio = fetchUserBio($event->user), $matchedWords))
    $kickReason = 'bio';

if (!empty($kickReason)) {
    bot('kickChatMember', [
        'chat_id' => $chatId,
        'user_id' => $event->user->id,
        'revoke_messages' => true
    ]);

    $log = bot('sendMessage', [
        'chat_id' => $chatId,
        'text' => getLogText($event->user->name, $event->user->id, $kickReason, $matchedWords[0]),
        'parse_mode' => 'Markdown'
    ], true);

    bot('deleteMessage', [
        'chat_id' => $chatId,
        'message_id' => $log->result->message_id
    ]);
}
