<?php

namespace Fusion\Card\Event\Tab;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class OnEntityDetailsTabsInitialized
{
    public static function init(Event $event)
    {
        $entityTypeId = $event->getParameter('entityTypeID');

        $tabs = $event->getParameter('tabs');

        if (\CCrmOwnerType::Deal === (int) $entityTypeId) {
            $tabs[] = self::getPostTab();
        }

        $event->setParameter('tabs', $tabs);

        return new EventResult(EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);
    }

    private static function getPostTab(): array
    {
        return [
            'id' => 'fusion_card',
            'name' => 'Посты',
            'loader' => [
                'serviceUrl' => '/local/components/fusion/fusion.posts/lazyload.ajax.php',
                'componentData' => [
                    'template' => ''
                ]
            ]
        ];
    }
}
