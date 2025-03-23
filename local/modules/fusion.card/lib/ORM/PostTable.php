<?php

namespace Fusion\Card\ORM;

use Bitrix\Main;
use Bitrix\Main\ORM;

class PostTable extends ORM\Data\DataManager
{
    const NAME = 'NAME';
    const DESCRIPTION = 'DESCRIPTION';
    const DATE_CREATED = 'DATE_CREATED';

    public static function getMap(): array
    {
        return [
            'ID' => new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            'NAME' => new ORM\Fields\StringField('NAME', [
                'required' => true,
            ]),
            'DESCRIPTION' => new ORM\Fields\StringField('DESCRIPTION', [
                'required' => true,
            ]),
            'DATE_CREATED' => new ORM\Fields\DatetimeField('DATE_CREATE', [
                'default_value' => function()
                {
                    return new Main\Type\DateTime();
                }
            ]),
        ];
    }

    public static function getTableName(): string
    {
        return 'fusion_posts';
    }
}
