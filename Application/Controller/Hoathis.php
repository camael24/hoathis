<?php
namespace {
    from('Application')->import('Model.*');
    from('Application')->import('Controller.Generic');
}
namespace Application\Controller {
    class Hoathis extends Generic {
        public function IndexAction($page) {

            $page = intval($page);
            if (!is_int($page) && $page > 0) {
                $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
            } else {

                $model       = new \Application\Model\Library();

                $all = false;
                if(\Hoa\Session\Session::isNamespaceSet('admin')){
                    $all = true;
                }

                $information = $model->getInformation($page , $all);

                if (\Hoa\Session\Session::isNamespaceSet('user')) {
                    $session = new \Hoa\Session\QNamespace('user');
                    if (intval($information['refUser']) === $session->idUser or \Hoa\Session\Session::isNamespaceSet('admin')) {
                        $information['editing'] = '<a href="/e/' . $page . '" class="btn btn-danger btn-mini pull-right"><i class="icon-white icon-pencil"></i></a>';     //TODO its for ... emulate an flag
                    }

                }
                $this->data->information = $information;
                if (empty($information)) {
                    $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
                } else {
                    $this->view->addOverlay('hoa://Application/View/Hoathis/Library.xyl');
                }
            }
            $this->view->render();
        }

        public function EditAction($page) {
            if (!\Hoa\Session\Session::isNamespaceSet('user')) {
                return;
            }

            $error = array();

            $check   = function ($id) use (&$error) {
                if (array_key_exists($id, $_POST) && $_POST[$id] != '')
                    return $_POST[$id];
                else {
                    $error[] = $id;

                    return null;
                }

            };
            $session = new \Hoa\Session\QNamespace('user');
            $model   = new \Application\Model\Library();

            if (intval($model->refUser) === $session->idUser or \Hoa\Session\Session::isNamespaceSet('admin')) {
                $page = intval($page);
                if (!is_int($page) && $page > 0) {
                    $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
                } else {
                    if (!empty($_POST)) {
                        $descripion    = $check('description');
                        $home          = $check('home');
                        $release       = $check('release');
                        $issue         = $check('issues');
                        $documentation = $check('doc');
                        $model->update($page, $descripion, $home, $release, $documentation, $issue);

                        $this->data->message = 'Edition success';
                        $this->view->addOverlay('hoa://Application/View/Front/Success.xyl');

                    } else {

                        $information             = $model->getInformation($page);
                        $information['page']     = $page;
                        $this->data->information = $information;
                        if (empty($information)) {
                            $this->view->addOverlay('hoa://Application/View/Hoathis/404.xyl');
                        } else {
                            $this->view->addOverlay('hoa://Application/View/Hoathis/Edit.xyl');
                        }
                    }
                }
            }
            $this->view->render();
        }


    }
}
