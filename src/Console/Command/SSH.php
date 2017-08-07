<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use VBoxCLI\Console\CommandPath;
use VBoxCLI\Console\Config;

/**
 * Class SSH
 * @author Abu Bakar Al-Idrus
 */
class SSH extends Command
{
    const COMMAND    = 'ssh';

    /**
     *
     */
    public function configure()
    {
        $this->setName('ssh')
             ->setDescription('Connect to virtual machine by VM#.')
             ->addOption(
                 'custom-options',
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
        // Set ansi output on by default
        $output->setDecorated(true);

        // Get options
        $customOptions = $input->getOption('custom-options');

        // Get arguments
        $vmNum    = $input->getArgument('vm-number');
        $username = $input->getArgument('username');

        // Get VM configuration list
        $list = Config::get();

        if ($list === null)
        {
            $output->writeln('<error>VM with vmNum '.$vmNum.' does not exist.</error>');
        }

        // Get SSH host and port
        $sshPort = null;
        $sshHost = null;
        foreach($list as $uuid => $config)
        {
            if (array_key_exists('vmNum', $config)
                && $vmNum === $config['vmNum']
                && array_key_exists('sshHost', $config)
                && array_key_exists('sshPort', $config))
            {
                $sshHost = $config['sshHost'];
                $sshPort = $config['sshPort'];
                break;
            }
        }

        if ($sshHost === null || $sshPort === null)
        {
            $output->writeln('<error>VM with vmNum '.$vmNum.' does not have a complete SSH configuration.</error>');
        }

        // Get full path for SSH executable.
        $commandPath = CommandPath::command(self::COMMAND);

        // Construct ssh command line.
        $command = sprintf(
            '%s -p %d -l %s %s',
            $commandPath->get(),
            $sshPort,
            $username,
            $sshHost
        ).($customOptions !== false ? ' '.$customOptions : '');

        // Make sure no output buffering happens.
        ob_start();
        ob_end_flush();

        $maxTries  = 3;
        $returnVar = 1;
        $tryCount  = 0;

        while($returnVar !== 0 && $tryCount < $maxTries)
        {
            // Increment try count
            $tryCount++;

            if ($tryCount > 1)
            {
                echo "Failed to connect. Retrying in 2 seconds.\n";
                sleep(2);
            }

            // Execute ssh connection.
            passthru($command, $returnVar);

            if ($returnVar !== 0 && $tryCount >= $maxTries)
            {
                echo "Giving up... sorry.\n";
            }
        }
    }
}
