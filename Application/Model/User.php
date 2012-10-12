<?php

namespace {

    from('Hoa')
        ->import('Model.~')
        ->import('Database.Dal');
}

namespace Application\Model {

    class User extends \Hoa\Model\Model {

        /**
         * @invariant idUser: undefined();
         */
        protected $_idUser;
        /**
         * @invariant username: undefined();
         */
        protected $_username;
        /**
         * @invariant password: undefined();
         */
        protected $_password;
        /**
         * @invariant email: undefined();
         */
        protected $_email;
        /**
         * @invariant rang: undefined();
         */
        protected $_rang;

        protected function construct() {

            $this->setMappingLayer(\Hoa\Database\Dal::getLastInstance());

            return;
        }


        public function connect($user, $password) {
            $select = 'SELECT * FROM `user` WHERE `username` = :name AND `password` = SHA1(:pass) AND rang > 0';
            $select = $this->getMappingLayer()
                ->prepare($select)
                ->execute(array(
                'name'     => $user,
                'pass'     => $password
            ))->fetchAll();

            if (count($select) === 0) {
                return false;
            }
            if (count($select) > 1)
                throw new \Exception('ERROR SQL');
            else
                $this->map($select[0]);

            return true;
        }


        public function checkMail($mail) {
            $select = 'SELECT * FROM `user` WHERE `email` = :mail';
            $select = $this->getMappingLayer()
                ->prepare($select)
                ->execute(array(
                'mail'     => $mail,
            ))->fetchAll();

            return (count($select) === 0);
        }

        public function checkUser($user) {
            $select = 'SELECT * FROM `user` WHERE `name` = :user';
            $select = $this->getMappingLayer()
                ->prepare($select)
                ->execute(array(
                'user'     => $user,
            ))->fetchAll();

            return (count($select) === 0);
        }

        public function insert($user, $password, $mail) {

            // RANG 0 = Unactivate or Banned
            $sql    = 'INSERT INTO `user` (`idUser` ,`username` ,`password` ,`email` ,`rang`)VALUES (NULL , :name, SHA1(:pass), :mail, 1);';
            $select = $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'name'     => $user,
                'pass'     => $password,
                'mail'     => $mail
            ));
        }

        public function open(Array $constraints = array()) {
            $id     = $constraints['id'];
            $select = 'SELECT * FROM `user` WHERE `idUser` = :id';
            $select = $this->getMappingLayer()
                ->prepare($select)
                ->execute(array(
                'id'     => $id
            ))->fetchAll();

            if (count($select) === 1)
                $this->map($select[0]);
            else
                return false;

            return;
        }

        public function setPassword($id, $pass) {
            if ($pass === null)
                return;
            $sql = 'UPDATE `user` SET `password` = SHA1(:pass) WHERE `idUser` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
                'pass'     => $pass
            ));
        }

        public function setRang($id, $value) {
            if ($value === null)
                return;

            $sql = 'UPDATE `user` SET `rang` = :rang WHERE `idUser` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
                'rang'     => $value
            ));
        }

        public function setMail($id, $mail) {
            if ($mail === null)
                return;
            $sql = 'UPDATE `user` SET `email` = :mail WHERE `idUser` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
                'mail'     => $mail
            ));
        }

        public function setUsername($id, $username) {
            if ($username === null)
                return;
            $sql = 'UPDATE `user` SET `username` = :user WHERE `idUser` = :id;';
            $this->getMappingLayer()
                ->prepare($sql)
                ->execute(array(
                'id'       => $id,
                'user'     => $username
            ));
        }

        public function update($id, $pass, $mail) {
            $this->setMail($id, $mail);
            $this->setPassword($id, $pass);
        }

        public function search($data) {
            $select = 'SELECT *  FROM user WHERE username LIKE :data AND rang > 0 LIMIT 20';
            $select = $this->getMappingLayer()->prepare($select)->execute(
                array(
                    'data'  => $data . '%'
                )
            )->fetchAll(); // TODO : use fulltext search
            return $select;
        }

        public function all() {
            $select = 'SELECT *  FROM user';
            $select = $this->getMappingLayer()->prepare($select)->execute()->fetchAll();

            return $select;
        }

    }

}
            