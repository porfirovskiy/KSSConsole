<?php
/**
 * Date: 26.04.20
 * Time: 0:31
 */

namespace KSSConsole;

/**
 * Class App
 * @package KSSConsole
 */
class App
{
    const COMMAND_ERROR_VALIDATION_MASSAGE = ": command not valid" .  PHP_EOL;

    private $arguments = [];
    private $commands;

    /**
     * App constructor.
     * @param array $arguments
     * @param Commands $commands
     */
    public function __construct(array $arguments, Commands $commands)
    {
        $this->arguments = $arguments;
        $this->commands = $commands;
    }

    /**
     *
     */
    public function run(): void
    {
        $this->print("Hello!");

        $result = $this->parseArguments();

        if ($this->commands->isValidCommand($result['command'], $result['params'])) {
            $commandResult = $this->commands->execute($result['command'], $result['params']);
            var_dump($commandResult);
        } else {
            $this->print($result['command'] . self::COMMAND_ERROR_VALIDATION_MASSAGE);
        }
    }

    /**
     * @return array
     */
    private function parseArguments(): array
    {
        return [
            'script_name' => $this->arguments[0],
            'command' => $this->arguments[1],
            'params' => array_slice($this->arguments, 2)
        ];
    }

    private function print(string $message): void
    {
        echo $message . PHP_EOL;
    }
}