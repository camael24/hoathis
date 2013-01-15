<?php
namespace {
}
namespace Application\Controller {

    class User extends Generic {

        public function IndexAction() {

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function ListAction($user) {

            $lib      = new \Application\Model\Library();
            $userData = new \Application\Model\User();
            $userData->openByName(array('name' => $user));

            $this->data->user    = ucfirst($userData->username);
            $this->data->project = $lib->getFromAuthorName($userData->username);

            $this->view->addOverlay('hoa://Application/View/User/List.xyl');
            $this->view->render();
        }

        public function ProfilAction($user) {


            $userD = new \Hoa\Session\Session('user');
            $userM = new \Application\Model\User();
            $userM->openByName(array('name' => $user));

            $this->data->login = $userM->username;
            $this->data->mail  = $userM->email;
            $this->data->rang  = 'Basic';

            if (intval($userM->idUser) === $userD['idUser']) // TODO : Here check for editing Profil !
                $this->data->edit = '<a href="' . $this->router->unroute('up', array('user' => $user, '_able' => 'edit')) . '"><i class="icon-pencil"></i></a>';

            $this->view->addOverlay('hoa://Application/View/User/Profil.xyl');
            $this->view->render();

        }


        public function EditAction($user) {

            $this->guestGuard();

            if (!empty($_POST)) {
                $userD = new \Hoa\Session\Session('user');
                $userM = new \Application\Model\User();
                $userM->openByName(array('name' => $user));
                $error = false;
                if (intval($userM->idUser) !== $userD['idUser']) { // TODO : Here check for editing Profil !
                    $this->popup('error', 'You are not allow to edit this');
                    $error = true;
                }
                $password  = $this->check('pass');
                $rpassword = $this->check('rpass');
                $mail      = $this->check('mail', true);


                if ($password === null) {
                    $this->popup('error', 'The field password is empty ');
                    $error = true;
                } else if ($rpassword === null) {
                    $this->popup('error', 'The field retype-password is empty ');
                    $error = true;
                } else if ($mail === null) {
                    $this->popup('error', 'The field Email is empty ');
                    $error = true;
                } else if ($password !== $rpassword) {
                    $this->popup('error', 'The field password and retype your password must be egal ');
                    $error = true;
                }


                if ($error === true) {
                    $this->getKit('Redirector')->redirect('up', array('user' => $user, '_able' => 'edit'));
                } else {
                    $userM->setPassword($userM->idUser, $password);
                    $userM->setMail($userM->idUser, $mail);
                    $this->popup('success', 'Your register is an success !, welcome here');
                    $this->getKit('Redirector')->redirect('i', array());
                }
            }


            $userM = new \Application\Model\User();
            $userM->openByName(array('name' => $user));

            $this->data->login = $userM->username;
            $this->data->mail  = $userM->email;
            $this->data->rang  = 'Basic';

            $this->view->addOverlay('hoa://Application/View/User/Edit.xyl');
            $this->view->render();
        }


    }
}

