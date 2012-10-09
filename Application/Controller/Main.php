<?php
namespace Application\Controller {
    class Main extends \Hoa\Dispatcher\Kit
    {
        public function IndexAction()
        {
            $this->view->addOverlay('hoa://Application/View/Index/Main.xyl');
            $this->view->render();
        }

        public function FooActionAsync()
        {
            $this->FooAction();
        }

        public function FooAction()
        {
            echo json_encode(array(
                'foo',
                'bar',
                'jjj'
            ));
        }
    }
}
