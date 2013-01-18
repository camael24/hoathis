<?php
namespace {
    from('Hoathis')->import('Context.~');
}

namespace Hoathis\Context\Bin {


    class Set extends \Hoa\Console\Dispatcher\Kit {


        protected $options = array(
            array('copy', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'c'),
            array('file', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'f'),
            array('force', \Hoa\Console\GetOption::NO_ARGUMENT, 'p'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?')
        );


        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main() {

            $force   = false;
            $copy    = null;
            $file    = null;
            $context = new \Hoathis\Context\Context();

            while (false !== $c = $this->getOption($v)) switch ($c) {

                case 'f':
                    $file = $v;
                    break;
                case 'c':
                    $copy = $v;
                    break;
                case 'p':
                    $force = true;
                    break;
                case 'h':
                case '?':
                default:
                    return $this->usage();
                    break;
            }


            $this->parser->listInputs($name);

            if (null === $name)
                return $this->usage();

            if ($file !== null) {
                $context->add($name, $file, $force);

            } else if ($copy !== null) {
                $context->newContextFrom($name, $copy);
            }
            $context->setDefaultContext($name, $force);

            return;
        }


        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage() {

            echo 'Usage   : hoathis context:set name', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList(array(
                'copy'  => 'create new context from current context.',
                'file'  => 'add an external file to context configuration',
                'force' => 'Force parameter',
                'help'  => 'This help.'
            )), "\n";

            return;
        }
    }

}
