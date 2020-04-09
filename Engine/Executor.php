<?php

namespace Engine;

class Executor
{
    public static function execute($request)
    {
        if (!class_exists($request['class'])) {
            throw new \Exception($request['class'] . ' class not found');
        }
        $refClass = new \ReflectionClass($request['class']);
        if ($refClass->isAbstract()) {
            throw new \Exception($request['class'] . ' class not found');
        }
        if (!$refClass->hasMethod($request['method'])) {
            throw new \Exception($request['method'] . ' method not found in ' . $request['class']);
        }
        $refMethod = $refClass->getMethod($request['method']);
        if ($refMethod->isPrivate()) {
            throw new \Exception($request['method'] . ' method not found in ' . $request['class']);
        }
        $refParameters = $refMethod->getParameters();
        $parameters = [];
        foreach ($refParameters as $refParameter) {
            $paramName = $refParameter->getName();
            if (!isset($request['params'][$paramName])) {
                if (!$refParameter->isOptional()) {
                    throw new \Exception($paramName . ' is required');
                }
                $param = $refParameter->getDefaultValue();
            } else {
                $param = $request['params'][$paramName];
            }
            $parameters[$refParameter->getPosition()] = $param;
        }
        if ($refMethod->isStatic()) {
            return forward_static_call_array([$request['class'], $request['method']], $parameters);
        } else {
            return call_user_func_array([new $request['class'], $request['method']], $parameters);
        }
    }
}
