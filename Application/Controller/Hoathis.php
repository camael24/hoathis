<?php
namespace Application\Controller {
    class Hoathis extends \Hoa\Dispatcher\Kit {
        public function IndexAction($page) {

            $tags = function ($list) {
                $a    = explode(',', $list);
                $elmt = '';
                foreach ($a as $e) {
                    $elmt .= '<div class="btn">' . $e . '</div>';
                }

                return $elmt;
            };

            $information = array(
                'author'             => 'Camael',
                'library'            => 'Hawk',
                'date'               => date('d/m/Y'),
                'home'               => 'http://ark.im/',
                'release-link'       => 'https://github.com/camael24/Hawk/',
                'issues-link'        => 'https://github.com/camael24/Hawk/issues',
                'documentation-link' => 'https://github.com/camael24/Hawk/wiki',
                'contact'            => 'thehawk@hoa-project.net',
                'tags'               => $tags('awesome,god-like,extra-author')
            );

            $this->data->information = $information;

            $this->view->addOverlay('hoa://Application/View/Hoathis/Library.xyl');
            $this->view->render();
        }

    }
}
