<?php

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => $arResult['FILTER_ID'],
        'GRID_ID' => $arResult['GRID_ID'],
        'FILTER' => $arResult['FILTER_COLUMNS'],
        'ENABLE_LABEL' => true
    ]
);

$nav = $arResult['NAV'];

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $arResult['GRID_ID'],
        'COLUMNS' => $arResult['COLUMNS'],
        'ROWS' => $arResult['ROWS'],
        'AJAX_MODE' => 'Y',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_PAGESIZE' => true,
        'CURRENT_PAGE' => $nav->getCurrentPage(),
        'NAV_OBJECT' => $nav,
        'TOTAL_ROWS_COUNT' => $nav->getRecordCount(),
        'ALLOW_SORT' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_ROWS_SORT' => true,
        'SHOW_GRID_SETTINGS_MENU' => false,
        'ALLOW_COLUMNS_RESIZE' => false
    ]
);
