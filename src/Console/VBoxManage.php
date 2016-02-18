<?php

namespace XDMS\Console;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use XDMS\Console\CommandPath;

/**
 * Class VBoxManage
 * @author Abu Bakar Al-Idrus
 */
class VBoxManage
{
    const COMMAND = 'VBoxManage';

    private $command;

    private $directives = '';

    private $outputLines = [];

    /**
     * Class constructor receives the command as an argument.
     */
    private function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * Instantiate the class using a static method.
     *
     */
    public static function create($directives = '')
    {
        $command = CommandPath::command(self::COMMAND);

        if ($command === null)
        {
            return null;
        }

        $instance = new static($command);

        return strlen($directives) > 0
            ? $instance->setDirective($directives)
            : $instance;
    }

    /**
     * Append to VBoxManage command directives.
     *
     */
    public function setDirective($directives)
    {
        $this->directives = (strlen($this->directives) > 0)
                          ? ' '.$directives
                          : $directives;

        return $this;
    }

    /**
     * Execute the VBoxManage command.
     *
     */
    public function run()
    {
        $process = new Process($this->command->fullPath().' '.$this->directives);

        $process->run();

        if (!$process->isSuccessful())
        {
            return false;
        }

        $this->setOutput($process->getOutput());

        return true;
    }

    /**
     * Get the output of the command.
     *
     */
    public function getOutput($as = 'vmlist')
    {
        if ($as === 'vmlist')
        {
            return ParseOutput::asVMListing($this->output);
        }

        if ($as === 'vminfo')
        {
            return ParseOutput::asVMInfo($this->output);
        }

        if ($as === 'extradata')
        {
            return ParseOutput::asExtraData($this->output);
        }

        return $this->output;
    }

    /**
     * Set the output of the VBoxManage command.
     */
    private function setOutput($outputLines)
    {
        $this->output = preg_split('/$\R?^/m', trim($outputLines));

        return $this;
    }
}
