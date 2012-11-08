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

        public function IndexAction($_this) {
            $model = new \Application\Model\Library();


            $validate = $model->getAll();

            $validated   = array();
            $unvalidated = array();

            foreach ($validate as $elmt)
                if ($elmt['valid'] == '0')
                    $unvalidated[] = $elmt;
                else
                    $validated[] = $elmt;

            // TODO : Hywan here i cant did what i want you must explmain me on IRC :)

            $html  = '';
            $title = function ($title) use (&$html) {
                $html .= '<p class="lead">' . $title . '</p>';
            };
            $t = $this;
            $item  = function ($item, $validate) use (&$html , $t) {
                $html .= '<li><div class="row">
                        <h2 class="span6">' . $item['username'] . '/<span class="emphase">' . $item['name'] . '</span></h2>
                        <a href="'.$t->router->unroute('p', array('project' => $item['name'])).'" class="btn btn-primary"><i class="icon-white icon-chevron-right"></i></a>';
                if ($validate === false) {
                    $html .= '<a href="'.$t->router->unroute('api', array('_able' => 'validatelib' , 'id' => $item['idLibrary'])).'" class="btn btn-success" title="validate it"><i class="icon-white icon-ok"></i></a>
                          <a href="'.$t->router->unroute('api', array('_able' => 'delete' , 'id' => $item['idLibrary'])).'" class="btn btn-danger" title="remove it"><i class="icon-white icon-remove"></i></a>';
                } else {
                    $html .= '<a href="'.$t->router->unroute('api', array('_able' => 'unvalidatelib' , 'id' => $item['idLibrary'])).'" class="btn btn-warning" title="Unvalidate it"><i class="icon-white icon-chevron-down"></i></a>';
                }
                $html .= '</div><p>' . $item['description'] . '</p></li>';

            };

            $title('Application not validate');
            $html .= '<ul>';
            foreach ($unvalidated as $elmt)
                $item($elmt, false);


            $html .= '</ul>';

            $title('Application validate');
            $html .= '<ul>';
            foreach ($validated as $elmt)
                $item($elmt, true);

            $html .= '</ul>';


            $this->data->result = $html;
            $this->view->addOverlay('hoa://Application/View/Admin/List.xyl');
            $this->view->render();

        }

        public function UsersAction() {
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

//        public function EdituserAction($_this, $user) {
//            if (!\Hoa\Session\Session::isNamespaceSet('admin')) {
//                $this->flash('error', 'Error we dont have correct credential');
//                $_this->getKit('Redirector')->redirect('i', array());
//
//                return;
//            }
//
//
//            $user = intval($user);
//            if (!is_int($user) || $user == 0) {
//                $this->view->render();
//
//                return;
//            }
//            $users = new \Application\Model\User();
//            $error = array();
//            $check = function ($id, $check = true, $compare = null) use (&$error) {
//                if (array_key_exists($id, $_POST) && $_POST[$id] != '') {
//                    if ($compare !== null)
//                        if ($_POST[$id] !== $compare)
//                            return $_POST[$id];
//                        else
//                            return null;
//
//                    return $_POST[$id];
//                } else {
//                    if ($check === true)
//                        $error[] = $id;
//
//                    return null;
//                }
//
//            };
//
//            if (false === $users->open(array('id' => $user))) {
//                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
//            } else if (!empty($_POST)) {
//                $id = intval($check('idelmt', true));
//                if ($id === $user) {
//                    $name  = $check('user', true, $users->username);
//                    $pass  = $check('pass', false);
//                    $rpass = $check('rpass', false);
//                    $mail  = $check('mail', true, $users->email);
//                    $rang  = $check('rang', true, strval($users->rang));
//
//                    if (empty($error)) {
//                        if ($pass === $rpass) {
//                            $users->update($id, $pass, $mail);
//                            $users->setRang($id, $rang);
//                            $users->setUsername($id, $name);
//                            $this->flash('success', 'Modification success');
//                            $_this->getKit('Redirector')->redirect('i', array());
//                        } else {
//                            $this->flash('error', 'Your field "Password" and "Retype Password" are not equal');
//                            $_this->getKit('Redirector')->redirect('i', array());
//                        }
//
//                    } else {
//                        $this->flash('error', 'This input are empty ' . implode(',', $error));
//                        $_this->getKit('Redirector')->redirect('i', array());
//                    }
//
//
//                }
//            } else {
//
//
//                $this->data->login  = $users->username;
//                $this->data->mail   = $users->email;
//                $this->data->idUser = $users->idUser;
//
//                $select = '<select id="rang" name="rang">'; //TODO : suivant avancée de Hoa\Xyl !
//
//                foreach (array('Banned or unactivate', 'User', 'Administrator') as $id => $value)
//                    $select .= '<option value="' . $id . '" ' . (($users->rang == $id)
//                        ? 'selected="selected"'
//                        : '') . '>' . $value . '</option>';
//
//                $select .= '</select>';
//                $this->data->rang = $select;
//
//                $this->view->addOverlay('hoa://Application/View/Admin/Profil.xyl');
//            }
//
//            $this->view->render();
//        }

        public function UnvalidatelibAction($_this, $id) {
            $model = new \Application\Model\Library();
            $model->setValid($id, 0);
            $_this->getKit('Redirector')->redirect('a', array());

        }

        public function ValidatelibAction($_this, $id) {
            $model = new \Application\Model\Library();
            $model->setValid($id, 1);
            $_this->getKit('Redirector')->redirect('a', array());
        }

        public function DeleteAction($_this, $page) {
            $model = new \Application\Model\Library();
            $model->delete($page);
            $_this->getKit('Redirector')->redirect('a', array());
        }
    }
}

