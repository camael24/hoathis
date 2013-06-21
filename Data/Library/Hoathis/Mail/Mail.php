<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 21/06/13
     * Time: 09:23
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Mail {
        class Mail
        {
            private $_from = 'Hoa Mail (CLI) <julien.clauzel@hoa-project.net>';
            private $_to = null;
            private $_subject = null;
            private $_cc = null;
            private $_handler;

            public function __construct()
            {
                $this->_handler = new \Hoa\Mail\Message();

            }

            /**
             * @param null $cc
             */
            public function setCc($cc)
            {
                $this->_cc = $cc;
            }

            /**
             * @return null
             */
            public function getCc()
            {
                return $this->_cc;
            }

            /**
             * @param string $from
             */
            public function setFrom($from)
            {
                $this->_from = $from;
            }

            /**
             * @return string
             */
            public function getFrom()
            {
                return $this->_from;
            }

            /**
             * @param null $subject
             */
            public function setSubject($subject)
            {
                $this->_subject = $subject;
            }

            /**
             * @return null
             */
            public function getSubject()
            {
                return $this->_subject;
            }

            /**
             * @param null $to
             */
            public function setTo($to)
            {
                $this->_to = $to;
            }

            /**
             * @return null
             */
            public function getTo()
            {
                return $this->_to;
            }

            public function setContent(\Hoa\Mail\Content $content)
            {
                $this->_handler->addContent($content);

            }

            public function send()
            {
                $msg            = $this->_handler;
                $msg['From']    = $this->getFrom();
                $msg['To']      = $this->getTo();
                $msg['Subject'] = $this->getSubject();

                $cc = $this->getCc();
                if ($cc != null) {
                    $msg['Cc'] = $cc;
                }

                $msg->send();
            }


        }
    }
