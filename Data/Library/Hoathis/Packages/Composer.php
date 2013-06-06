<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 06/06/13
     * Time: 14:29
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Packages {
        class Composer
        {
            private $_composer = array();

            public function setComposer ($composer)
            {
                $this->_composer = $composer;
            }


            public function getComposer ()
            {
                return $this->_composer;
            }

            public function setKey ($key, $value)
            {
                $this->_composer[$key] = $value;
            }

            public function getKey ($key)
            {
                if (array_key_exists($key, $this->_composer)) {
                    return $this->_composer[$key];
                }

                return null;
            }

        }
    }
