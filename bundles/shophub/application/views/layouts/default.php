<?= View::make('shophub::partials.header', $header_data) ?>
<?= View::make('shophub::partials.menu', $menu_data) ?>
<?= $content ?>
<?php Anbu::render(); ?>
<?= $footer ?>