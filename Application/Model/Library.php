<?php

namespace {

    from('Hoa')
        ->import('Model.~')
        ->import('Database.Dal');
}

namespace Application\Model {

    class Library extends \Hoa\Model\Model {

        /**
         * @invariant idLibrary: undefined();
         */
        protected $_idLibrary;
        /**
         * @invariant refUser: undefined();
         */
        protected $_refUser;
        /**
         * @invariant name: undefined();
         */
        protected $_name;
        /**
         * @invariant description: undefined();
         */
        protected $_description;
        /**
         * @invariant home: undefined();
         */
        protected $_home;
        /**
         * @invariant release: undefined();
         */
        protected $_release;
        /**
         * @invariant documentation: undefined();
         */
        protected $_documentation;
        /**
         * @invariant issues: undefined();
         */
        protected $_issues;
        /**
         * @invariant time: undefined();
         */
        protected $_time;
        /**
         * @invariant valid: undefined();
         */
        protected $_valid;

        protected function construct() {

            $this->setMappingLayer(\Hoa\Database\Dal::getLastInstance());

            return;
        }

        public function getInformation($id, $all = false) {
            if ($all === true) {
                $select = 'SELECT * FROM `library` WHERE `idLibrary` = :id';
            } else {
                $select = 'SELECT * FROM `library` WHERE `idLibrary` = :id AND `valid` = "1"';
            }
            $select = $this->getMappingLayer()
                ->prepare($select)
                ->execute(array('id' => $id))
                ->fetchAll();

            if (count($select) == 1)
                $select = $select[0];
            else
                return array();

            $user = new User();


            $user->open(array('id' => $select['refUser']));

            if ($user->name === null) {
                $user->name = '#ERROR';
            }

            $select['author']  = $user->name;
            $select['contact'] = $user->name;


            return $select;
        }

        public function search($data) {
            $select = "SELECT * FROM `library`  WHERE name LIKE '%" . $data . "%' AND `valid` = '1'";
            $select = $this->getMappingLayer()->prepare($select)->execute()->fetchAll();

            $user = new User();

            foreach ($select as $id => $elmt) {


                $user->open(array('id' => $elmt['refUser']));

                if ($user->name === null) {
                    $user->name = '#ERROR';
                }

                $select[$id]['author']  = $user->name;
                $select[$id]['contact'] = $user->name;
            }

            return $select;
        }

        private function _set($id, $champ, $value) {
            if ($value === null)
                return;
            $sql = 'UPDATE `library` SET `' . $champ . '` = :data WHERE `idLibrary` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
                'data'     => $value
            ));
        }

        public function setValid($id, $valid) {
            $this->_set($id, 'valid', $valid);
            $sql = 'UPDATE `library` SET `time` = NOW() WHERE `idLibrary` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
            ));
        }

        public function update($id, $description, $homepage, $release, $documentation, $issue) {
            $this->_set($id, 'description', $description);
            $this->_set($id, 'home', $homepage);
            $this->_set($id, 'release', $release);
            $this->_set($id, 'documentation', $documentation);
            $this->_set($id, 'issues', $issue);

            $sql = 'UPDATE `library` SET `time` = NOW() WHERE `idLibrary` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
            ));

        }

        public function insert($user, $name, $description, $homepage, $release, $documentation, $issue) {
            $map = array(
                'refUser'       => $user,
                'name'          => $name,
                'description'   => $description,
                'home'          => $homepage,
                'release'       => $release,
                'documentation' => $documentation,
                'issue'         => $issue,
                'valid'         => '0'
            );


            $select = 'SELECT * FROM `library` WHERE `name` = :name';
            $select = $this->getMappingLayer()->prepare($select)->execute(array('name' => $name))->fetchAll();
            if (count($select) < 1) {

                $insert = 'INSERT INTO `library` (`idLibrary`, `refUser`, `name`, `description`, `home`, `release`, `documentation`, `issues`, `time` , `valid`)
            VALUES (NULL, :refUser, :name, :description, :home, :release, :documentation, :issue, NOW() , :valid);';


                $this->getMappingLayer()
                    ->prepare($insert)
                    ->execute($map);

                return true;
            } else {
                return false;
            }
        }

        public function getFromValidity($v = 0) {
            $select = "SELECT * FROM `library`  WHERE valid = " . $v;
            $select = $this->getMappingLayer()->prepare($select)->execute()->fetchAll();

            $user = new User();

            foreach ($select as $id => $elmt) {


                $user->open(array('id' => $elmt['refUser']));

                if ($user->name === null) {
                    $user->name = '#ERROR';
                }

                $select[$id]['author']  = $user->name;
                $select[$id]['contact'] = $user->name;
            }

            return $select;

        }

        public function getFromAuthor($id) {
            $select = "SELECT * FROM `library`  WHERE refUser = :refUser AND `valid` = '1'";
            $select = $this->getMappingLayer()->prepare($select)->execute(array('refUser' => $id))->fetchAll();

            $user = new User();

            foreach ($select as $id => $elmt) {


                $user->open(array('id' => $elmt['refUser']));

                if ($user->name === null) {
                    $user->name = '#ERROR';
                }

                $select[$id]['author']  = $user->name;
                $select[$id]['contact'] = $user->name;
            }

            return $select;

        }

        public function delete($id) {
            $sql    = "DELETE FROM `hoathis`.`library` WHERE `library`.`idLibrary` = :id";
            $select = $this->getMappingLayer()->prepare($sql)->execute(array('id' => $id))->fetchAll();
        }


    }

}
            