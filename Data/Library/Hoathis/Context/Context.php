<?php
namespace {
    from('Hoathis')->import('Context.Exception');
}
namespace Hoathis\Context {
    class Context {
        private $_directory = 'hoa://Data/Etc/Context';
        private $_defaultContainer = 'hoa://Data/Etc/Context/default';

        public function setdefaultContainer($contextDefaultContainer = 'default') {
            $this->_defaultContainer = $contextDefaultContainer;
        }

        public function getDefaultContainer() {
            return $this->_defaultContainer;
        }


        public function setDirectory($contextDirectory = 'hoa://Data/Etc/Context') {
            $this->_directory = $contextDirectory;
        }

        public function getDirectory() {
            return $this->_directory;
        }

        public function setDefaultContext($contextName, $force = false) {
            if ($force === false) {
                if (!file_exists($this->getDirectory() . '/' . $contextName . '.json')) {
                    throw new Exception('The context %s seems not active', 0, array($contextName));
                }

            }
            file_put_contents($this->getDefaultContainer(), $contextName);

        }

        public function getDefaultContext() {
            return file_get_contents($this->getDefaultContainer());
        }

        public function getContext($contextName) {
            $uri = $this->getDirectory() . '/' . $contextName . '.json';

            return file_get_contents($uri);
        }

        public function add($name, $uriFile, $force = false) {
            $destinationUri = $this->getDirectory() . '/' . $name . '.json';
            if (true === $force) {
                if (true === file_exists($destinationUri))
                    if (false === unlink($destinationUri))
                        throw new Exception('The file can not be forced', 0, array());

            }
            copy($uriFile, $destinationUri);
        }


        public function newContextFrom($newContextName, $contextNameReference = null) {
            if ($contextNameReference === null)
                $contextNameReference = $this->getDefaultContext();

            $destination = $this->getDirectory() . '/' . $newContextName . '.json';
            $source      = $this->getDirectory() . '/' . $contextNameReference . '.json';
            if (file_exists($destination))
                throw new Exception('This %s context ever exist', 0, array($destination));
            if (!file_exists($source))
                throw  new Exception('This %s context not exist', 0, array($source));

            copy($source, $destination);
        }


    }
}
