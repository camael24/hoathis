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

            $information = array(
                'login'         => 'foo',
                'name'          => 'foo',
                'description'   => 'an foo bar exemple project :D',
                'time'          => date('d/m/T H:i:s'),
                'home'          => 'http://foo.bar',
                'author'        => 'Camael',
                'release'       => 'http://bar.net',
                'documentation' => 'http://lol.net',
                'issues'        => 'http://vtff.net'
            );

            if (true) // TODO : Here check for editing Profil !
                $information['editing'] = '<a href="' . $this->router->unroute('pp', array('project' => $project, '_able' => 'edit')) . '"><i class="icon-pencil"></i></a>';

            $this->data->information = $information;

            $this->view->addOverlay('hoa://Application/View/Project/Info.xyl');
            $this->view->render();

        }

        public function EditAction($project) {

            $this->guestGuard();

            $information = array(
                'login'         => 'foo',
                'name'          => 'foo',
                'description'   => 'an foo bar exemple project :D',
                'time'          => date('d/m/T H:i:s'),
                'home'          => 'http://foo.bar',
                'author'        => 'Camael',
                'release'       => 'http://bar.net',
                'documentation' => 'http://lol.net',
                'issues'        => 'http://vtff.net'
            );

            if (!empty($_POST)) {
                $description = $this->check('description', true);
                $home        = $this->check('home', true);
                $release     = $this->check('release', true);
                $issue       = $this->check('issues');
                $doc         = $this->check('documentation');

                $error = false;
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
                    // TODO Connection @BDD
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

