<?php

use Bitrix\Main\UI\Filter\DateType;
use Bitrix\Main\UI\Filter\FieldAdapter;
use Bitrix\Main\UI\Filter\Options;
use Bitrix\Main\UI\PageNavigation;
use Fusion\Card\ORM\PostTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class FusionPostComponent extends \CBitrixComponent
{
    private ?array $filter = null;

    const LIMIT_ELEMENTS_PAGE = 2;

    public function onPrepareComponentParams($arParams): void
    {
        $this->arResult['CURRENT_PAGE'] = $arParams['CURRENT_PAGE'] ?? 1;
        $this->arResult['ORDER'] = $arParams['ORDER'] ?? [];
    }

    private function getSort(): array
    {
        return $this->arResult['ORDER'];
    }

    public function executeComponent(): void
    {
        try {
            $this->arResult['ROWS'] = $this->getPosts();
            $this->arResult['NAV'] = $this->getNavObject();
            $this->arResult['COLUMNS'] = $this->getGridColumns();
            $this->arResult['FILTER_COLUMNS'] = $this->getFilterColumns();
            $this->arResult['GRID_ID'] = $this->getGridId();
            $this->arResult['FILTER_ID'] = $this->getGridFilterId();
            $this->includeComponentTemplate();

            $this->filter = null;

        } catch (\Throwable $exception) {
            echo '<pre>'; print_r($exception); echo '</pre>'; die;
        }
    }

    private function getGridId(): string
    {
        return 'fusion_posts_grid_id';
    }

    private function getGridFilterId(): string
    {
        return 'filter_' . $this->getGridId();
    }

    private function getNavObject(): PageNavigation
    {
        $nav = new PageNavigation($this->getGridId());

        $count = PostTable::getList([
            'select' => ['ID'],
            'filter' => $this->getFilter(),
        ])->getSelectedRowsCount();

        $nav->setRecordCount($count);
        $nav->setPageSize(static::LIMIT_ELEMENTS_PAGE);
        $nav->setCurrentPage($this->getCurrentPage());

        return $nav;
    }

    private function getCurrentPage(): int
    {
        return $this->arResult['CURRENT_PAGE'] ?? 1;
    }

    private function getPosts(): array
    {
        $currentPage = $this->getCurrentPage();
        $offset = ($currentPage - 1) * self::LIMIT_ELEMENTS_PAGE;

        $res = PostTable::getList([
            'limit' => self::LIMIT_ELEMENTS_PAGE,
            'offset' => $offset,
            'order' => $this->getSort(),
            'filter' => $this->getFilter()
        ]);

        $posts = [];

        while ($row = $res->fetch()) {
            $posts[] = [
                'id' => 'string_' . $row['ID'],
                'data' => [
                    'name' => $row['NAME'],
                    'description' => $row['DESCRIPTION'],
                    'date' => $row['DATE_CREATED'],
                ]
            ];
        }

        return $posts;
    }

    private function getGridColumns(): array
    {
        return [
            [
                'id' => 'name',
                'name' => 'Название',
                'default' => true,
                'sort' => PostTable::NAME
            ],
            [
                'id' => 'description',
                'name' => 'Описание',
                'default' => true,
                'sort' => PostTable::DESCRIPTION
            ],
            [
                'id' => 'date',
                'name' => 'Дата создания',
                'default' => true,
                'sort' => PostTable::DATE_CREATED
            ]
        ];
    }

    private function getExcludeDateTypes(): array
    {
        return [
            DateType::YESTERDAY,
            DateType::CURRENT_DAY,
            DateType::TOMORROW,
            DateType::CURRENT_WEEK,
            DateType::CURRENT_MONTH,
            DateType::CURRENT_QUARTER,
            DateType::LAST_7_DAYS,
            DateType::LAST_30_DAYS,
            DateType::LAST_60_DAYS,
            DateType::LAST_90_DAYS,
            DateType::PREV_DAYS,
            DateType::NEXT_DAYS,
            DateType::MONTH,
            DateType::QUARTER,
            DateType::YEAR,
            DateType::EXACT,
            DateType::LAST_WEEK,
            DateType::LAST_MONTH,
//    DateType::RANGE,
            DateType::NEXT_WEEK,
            DateType::NEXT_MONTH,
        ];
    }

    private function getFilterColumns(): array
    {
        return [
            [
                'id' => PostTable::DATE_CREATED,
                'name' => 'Дата Создания',
                'type' => FieldAdapter::DATE,
                'exclude' => $this->getExcludeDateTypes(),
            ],
            [
                'id' => PostTable::NAME,
                'name' => 'Название',
                'type' => FieldAdapter::STRING,
            ],
            [
                'id' => PostTable::DESCRIPTION,
                'name' => 'Описание',
                'type' => FieldAdapter::STRING,
            ],
        ];
    }

    private function getFilter(): array
    {
        if ($this->filter !== null) {
            return $this->filter;
        }

        $columns = [];

        foreach ($this->getFilterColumns() as $column) {
            $columns[] = $column['id'];
        }

        $filterOptions = new Options($this->getGridFilterId());

        $filterDb = $filterOptions->getFilter();

        $filter = [];

        foreach ($filterDb as $k => $v) {
            if (empty($v)) {
                continue;
            }

            $replace = $this->isDate($k);

            if (is_string($replace)) {
                $k = $this->prepareDate($replace, $k);
            } elseif (!in_array($k, $columns)) {
                continue;
            }


            $filter[$k] = $v;
        }

        $this->filter = $filter;

        return $filter;
    }

    private function isDate(string $field): string|false
    {
        if (str_contains($field, $replace = '_from') || str_contains($field, $replace = '_to')) {
            return $replace;
        } else {
            return false;
        }
    }

    private function prepareDate(string $replace, string $field): string
    {
        if ($replace === '_from') {
            $prefix = '>';
        } else {
            $prefix = '<';
        }

        $prepare = str_replace($replace, '', $field);

        return $prefix . $prepare;
    }
}
