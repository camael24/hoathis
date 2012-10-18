<?php
namespace Application\Controller {
    class Generic extends \Hoa\Dispatcher\Kit {
        public function construct() {
            if (!\Hoa\Session\Session::isNamespaceSet('user')) {
                $this->view->addUse('hoa://Application/View/Navbar.Disconnected.xyl');
            } else {
                $data  = new \Hoa\Session\QNamespace('user');
                $model = new \Application\Model\User();
                $model->open(array('id' => $data->idUser));
                $this->data->username = ucfirst($model->username);
                $this->view->addUse('hoa://Application/View/Navbar.Connected.xyl');
            }

            if (\Hoa\Session\Session::isNamespaceSet('admin')) {
                $this->view->addUse('hoa://Application/View/Admin/Icon-Admin.xyl');
                $this->view->addUse('hoa://Application/View/Menu-Admin.xyl');
                $this->view->addUse('hoa://Application/View/Menu-User.xyl');
            } else if (\Hoa\Session\Session::isNamespaceSet('user')) {
                $this->view->addUse('hoa://Application/View/Admin/Icon.xyl');
                $this->view->addUse('hoa://Application/View/Menu-User.xyl');
            } else {
                $this->view->addUse('hoa://Application/View/Admin/Icon.xyl');
                $this->view->addUse('hoa://Application/View/Menu.xyl');
                $this->view->addUse('hoa://Application/View/Navbar.Disconnected.xyl');
            }

            if (array_key_exists('hoathis', $_SESSION) && !empty($_SESSION['hoathis'])) {
                $hoathis = $_SESSION['hoathis'];
                if (array_key_exists('error', $hoathis)) {

                    $this->data->type    = "alert alert-error";
                    $this->data->message = $hoathis['error'];

                } else if (array_key_exists('info', $hoathis)) {

                    $this->data->type    = "alert alert-info";
                    $this->data->message = $hoathis['info'];

                } elseif (array_key_exists('success', $hoathis)) {

                    $this->data->type    = "alert alert-success";
                    $this->data->message = $hoathis['success'];

                }
                $this->view->addOverlay('hoa://Application/View/Flash.xyl');
            }
            $_SESSION['hoathis'] = array();

        }

        public function flash($level, $message) {
            $_SESSION['hoathis'][$level] = $message;
        }


    }
}
