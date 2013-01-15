<?php
namespace {
}
namespace Application\Controller\Admin {

    class Main extends Generic {

        public function IndexAction() {
            $this->adminGuard();

            $this->view->render();
        }

    }
}

