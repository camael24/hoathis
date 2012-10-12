<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Front extends Generic {

        public function ConnexionAction() {
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

                        $this->data->message = 'Connexion success';
                        $this->view->addOverlay('hoa://Application/View/Front/Success.xyl');

                    } else {
                        $this->data->error = 'This credentials are not reconized here';
                        $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                    }

                } else {
                    $this->data->error = 'This input are empty ' . implode(',', $error);
                    $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                }


            } else {
                $this->view->addOverlay('hoa://Application/View/Front/Connexion.xyl');
            }

            $this->view->render();

        }

        public function DisconnectAction() {
            \Hoa\Session\Session::unsetAllFlashes();
            \Hoa\Session\Session::unsetAllNamespaces();
            \Hoa\Session\Session::forgetMe();
            \Hoa\Session\Session::destroy();
            $this->view->addOverlay('hoa://Application/View/Front/Disconnect.xyl');
            $this->view->render();
        }

        public function RegisterAction() {
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
                            $this->data->error = 'Your mail has ever register';
                            $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                        } else if (!$model->checkUser($user)) {
                            $this->data->error = 'Your username has ever register';
                            $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                        } else {
                            $model->insert($user, $password, $mail);
                            $this->data->message = 'Register success';
                            $this->view->addOverlay('hoa://Application/View/Front/Success.xyl');
                        }

                    } else {
                        $this->data->error = 'Your field "Password" and "Retype Password" are not equal';
                        $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                    }
                } else {
                    $this->data->error = 'This input are empty ' . implode(',', $error);
                    $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                }

            } else {
                $this->view->addOverlay('hoa://Application/View/Front/Register.xyl');
            }

            $this->view->render();

        }

        public function ForgotAction() {
            $this->view->addOverlay('hoa://Application/View/Notimplement.xyl');
            $this->view->render();
        }
    }
}

