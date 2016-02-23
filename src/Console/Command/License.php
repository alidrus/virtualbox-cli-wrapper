<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use VBoxCLI\LicenseText;

/**
 * Class License
 * @author Abu Bakar Al-Idrus
 */
class License extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('license')
             ->setDescription('Display software license');
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Set ansi output on by default.
        $output->setDecorated(true);

        // Get and display license text.
        $output->writeln('<info>'.LicenseText::get().'</info>');
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
