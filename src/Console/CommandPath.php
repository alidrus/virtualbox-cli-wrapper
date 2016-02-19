<?php

namespace XDMS\Console;

use Symfony\Component\Process\Process;

/**
 * Class CommandPath
 * @author Abu Bakar Al-Idrus
 */
class CommandPath
{
    private $commandPath;

    /**
     * Constructor for the CommandPath class.
     *
     **/
    private function __construct($commandPath)
    {
        $this->commandPath = $commandPath;
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
    public function get()
    {
        return $this->commandPath;
    }
}
