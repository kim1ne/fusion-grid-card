<?php

namespace Fusion\Card\Event;

use Bitrix\Main\EventManager;
use Fusion\Card\Event\Tab\OnEntityDetailsTabsInitialized;

class OnBeforeProlog
{
    public static function init(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->addEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            [OnEntityDetailsTabsInitialized::class,  'init']
        );
    }
}
