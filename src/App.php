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
    private $visualization;

    /**
     * App constructor.
     * @param array $arguments
     * @param Commands $commands
     */
    public function __construct(array $arguments, Commands $commands, Help $help, Visualization $visualization)
    {
        $this->arguments = $arguments;
        $this->commands = $commands;
        $this->help = $help;
        $this->visualization = $visualization;
    }

    /**
     *
     */
    public function run(): void
    {
        $result = $this->parseArguments();

        if ($result['command'] == self::HELP_COMMAND) {
            $this->help->print();
        } else {
            $this->executeCommand($result['command'], $result['params']);
        }
    }

    /**
     * @param string $command
     * @param array $params
     */
    private function executeCommand(string $command, array $params): void
    {
        if ($this->commands->isValidCommand($command, $params)) {
            $commandResult = $this->commands->execute($command, $params);
            if (is_array($commandResult)) {
                $this->visualization->show($command, $commandResult);
            } else {
                var_dump($commandResult);
            }
        } else {
            $this->print($command . self::COMMAND_ERROR_VALIDATION_MASSAGE);
        }
    }

    /**
     * 
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

    /**
     *
     * @param string $message
     */
    private function print(string $message): void
    {
        echo $message . PHP_EOL;
    }
}