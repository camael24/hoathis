<?php

namespace {

    from('Hoa')
        ->import('Model.~')
        ->import('Database.Dal');
}

namespace Application\Model {

    class Rang extends \Hoa\Model\Model {

        /**
         * @invariant idRang: undefined();
         */
        protected $_idRang;
        /**
         * @invariant RangLabel: undefined();
         */
        protected $_RangLabel;
        /**
         * @invariant RangClass: undefined();
         */
        protected $_RangClass;

        protected function construct() {

            $this->setMappingLayer(\Hoa\Database\Dal::getLastInstance());

            return;
        }

        public function all() {
            $select = 'SELECT *  FROM rang ORDER BY idRang ';
            return $this->getMappingLayer()->prepare($select)->execute(array())->fetchAll();
        }


    }

}
