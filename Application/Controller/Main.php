<?php
namespace Application\Controller {
    class Main extends \Hoa\Dispatcher\Kit {
        public function IndexAction() {

            $this->data->label  = 'Most wanted';
            $this->data->search = array(
                array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
            );


            $this->view->addOverlay('hoa://Application/View/Index/Search.xyl');
            $this->view->render();
        }

        public function SearchActionAsync() {
            $this->SearchAction();
        }

        public function SearchAction() {
            $elmt = array(
                'foo',
                'bar',
                'jjj'
            );
            if (array_key_exists('term', $_POST)) {
                echo json_encode($elmt);
            } else if (array_key_exists('search', $_POST)) {
                $main = 'hoa://Application/View/Index/Fragment/List.xyl';

                $xyl        = new \Hoa\Xyl(
                    new \Hoa\File\Read($main),
                    new \Hoa\Http\Response(),
                    new \Hoa\Xyl\Interpreter\Html(),
                    $this->router
                );
                $this->view = $xyl;
                $this->data = $xyl->getData();

                $this->data->label  = 'Search of : ' . $_POST['search'];
                $this->data->search = array(
                    array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                    array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                    array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                    array('uri' => 'bar', 'author' => 'Camael', 'library' => 'License', 'description' => 'an exemple of foobar'),
                );

                $this->view->interprete();
                $this->view->render($this->view->getSnippet('main_list'));
            }


        }
    }
}
