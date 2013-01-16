<?php
namespace {
    from('Hoathis')->import('Context.~');
}

namespace Hoathis\Context\Bin {


    class Load extends \Hoa\Console\Dispatcher\Kit {


        protected $options = array(
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

            while (false !== $c = $this->getOption($v)) switch ($c) {

                case 'h':
                case '?':
                default:
                    return $this->usage();
                    break;
            }

            $this->parser->listInputs($contextName);

            if ($contextName === null) {
                echo 'You must precise an context name', "\n";

                return $this->usage();
            }

            $dir = 'hoa://Data/Etc/Context/' . $contextName . '/';
            if (!is_dir($dir) or !is_file($dir . 'context.json')) {
                echo 'You must run hoathis context:add before';
                return;
            }


            $loader = new \Hoathis\Context\Context();
            $loader->load($contextName);


            return;
        }


        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage() {

            echo 'Usage   : hoathis context:add <options> [context-name] [context.json]', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList(array(
                'help' => 'This help.'
            )), "\n";

            return;
        }
    }

}
