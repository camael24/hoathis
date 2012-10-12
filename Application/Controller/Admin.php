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
            $model               = new \Application\Model\Library();
            $this->data->label   = 'Application not yet validate';

            $this->data->search  = $model->getFromValidity();
            $this->data->label2  = 'Application validated';
            $this->data->search2 = $model->getFromValidity(1);

            $this->view->addOverlay('hoa://Application/View/Admin/List.xyl');
            $this->view->render();

        }

        public function UsersAction() {


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

