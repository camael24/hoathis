<?php

namespace Application\Controller {
    class Generic extends \Hoa\Dispatcher\Kit {
        public function construct() {
            if (!\Hoa\Session\Session::isNamespaceSet('user')) {
                $this->view->addUse('hoa://Application/View/Navbar.Disconnected.xyl');
            } else {
                $data                 = new \Hoa\Session\QNamespace('user');
                $this->data->username = ucfirst($data->user);
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
        }


    }
}
