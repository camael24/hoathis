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
            if (array_key_exists('term', $_POST)) {
                $s = $model->search($_POST['term']);

                $elmt = array();
                foreach ($s as $i)
                    $elmt[] = $i['name'];


                echo json_encode($elmt);
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


                $this->data->label  = 'Search of : ' . $_POST['search'];
                $this->data->search = $model->search($_POST['search']);


                $this->view->interprete();
                $this->view->render($this->view->getSnippet('main_list'));
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
    }
}

