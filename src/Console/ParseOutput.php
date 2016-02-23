<?php

namespace VBoxCLI\Console;

/**
 * Class ParseOutput
 * @author Abu Bakar Al-Idrus
 */
class ParseOutput
{
    /**
     * Parse output as vm listing (list vms).
     */
    public static function asVMListing(Array $rawOutput)
    {
        $vmListing = array_map(function ($line) {
            if (preg_match('/^\"([^\"]+)\" \{([^\}]+)\}$/', trim($line), $parts))
            {
                return ['name' => $parts[1], 'uuid' => $parts[2]];
            }

            return $line;
        }, $rawOutput);

        return $vmListing;
    }

    /**
     * Parse output as vm info (showvminfo)
     */
    public static function asVMInfo(Array $rawOutput)
    {
        $vmInfo = [];

        foreach($rawOutput as $line)
        {
            $match = preg_match('/^([^=]+)=\"?([^\"]*)\"?$/', $line, $parts);

            if ($match)
            {
                $vmInfo[$parts[1]] = $parts[2];
            }
        }

        return $vmInfo;
    }

    /**
     * Parse output as extradata value (getextradata)
     */
    public static function asExtraData(Array $rawOutput)
    {
        foreach($rawOutput as $line)
        {
            $match = preg_match('/^Value: (.*)$/', $line, $parts);

            if ($match)
            {
                return $parts[1];
            }
        }

        return '';
    }
}
