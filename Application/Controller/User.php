<?php
    namespace {
    }
    namespace Application\Controller {

        class User extends Generic {

            public function IndexAction () {

                $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
                $this->view->render();

            }

            public function ProfilAction ($user) {


                $userD = new \Hoa\Session\Session('user');
                $userM = new \Application\Model\User();

                $userM->openByName(array('name' => $user));

                $lib                 = new \Application\Model\Library();
                $rang                = $userM->getRangLabel($userM->rang);
                $this->data->user    = ucfirst($userM->username);
                $this->data->project = $lib->getFromAuthorName($userM->username);


                $this->data->login     = $userM->username;
                $this->data->mail      = $userM->email;
                $this->data->RangLabel = $rang['RangLabel'];
                $this->data->RangClass = $rang['RangClass'];

                if(intval($userM->idUser) === $userD['idUser']) // TODO : Here check for editing Profil !
                    $this->data->edit = '<a href="' . $this->router->unroute('user-caller', array(
                                                                                                 'user'  => $user,
                                                                                                 '_able' => 'edit'
                                                                                            )
                    ) . '"><i class="icon-pencil"></i></a>';

                $this->view->addOverlay('hoa://Application/View/User/Profil.xyl');
                $this->view->render();

            }


            public function EditAction ($user) {

                $userD = new \Hoa\Session\Session('user');
                $userM = new \Application\Model\User();
                $userM->openByName(array('name' => $user));

                if($userD['idUser'] !== intval($userM->idUser)) {
                    $this->popup('info', 'You don`t have the require credential');
                    $this
                        ->getKit('Redirector')
                        ->redirect('home-caller', array('_able' => 'connect'));
                }

                $this->guestGuard();

                if(!empty($_POST)) {
                    $error     = false;
                    $password  = $this->check('pass');
                    $rpassword = $this->check('rpass');
                    $mail      = $this->check('mail', true);


                    if($password === null) {
                        $this->popup('error', 'The field password is empty ');
                        $error = true;
                    }
                    else if($rpassword === null) {
                        $this->popup('error', 'The field retype-password is empty ');
                        $error = true;
                    }
                    else if($mail === null) {
                        $this->popup('error', 'The field Email is empty ');
                        $error = true;
                    }
                    else if($password !== $rpassword) {
                        $this->popup('error', 'The field password and retype your password must be egal ');
                        $error = true;
                    }

                    if(!empty($password) && $userM->check($password, 'pass') === false) {
                        $this->popup('error', 'The field password is not valid');
                        $error = true;
                    }
                    else if(!empty($mail) && $userM->check($mail, 'mail') === false) {
                        $this->popup('error', 'The field mail is not valid');
                        $error = true;
                    }


                    if($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('user-caller', array(
                                                           'user'  => $user,
                                                           '_able' => 'edit'
                                                      )
                            );
                    }
                    else {
                        $userM->setPassword($userM->idUser, $password);
                        $userM->setMail($userM->idUser, $mail);
                        $this->popup('success', 'Thanks for update your personnal information');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home', array());
                    }
                }


                $this->data->login = $userM->username;
                $this->data->mail  = $userM->email;
                $this->data->rang  = $userM->RangLabel;

                $this->view->addOverlay('hoa://Application/View/User/Edit.xyl');
                $this->view->render();
            }


        }
    }