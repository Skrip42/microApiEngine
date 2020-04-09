<?php
define('ENGINE_DIR', 'Engine');
define('METHOD_DIR', 'Methods');
define('SERVICES_DIR', 'Services');

function loadClass(string $dir)
{
    $catalog = opendir($dir);
    while ($filename = readdir($catalog)) {
        if ($filename == '.' || $filename == '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        if (is_dir($path)) {
            loadClass($dir);
        } else {
            include_once($path);
        }
    }
    closedir($catalog);
}

loadClass('.' . DIRECTORY_SEPARATOR . ENGINE_DIR);
loadClass('.' . DIRECTORY_SEPARATOR . METHOD_DIR);
loadClass('.' . DIRECTORY_SEPARATOR . SERVICES_DIR);

try {
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
