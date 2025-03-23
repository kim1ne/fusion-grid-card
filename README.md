# Модуль fusion.card

при установке модуля регистрируется в прологе обработчик \Fusion\Card\Event\OnBeforeProlog, создаётся таблица fusion_posts и в таблицу с постами добавляется 3 записи для тестовых данных

в обработчике пролога регистрируется событие onEntityDetailsTabsInitialized

Появляется таб "Посты" в карточке сделки

при клике на таб запрос уходит в компонент на файл local/components/fusion/fusion.posts/lazyload.ajax.php
