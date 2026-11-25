<?php
header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/data.json';

// Only the fields the ESP32 (DHT11, Raindrop, PIR) actually sends.
// Anything else (e.g. old distance/light/sonar fields) is dropped.
$allowedFields = [
    'system_ok',
    'missing',
    'temperature',
    'humidity',
    'rain_status',
    'motion',
    'dht_ok',
    'rain_ok',
    'pir_ok',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    $data = array_intersect_key($data, array_flip($allowedFields));

    $written = @file_put_contents($file, json_encode($data), LOCK_EX);

    if ($written === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Could not write data.json — check folder write permissions']);
        exit;
    }

    echo json_encode(['status' => 'OK']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!file_exists($file)) {
        echo json_encode(['error' => 'No data yet']);
        exit;
    }

    $raw  = file_get_contents($file);
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        echo json_encode(['error' => 'Corrupt data file']);
        exit;
    }

    // Strip leftovers from older firmware that may still be in data.json
    $data = array_intersect_key($data, array_flip($allowedFields));
    $data['_last_modified'] = filemtime($file);
    echo json_encode($data);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);