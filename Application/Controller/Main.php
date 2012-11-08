<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Main extends Generic {
        public function IndexAction() {
            $this->view->addOverlay('hoa://Application/View/Main/Search.xyl');
            $this->view->render();
        }

        public function SearchActionAsync() {
            $this->SearchAction();
        }

        public function SearchAction() {
            $model = new \Application\Model\Library();
            $user  = new \Application\Model\User();
            if (array_key_exists('term', $_POST)) {
                if (strpos($_POST['term'], '@') === 0) {
                    $term = substr($_POST['term'], 1);
                    $s    = $user->search($term);


                    $elmt = array();
                    foreach ($s as $i)
                        $elmt[] = '@' . $i['username'];

                    echo json_encode($elmt);
                } else {

                    $s = $model->search($_POST['term']);

                    $elmt = array();
                    foreach ($s as $i)
                        $elmt[] = $i['name'];


                    echo json_encode($elmt);
                }
            } else if (array_key_exists('search', $_POST)) {
                $main = 'hoa://Application/View/Main/Fragment/List.xyl';

                $xyl        = new \Hoa\Xyl(
                    new \Hoa\File\Read($main),
                    new \Hoa\Http\Response(),
                    new \Hoa\Xyl\Interpreter\Html(),
                    $this->router
                );
                $this->view = $xyl;
                $this->data = $xyl->getData();


                if (strpos($_POST['search'], '@') === 0) {
                    $term              = substr($_POST['search'], 1);
                    $this->data->label = 'Search of Author : ' . $term;
                    $searchs           = $user->search($term);
                    foreach ($searchs as $id => $search) {
                        $rang = null;
                        switch ($search['rang']) {
                            case 2:
                                $rang = '<span class="label label-important">Administrator</span>';
                                break;
                            case 1:
                                $rang = '<span class="label label-success">User</span>';
                                break;
                            case 0:
                            default:
                                $rang = '<span class="label label-inverse">Banned or Unactivate</span>';
                        }

                        $searchs[$id]['rang'] = $rang;
                    }
                    $this->data->search = $searchs;
                    $this->view->interprete();
                    $this->view->render($this->view->getSnippet('author_list'));

                } else {
                    $this->data->label  = 'Search of : ' . $_POST['search'];
                    $this->data->search = $model->search($_POST['search']);
                    $this->view->interprete();
                    $this->view->render($this->view->getSnippet('main_list'));
                }

            }

        }

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
                        $_this->getKit('Redirector')->redirect('up', array('user' => $model->username, '_able' => 'list'));


                    } else {
                        $this->flash('error', 'This credentials are not reconized here, your are might be banned or unactived');
                        $_this->getKit('Redirector')->redirect('w', array('_able' => 'connexion'));
                    }

                } else {
                    $this->flash('error', 'This input are empty ' . implode(',', $error));
                    $_this->getKit('Redirector')->redirect('w', array('_able' => 'connexion'));
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

                        //TODO : check valid data ! before register !

                        $model = new \Application\Model\User();

                        if (!$model->checkMail($mail)) {
                            $this->flash('error', 'Your mail has ever register');
                            $_this->getKit('Redirector')->redirect('w', array('_able' => 'register'));
                        } else if (!$model->checkUser($user)) {
                            $this->flash('error', 'Your username has ever register');
                            $_this->getKit('Redirector')->redirect('w', array('_able' => 'register'));
                        } else {
                            $model->insert($user, $password, $mail);
                            $this->flash('success', 'Register success');
                            $_this->getKit('Redirector')->redirect('i', array());
                        }

                    } else {

                        $this->flash('error', 'Your field "Password" and "Retype Password" are not equal');
                        $_this->getKit('Redirector')->redirect('w', array('_able' => 'register'));
                    }
                } else {
                    $this->flash('error', 'This input are empty ' . implode(',', $error));
                    $_this->getKit('Redirector')->redirect('w', array('_able' => 'register'));
                }

            } else {
                $this->view->addOverlay('hoa://Application/View/Front/Register.xyl');
            }

            $this->view->render();

        }

        public function ForgotAction($_this) {
            $this->flash('info', 'This is not implement yet');
            $_this->getKit('Redirector')->redirect('i', array());
        }

        public function CreateAction($_this) {
            if (!\Hoa\Session\Session::isNamespaceSet('user')) {
                $this->flash('error', 'You don`t have the require credential');
                $_this->getKit('Redirector')->redirect('i', array());

                return;
            }

            $session = new \Hoa\Session\QNamespace('user');
            $model   = new \Application\Model\User();
            if (false === $model->open(array('id' => $session->idUser))) {
                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
            } else {

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
                    //TODO : check valid data !
                    $name          = $check('name');
                    $descripion    = $check('description');
                    $home          = $check('home');
                    $release       = $check('release');
                    $issue         = $check('issues');
                    $documentation = $check('doc');


                    if (empty($error)) {
                        $model = new \Application\Model\Library();
                        $valid = $model->insert($session->idUser, $name, $descripion, $home, $release, $documentation, $issue);

                        if ($valid === true) {
                            $this->flash('success', 'Create success');
                            $_this->getKit('Redirector')->redirect('i', array());
                        } else {
                            $this->flash('error', 'An library as ever a same name !');
                            $_this->getKit('Redirector')->redirect('w', array('_able' => 'create'));
                        }
                    } else {
                        $this->flash('error', 'This input are empty ' . implode(',', $error));
                        $_this->getKit('Redirector')->redirect('w', array('_able' => 'create'));
                    }
                } else {
                    $this->view->addOverlay('hoa://Application/View/Main/Create.xyl');

                }
            }
            $this->view->render();

        }
    }
}

