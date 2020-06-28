<?php

namespace VBoxCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

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
        // Set ansi output on by default.
        $output->setDecorated(true);

        // Get arguments
        $uuid      = $input->getArgument('uuid');
        $parameter = $input->getArgument('parameter');
        $value     = $input->getArgument('value');

        if ($parameter === null)
        {
            if (Configuration::get($uuid) === null)
            {
                $output->writeln('<error>The VM\'s configuration is blank.</error>');
                exit(1);
            }

            $list = [];
            foreach (Configuration::get($uuid) as $key => $val)
            {
                $list[] = array($key, $val);
            }

            $table = new Table($output);

            $table->setHeaders(['Parameter', 'Value'])
                  ->setRows($list)
                  ->render();

            exit(0);
        }

        if ($value === null)
        {
            $output->writeln(sprintf("Value for %s is: %s", $parameter, Configuration::get($uuid, $parameter)));

            exit(0);
        }

        $result = Configuration::set($uuid, $parameter, $value);
        $tag = $result === false ? 'error' : 'info';
        $message = $result === false ? 'Unable to set configuration.' : 'Configuration has been set.';

        $output->writeln('<'.$tag.'>'.$message.'</'.$tag.'>');

        exit(0);
    }
}

/**
 * vim: syntax=php expandtab tabstop=4 shiftwidth=4 softtabstop=4:
 */
