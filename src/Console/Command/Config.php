<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use VBoxCLI\Console\Config as Configuration;

/**
 * Class Config
 * @author Abu Bakar Al-Idrus
 */
class Config extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('config')
             ->setDescription('Set configuration parameter for VM by UUID.')
             ->addArgument(
                 'uuid',
                 InputArgument::REQUIRED,
                 'The UUID of the virtual machine configuration to get or set.'
             )
             ->addArgument(
                 'parameter',
                 InputArgument::OPTIONAL,
                 'The parameter to get or set.'
             )
             ->addArgument(
                 'value',
                 InputArgument::OPTIONAL,
                 'The value to set the parameter to.'
             );
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Get arguments
        $uuid      = $input->getArgument('uuid');
        $parameter = $input->getArgument('parameter');
        $value     = $input->getArgument('value');

        if ($parameter === null)
        {
            var_dump(Configuration::get($uuid));
            exit(0);
        }

        if ($value === null)
        {
            var_dump(Configuration::get($uuid, $parameter));
            exit(0);
        }

        Configuration::set($uuid, $parameter, $value);

        exit(0);
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
