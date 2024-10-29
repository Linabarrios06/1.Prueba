<?php
require 'vendor/autoload.php';

use Dnetix\Redirection\PlacetoPay;

$placetopay = new PlacetoPay([
    'login' => '2d9eaf1e662518756a3d78806543af5b',
    'tranKey' => '3YC5brb5eAR4xBGQ',
    'baseUrl' => 'https://checkout-test.placetopay.com/',
    'timeout' => 10,
]);

$requestId = '3003294'; // Reemplaza con tu requestId

$response = $placetopay->query($requestId);

if ($response->isSuccessful()) {
    $status = $response->status()->status();
    if ($status == 'APPROVED') {
        echo 'Pago aprobado';
    } elseif ($status == 'REJECTED') {
        echo 'Pago rechazado';
    } else {
        echo 'Pago pendiente u otro estado: ' . $status;
    }
} else {
    echo 'Error: ' . $response->status()->message();
}
?>