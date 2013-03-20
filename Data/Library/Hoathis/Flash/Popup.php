<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 20/03/13
     * Time: 12:56
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Flash {
        class Popup {
            private static $_instance = null;

            /**
             * @return Popup
             */

            public static function getInstance () {
                if(self::$_instance === null)
                    self::$_instance = new Popup();

                return self::$_instance;
            }

            public function __call ($name, $args) {
                $message = array_pop($args);
                $this->_message($name, $message);

            }

            protected function _message ($type, $message) {
                $session         = new \Hoa\Session\Session('popups');
                $array           = $session['pops'];
                $array[]         = array(
                    'type'    => $type,
                    'message' => $message
                );
                $session['pops'] = $array;
            }

            public function serve () {
                $session = new \Hoa\Session\Session('popups');
                if($session->isEmpty())
                    return array();

                $pops = $session['pops'];
                unset($session['pops']);

                return $pops;
            }

        }
    }
