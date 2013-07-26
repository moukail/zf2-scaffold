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

class ExceptionCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('exception');
        $this->setDescription('Create exceptions');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
    }

    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writeState = new State($this->configWriter);
        $writeState->addModel($state->getRuntimeException());
        $writeState->addModel($state->getNotFoundException());

        parent::write($writeState, $input, $output);
    }
}