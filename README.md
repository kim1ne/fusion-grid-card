# Модуль fusion.card

при установке модуля регистрируется в прологе обработчик \Fusion\Card\Event\OnBeforeProlog

он внутри регистрирует событие onEntityDetailsTabsInitialized

Появляется таб "Посты" в карточке сделки

при клике на таб запрос уходит в компонент на файл local/components/fusion/fusion.posts/lazyload.ajax.php
