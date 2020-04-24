<?php

namespace Services;

class ConfigReader extends \Engine\Common\Singleton implements \ArrayAccess
{
    private $config = [];
    
    private function readConfigs($dir)
    {
        $catalog = opendir($dir);
        while ($filename = readdir($catalog)) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            if (is_dir($path)) {
                readConfig($path);
            } else {
                $this->config[str_replace('.ini', '', $filename)]
                    = parse_ini_file($path, true, INI_SCANNER_TYPED);
            }
        }
        closedir($catalog);
    }

    protected function __construct()
    {
        $this->readConfigs('.' . DIRECTORY_SEPARATOR . 'Config');
    }
    
    public function offsetSet($offset, $value)
    {
        throw new \Exception('only read operation allowed');
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetUnset($offset)
    {
        throw new \Exception('only read operation allowed');
    }

    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }
}
