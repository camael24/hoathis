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

            protected function construct () {

                $this->setMappingLayer(\Hoa\Database\Dal::getLastInstance());

                return;
            }


            public function connect ($user, $password) {

                $select = 'SELECT * FROM user WHERE username = :name AND password = :pass AND rang > 1';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array(
                                   'name' => strtolower($user),
                                   'pass' => sha1($password)
                              )
                    )
                    ->fetchAll();


                if(count($select) === 0) {
                    return false;
                }
                if(count($select) > 1)
                    throw new \Exception('ERROR SQL');
                else
                    $this->map($select[0]);

                return true;
            }


            public function checkMail ($mail) {
                $select = 'SELECT * FROM user WHERE email = :mail';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array(
                                   'mail' => $mail,
                              )
                    )
                    ->fetchAll();

                return (count($select) === 0);
            }

            public function checkUser ($user) {
                $select = 'SELECT * FROM user WHERE username = :user';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array(
                                   'user' => strtolower($user),
                              )
                    )
                    ->fetchAll();

                return (count($select) === 0);
            }

            public function insert ($user, $password, $mail) {

                // RANG 0 = Unactivate or Banned
                $sql    = 'INSERT INTO user (idUser ,username ,password ,email ,rang)VALUES (NULL , :name, :pass, :mail, 2);';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array(
                                   'name' => strtolower($user),
                                   'pass' => sha1($password),
                                   'mail' => $mail
                              )
                    );
            }

            public function open (Array $constraints = array()) {
                $id     = $constraints['id'];
                $select = $this->getById($id);
                if(count($select) === 1)
                    $this->map($select[0]);
                else
                    return false;

                return true;
            }

            public function getById ($id) {
                $select = 'SELECT * FROM user WHERE idUser = :id';

                return $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array('id' => $id))
                    ->fetchAll();

            }

            public function openByName (Array $constraints = array()) {
                $id     = $constraints['name'];
                $select = 'SELECT * FROM user  WHERE username = :id';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array(
                                   'id' => strtolower($id)
                              )
                    )
                    ->fetchAll();

                if(count($select) === 1)
                    $this->map($select[0]);
                else
                    return false;

                return;
            }

            public function getRangLabel ($rang) {
                $select = 'SELECT * FROM rang  WHERE idRang = :id';
                $s      = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array('id' => $rang))
                    ->fetchAll();

                return $s[0];
            }

            public function setPassword ($id, $pass) {
                if(empty($pass))
                    return;
                $sql = 'UPDATE user SET password = SHA1(:pass) WHERE idUser = :id;';
                $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array(
                                   'id'   => $id,
                                   'pass' => $pass
                              )
                    );
            }

            public function setRang ($id, $value) {
                if(empty($value))
                    return;

                $sql = 'UPDATE user SET rang = :rang WHERE idUser = :id;';
                $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array(
                                   'id'   => $id,
                                   'rang' => $value
                              )
                    );
            }

            public function setMail ($id, $mail) {
                if(empty($mail))
                    return;
                $sql = 'UPDATE user SET email = :mail WHERE idUser = :id;';
                $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array(
                                   'id'   => $id,
                                   'mail' => $mail
                              )
                    );
            }

            public function setUsername ($id, $username) {
                if(empty($username))
                    return;
                $sql = 'UPDATE user SET username = :user WHERE idUser = :id;';
                $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array(
                                   'id'   => $id,
                                   'user' => strtolower($username)
                              )
                    );
            }

            public function update ($id, $pass, $mail) {
                $this->setMail($id, $mail);
                $this->setPassword($id, $pass);
            }

            public function search ($data) {
                $select = 'SELECT *  FROM user AS u, rang AS r WHERE u.username LIKE :data AND u.rang > 1 AND u.rang = r.idRang LIMIT 20 ';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute(array(
                                   'data' => strtolower($data) . '%'
                              )
                )
                    ->fetchAll(); // TODO : use fulltext search
                return $select;
            }

            public function all () {
                $select = 'SELECT *  FROM user AS u, rang AS r WHERE rang = r.idRang';
                $select = $this
                    ->getMappingLayer()
                    ->prepare($select)
                    ->execute()
                    ->fetchAll();

                return $select;
            }

            public function check ($value, $champ) { // TODO check with atoum !
                switch ($champ) {
                    case 'username':
                        return (strlen($value) > 3);
                        break;
                    case 'password':
                        return (strlen($value) > 7);
                        break;
                    case 'email':
                        return ((filter_var($value, FILTER_VALIDATE_EMAIL) !== false) ? true : false);
                        break;
                    default:
                        return false;
                }
            }

            public function remove ($id) {
                $sql = 'DELETE FROM user WHERE idUser = :id';
                $this
                    ->getMappingLayer()
                    ->prepare($sql)
                    ->execute(array('id' => $id))
                    ->fetchAll();
            }

        }

    }
