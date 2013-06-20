<?php
    namespace {

        from('Hoa')

            /**
             * \Hoa\Xyl\Interpreter\Html
             */
            ->import('Xyl.Interpreter.Html.~');


        from('Hoathis')

            /**
             * \Hoathis\Xyl\Interpreter\Html\*
             */
            ->import('Xyl.Interpreter.Html.*');

    }

    namespace Hoathis\Xyl\Interpreter\Html {

        /**
         * Class \Hoathis\Xyl\Interpreter\Html.
         *
         * HTML interpreter.
         *
         * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
         * @copyright  Copyright © 2007-2012 Ivan Enderlin.
         * @license    New BSD License
         */

        class Html extends \Hoa\Xyl\Interpreter\Html
        {

            public function __construct()
            {

                $this->_rank += array(
                    'paginator' => '\Hoathis\Xyl\Interpreter\Html\Paginator'
                );
            }
        }

    }