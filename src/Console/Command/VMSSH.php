<?php

namespace XDMS\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use XDMS\Copyright;
use XDMS\Console\CommandPath;

/**
 * Class VMSSH
 * @author Abu Bakar Al-Idrus
 */
class VMSSH extends Command
{
    const PORT_START = 52200;
    const COMMAND    = 'ssh';

    /**
     *
     */
    public function configure()
    {
        $this->setName('vm:ssh')
             ->setDescription('Connect to virtual machine by VM#.')
             ->addOption(
                 'options',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Options to pass to SSH command.',
                 '-A'
             )
             ->addArgument(
                 'vm-number',
                 InputArgument::REQUIRED,
                 'The VM# of the virtual machine to connect to.'
             )
             ->addArgument(
                 'username',
                 InputArgument::REQUIRED,
                 'The username to connect as.'
             );
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Get options
        $options = $input->getOption('options');

        // Get arguments
        $vmNum    = $input->getArgument('vm-number');
        $username = $input->getArgument('username');

        // Get full path for SSH executable.
        $commandPath = CommandPath::command(self::COMMAND);

        // Construct ssh command line.
        $command = sprintf(
            '%s -p %d -l %s localhost',
            $commandPath->get(),
            self::PORT_START + intval($vmNum),
            $username
        ).($options !== false ? ' '.$options : '');

        // Execute it
        passthru($command);
    }

}
