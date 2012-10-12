<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Admin extends Generic {

        public function construct() {
            parent::construct();
            if (!\Hoa\Session\Session::isNamespaceSet('admin')) {
                header('Location:/');
            }
        }

        public function IndexAction() {
            $model             = new \Application\Model\Library();
            $this->data->label = 'Application not yet validate';

            $this->data->search  = $model->getFromValidity();
            $this->data->label2  = 'Application validated';
            $this->data->search2 = $model->getFromValidity(1);

            $this->view->addOverlay('hoa://Application/View/Admin/List.xyl');
            $this->view->render();

        }

        public function UsersAction() {
            // List de tous le monde + boutton admin ?
            $user              = new \Application\Model\User();
            $this->data->label = 'All users in da world';

            $searchs = $user->all();

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

            $this->view->addOverlay('hoa://Application/View/Admin/ListUser.xyl');
            $this->view->render();
        }

        public function EdituserAction($user) {
            if (!\Hoa\Session\Session::isNamespaceSet('admin')) {
                $this->view->addOverlay('hoa://Application/View/Error.Auth.xyl');
                $this->view->render();

                return;
            }


            $user = intval($user);
            if (!is_int($user) || $user == 0) {
                $this->view->render();

                return;
            }
            $users = new \Application\Model\User();
            $error = array();
            $check = function ($id, $check = true, $compare = null) use (&$error) {
                if (array_key_exists($id, $_POST) && $_POST[$id] != '') {
                    if ($compare !== null)
                        if ($_POST[$id] !== $compare)
                            return $_POST[$id];
                        else
                            return null;

                    return $_POST[$id];
                } else {
                    if ($check === true)
                        $error[] = $id;

                    return null;
                }

            };

            if (false === $users->open(array('id' => $user))) {
                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
            } else if (!empty($_POST)) {
                $id = intval($check('idelmt', true));
                if ($id === $user) {
                    $name  = $check('user', true, $users->username);
                    $pass  = $check('pass', false);
                    $rpass = $check('rpass', false);
                    $mail  = $check('mail', true, $users->email);
                    $rang  = $check('rang', true, strval($users->rang));

                    if (empty($error)) {
                        if ($pass === $rpass) {
                            $users->update($id, $pass, $mail);
                            $users->setRang($id, $rang);
                            $users->setUsername($id, $name);
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


                }
            } else {


                $this->data->login  = $users->username;
                $this->data->mail   = $users->email;
                $this->data->idUser = $users->idUser;

                $select = '<select id="rang" name="rang">'; //TODO : suivant avancée de Hoa\Xyl !

                foreach (array('Banned or unactivate', 'User', 'Administrator') as $id => $value)
                    $select .= '<option value="' . $id . '" ' . (($users->rang == $id)
                        ? 'selected="selected"'
                        : '') . '>' . $value . '</option>';

                $select .= '</select>';
                $this->data->rang = $select;

                $this->view->addOverlay('hoa://Application/View/Admin/Profil.xyl');
            }

            $this->view->render();
        }

        public function UnvalidateAction($page) {
            $model = new \Application\Model\Library();
            $model->setValid($page, 0);
            header('Location:/admin/');

        }

        public function ValidateAction($page) {
            $model = new \Application\Model\Library();
            $model->setValid($page, 1);
            header('Location:/admin/');
        }

        public function DeleteAction($page) {
            $model = new \Application\Model\Library();
            $model->delete($page);
            header('Location:/admin/');
        }
    }
}

