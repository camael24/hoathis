<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class User extends Generic {
        public function ProfilAction($user) {
            $model = new \Application\Model\User();
            if (false === $model->openByName(array('name' => strtolower($user)))) {
                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');

            } else {

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
                    $this->data->editing = '<a href="' . $this->router->unroute('up', array('user' => $user, '_able' => 'edit')) . '" class="btn btn-danger btn-mini pull-right"><i class="icon-white icon-pencil"></i></a>'; //TODO its for ... emulate an flag

                $this->data->login = $model->username;
                $this->data->mail  = $model->email;
                $this->data->rang  = $rang;

                $this->view->addOverlay('hoa://Application/View/Main/Profil.xyl');
            }
            $this->view->render();
        }

        public function ListAction($user) {
            $model              = new \Application\Model\Library();
            $this->data->label  = 'Hoathis : ' . $user;
            $this->data->search = $model->getFromAuthorName($user);

            $this->view->addOverlay('hoa://Application/View/Main/List.xyl');
            $this->view->render();
        }

        public function EditAction($_this, $user) {
            if (!\Hoa\Session\Session::isNamespaceSet('user')) {
                $this->flash('error', 'You don`t have the require credential');
                $_this->getKit('Redirector')->redirect('i', array());

                return;
            }
            $error = array();
            $model = new \Application\Model\User();
            if (false === $model->openByName(array('name' => $user))) {
                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
            } else {

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
                            $this->flash('success', 'Modification success');
                            $_this->getKit('Redirector')->redirect('u', array('user' => $model->username));
                        } else {
                            $this->flash('error', 'Your field "Password" and "Retype Password" are not equal');
                            $_this->getKit('Redirector')->redirect('up', array('user' => $model->username, '_able' => 'edit'));
                        }

                    } else {
                        $this->flash('error', 'This input are empty ' . implode(',', $error));
                        $_this->getKit('Redirector')->redirect('up', array('user' => $model->username, '_able' => 'edit'));
                    }


                } else {
                    $this->data->login = $model->username;
                    $this->data->mail  = $model->email;

                    $this->view->addOverlay('hoa://Application/View/Front/Profil.xyl');
                }
            }
            $this->view->render();
        }

    }
}

