<?php
    namespace {
    }
    namespace Application\Controller {

        use Hoathis\Context\Exception;

        class Api extends Generic
        {

            private $_composerCache = 'hoa://Data/Variable/Cache/composer_';
            private $_composerExt = '.json';

            public function AutocompleteActionAsync ()
            {
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

            public function ComposerActionAsync ()
            {
                $data  = $this->check('uri', true);
                $repos = $this->check('gh-repos', null);
                if ($data === null) {
                    echo json_encode(array('error' => 'wrong uri data'));
                }
                else {
                    try {

                        $f = file_get_contents($data);
                        if ($repos !== null and $repos !== '') {
                            var_dump($repos);
                            $file = $this->_composerCache . md5($repos) . $this->_composerExt;
                            file_put_contents($file, $f);
                        }
                        echo $f;
                    } catch (\Exception $e) {
                        echo json_encode(array('error' => 'The user / repo or the URI have a mistake please check it : ' . $data));
                    }
                }

            }

            public function updateAction ()
            {
                $query = $this->router->getQuery();
                $user  = (array_key_exists('user', $query) ? $query['user'] : null);
                $depot = (array_key_exists('depot', $query) ? $query['depot'] : null);

                if ($user === null or $depot === null) {
                    return;
                }

                $depot      = str_replace('_', '/', $depot);
                $depot_Hash = md5($depot);
                $depot_Uri  = $file = $this->_composerCache . $depot_Hash . $this->_composerExt;
                $user_Model = new \Application\Model\User();

                if ($user_Model->checkUser($user) === true or !file_exists($depot_Uri)) {
                    return;
                }

                $composer = json_decode(file_get_contents($depot_Uri) , true);
                echo '<pre>';
                var_dump($composer);


            }


        }
    }