<?php

use Bitrix\Main\EventManager;
use Fusion\Card\ORM\PostTable;

Class fusion_card extends CModule
{
    public $MODULE_ID;
    public $MODULE_NAME;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS = 'Y';

    function __construct()
    {
            $arModuleVersion = array();
        include (__DIR__ . '/version.php');
        $this->MESS_PREFIX = mb_strtoupper(get_class($this));
        $this->MODULE_ID = str_replace('_', '.', get_class($this));
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = 'Тестовое задание';
        $this->MODULE_DESCRIPTION = 'Тестовое задание';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        \CModule::IncludeModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallEvents();

        return true;
    }

    function DoUninstall(): bool
    {
        \CModule::IncludeModule($this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallEvents();
        $this->UnInstallDB();

        return true;
    }

    function InstallEvents(): bool
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandlerCompatible(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            \Fusion\Card\Event\OnBeforeProlog::class,
            'init'
        );
        return false;
    }

    function InstallDB(): bool
    {
        $connection = \Bitrix\Main\Application::getConnection();

        if ($connection->isTableExists(PostTable::getTableName())) {
            return true;
        }

        $entity = PostTable::getEntity();
        $sql = $entity->compileDbTableStructureDump();
        $connection->queryExecute(implode('; ', $sql));

        PostTable::add([
            PostTable::NAME => 'Пост 1',
            PostTable::DESCRIPTION => 'Описание 1',
            PostTable::DATE_CREATED => new \Bitrix\Main\Type\DateTime('23.03.2025')
        ]);

        PostTable::add([
            PostTable::NAME => 'Пост 2',
            PostTable::DESCRIPTION => 'Описание 2',
            PostTable::DATE_CREATED => new \Bitrix\Main\Type\DateTime('22.03.2025')
        ]);

        PostTable::add([
            PostTable::NAME => 'Пост 3',
            PostTable::DESCRIPTION => 'Описание 3',
            PostTable::DATE_CREATED => new \Bitrix\Main\Type\DateTime('21.03.2025')
        ]);

        return true;
    }

    function UnInstallDB(): bool
    {
        $connection = \Bitrix\Main\Application::getConnection();

        if ($connection->isTableExists(PostTable::getTableName())) {
            $connection->dropTable(PostTable::getTableName());
        }

        return true;
    }

    function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            \Fusion\Card\Event\OnBeforeProlog::class,
            'init'
        );
    }

    function InstallFiles()
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }
}
?>
