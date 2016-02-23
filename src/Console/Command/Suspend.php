<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use VBoxCLI\Console\VBoxManage;
use VBoxCLI\Console\VMListing;

/**
 * Class Suspend
 * @author Abu Bakar Al-Idrus
 */
class Suspend extends Command
{
    const DIRECTIVE = 'controlvm %s savestate';

    /**
     *
     */
    public function configure()
    {
        $this->setName('suspend')
             ->setDescription('Suspend virtual by VM#.')
             ->addArgument(
                 'vm-number',
                 InputArgument::REQUIRED,
                 'The VM# of the virtual machine to connect to.'
             );
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Set ansi output on by default
        $output->setDecorated(true);

        // Get arguments
        $vmNum    = $input->getArgument('vm-number');

        // Get vm information
        $output->writeln('<info>Getting information for virtual machine #'.$vmNum.'</info>');

        // Get list of virtual machines
        $vms = array_filter(VMListing::get($output), function ($vm) use ($vmNum) {
            return intval($vm['vmNum']) === intval($vmNum);
        });

        // Return error if no virtual machine matched
        if (sizeof($vms) < 1)
        {
            $output->writeln('<error>VM# '.$vmNum.' does not exist.</error>');
            exit(1);
        }

        // Get first match
        $vm = array_pop($vms);

        $output->writeln('<info>Attempting to suspend virtual machine named "'.$vm['name'].'"</info>');

        // Construct VBoxManage command for suspending a running vm.
        $vmSuspend = VBoxManage::create(sprintf(self::DIRECTIVE, $vm['uuid']));

        $vmSuspend->start();

        $progress = new ProgressBar($output);
        $progress->setFormat('[ %bar% ] [ Time Elapsed: %elapsed:6s% ]');
        $progress->start();

        $counter=0;
        while($vmSuspend->isRunning())
        {
            if ($counter % 5000 === 0)
            {
                $progress->advance();
            }

            $counter++;
        }

        $progress->finish();

        if ($vmSuspend->isSuccessful())
        {
            $output->writeln("\n<info>Virtual machine suspended.</info>");
            exit(0);
        }

        $output->writeln("\n".'<error>Failed to suspend virtual machine!</error>');
        exit(1);
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
