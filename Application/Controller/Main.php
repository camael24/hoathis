<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Main extends Generic {
        public function ProfilAction() {
            if (!\Hoa\Session\Session::isNamespaceSet('user'))
                return;
            $error   = array();
            $session = new \Hoa\Session\QNamespace('user');
            $model   = new \Application\Model\User();
            $model->open(array('id' => $session->idUser));

            $check = function ($id, $check = true) use (&$error) {
                if (array_key_exists($id, $_POST) && $_POST[$id] != '')
                    return $_POST[$id];
                else {
                    if ($check === true)
                        $error[] = $id;

                    return null;
                }

            };

            if (!empty($_POST)) {
                $mail  = $check('mail');
                $pass  = $check('pass', false);
                $rpass = $check('rpass', false);
                if (empty($error)) {
                    if ($pass === $rpass) {
                        $model->update($model->idUser, $pass, $mail);
                        $this->data->message = 'Modification success';
                        $this->view->addOverlay('hoa://Application/View/Front/Success.xyl');
                    } else {
                        $this->data->error = 'Your field "Password" and "Retype Password" are not equal';
                        $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                    }

                } else {
                    $this->data->error = 'This input are empty ' . implode(',', $error);
                    $this->view->addOverlay('hoa://Application/View/Front/Failed.xyl');
                }


            } else {
                $this->data->login = $model->username;
                $this->data->mail  = $model->email;

                $this->view->addOverlay('hoa://Application/View/Front/Profil.xyl');
            }

            $this->view->render();
        }

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

        public function CreateAction() {
            if (!\Hoa\Session\Session::isNamespaceSet('user'))
                return;

            $session = new \Hoa\Session\QNamespace('user');
            $model   = new \Application\Model\User();
            $model->open(array('id' => $session->idUser));

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
                        $this->view->addOverlay('hoa://Application/View/Main/Create.Success.xyl');
                    } else {
                        $this->data->error = 'An library as ever a same name !';
                        $this->view->addOverlay('hoa://Application/View/Main/Create.Failed.xyl');
                    }
                } else {
                    $this->data->error = 'This input are empty ' . implode(',', $error);
                    $this->view->addOverlay('hoa://Application/View/Main/Create.Failed.xyl');
                }
            } else {
                $this->view->addOverlay('hoa://Application/View/Main/Create.xyl');

            }
            $this->view->render();

        }

        public function ListAction() {

            if (!\Hoa\Session\Session::isNamespaceSet('user'))
                return;

            $session            = new \Hoa\Session\QNamespace('user');
            $model              = new \Application\Model\Library();
            $this->data->label  = 'My Hoathis';
            $this->data->search = $model->getFromAuthor($session->idUser);

            $this->view->addOverlay('hoa://Application/View/Main/List.xyl');
            $this->view->render();

        }

        public function UserAction($user) {
            $user = intval($user);
            if (is_int($user) && $user > 0) {
                $model = new \Application\Model\User();
                $model->open(array('id' => $user));


                $rang = null;
                switch ($model->rang) {
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


                if (\Hoa\Session\Session::isNamespaceSet('admin'))
                    $this->data->editing = '<a href="/u/' . $user . '/edit" class="btn btn-danger btn-mini pull-right"><i class="icon-white icon-pencil"></i></a>'; //TODO its for ... emulate an flag

                $this->data->login = $model->username;
                $this->data->mail  = $model->email;
                $this->data->rang  = $rang;

                $this->view->addOverlay('hoa://Application/View/Main/Profil.xyl');

                $this->view->render();
            }
        }
    }
}

