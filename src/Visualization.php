<?php
/**
 * Date: 26.04.20
 * Time: 17:04
 */

namespace KSSConsole;


class Visualization
{

    private $x = 50;
    private $y = 50;
    private $image;

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

        $this->buildImage("Project Tree");
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
        echo PHP_EOL;

        $this->image = @imagecreate(1000, 1300) or die("Error");

        $this->buildLevel([0], $projectData);

        $this->saveImageToFile();
    }

    /**
     * @param int $parentId
     * @param array $parts
     */
    private function buildLevel(array $parentIds, array $parts)
    {
        $childrenIds = [];
        $levelString = "";
        $levelLines = "";

        foreach ($parentIds as $parentId) {
            $children = $this->getChildren($parentId, $parts);
            if (!empty($children['ids'])) {
                $childrenIds = array_merge($childrenIds, $children['ids']);
                $levelString .= $children['child_string'];
            }
            $levelLines .= "|";
        }

        $consoleSize = $this->getConsoleSize();
        $freeWidth = (int)$consoleSize['columns'] - strlen($levelString);
        $freeWidth = round(abs($freeWidth) / 2, 0);
        $freeWidth = intval($freeWidth);
        //var_dump((int)$consoleSize['columns'], strlen($levelString), $freeWidth);

        if (!empty($childrenIds)) {
            echo str_repeat(" ", $freeWidth) . $levelString . PHP_EOL;
            echo $levelLines . PHP_EOL;

            $this->buildImageLevel(str_repeat(" ", $freeWidth) . $levelString);

            $this->buildLevel($childrenIds, $parts);
        }
    }

    /**
     * @param int $parentId
     * @param array $parts
     * @return array
     */
    private function getChildren(int $parentId, array $parts): array
    {
        $children = [
            'ids' => [],
            'child_string' => ""
        ];

        foreach ($parts as $part) {
            if ($part['parent_id'] == $parentId) {
                $children['ids'][] = $part['id'];
                $children['child_string'] .= '"' . $part['name'] . '" ';
                //echo "\033[32m". "coloured green text\n";
            }
        }

        return $children;
    }

    protected function buildImageLevel(string $text)
    {
        $backgroundColor = imagecolorallocate($this->image, 0, 0, 0);
        $textColor = imagecolorallocate($this->image, 233, 14, 91);

        //add text to img
        //imagestring($this->image, 5, 50, $this->y, $text, $textColor);
        imagettftext($this->image, 14, 0, 10, $this->y, $textColor, $font = '/usr/share/fonts/truetype/freefont/FreeMono.ttf', $text);

        $this->y = $this->y + 50;
    }

    protected function saveImageToFile()
    {
        imagepng($this->image, 'projectTreeLevel.png');
    }

    public function buildImage(string $command)
    {
        $image = @imagecreate(2000, 1700) or die("Error");
        $backgroundColor = imagecolorallocate($image, 0, 0, 0);
        $textColor = imagecolorallocate($image, 233, 14, 91);

        //add text to img
        imagestring($image, 5, 850, 50, $command, $textColor);

        //$textColor = imagecolorallocate($image, 0, 255, 0);
        imagestring($image, 5, 50, 70, "Text111", $textColor);

        $white = imagecolorallocate($image, 255, 255, 255);
        // координаты линии
        $x1 = 20; $y1 = 50; $x2 = 180; $y2 = 50;
        // рисуем обычную линию
        imageline ($image, $x1, $y1, $x2, $y2, $white);
        imageline ($image, $x1, $y1, 20, 100, $white);

        imagepng($image, 'projectTree.png');
    }

    // ==============
    public function createImage(string $title, array $methods,  array $subMethods) {
        $image = @imagecreate(1700, 1700) or die("Error");
        $backgroundColor = imagecolorallocate($image, 0, 0, 0);
        $textColor = imagecolorallocate($image, 233, 14, 91);

        //add text to img
        imagestring($image, 5, 50, 5, $title, $textColor);
        $y = 70;
        foreach ($methods as $method) {
            $textColor = imagecolorallocate($image, 0, 102, 204);
            imagestring($image, 3, 10, $y, $method, $textColor);
            $this->methodsPositions[$method] = $y;
            //render sub methods
            if (isset($subMethods[$method])) {
                $yy = $y + 10;
                foreach ($subMethods[$method] as $subMethod) {
                    $textColor = imagecolorallocate($image, 0, 255, 0);
                    imagestring($image, 2, 25, $yy, $subMethod, $textColor);
                    $yy += 10;
                }
            }
            echo "\0\0$method\n";
            $y += 55;
        }
        //end add text

        imagepng($image, 'structure.png');
    }

    public function createImage2(string $title, array $methods, $tree) {
        $image = @imagecreate(2000, 1700) or die("Error");
        $backgroundColor = imagecolorallocate($image, 0, 0, 0);
        $textColor = imagecolorallocate($image, 233, 14, 91);

        //add text to img
        imagestring($image, 5, 50, 5, $title, $textColor);
        $tree->getAroundTheStructure2('get_words_pairs($first_lang, $second_lang, $first_letter)', $image, $textColor);

        $white = imagecolorallocate($image, 255, 255, 255);
        // координаты линии
        $x1 = 20; $y1 = 50; $x2 = 180; $y2 = 50;
        // рисуем обычную линию
        imageline ($image, $x1, $y1, $x2, $y2, $white);

        imagepng($image, 'structure2.png');
    }

}