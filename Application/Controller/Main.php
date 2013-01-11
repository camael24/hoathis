<?php
namespace {
}
namespace Application\Controller {

    class Main extends Generic {

        public function IndexAction() {

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function RegisterAction() {
            $this->view->addOverlay('hoa://Application/View/Main/Register.xyl');
            $this->view->render();
        }

        public function ConnectAction() {

            if (!empty($_POST)) {
                $email    = $this->check('email', true);
                $password = $this->check('password', true);
                $remember = $this->check('remember'); //TODO add support of cookie


                $error = false;
                if ($email === null) {
                    $this->popup('error', 'The field email is empty ');
                    $error = true;
                } else if ($password === null) {
                    $this->popup('error', 'The field password is empty ');
                    $error = true;
                }


                if ($error === true) {
                    $this->getKit('Redirector')->redirect('w', array('_able' => 'connect'));
                } else {
                    // TODO Connection @BDD
                    $user        = new \Hoa\Session\Session('user');
                    $user['foo'] = 'bar';


                    $this->popup('success', 'Hello @NAME :D'); //TODO change here

                    $this->getKit('Redirector')->redirect('i', array());
                }

            } else {

            }

            $this->view->addOverlay('hoa://Application/View/Main/Connect.xyl');
            $this->view->render();
        }

        public function DisconnectAction() {
            \Hoa\Session\Session::destroy();
            $this->getKit('Redirector')->redirect('i', array());
        }
    }
}

