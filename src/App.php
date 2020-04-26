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
    const HELP_COMMAND = "help";

    private $arguments = [];
    private $commands;
    private $help;

    /**
     * App constructor.
     * @param array $arguments
     * @param Commands $commands
     */
    public function __construct(array $arguments, Commands $commands, Help $help)
    {
        $this->arguments = $arguments;
        $this->commands = $commands;
        $this->help = $help;
    }

    /**
     *
     */
    public function run(): void
    {
        $result = $this->parseArguments();

        if ($result['command'] == self::HELP_COMMAND) {
            $this->help->printHelp();
        } else {
            if ($this->commands->isValidCommand($result['command'], $result['params'])) {
                $commandResult = $this->commands->execute($result['command'], $result['params']);
                var_dump($commandResult);
            } else {
                $this->print($result['command'] . self::COMMAND_ERROR_VALIDATION_MASSAGE);
            }
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