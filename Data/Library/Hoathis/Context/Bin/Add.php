<?php
namespace {

}

namespace Hoathis\Context\Bin {


    class Add extends \Hoa\Console\Dispatcher\Kit {


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

            $this->parser->listInputs($contextName, $contextFile);

            if ($contextName === null || $contextFile === null) {
                echo 'You must precise an context name and an context file', "\n";

                return $this->usage();
            }

            $dir = 'hoa://Data/Etc/Context';
            if (!is_dir($dir . '/' . $contextName))
                mkdir($dir . '/' . $contextName);

            if (is_file($contextFile))
                copy($contextFile, $dir . '/' . $contextName . '/context.json');

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
