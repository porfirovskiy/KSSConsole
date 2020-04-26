<?php
/**
 * Date: 26.04.20
 * Time: 0:55
 */

namespace KSSConsole;

use KSS\KSSEngine;

/**
 * Class Commands
 * @package KSSConsole
 */
class Commands
{
    private $kss;

    /**
     * Commands constructor.
     * @param KSSEngine $kss
     */
    public function __construct(KSSEngine $kss)
    {
        $this->kss = $kss;
    }

    /**
     * @return array
     */
    private function getList(): array
    {
        return [
            'project/create' => [
                'method' => 'createProject',
                'params' => ['name']
            ],
            'part/add' => [
                'method' => 'createPart',
                'params' => ['name', 'project_id', 'parent_id']
            ],
            'part/delete' => [
                'method' => 'deletePart',
                'params' => ['id']
            ]
        ];
    }

    /**
     * @param string $command
     * @param array $params
     * @return bool
     */
    public function isValidCommand(string $command, array $params): bool
    {
        $list = $this->getList();
        if (isset($list[$command])) {
            if (count($params) === count($list[$command]['params'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $command
     * @return string
     */
    private function getMethod(string $command): string
    {
        $list = $this->getList();

        return $list[$command]['method'];
    }

    /**
     * @param string $command
     * @return array
     */
    private function getMethodParams(string $command): array
    {
        $list = $this->getList();

        return $list[$command]['params'];
    }

    /**
     * @param string $command
     * @param array $params
     * @return mixed
     */
    public function execute(string $command, array $params)
    {
        $method = $this->getMethod($command);
        $methodParams = $this->getMethodParams($command);
        $readyParams = [array_combine($methodParams, $params)];

        return call_user_func_array([$this->kss, $method], $readyParams);
    }
}