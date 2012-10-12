<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Front extends Generic {

        public function ConnexionAction($_this) {
            $error = array();

            $check = function ($id) use (&$error) {
                if (array_key_exists($id, $_POST) && $_POST[$id] != '')
                    return $_POST[$id];
                else {
                    $error[] = $id;

                    return null;
                }

            };

            if (!empty($_POST)) {
                $user = $check('user');
                $pass = $check('pass');
                if (empty($error)) {
                    $model  = new \Application\Model\User();
                    $result = $model->connect($user, $pass);

                    if ($result === true) {
                        $session         = new \Hoa\Session\QNamespace('user');
                        $session->idUser = $model->idUser;

                        if ($model->rang == 2) {
                            new \Hoa\Session\QNamespace('admin'); // TODO : not really an ACL
                        }
                        $this->flash('success', 'Connexion success');
                        $_this->getKit('Redirector')->redirect('i', array());
                        $_this->getKit('Redirector')->redirect('i', array());


                    } else {
                        $this->flash('error', 'This credentials are not reconized here, your are might be banned or unactived');
                        $_this->getKit('Redirector')->redirect('i', array());
                    }

                } else {
                    $this->flash('error', 'This input are empty ' . implode(',', $error));
                    $_this->getKit('Redirector')->redirect('i', array());
                }


            } else {
                $this->view->addOverlay('hoa://Application/View/Front/Connexion.xyl');
            }

            $this->view->render();

        }

        public function DisconnectAction($_this) {
            \Hoa\Session\Session::unsetAllFlashes();
            \Hoa\Session\Session::unsetAllNamespaces();
            \Hoa\Session\Session::forgetMe();
            \Hoa\Session\Session::destroy();
//            $this->flash('success', 'Disconnect success');
            $_this->getKit('Redirector')->redirect('i', array());
        }

        public function RegisterAction($_this) {
            $error = array();

            $check = function ($id) use (&$error) {
                if (array_key_exists($id, $_POST) && $_POST[$id] != '')
                    return $_POST[$id];
                else {
                    $error[] = $id;

                    return null;
                }

            };

            if (!empty($_POST)) {
                $user      = $check('user');
                $password  = $check('pass');
                $rpassword = $check('rpass');
                $mail      = $check('mail');

                if (empty($error)) {
                    if ($password === $rpassword) {

                        $model = new \Application\Model\User();

                        if (!$model->checkMail($mail)) {
                            $this->flash('error', 'Your mail has ever register');
                            $_this->getKit('Redirector')->redirect('i', array());
                        } else if (!$model->checkUser($user)) {
                            $this->flash('error', 'Your username has ever register');
                            $_this->getKit('Redirector')->redirect('i', array());
                        } else {
                            $model->insert($user, $password, $mail);
                            $this->flash('success', 'Register success');
                            $_this->getKit('Redirector')->redirect('i', array());
                        }

                    } else {

                        $this->flash('error', 'Your field "Password" and "Retype Password" are not equal');
                        $_this->getKit('Redirector')->redirect('i', array());
                    }
                } else {
                    $this->flash('error', 'This input are empty ' . implode(',', $error));
                    $_this->getKit('Redirector')->redirect('i', array());
                }

            } else {
                $this->view->addOverlay('hoa://Application/View/Front/Register.xyl');
            }

            $this->view->render();

        }

        public function ForgotAction($_this) {
            $this->flash('info', 'This is not implement');
            $_this->getKit('Redirector')->redirect('i', array());
        }
    }
}

