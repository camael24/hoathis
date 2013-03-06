<?php
    namespace {
    }
    namespace Application\Controller {

        class Main extends Generic {

            public function IndexAction () {

                $library = new \Application\Model\Library();
                $library = $library->getLastUpdateLibrary(5);

                $this->data->lastUpdate = $library;

                $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
                $this->view->render();
            }


            public function AllAction () {
                $model = new \Application\Model\Library();

                $all = $model->getAll();
                if(count($all) == 0) {

                    $this->view->addOverlay('hoa://Application/View/Main/NoHoathis.xyl');
                }
                else {
                    $this->data->hoathis = $all;
                    $this->view->addOverlay('hoa://Application/View/Main/List.xyl');
                }

                $this->view->render();
            }

            public function RegisterAction () {
                if(!empty($_POST)) {
                    $login     = $this->check('login', true);
                    $password  = $this->check('pass', true);
                    $rpassword = $this->check('rpass', true);
                    $mail      = $this->check('mail', true);

                    $error = false;
                    if($login === null) {
                        $this->popup('error', 'The field login is empty ');
                        $error = true;
                    }
                    else if($password === null) {
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
                        $this->popup('error', 'The filed password and retype your password must be egal ');
                        $error = true;
                    }
                    else if(strlen($login) < 3) {
                        $this->popup('error', 'Your login must have over 3 characters !');
                        $error = true;
                    }

                    $userModel = new \Application\Model\User();

                    if($userModel->check($login, 'username') === false) {
                        $this->popup('error', 'The filed username is not valid');
                        $error = true;
                    }
                    else if($userModel->check($mail, 'email') === false) {
                        $this->popup('error', 'The filed mail is not valid');
                        $error = true;
                    }
                    else if($userModel->check($password, 'password') === false) {
                        $this->popup('error', 'The filed password is not valid');
                        $error = true;
                    }

                    if($error === false) {
                        if(!$userModel->checkMail($mail)) {
                            $this->popup('error', 'Your email address has ever register in our database ');
                            $error = true;
                        }
                        if(!$userModel->checkUser($login)) {
                            $this->popup('error', 'Your login name has ever register in our database');
                            $error = true;
                        }
                    }
                    if($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'register'));
                    }
                    else {

                        $userModel->insert($login, $password, $mail);

                        $this->popup('success', 'you successfully registered! Welcome here you can connect');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'connect'));
                    }
                }


                $this->view->addOverlay('hoa://Application/View/Main/Register.xyl');
                $this->view->render();
            }

            public function ConnectAction () {

                $s = new \Hoa\Session\Session('user');
                if(!$s->isEmpty())
                    $this
                        ->getKit('Redirector')
                        ->redirect('home', array());

                $query = $this->router->getQuery(); //TODO a faire
                $page  = (isset($query['redirect']) && !empty($query['redirect'])) ? $query['redirect'] : null;


                $this->data->redirect = $page;


                if(!empty($_POST)) {
                    $email    = $this->check('login', true);
                    $password = $this->check('password', true);
                    $redirect = $this->check('redirect', true);
//                $remember = $this->check('remember'); //TODO add support of cookie


                    $error = false;
                    if($email === null) {
                        $this->popup('error', 'The field login is empty ');
                        $error = true;
                    }
                    else if($password === null) {
                        $this->popup('error', 'The field password is empty ');
                        $error = true;
                    }


                    $user = new \Application\Model\User();
                    if(!$user->connect($email, $password)) {
                        $this->popup('error', 'This credentials are not reconized here, your are might be banned or unactived');
                        $error = true;
                    }

                    if($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'connect'));
                    }
                    else {
                        $sUser             = new \Hoa\Session\Session('user');
                        $sUser['idUser']   = $user->idUser;
                        $sUser['username'] = $user->username;
                        $sUser['email']    = $user->mail;
                        ;

                        $this->popup('success', 'Hello ' . $user->username); //TODO change here
                        if($redirect === null)
                            $this
                                ->getKit('Redirector')
                                ->redirect('home', array());
                        else {
                            header('location:' . $page);
                            exit;
                        }
                    }

                }
                $this->view->addOverlay('hoa://Application/View/Main/Connect.xyl');
                $this->view->render();
//
            }

            public function ForgotAction () {
                $this->popup('info', 'this function is not implement yet!'); //TODO change here
                $this
                    ->getKit('Redirector')
                    ->redirect('home', array());

            }

            public function DisconnectAction () {
                \Hoa\Session\Session::destroy();
                $this
                    ->getKit('Redirector')
                    ->redirect('home', array());
            }

            public function SearchAction () {
                $search = $this->check('search', true);
                if($search === null) {
                    $this->popup('error', 'The field search is empty ');
                    $this
                        ->getKit('Redirector')
                        ->redirect('home', array());
                }

                if(strpos($search, '@') === 0) {
                    $user               = new \Application\Model\User();
                    $this->data->author = $user->search(substr($search, 1));


                }
                else {
                    $user               = new \Application\Model\User();
                    $this->data->author = $user->search($search);

                    $library            = new \Application\Model\Library();
                    $this->data->search = $library->search($search); //TODO : Allow search by user @foobar

                }
                $this->view->addOverlay('hoa://Application/View/Main/Search.xyl');
                $this->view->render();

            }

            public function CreateAction () {
                $this->guestGuard();

                if(!empty($_POST)) {
                    $name        = $this->check('name', true);
                    $description = $this->check('description', true);
                    $home        = $this->check('home', true);
                    $release     = $this->check('release', true);
                    $issue       = $this->check('issues');
                    $doc         = $this->check('doc');

                    $error = false;
                    if($name === null) {
                        $this->popup('error', 'The field name is empty ');
                        $error = true;
                    }
                    else if($description === null) {
                        $this->popup('error', 'The field description is empty ');
                        $error = true;
                    }
                    else if($home === null) {
                        $this->popup('error', 'The field homepage is empty ');
                        $error = true;
                    }
                    else if($release === null) {
                        $this->popup('error', 'The field release is empty ');
                        $error = true;
                    }

                    $user = new \Hoa\Session\Session('user');
                    $id   = $user['idUser'];

                    $library = new \Application\Model\Library();


                    if($library->check($description, 'description') === false) {
                        $this->popup('error', 'The field description is not valid');
                        $error = true;
                    }
                    else if($library->check($home, 'home') === false) {
                        $this->popup('error', 'The field home is not valid');
                        $error = true;
                    }
                    else if($library->check($release, 'release') === false) {
                        $this->popup('error', 'The field release is not valid');
                        $error = true;
                    }

                    if($error === false && $library->insert($id, $name, $description, $home, $release, $doc, $issue) === false) {
                        $this->popup('error', 'An project has ever a same name');
                        $error = true;
                    }

                    if($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'create'));
                    }
                    else {

                        $this->popup('success', 'Your projet has been create, you might wait his acception by the staff'); //TODO change here
                        $this
                            ->getKit('Redirector')
                            ->redirect('home', array());
                    }
                }

                $this->view->addOverlay('hoa://Application/View/Main/Create.xyl');
                $this->view->render();
            }

            public function ProfilAction () {
                $user = new \Hoa\Session\Session('user');

                $this
                    ->getKit('Redirector')
                    ->redirect('user-home', array('user' => $user['username']));
            }

            public function ListAction () { //TODO : List project by user like search @
                $user = new \Hoa\Session\Session('user');

                $this
                    ->getKit('Redirector')
                    ->redirect('user-caller', array(
                                                   'user'  => $user['username'],
                                                   '_able' => 'list'
                                              )
                );
            }
        }
    }

