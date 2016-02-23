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
 * Class Start
 * @author Abu Bakar Al-Idrus
 */
class Start extends Command
{
    const DIRECTIVE = 'startvm %s --type headless';

    /**
     *
     */
    public function configure()
    {
        $this->setName('start')
             ->setDescription('Start virtual by VM#.')
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

        // Construct VBoxManage command for starting vm.
        $vmStart = VBoxManage::create(sprintf(self::DIRECTIVE, $vm['uuid']));

        $output->writeln('<info>Attempting to start virtual machine named "'.$vm['name'].'"</info>');

        $vmStart->start();

        $progress = new ProgressBar($output);
        $progress->setFormat('[ %bar% ] [ Time Elapsed: %elapsed:6s% ]');
        $progress->start();

        $counter=0;
        while($vmStart->isRunning())
        {
            if ($counter % 5000 === 0)
            {
                $progress->advance();
            }

            $counter++;
        }

        $progress->finish();

        if ($vmStart->isSuccessful())
        {
            $outputLines = $vmStart->getOutput();
            $output->writeln("\n<info>".trim(array_pop($outputLines)).'</info>');
            exit(0);
        }

        $output->writeln("\n".'<error>Virtual machine failed to start!</error>');
        exit(1);
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
