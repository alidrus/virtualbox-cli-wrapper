<?php

namespace XDMS\Console;

use Symfony\Component\Process\Process;

/**
 * Class CommandPath
 * @author Abu Bakar Al-Idrus
 */
class CommandPath
{
    private $fullPath;

    /**
     * Constructor for the CommandPath class.
     *
     **/
    private function __construct($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * Set the command we want the full path for.
     *
     */
    public static function command($command)
    {
        $whichCommand = new Process('which '.$command);

        $whichCommand->run();

        return $whichCommand->isSuccessful()
             ? new static(trim($whichCommand->getOutput()))
             : null;
    }

    /**
     * Get full path of the command.
     *
     */
    public function fullPath()
    {
        return $this->fullPath;
    }
}
