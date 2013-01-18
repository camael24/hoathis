<?php
namespace {
    from('Hoathis')->import('Context.Exception');
}
namespace Hoathis\Context {
    /**
     *
     */
    class Context {
        /**
         * @var string
         */
        private $_directory = 'hoa://Data/Etc/Context';
        /**
         * @var string
         */
        private $_defaultContainer = 'hoa://Data/Etc/Context/default';
        /**
         * @var null
         */
        private $_mappingContextKey = null;

        /**
         * @param $key
         *
         * @return mixed|null
         */
        public function __get($key) {
            if ($this->_mappingContextKey === null)
                $this->load();

            $reflection = new \ReflectionObject($this->_mappingContextKey);
            if ($reflection->hasProperty($key))
                return $reflection->getProperty($key)->getValue($this->_mappingContextKey);
            else
                return null;

        }

        /**
         * @param null $contextName
         */
        public function load($contextName = null) {
            if ($contextName === null)
                $contextName = $this->getDefaultContext();

            $context                  = $this->getContext($contextName);
            $this->_mappingContextKey = json_decode($context);
        }

        /**
         * @param string $contextDefaultContainer
         */
        public function setdefaultContainer($contextDefaultContainer = 'default') {
            $this->_defaultContainer = $contextDefaultContainer;
        }

        /**
         * @return string
         */
        public function getDefaultContainer() {
            return $this->_defaultContainer;
        }


        /**
         * @param string $contextDirectory
         */
        public function setDirectory($contextDirectory = 'hoa://Data/Etc/Context') {
            $this->_directory = $contextDirectory;
        }

        /**
         * @return string
         */
        public function getDirectory() {
            return $this->_directory;
        }

        /**
         * @param      $contextName
         * @param bool $force
         *
         * @throws Exception
         */
        public function setDefaultContext($contextName, $force = false) {
            if ($force === false) {
                if (!file_exists($this->getDirectory() . '/' . $contextName . '.json')) {
                    throw new Exception('The context %s seems not active', 0, array($contextName));
                }

            }
            file_put_contents($this->getDefaultContainer(), $contextName);

        }

        /**
         * @return string
         */
        public function getDefaultContext() {
            return file_get_contents($this->getDefaultContainer());
        }

        /**
         * @param $contextName
         *
         * @return string
         */
        public function getContext($contextName) {
            $uri = $this->getDirectory() . '/' . $contextName . '.json';

            return file_get_contents($uri);
        }

        /**
         * @param      $name
         * @param      $uriFile
         * @param bool $force
         *
         * @throws Exception
         */
        public function add($name, $uriFile, $force = false) {
            $destinationUri = $this->getDirectory() . '/' . $name . '.json';
            if (true === $force) {
                if (true === file_exists($destinationUri))
                    if (false === unlink($destinationUri))
                        throw new Exception('The file can not be forced', 0, array());

            }
            copy($uriFile, $destinationUri);
        }


        /**
         * @param      $newContextName
         * @param null $contextNameReference
         *
         * @throws Exception
         */
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
