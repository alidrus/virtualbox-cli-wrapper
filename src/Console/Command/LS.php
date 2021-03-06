<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use VBoxCLI\Console\VMListing;

/**
 * Class LS
 * @author Abu Bakar Al-Idrus
 */
class LS extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('ls')
             ->setDescription('List virtual machines');
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Set ansi output on by default.
        $output->setDecorated(true);

        $vms = VMListing::get($output);

        usort($vms, function ($a, $b) {
            return $a['vmNum'] === $b['vmNum'] ? 0
                   : ( $a['vmNum'] < $b['vmNum'] ? -1 : 1 );
        });

        $output->writeln('<info>Virtual Machine Listing</info>');

        $table = new Table($output);

        $table->setHeaders(['VM#', 'VM Name', 'UUID', 'State'])
              ->setRows($vms)
              ->render();
    }

}
