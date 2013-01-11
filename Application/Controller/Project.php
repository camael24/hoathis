<?php
namespace {
}
namespace Application\Controller {

    class Project extends Generic {

        public function IndexAction() {

            $this->view->addOverlay('hoa://Application/View/Main/Index.xyl');
            $this->view->render();

        }

        public function InfoAction($project) {


            $library     = new \Application\Model\Library();
            $information = $library->getInformationFromName($project);

            $user = new \Hoa\Session\Session('user');

            if (intval($information['refUser']) === $user['idUser']) //TODO add when we get ACL OR rang information
                $information['editing'] = '<a href="' . $this->router->unroute('pp', array('project' => $project, '_able' => 'edit')) . '"><i class="icon-pencil"></i></a>';

            $this->data->information = $information;

            $this->view->addOverlay('hoa://Application/View/Project/Info.xyl');
            $this->view->render();

        }

        public function EditAction($project) {

            $this->guestGuard();

            $library     = new \Application\Model\Library();
            $information = $library->getInformationFromName($project);

            $user = new \Hoa\Session\Session('user');

            if (!empty($_POST)) {
                $error = false;
                if (intval($information['refUser']) !== $user['idUser']) { //TODO add when we get ACL OR rang information
                    $this->popup('error', 'You are not allow to edit this');
                    $error = true;
                }
                $description = $this->check('description', true);
                $home        = $this->check('home', true);
                $release     = $this->check('release', true);
                $issue       = $this->check('issues');
                $doc         = $this->check('doc');


                if ($description === null) {
                    $this->popup('error', 'The field description is empty ');
                    $error = true;
                } else if ($home === null) {
                    $this->popup('error', 'The field homepage is empty ');
                    $error = true;
                } else if ($release === null) {
                    $this->popup('error', 'The field release is empty ');
                    $error = true;
                }


                if ($error === true) {
                    $this->getKit('Redirector')->redirect('pp', array('project' => $project, '_able' => 'create'));
                } else {
                    $library->update($information['idLibrary'], $description, $home, $release, $doc, $issue);
                    $this->popup('success', 'Your projet has been update'); //TODO change here
                    $this->getKit('Redirector')->redirect('p', array('project' => $project));
                }

            }

            $this->data->information = $information;
            $this->view->addOverlay('hoa://Application/View/Project/Edit.xyl');
            $this->view->render();
        }


    }
}

