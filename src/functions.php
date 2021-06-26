<?php
global $webhookSecret;

/**
 * Send a request to the Telegram API
 *
 * @param string $method
 * @param null|array $data
 * @param bool $return
 *
 * @return bool|object
 */
function bot(string $method, array $data = null, bool $return = false) {
    global $botToken;

    $url = 'https://api.telegram.org/bot' . $botToken . '/' . $method;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($data))
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $result = curl_exec($ch);

    if (curl_error($ch))
        return false;
    else
        return $return ? json_decode($result) : null;
}

/**
 * Get User bio
 *
 * @param object $user
 * @return string|null
 */
function fetchUserBio(object $user) {
    // Crawl from Telegram website
    if (isset($user->username))
        return getBioByUsername($user->username);
    // Get using the API
    else
        return getBioById($user->id);
}

/**
 * Get bio by crawling Telegram website
 *
 * @param string $username
 * @return string|null
 */
function getBioByUsername(string $username) {
    $url = 'https://t.me/' . $username;
    $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36';

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => $userAgent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_MAXREDIRS => 3
    ]);

    $result = curl_exec($ch);
    preg_match('/<div class="tgme_page_description ">(.*)<\/div>/i', $result, $matches);
    return $matches[1] ?? null;
}

/**
 * Get user bio by using the API
 *
 * @param int $userId
 * @return string|null
 */
function getBioById(int $userId) {
    // @TODO: implement this function
    return null;
}

/**
 * Get log message text
 *
 * @param string $name
 * @param int $id
 * @param string $reason
 * @param string $word
 * @return string
 */
function getLogText(string $name, int $id, string $reason, string $word) {
    $reason = $reason === 'name' ? 'نام' : 'بیو';

    $text = '👮‍♂️';
    $text .= 'کاربر ' . "[$name](tg://user?id=$id) ";
    $text .= "به علت $reason مستهجن" . "($word) ";
    $text .= 'ریموو شد.';

    return $text;
}
