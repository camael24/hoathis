<?php
    namespace {
    }
    namespace Application\Controller {

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
        }
    }