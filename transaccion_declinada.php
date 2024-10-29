<?php
require 'vendor/autoload.php';

use Dnetix\Redirection\PlacetoPay;

$placetopay = new PlacetoPay([
    'login' => '2d9eaf1e662518756a3d78806543af5b',
    'tranKey' => '3YC5brb5eAR4xBGQ',
    'baseUrl' => 'https://checkout-test.placetopay.com/',
    'timeout' => 10,
]);

$reference = 'TRANSACCION_RECHAZADA';
$request = [
    'payment' => [
        'reference' => $reference,
        'description' => 'Prueba rechazo',
        'amount' => [
            'currency' => 'USD',
            'total' => 120,
        ],
    ],
    'expiration' => date('c', strtotime('+2 days')),
    'returnUrl' => 'http://example.com/response?reference=' . $reference,
    'ipAddress' => '127.0.0.1',
    'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
];

$response = $placetopay->request($request);

if ($response->isSuccessful()) {
    echo 'Process URL: ' . $response->processUrl();
} else {
    echo 'Error: ' . $response->status()->message();
}
?>
