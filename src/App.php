<?php
/**
 * Date: 26.04.20
 * Time: 0:31
 */

namespace KSSConsole;

use KSSConsole\Commands;

/**
 * Class App
 * @package KSSConsole
 */
class App
{
    private $arguments = [];
    private $commands;

    /**
     * App constructor.
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
        $this->commands = new Commands();
        echo "Hello!" . PHP_EOL;
    }

    /**
     *
     */
    public function run(): void
    {
        $result = $this->parseArguments();

        if ($this->commands->isValidCommand($result['command'])) {
            echo $result['command'] .  PHP_EOL;
        } else {
            echo $result['command'] . ": command not found" .  PHP_EOL;
        }
    }

    /**
     * @return array
     */
    private function parseArguments(): array
    {
        return [
            'script_name' => $this->arguments[0],
            'command' => $this->arguments[1]
        ];
    }
}