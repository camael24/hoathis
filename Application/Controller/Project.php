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


            $library = new \Application\Model\Library();


            $information = $library->getInformationFromName($project, $this->isAdminAllowed());

            if(empty($information)) {
                $this->popup('info' , 'this project is not yet activate by staff or you have make an error in the URL, be patient thanks');
                $this->getKit('Redirector')->redirect('home', array());
            }


            $user = new \Hoa\Session\Session('user');

            if (array_key_exists('refUser', $information) && array_key_exists('idUser', $user) && intval($information['refUser']) === $user['idUser']) //TODO add when we get ACL OR rang information
                $information['editing'] = '<a href="' . $this->router->unroute('project-caller', array('project' => $project, '_able' => 'edit')) . '"><i class="icon-pencil"></i></a>';

            $this->data->information = $information;

            $this->view->addOverlay('hoa://Application/View/Project/Info.xyl');
            $this->view->render();

        }

        public function EditAction($project) {

            $this->guestGuard();

            $library     = new \Application\Model\Library();
            $information = $library->getInformationFromName($project, $this->isAdminAllowed());

            if(empty($information)) {
                $this->popup('info' , 'this project is not yet activate by staff, be patient thanks');
                $this->getKit('Redirector')->redirect('home', array());
            }


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
                    $this->getKit('Redirector')->redirect('project-caller', array('project' => $project, '_able' => 'create'));
                } else {
                    $library->update($information['idLibrary'], $description, $home, $release, $doc, $issue);
                    $this->popup('success', 'Your projet has been update'); //TODO change here
                    $this->getKit('Redirector')->redirect('project-home', array('project' => $project));
                }

            }

            $this->data->information = $information;
            $this->view->addOverlay('hoa://Application/View/Project/Edit.xyl');
            $this->view->render();
        }


    }
}

