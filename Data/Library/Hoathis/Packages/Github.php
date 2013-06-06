<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 06/06/13
     * Time: 15:19
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Packages {
        class Github
        {
            private $_repos = null;

            public function setRepos ($repos)
            {
                $this->_repos = $repos;
            }

            public function getRepos ()
            {
                return $this->_repos;
            }

            public function getLastCommit ()
            {
                $commit = $this->getCommits();

                return $commit[0];

            }

            public function getCommits ()
            {
                $api = 'https://api.github.com/repos/' . $this->getRepos() . '/commits';
                $r   = $this->_request($api);

                return json_decode($r, true);

            }

            private function _request ($request)
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.83 Safari/537.1');
                $response = curl_exec($ch);
                curl_close($ch);

                return $response;
            }
        }
    }
