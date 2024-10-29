<?php
function makeSignature($nonce, $seed, $secretKey) {
    return base64_encode(sha1($nonce . $seed . $secretKey, true));
}

$login = '2d9eaf1e662518756a3d78806543af5b';
$secretKey = '3YC5brb5eAR4xBGQ';

$nonce = bin2hex(random_bytes(16));
$seed = date('c');
$tranKey = makeSignature($nonce, $seed, $secretKey);

$auth = [
    'login' => $login,
    'seed' => $seed,
    'nonce' => base64_encode($nonce),
    'tranKey' => $tranKey
];

$data = [
    'auth' => $auth,
    'payment' => [
        'reference' => 'ORDER_ID_12345',
        'description' => 'Testing payment',
        'amount' => [
            'currency' => 'USD',
            'total' => 120,
        ],
    ],
    'expiration' => date('c', strtotime('+2 days')),
    'returnUrl' => 'http://example.com/response?reference=ORDER_ID_12345',
    'ipAddress' => '127.0.0.1',
    'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
];

$ch = curl_init('https://checkout-test.placetopay.com/api/session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

curl_close($ch);

if ($response === false) {
    echo 'Error en la solicitud cURL: ' . $curl_error;
} else {
    echo 'Código HTTP: ' . $http_code . '<br>';
    echo 'Respuesta completa del servidor: ' . $response . '<br>';
    $result = json_decode($response, true);
    if ($result === null) {
        echo 'Error al decodificar la respuesta JSON.';
    } else {
        if (isset($result['status']['status']) && $result['status']['status'] == 'OK') {
            echo 'Preautorización exitosa. ID: ' . $result['requestId'];
        } else {
            echo 'Error en la preautorización: ' . ($result['status']['message'] ?? 'Respuesta desconocida');
        }
    }
}
?>
