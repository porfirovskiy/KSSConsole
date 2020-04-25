<?php
/**
 * Date: 26.04.20
 * Time: 0:55
 */

namespace KSSConsole;

/**
 * Class Commands
 * @package KSSConsole
 */
class Commands
{
    /**
     * @return array
     */
    private function getList(): array
    {
        return [
            'create/project' => ['name'],
            'add/part' => ['name', 'parent_id', 'project_id']
        ];
    }

    /**
     * @param string $command
     * @return bool
     */
    public function isValidCommand(string $command): bool
    {
        $list = $this->getList();
        if (in_array($command, $list)) {
            return true;
        } else {
            return false;
        }
    }
}