<?php
    namespace {
    }
    namespace Application\Controller\Admin {

        class Project extends Generic {

            public function ListAction () {


                $project                = new \Application\Model\Library();
                $this->data->unvalidate = $project->getUnValidate();
                $this->data->validate   = $project->getValidate();


                $this->view->addOverlay('hoa://Application/View/Admin/Project/List.xyl');
                $this->view->render();
            }

            public function ActivateAction ($id) {
                $project = new \Application\Model\Library();
                $project->setValid($id, '1');

                $u = $project->getInformation($id, true);


                $msg            = new \Hoa\Mail\Message();
                $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                $msg['To']      = $u['email'];
                $msg['Subject'] = 'Activation of libray ' . $u['name'];


                $text = 'Your library ' . $u['name'] . 'are available on hoathis.net :' . "\n";
                $text .= '----------------------------' . "\n";
                $text .= '  Library name            : ' . $u['name'] . "\n";
                $text .= '  Library description     : ' . $u['description'] . "\n";
                $text .= '----------------------------' . "\n";
                $text .= 'This email come from a bot , not reply to this mail' . "\n";

                $msg->addContent(new \Hoa\Mail\Content\Text($text));

                $msg->send();


                $this->popup('success', 'the project has been accepted and publish');
                $this
                    ->getKit('Redirector')
                    ->redirect('admin-project', array('_able' => 'list'));
            }

            public function UnactivateAction ($id) {
                $project = new \Application\Model\Library();
                $project->setValid($id, '0');

                $u = $project->getInformation($id, true);

                $msg            = new \Hoa\Mail\Message();
                $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                $msg['To']      = $u['email'];
                $msg['Subject'] = 'Unactivation of libray ' . $u['name'];


                $text = 'Your library ' . $u['name'] . 'are unvalidate on hoathis.net :' . "\n";
                $text .= '----------------------------' . "\n";
                $text .= '  Library name            : ' . $u['name'] . "\n";
                $text .= '  Library description     : ' . $u['description'] . "\n";
                $text .= '----------------------------' . "\n";
                $text .= 'This email come from a bot , not reply to this mail' . "\n";

                $msg->addContent(new \Hoa\Mail\Content\Text($text));

                $msg->send();

                $this->popup('error', 'the project has been unvalidate or banned');
                $this
                    ->getKit('Redirector')
                    ->redirect('admin-project', array('_able' => 'list'));
            }

            public function DeleteAction ($id) {
                if(!empty($_POST)) {
                    $userSession = new \Hoa\Session\Session('user');
                    $user        = new \Application\Model\User();

                    if($user->connect($userSession['username'], $this->check('password', true)) && $this->isAdminAllowed()) {
                        $library = new \Application\Model\Library();
                        $u       = $library->getInformation($id, true);
                        $library->delete($id);


                        $msg            = new \Hoa\Mail\Message();
                        $msg['From']    = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
                        $msg['To']      = $u['email'];
                        $msg['Subject'] = 'Delete of libray ' . $u['name'];


                        $text = 'Your library ' . $u['name'] . 'are delete on hoathis.net :' . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= '  Library name            : ' . $u['name'] . "\n";
                        $text .= '  Library description     : ' . $u['description'] . "\n";
                        $text .= '----------------------------' . "\n";
                        $text .= 'This email come from a bot , not reply to this mail' . "\n";

                        $msg->addContent(new \Hoa\Mail\Content\Text($text));

                        $msg->send();

                        $this->popup('success', 'the project has been delete');
                        $this
                            ->getKit('Redirector')
                            ->redirect('admin-project', array('_able' => 'list'));

                    }
                    else {
                        $this->popup('error', 'your password confirmation is not correct or you don\'t the require credential to proceed this action');
                        $this
                            ->getKit('Redirector')
                            ->redirect('admin-project', array('_able' => 'list'));
                    }
                }
                else {
                    $this->view->addOverlay('hoa://Application/View/Admin/Project/Confirm.xyl');
                    $this->view->render();
                }
            }


        }
    }

