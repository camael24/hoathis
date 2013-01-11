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
            if (!empty($_POST)) {
                $login     = $this->check('login', true);
                $password  = $this->check('pass', true);
                $rpassword = $this->check('rpass', true);
                $mail      = $this->check('mail', true);

                $error = false;
                if ($login === null) {
                    $this->popup('error', 'The field login is empty ');
                    $error = true;
                } else if ($password === null) {
                    $this->popup('error', 'The field password is empty ');
                    $error = true;
                } else if ($rpassword === null) {
                    $this->popup('error', 'The field retype-password is empty ');
                    $error = true;
                } else if ($mail === null) {
                    $this->popup('error', 'The field Email is empty ');
                    $error = true;
                } else if ($password !== $rpassword) {
                    $this->popup('error', 'The filed password and retype your password must be egal ');
                    $error = true;
                }


                if ($error === true) {
                    $this->getKit('Redirector')->redirect('w', array('_able' => 'register'));
                } else {
                    // TODO Connection @BDD
                    $user        = new \Hoa\Session\Session('user');
                    $user['foo'] = 'bar';


                    $this->popup('success', 'Your register is an success !, welcome here'); //TODO change here

                    $this->getKit('Redirector')->redirect('i', array());
                }
            }


            $this->view->addOverlay('hoa://Application/View/Main/Register.xyl');
            $this->view->render();
        }

        public function ConnectAction() {

            if (!empty($_POST)) {
                $email    = $this->check('login', true);
                $password = $this->check('password', true);
                $remember = $this->check('remember'); //TODO add support of cookie


                $error = false;
                if ($email === null) {
                    $this->popup('error', 'The field login is empty ');
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

            }

            $this->view->addOverlay('hoa://Application/View/Main/Connect.xyl');
            $this->view->render();
        }

        public function ForgotAction() {
            $this->popup('info', 'this function is not implement yet!'); //TODO change here
            $this->getKit('Redirector')->redirect('i', array());

        }

        public function DisconnectAction() {
            \Hoa\Session\Session::destroy();
            $this->getKit('Redirector')->redirect('i', array());
        }

        public function SearchAction() {
            $search = $this->check('search', true);
            if ($search === null) {
                $this->popup('error', 'The field search is empty ');
                $this->getKit('Redirector')->redirect('i', array());
            }

            var_dump('Here is the search !');

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function CreateAction() {
            $this->guestGuard();

            $this->view->addOverlay('hoa://Application/View/Main/Create.xyl');
            $this->view->render();
        }
    }
}

