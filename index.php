<?php
header('Content-Type: application/json');

require_once('./vendor/autoload.php'); //include composer autoloader

try {       //try to execute request
    $request = \Engine\RequestDecoder::getRequest();
    $data = \Engine\Executor::execute($request);
    $response = [
        'success' => 1
    ];
    if (!is_null($data)) {
        $response['data'] = $data;
    }
} catch (\Exception $e) {
    $response = [
        'success' => 0,
        'error' => $e->getMessage(),
        'error_code' => $e->getCode(),
    ];
}
echo json_encode($response);
