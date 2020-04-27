<?php
/**
 * Date: 26.04.20
 * Time: 17:04
 */

namespace KSSConsole;


class Visualization
{

    /**
     * @return array
     */
    private function getCommandsList(): array
    {
        return [
            'project/create' => [
                'method' => 'createProject'
            ],
            'project/tree' => [
                'method' => 'showProjectTree'
            ],
            'part/add' => [
                'method' => 'createPart'
            ],
            'part/delete' => [
                'method' => 'deletePart'
            ]
        ];
    }

    /**
     * @param string $command
     * @param array $data
     */
    public function show(string $command, array $data): void
    {
        $commandsList = $this->getCommandsList();

        call_user_func_array([$this, $commandsList[$command]['method']], [$data]);
    }

    /**
     * Get current console window size(rows(width), columns(height))
     *
     * @return array
     */
    private function getConsoleSize(): array
    {
        preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);

        return [
            'rows' => $output[1][0],
            'columns' => $output[2][0]
        ];
    }

    /**
     * @param array $projectData
     */
    private function showProjectTree(array $projectData): void
    {
        $this->buildLevel([0], $projectData);

        var_dump($this->getConsoleSize());
    }

    /**
     * @param int $parentId
     * @param array $parts
     */
    private function buildLevel(array $parentIds, array $parts)
    {
        $childrenIds = [];
        foreach ($parentIds as $parentId) {
            //var_dump('begin', $parentIds, 'delimiter', $parentId);
            $childrenIds = array_merge($childrenIds, $this->getChildren($parentId, $parts));
        }

        echo PHP_EOL;
        if (!empty($childrenIds)) {
            $this->buildLevel($childrenIds, $parts);
        }
        //var_dump($part);die();
    }

    /**
     * @param int $parentId
     * @param array $parts
     * @return array
     */
    private function getChildren(int $parentId, array $parts): array
    {
        $childrenIds = [];
        foreach ($parts as $part) {
            if ($part['parent_id'] == $parentId) {
                $childrenIds[] = $part['id'];
                echo $part['name'] . " -- ";
                //echo "\033[32m". "coloured green text\n";
            }
        }

        return $childrenIds;
    }
}