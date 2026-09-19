<?php
declare(strict_types=1);

$to = 'karolina.panada@gmail.com, kercha023@gmail.com, vladmikhalkevich@gmail.com';

function clean_header_value(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

// Also logs each submission as a row in a Google Sheet via a linked Google Form.
// TODO: replace FORM_ID and the three entry.XXXXXXXXX field IDs below with your own
// (Form editor -> "..." menu -> "Get pre-filled link" -> fill dummy values -> copy the generated URL).
function submit_to_google_form(string $name, string $email, string $message): array
{
    $formId = '1FAIpQLSdFGOGev8HaS5syCpEGWtYwGTZii3EjHK8i-9k29DBKKrQgnQ';
    $formUrl = "https://docs.google.com/forms/d/e/{$formId}/formResponse";
    $fields = [
        'entry.659399239' => $name,
        'entry.705874084' => $email,
        'entry.1481844681' => $message,
    ];

    if (!function_exists('curl_init')) {
        return ['ok' => false, 'detail' => 'curl extension is not available on this PHP install'];
    }

    $ch = curl_init($formUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $result   = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    return [
        'ok'     => $result !== false && $httpCode === 200,
        'detail' => "HTTP code: {$httpCode}" . ($error !== '' ? ", curl error: {$error}" : ''),
    ];
}

$name     = isset($_POST['name']) ? clean_header_value($_POST['name']) : '';
$email    = isset($_POST['email']) ? clean_header_value($_POST['email']) : '';
$message  = isset($_POST['message']) ? trim($_POST['message']) : '';
$honeypot = $_POST['hp_check_7f2a'] ?? '';

// Honeypot field: if filled in, a bot submitted the form — silently treat as success
if ($honeypot !== '') {
    header('Location: index.html?sent=1');
    exit;
}

$errors = [];
if ($name === '') {
    $errors[] = 'Please enter your name';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}
if ($message === '') {
    $errors[] = 'Message cannot be empty';
}

if ($errors) {
    http_response_code(400);
    echo '<p>Error: ' . htmlspecialchars(implode(', ', $errors), ENT_QUOTES, 'UTF-8')
        . '. <a href="index.html#contacts">Go back and try again</a></p>';
    exit;
}

$subject = '=?UTF-8?B?' . base64_encode('[VIBECODEQA] New contact form submission') . '?=';
$body    = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
$headers = "From: no-reply@" . ($_SERVER['SERVER_NAME'] ?? 'vibecodetesting.com') . "\r\n"
    . "Reply-To: $email\r\n"
    . "Content-Type: text/plain; charset=UTF-8\r\n"
    . "Content-Transfer-Encoding: 8bit";

mail($to, $subject, $body, $headers);
submit_to_google_form($name, $email, $message);

header('Location: index.html?sent=1');
exit;
