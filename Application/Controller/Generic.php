<?php
    namespace {
    }
    namespace Application\Controller {

        abstract class Generic extends \Hoathis\Kit\Aggregator {

            public function construct () {
                $this->displayPopup();

                $user = new \Hoa\Session\Session('user');
                if($user->isEmpty() === true)
                    $this->view->addUse('hoa://Application/View/Navbar/Default.xyl');
                else
                    $this->view->addUse('hoa://Application/View/Navbar/Connect.xyl');


                if(!$this->isAdminAllowed())
                    $this->view->addUse('hoa://Application/View/Admin/Navbar/Default.xyl');
                else
                    $this->view->addUse('hoa://Application/View/Admin/Navbar/Connect.xyl');

                $this->view->addUse('hoa://Application/View/Navbar/Search.xyl');
            }

            public function check ($name, $strict = false, &$variable = null) {
                if($variable === null)
                    $variable = $_POST;

                if($strict === true)
                    if(array_key_exists($name, $variable) && !empty($variable[$name]))
                        return $variable[$name];
                    else
                        return null;
                else if(array_key_exists($name, $variable))
                    return $variable[$name];
                else
                    return null;

            }

            public function displayPopup () {

                $flash = \Hoathis\Flash\Popup::getInstance();
                $pop   = $flash->serve();
                if(!empty($pop)) {
                    $this->data->stack = $pop;
                    $this->view->addOverlay('hoa://Application/View/Flash.xyl');
                }
            }

            public function popup ($type, $message) {

                $flash = \Hoathis\Flash\Popup::getInstance();
                $flash->{$type}($message);
            }

            public function guestGuard () {
                $user = new \Hoa\Session\Session('user');
                if($user->isEmpty() === true) {
                    $this->popup('info', 'You don`t have the require credential');
                    $this
                        ->getKit('Redirector')
                        ->redirect('home-caller', array('_able' => 'connect'));
                }
            }

            public function isAdminAllowed () {
                $user = new \Hoa\Session\Session('user');
                if(!$user->isEmpty()) {
                    $id = $user['idUser'];
                    if($id !== null) {
                        $model_user = new \Application\Model\User();
                        if($model_user->open(array('id' => $id)) === true) {
                            $rang = intval($model_user->rang);
                            if($rang >= 3) {
                                return true;
                            }

                        }
                    }
                }

                return false;
            }


        }
    }

