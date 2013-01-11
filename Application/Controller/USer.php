<?php
namespace {
}
namespace Application\Controller {

    class User extends Generic {

        public function IndexAction() {

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function ProfilAction($user) {

            $this->data->login = $user;
            $this->data->mail  = 'foo@bar.com';
            $this->data->rang  = 'Boulet';

            if (true) // TODO : Here check for editing Profil !
                $this->data->edit = '<a href="' . $this->router->unroute('up', array('user' => $user, '_able' => 'edit')) . '"><i class="icon-pencil"></i></a>';

            $this->view->addOverlay('hoa://Application/View/User/Profil.xyl');
            $this->view->render();

        }

        public function EditAction($user) {

            $this->guestGuard();

            if (!empty($_POST)) {
                $login     = $this->check('login', true);
                $password  = $this->check('pass');
                $rpassword = $this->check('rpass');
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
                    $this->getKit('Redirector')->redirect('up', array('user' => $user, '_able' => 'edit'));
                } else {
                    // TODO Connection @BDD
                    $this->popup('success', 'Your register is an success !, welcome here'); //TODO change here

                    $this->getKit('Redirector')->redirect('i', array());
                }
            }


            $this->data->login = $user;
            $this->data->mail  = 'foo@bar.com';
            $this->data->rang  = 'Boulet';

            $this->view->addOverlay('hoa://Application/View/User/Edit.xyl');
            $this->view->render();
        }


    }
}

