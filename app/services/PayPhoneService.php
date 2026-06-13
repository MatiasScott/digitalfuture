<?php

declare(strict_types=1);

class PayPhoneService
{
    public function createCharge(array $payload): array
    {
        $apiUrl = Env::get('PAYPHONE_API_URL', '');
        $token = Env::get('PAYPHONE_TOKEN', '');
        $storeId = Env::get('PAYPHONE_STORE_ID', '');

        if ($apiUrl === '' || $token === '' || $storeId === '') {
            return [
                'success' => false,
                'message' => 'Configuracion PayPhone incompleta en .env',
                'data' => null,
            ];
        }

        $requestPayload = [
            'storeId' => (int) $storeId,
            'amount' => (int) round(((float) $payload['amount']) * 100),
            'amountWithoutTax' => (int) round(((float) $payload['amount']) * 100),
            'tax' => 0,
            'clientTransactionId' => $payload['clientTransactionId'],
            'reference' => $payload['reference'],
            'currency' => 'USD',
        ];

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($requestPayload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 20,
        ]);

        $responseBody = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($responseBody === false || $curlError !== '') {
            return [
                'success' => false,
                'message' => 'Error de conexion con PayPhone: ' . $curlError,
                'data' => null,
            ];
        }

        $decoded = json_decode((string) $responseBody, true);
        if (!is_array($decoded)) {
            return [
                'success' => false,
                'message' => 'Respuesta invalida de PayPhone',
                'data' => ['http_code' => $httpCode, 'raw' => $responseBody],
            ];
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            return [
                'success' => false,
                'message' => 'PayPhone rechazo la solicitud',
                'data' => ['http_code' => $httpCode, 'response' => $decoded],
            ];
        }

        return [
            'success' => true,
            'message' => 'Pago inicializado en PayPhone',
            'data' => $decoded,
        ];
    }
}
