<?php
    namespace {
    }
    namespace Application\Controller {

        use Hoathis\Context\Exception;

        class Api extends Generic {

            public function AutocompleteActionAsync () {
                $library = new \Application\Model\Library();
                $library = $library->getAll();
                $user    = new \Application\Model\User();
                $user    = $user->all();
                $data    = array();

                foreach ($library as $id => $elmt)
                    $data[] = $elmt['name'];

                foreach ($user as $idx => $elmtx)
                    $data[] = '@' . $elmtx['username'];

                echo json_encode($data);
            }

            public function ComposerActionAsync () {
                $data = $this->check('uri', true);
                if($data === null)
                    echo json_encode(array('error' => 'wrong uri data'));
                else {
                    try {
                        echo file_get_contents($data);
                    }
                    catch (\Exception $e) {
                        echo json_encode(array('error' => 'The user/repo or the URI have a mistake please check it : ' . $data));
                    }
                }

            }

            public function ComposerAction () {
                echo file_get_contents('https://raw.github.com/hoaproject/String/master/composer.json');
            }
        }
    }