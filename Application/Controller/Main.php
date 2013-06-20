<?php
    namespace {
    }
    namespace Application\Controller {

        class Main extends Generic
        {

            public function IndexAction()
            {

                $library = new \Application\Model\Library();
                $library = $library->getLastUpdateLibrary(5);

                if (count($library) < 1) {
                    $library[0] = array(
                        'label' => 'No Library Found',
                        'time'  => time()
                    );
                }

                $this->data->lastUpdate = $library;

                $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
                $this->view->render();
            }


            public function AllAction()
            {
                $model               = new \Application\Model\Library();
                $query               = $this->router->getQuery();
                $page                = isset($query['page']) ? $query['page'] : 1;
                $itemPerPage         = 10;
                $fistEntry           = ($page - 1) * $itemPerPage;
                $all                 = $model->getList($fistEntry, $itemPerPage);
                $this->data->number  = ceil(count($model->getAll()) / $itemPerPage);
                $this->data->current = $page;
                if (count($all) == 0) {
                    $this->view->addOverlay('hoa://Application/View/Main/NoHoathis.xyl');
                } else {
                    $this->data->hoathis = $all;
                    $this->view->addOverlay('hoa://Application/View/Main/List.xyl');
                }

                $this->view->render();
            }

            public function RegisterAction()
            {
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
                    } else if (strlen($login) < 3) {
                        $this->popup('error', 'Your login must have over 3 characters !');
                        $error = true;
                    }

                    $userModel = new \Application\Model\User();

                    if ($userModel->check($login, 'username') === false) {
                        $this->popup('error', 'The filed username is not valid');
                        $error = true;
                    } else if ($userModel->check($mail, 'email') === false) {
                        $this->popup('error', 'The filed mail is not valid');
                        $error = true;
                    } else if ($userModel->check($password, 'password') === false) {
                        $this->popup('error', 'The filed password is not valid');
                        $error = true;
                    }

                    if ($error === false) {
                        if (!$userModel->checkMail($mail)) {
                            $this->popup('error', 'Your email address has ever register in our database ');
                            $error = true;
                        }
                        if (!$userModel->checkUser($login)) {
                            $this->popup('error', 'Your login name has ever register in our database');
                            $error = true;
                        }
                    }
                    if ($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'register'));
                    } else {

                        $userModel->insert($login, $password, $mail);

                        $msg            = new \Hoa\Mail\Message();
                        $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                        $msg['To']      = $login . ' <' . $mail . '>';
                        $msg['Subject'] = 'Register on Hoathis.net';


                        $text = 'Welcome on Hoathis :' . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= '  Your login          : ' . $login . "\n";
                        $text .= '  You can log in here : http://hoathis.net' . $this->router->unroute('home-caller', array('_able' => 'connect')) . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= 'This email come from a bot , not reply to this mail' . "\n";

                        $msg->addContent(new \Hoa\Mail\Content\Text($text));

                        $msg->send();


                        $this->popup('success', 'you successfully registered! Welcome here you can connect');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'connect'));
                    }
                }


                $this->view->addOverlay('hoa://Application/View/Main/Register.xyl');
                $this->view->render();
            }

            public function ConnectAction()
            {

                $s = new \Hoa\Session\Session('user');
                if (!$s->isEmpty())
                    $this
                        ->getKit('Redirector')
                        ->redirect('home', array());

                $query = $this->router->getQuery();
                $page  = (isset($query['redirect']) && !empty($query['redirect'])) ? $query['redirect'] : null;


                $this->data->redirect = $page;


                if (!empty($_POST)) {
                    $email    = $this->check('login', true);
                    $password = $this->check('password', true);
                    $redirect = $this->check('redirect', true);

                    $error = false;
                    if ($email === null) {
                        $this->popup('error', 'The field login is empty ');
                        $error = true;
                    } else if ($password === null) {
                        $this->popup('error', 'The field password is empty ');
                        $error = true;
                    }


                    $user = new \Application\Model\User();
                    if (!$user->connect($email, $password)) {
                        $this->popup('error', 'This credentials are not reconized here, your are might be banned or unactived');
                        $error = true;
                    }

                    if ($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'connect'));
                    } else {
                        $sUser             = new \Hoa\Session\Session('user');
                        $sUser['idUser']   = $user->idUser;
                        $sUser['username'] = $user->username;
                        $sUser['email']    = $user->mail;;

                        $this->popup('success', 'Welcomme in Hoathis.net project');
                        if ($redirect === null)
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

            public function ForgotAction()
            {

                if (!empty($_POST)) {
                    $email = $this->check('email', true);
                    $token = md5(time());

                    $error = false;
                    if ($email === null) {
                        $this->popup('error', 'The field email is empty ');
                        $error = true;
                    }

                    $user = new \Application\Model\User();

                    if ($user->check($email, 'email') === false) {
                        $this->popup('error', 'The field email is not valid');
                        $error = true;
                    }

                    if ($error === false && $user->setToken($email, $token) === false) {
                        $this->popup('error', 'This email is not register here !');
                        $error = true;
                    }
                    if ($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'forgot'));
                    } else {
                        $author = $user->getByEmail($email);

                        $msg            = new \Hoa\Mail\Message();
                        $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                        $msg['To']      = $author['username'] . ' < ' . $author['email'] . ' >';
                        $msg['Subject'] = 'Recovery password';


                        $text = 'Recovery password process :' . "\n";
                        $text .= '----------------------------------------------------------------------------------------------------------------' . "\n";
                        $text .= '  Email address                                                            : ' . $email . "\n";
                        $text .= '  Recovery token                                                           : ' . $token . "\n";
                        $text .= '  Recovery link                                                            : http://hoathis.net' . $this->router->unroute('home-caller', array('_able' => 'recovery')) . '?token=' . $token . '&email=' . $email . "\n";
                        $text .= '----------------------------------------------------------------------------------------------------------------' . "\n";
                        $text .= '  If the previous link doesn\'t work well , please go manually to          : http://hoathis.net' . $this->router->unroute('home-caller', array('_able' => 'recovery')) . "\n";
                        $text .= '----------------------------------------------------------------------------------------------------------------' . "\n";
                        $text .= 'This email come from a bot , not reply to this mail' . "\n";

                        $msg->addContent(new \Hoa\Mail\Content\Text($text));

                        $msg->send();


                        $this->popup('success', 'An email has been sent with the procedure');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home', array());
                    }
                }

                $this->view->addOverlay('hoa://Application/View/Main/Forgot.xyl');
                $this->view->render();
            }

            public function DisconnectAction()
            {
                \Hoa\Session\Session::destroy();
                $this
                    ->getKit('Redirector')
                    ->redirect('home', array());
            }

            public function SearchAction()
            {
                $search = $this->check('search', true);
                if ($search === null) {
                    $this->popup('error', 'The field search is empty ');
                    $this
                        ->getKit('Redirector')
                        ->redirect('home', array());
                }

                if (strpos($search, '@') === 0) {
                    $user = new \Application\Model\User();
                    $user = $user->search(substr($search, 1));

                    if (count($user) < 1) {
                        $user[0] = array(
                            'label' => 'No User found',
                        );
                    }
                    foreach ($user as $i => $u)
                        if (array_key_exists('email', $u))
                            $user[$i]['gravatar'] = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($u['email']))) . '?d=identicon&s=25';
                    $this->data->author = $user;

                } else {
                    $user = new \Application\Model\User();
                    $user = $user->search($search);
                    if (count($user) < 1) {
                        $user[0] = array(
                            'label' => 'No User found',
                        );
                    }
                    foreach ($user as $i => $u)
                        if (array_key_exists('email', $u))
                            $user[$i]['gravatar'] = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($u['email']))) . '?d=identicon&s=25';
                    $this->data->author = $user;

                    $library = new \Application\Model\Library();
                    $library = $library->search($search);
                    if (count($library) < 1) {
                        $library[0] = array(
                            'label' => 'No Library Found',
                            'time'  => time()
                        );
                    }
                    $this->data->search = $library;

                }
                $this->view->addOverlay('hoa://Application/View/Main/Search.xyl');
                $this->view->render();

            }

            public function CreateAction()
            {
                $this->guestGuard();

                if (!empty($_POST)) {
                    $name        = $this->check('name', true);
                    $description = $this->check('description', true);
                    $home        = $this->check('home', true);
                    $release     = $this->check('release', true);
                    $issue       = $this->check('issues');
                    $doc         = $this->check('doc');

                    $error = false;
                    if ($name === null) {
                        $this->popup('error', 'The field name is empty ');
                        $error = true;
                    } else if ($description === null) {
                        $this->popup('error', 'The field description is empty ');
                        $error = true;
                    } else if ($home === null) {
                        $this->popup('error', 'The field homepage is empty ');
                        $error = true;
                    } else if ($release === null) {
                        $this->popup('error', 'The field release is empty ');
                        $error = true;
                    }

                    $user = new \Hoa\Session\Session('user');
                    $id   = $user['idUser'];

                    $library = new \Application\Model\Library();


                    if ($library->check($description, 'description') === false) {
                        $this->popup('error', 'The field description is not valid');
                        $error = true;
                    } else if ($library->check($name, 'name') === false) {
                        $this->popup('error', 'The field name is not valid');
                        $error = true;
                    } else if ($library->check($home, 'home') === false) {
                        $this->popup('error', 'The field home is not valid');
                        $error = true;
                    } else if ($library->check($release, 'release') === false) {
                        $this->popup('error', 'The field release is not valid');
                        $error = true;
                    }


                    if ($error === false && $library->insert($id, $name, $description, $home, $release, $doc, $issue) === false) {
                        $this->popup('error', 'An project has ever a same name');
                        $error = true;
                    }
                    if ($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array('_able' => 'create'));
                    } else {

                        $u      = new \Application\Model\User();
                        $mail   = $u->getMaillingFromGroup(3);
                        $author = $u->getById($id);
                        $author = $author[0];

                        $list = array();
                        foreach ($mail as $admin)
                            $list[] = $admin['username'] . ' < ' . $admin['email'] . ' >';

                        $msg            = new \Hoa\Mail\Message();
                        $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                        $msg['To']      = array_shift($list);
                        $msg['Subject'] = 'New Libray';
                        if (count($list) > 0)
                            $msg['Cc'] = implode(',', $list);


                        $name = strtolower(preg_replace('#[^[:alnum:]]#', '', $name));

                        $text = 'An new library is available on hoathis.net :' . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= '  Library name            : ' . $name . "\n";
                        $text .= '  Library description     : ' . $description . "\n";
                        $text .= '  Library Author          : ' . $author['username'] . "\n";
                        $text .= '  You can log go here     : http://hoathis.net' . $this->router->unroute('project-home', array('project' => $name)) . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= 'This email come from a bot , not reply to this mail' . "\n";

                        $msg->addContent(new \Hoa\Mail\Content\Text($text));

                        $msg->send();


                        $this->popup('success', 'Your projet has been create, you might wait his acception by the staff');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home', array());
                    }
                }

                $this->view->addOverlay('hoa://Application/View/Main/Create.xyl');
                $this->view->render();
            }

            public function ProfilAction()
            {
                $user = new \Hoa\Session\Session('user');

                $this
                    ->getKit('Redirector')
                    ->redirect('user-home', array('user' => $user['username']));
            }

            public function ListAction()
            {
                $user = new \Hoa\Session\Session('user');

                $this
                    ->getKit('Redirector')
                    ->redirect('user-caller', array(
                            'user'  => $user['username'],
                            '_able' => 'list'
                        )
                    );
            }

            public function RecoveryAction()
            {
                $userModel = new \Application\Model\User();
                if (!empty($_POST)) {
                    $token = $this->check('token', true);
                    $email = $this->check('email', true);
                    $pass  = $this->check('pass', true);
                    $rpass = $this->check('rpass', true);
                    $error = false;

                    if ($email === null) {
                        $this->popup('error', 'The field email is empty ');
                        $error = true;
                    } else if ($token === null) {
                        $this->popup('error', 'The field token is empty ');
                        $error = true;
                    } else if ($pass === null) {
                        $this->popup('error', 'The field password is empty ');
                        $error = true;
                    } else if ($rpass === null) {
                        $this->popup('error', 'The field retype-password is empty ');
                        $error = true;
                    } else if ($pass !== $rpass) {
                        $this->popup('error', 'The filed password and retype your password must be egal ');
                        $error = true;
                    }


                    if ($userModel->check($email, 'email') === false) {
                        $this->popup('error', 'The filed email is not valid');
                        $error = true;
                    } else if ($userModel->check($pass, 'password') === false) {
                        $this->popup('error', 'The filed password is not valid');
                        $error = true;
                    } else if ($userModel->checkMail($email)) {
                        $this->popup('error', 'Your email address are not register in our database ');
                        $error = true;
                    } else if ($userModel->checkToken($token) === true) {
                        $this->popup('error', 'Your token are not register in our database');
                        $error = true;
                    }
                    if ($error === true) {
                        $this
                            ->getKit('Redirector')
                            ->redirect('home-caller', array(
                                    '_able' => 'recovery'
                                )
                            );
                    } else {
                        $user = $userModel->getByEmail($email);
                        $userModel->setToken($email, '');
                        $userModel->setPassword($user['idUser'], $pass);
                        $this->popup('success', 'Your password has been update');
                        $this
                            ->getKit('Redirector')
                            ->redirect('home', array());
                    }

                }


                $query             = $this->router->getQuery();
                $token             = isset($query['token']) ? $query['token'] : null;
                $this->data->email = isset($query['email']) ? $query['email'] : null;
                if ($token !== null and $userModel->checkToken($token) === true) {
                    $this->popup('error', 'Your token are not register in our database');
                    $this
                        ->getKit('Redirector')
                        ->redirect('home', array());
                }

                $this->view->addOverlay('hoa://Application/View/Main/Recovery.xyl');
                $this->view->render();
            }
        }
    }

