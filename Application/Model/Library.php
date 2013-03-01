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

        public function getInformationFromName($name, $all = false) {

            $v = 1;
            if ($all === true) {
                $v = 0;
            }


            $select = 'SELECT *  FROM library AS l, user AS u WHERE l.name = :name AND l.valid >= :valid AND l.refUser = u.idUser;';
            $select = $this->getMappingLayer()->prepare($select)->execute(
                array(
                    'name'  => $name,
                    'valid' => $v
                )
            )->fetchAll();

            if (count($select) == 1)
                $select = $select[0];
            else
                return array();

            return $select;
        }


        public function getInformation($id, $all = false) {

            $v = 1;
            if ($all === true) {
                $v = 0;
            }


            $select = 'SELECT *  FROM library AS l, user AS u WHERE l.idLibrary = :id AND l.valid >= :valid AND l.refUser = u.idUser;';
            $select = $this->getMappingLayer()->prepare($select)->execute(
                array(
                    'id'    => $id,
                    'valid' => $v
                )
            )->fetchAll(); // TODO : use fulltext search

            if (count($select) == 1)
                $select = $select[0];
            else
                return array();

            return $select;
        }

        public function search($data) {
            $select = 'SELECT *  FROM library AS l, user AS u WHERE l.name LIKE :data AND l.valid = :valid AND l.refUser = u.idUser LIMIT 20;';
            $select = $this->getMappingLayer()->prepare($select)->execute(
                array(
                    'data'  => '%' . $data . '%',
                    'valid' => 1
                )
            )->fetchAll(); // TODO : use fulltext search
            return $select;
        }

        private function _set($id, $champ, $value) {
            if ($value === null)
                return;
            $sql = 'UPDATE `library` SET `' . $champ . '` = :data WHERE `idLibrary` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'   => $id,
                'data' => $value
            ));
        }

        public function setValid($id, $valid) {
            $this->_set($id, 'valid', $valid);
            $sql = 'UPDATE `library` SET `time` = NOW() WHERE `idLibrary` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id' => $id,
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
                'id' => $id,
            ));

        }

        public function insert($user, $name, $description, $homepage, $release, $documentation, $issue) {
            var_dump($documentation);
            $map = array(
                'refUser'       => $user,
                'name'          => preg_replace('#[^[:alnum:]]#', '', $name),
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

        public function getAll() {
            // SELECT *  FROM library AS l, user AS u WHERE l.valid = ? AND l.refUser = u.idUser
            $select = 'SELECT * FROM library INNER JOIN user ON library.refUser = user.idUser';
            $select = $this->getMappingLayer()->prepare($select)->execute()->fetchAll();

            return $select;

        }

        public function getFromAuthor($id) {
//            $select = "SELECT * FROM `library`  WHERE refUser = :refUser AND `valid` = '1'";
            $select = 'SELECT *  FROM library AS l, user AS u WHERE l.valid = 1 AND refUser = ? AND l.refuser = u.idUser';
            $select = $this->getMappingLayer()->prepare($select)->execute(array($id))->fetchAll();

            return $select;

        }

        public function getFromAuthorName($name) {
//            $select = "SELECT * FROM `library`  WHERE refUser = :refUser AND `valid` = '1'";
            $select = 'SELECT *  FROM library AS l, user AS u WHERE l.valid = 1 AND u.username = ? AND l.refuser = u.idUser';
            $select = $this->getMappingLayer()->prepare($select)->execute(array($name))->fetchAll();

            return $select;

        }

        public function delete($id) {
            $sql = "DELETE FROM `hoathis`.`library` WHERE `library`.`idLibrary` = :id";
            $this->getMappingLayer()->prepare($sql)->execute(array('id' => $id))->fetchAll();
        }

        public function getValidate() {
            $select = 'SELECT * FROM library INNER JOIN user ON library.refUser = user.idUser AND library.valid = 1';

            return $this->getMappingLayer()->prepare($select)->execute()->fetchAll();
        }

        public function getUnValidate() {
            $select = 'SELECT * FROM library INNER JOIN user ON library.refUser = user.idUser AND library.valid = 0';

            return $this->getMappingLayer()->prepare($select)->execute()->fetchAll();
        }

        public function check($value, $champ) {
            switch ($champ) {
                case 'name':
                    return preg_match('#[^[:alnum:]]#', $value);
                    break;
                case 'description':
                    return (strlen($value) > 5);
                    break;
                case 'home':
                case 'release':
                case 'documentation':
                case 'issues':
                    return filter_input(FILTER_VALIDATE_URL, $value);
                    break;
                default:
            }
            return false;
        }


    }

}
