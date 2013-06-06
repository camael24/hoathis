<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 06/06/13
     * Time: 14:29
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Packages {
        class Create
        {
            private $_repos = array();
            private $_branch = 'master';
            private $_packages = array();
            private $_composer = null;

            public function __construct ($repos, Composer $composer)
            {
                $this->_repos    = $repos;
                $this->_composer = $composer;
                $this->setComposerKey('name');
                $this->setBranch();
            }

            public function setKey ($key, $value)
            {
                if ($value !== null) {
                    $this->_packages[$key] = $value;
                }
            }

            public function setComposerKey ($key)
            {
                $this->setKey($key, $this->_composer->getKey($key));
            }

            public function getKey ($key)
            {
                if (array_key_exists($key, $this->_packages)) {
                    return $this->_packages[$key];
                }

                return null;
            }

            public function getPackages ()
            {
                return array($this->_branch => $this->_packages);
            }

            public function setBranch ($defaultBranch = 'master')
            {
                $this->_branch = $defaultBranch;
                $this->setKey('version', $defaultBranch);
            }

            public function setVersionNormalized ($version)
            {
                $this->setKey('version_normalised', $version);
            }

            public function setSource ($type, $ref)
            {
                $repos  = $this->_repos;
                $github = 'https://github.com/' . $repos . '.git';

                $array = array(
                    'type'      => $type,
                    'url'       => $github,
                    'reference' => $ref
                );
                $this->setKey('source', $array);
            }

            public function setZipBall ($ref)
            {
                $array = array(
                    'type'      => 'zip',
                    'url'       => 'https://api.github.com/repos/' . $this->_repos . '/zipball/' . $ref,
                    'reference' => $ref,
                    'shasum'    => ''
                );

                $this->setKey('dist', $array);
            }

        }
    }
