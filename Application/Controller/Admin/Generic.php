<?php
namespace {
}
namespace Application\Controller\Admin {

    abstract class Generic extends \Application\Controller\Generic {

        public function construct() {
            parent::construct();
            $this->adminGuard();
        }

        public function adminGuard() {
            $this->guestGuard();
            if (!$this->isAdminAllowed()) {
                $this->popup('info', 'You don`t allow to access here');
                $this->getKit('Redirector')->redirect('home-caller', array('_able' => 'connect'));
            }

        }


    }
}

