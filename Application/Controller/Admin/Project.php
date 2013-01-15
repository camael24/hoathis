<?php
namespace {
}
namespace Application\Controller\Admin {

    class Project extends Generic {

        public function ListAction() {


            $project                = new \Application\Model\Library();
            $this->data->unvalidate = $project->getUnValidate();
            $this->data->validate   = $project->getValidate();


            $this->view->addOverlay('hoa://Application/View/Admin/Project/List.xyl');
            $this->view->render();
        }

        public function ActivateAction($id) {
            $project = new \Application\Model\Library();
            $project->setValid($id, '1');

            $this->popup('success', 'the project has been accepted and publish');
            $this->getKit('Redirector')->redirect('admin-project', array('_able' => 'list'));
        }

        public function UnactivateAction($id) {
            $project = new \Application\Model\Library();
            $project->setValid($id, '0');

            $this->popup('error', 'the project has been unvalidate or banned');
            $this->getKit('Redirector')->redirect('admin-project', array('_able' => 'list'));
        }

        public function DeleteAction($id) {
            if (!empty($_POST)) {
                $userSession = new \Hoa\Session\Session('user');
                $user        = new \Application\Model\User();

                if ($user->connect($userSession['username'], $this->check('password', true)) && $this->isAdminAllowed()) {
                    $library = new \Application\Model\Library();
                    $library->delete($id);


                    $this->popup('success', 'the project has been delete');
                    $this->getKit('Redirector')->redirect('admin-project', array('_able' => 'list'));

                } else {
                    $this->popup('error', 'your password confirmation is not correct or you don\'t the require credential to proceed this action');
                    $this->getKit('Redirector')->redirect('admin-project', array('_able' => 'list'));
                }
            } else {
                $this->view->addOverlay('hoa://Application/View/Admin/Project/Confirm.xyl');
                $this->view->render();
            }
        }


    }
}

