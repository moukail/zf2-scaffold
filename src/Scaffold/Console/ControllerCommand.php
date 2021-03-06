<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\State;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends AbstractCommand
{

    protected function configure()
    {
        parent::configure();

        $this->setName('controller');
        $this->setDescription('Generate controller');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Controller name');
        $this->addOption('rest', 'r', InputOption::VALUE_NONE, 'Generate RESTful controller');
    }

    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writeState = new State($this->configWriter);
        $writeState->addModel($state->getControllerModel());

        parent::write($writeState, $input, $output);
    }

}
