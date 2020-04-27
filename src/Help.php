<?php
/**
 * Date: 26.04.20
 * Time: 16:49
 */

namespace KSSConsole;


class Help
{
    /**
     * @return array
     */
    private function getCommandsList(): array
    {
        return [
            "project/create" => [
                'description' => "creating new project",
                "params" => "name",
                "example" => "kssconsole create/project 'PHP language'"
            ],
            "part/add" => [
                'description' => "creating new part for project or parent part",
                "params" => "name, project_id, parent_id",
                "example" => "kssconsole add/part 'Design patterns on PHP' 25 0"
            ],
            "part/delete" => [
                'description' => "deleting part by id",
                "params" => "id",
                "example" => "kssconsole part/delete 23"
            ],
        ];
    }

    /**
     *
     */
    public function print()
    {
        $helpList = $this->getCommandsList();

        echo "-- HELP, LIST OF COMMANDS -- ". PHP_EOL . PHP_EOL;

        foreach ($helpList as $command => $items) {
            echo $command . " - " . $items['description'] . PHP_EOL;
            echo "params: " . $items['params'] . PHP_EOL;
            echo "example: " . PHP_EOL;
            echo $items['example'] . PHP_EOL . PHP_EOL;
        }
    }
}