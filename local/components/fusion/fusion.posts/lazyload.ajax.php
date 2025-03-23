<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$action = $request->get('grid_action');

$currentPage = 1;

if (!empty($page = $request->get('fusion_posts_grid_id'))) {
    $currentPage = (int) str_replace('page-', '', $page);
}

$order = [];
if ($action === 'sort') {
    $order[$request->get('by')] = $request->get('order');
}

global $APPLICATION;
$APPLICATION->ShowAjaxHead();

$APPLICATION->IncludeComponent(
    'fusion:fusion.posts',
    '',
    [
        'CURRENT_PAGE' => $currentPage,
        'ORDER' => $order,
    ]
);

\CMain::FinalActions();
