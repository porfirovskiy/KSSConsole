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

    public function execute()
    {
        /**
         * Нужен меппинг команд на вызываемые методы ксс движка
         * Посмотреть как устроен роутинг в фреймворке, ибо там тоже на каждый запрос
         * создаются классы и вызываются нужные методы, у меня проще - класс один, значит меппенг только методов!
         */

        $this->kss->createProject(['name' => 'Translate.com']);

        $this->kss->createPart(['name' => 'Human Translations', 'project_id' => 18, 'parent_id' => 0]);

        $this->kss->createPartContent(['content' => 'Test text.', 'part_id' => 13]);
    }
}