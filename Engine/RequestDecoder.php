<?php

namespace Engine;

class RequestDecoder
{
    public static function getRequest() : array
    {
        $request = [
            'class' => '',
            'method' => '',
            'params' => []
        ];
        $request['class'] = '\\Methods' .  str_replace('/', '\\', ucwords($_SERVER['REQUEST_URI'], '/'));
        if ($pos = strpos($request['class'], '?')) {
            $request['class'] = substr($request['class'], 0, $pos);
        }
        $request['method'] = strtolower($_SERVER['REQUEST_METHOD']);
        $request['params'] = array_merge($_GET, $_POST);
        if (!empty($request['params']['method'])) {
            $request['method'] = $request['params']['method'];
        }
        return $request;
    }
}
