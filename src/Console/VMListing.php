<?php

namespace XDMS\Console;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class VMList
 * @author Abu Bakar Al-Idrus
 */
class VMListing
{
    const DIRECTIVE = 'list vms';

    /**
     * Get list of vms and construct an array.
     */
    public static function get(OutputInterface $output)
    {
        $vmList = VBoxManage::create();

        if ($vmList === null)
        {
            $output->writeln('<error>VBoxManage command not found!</error>');
            exit(1);
        }

        $vmList->setDirective(self::DIRECTIVE);

        if (!$vmList->run())
        {
            $output->writeln('<error>Unable to list vms!</error>');
            exit(2);
        }

        $vms = $vmList->getOutput('vmlist');

        $result = array_walk($vms, function(&$vm) {
            $vmStatus = VBoxManage::create()
                      ->setDirective('showvminfo --machinereadable {'.$vm['uuid'].'}');

            $vm['state'] = $vmStatus->run()
                         ? $vmStatus->getOutput('vminfo')['VMState']
                         : '';

            $extraData = VBoxManage::create()
                       ->setDirective('getextradata {'.$vm['uuid'].'} vmNum');

            $extraData->run();

            $vm = [ 'vmNum' => $extraData->getOutput('extradata') ]
                + $vm;
        });

        return $vms;
    }
}



