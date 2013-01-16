<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 16/01/13
 * Time: 15:37
 * To change this template use File | Settings | File Templates.
 */
namespace Hoathis\Context {
    class Context {
        private $_json = null;

        public function load($name) {
            file_put_contents('hoa://Data/Etc/Context/default', $name);

        }

        public function read() {

            $default = file_get_contents('hoa://Data/Etc/Context/default');
            $default = 'hoa://Data/Etc/Context/' . $default . '/context.json';
            $default = file_get_contents($default);

            $json = json_decode($default);

            $this->_json = $json;
        }

        public function __get($key) {
            $reflection = new \ReflectionObject($this->_json);
            if ($reflection->hasProperty($key))
                return $reflection->getProperty($key)->getValue($this->_json);
            else
                return null;

        }
    }
}
