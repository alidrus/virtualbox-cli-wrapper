<?php

namespace XDMS\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use XDMS\Console\VBoxManage;
use XDMS\Console\VMListing;

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

        // Construct VBoxManage command for suspending a running vm.
        $vmSuspend = VBoxManage::create(sprintf(self::DIRECTIVE, $vm['uuid']));

        if (!$vmSuspend->run())
        {
            $output->writeln('<error>Unable to suspend vm!</error>');
            exit(2);
        }

        var_dump($vmSuspend->getOutput());
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
