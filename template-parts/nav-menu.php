<?php
$name = get_query_var( 'vw_nav_menu' ); ?>

<nav class="header-menu">
    <ul>

    <?php
    foreach ( get_field('header_nav','option') as $item ): ?>    
        <li>
            <a href="#<?= $item['section_id'] ?>"><?= $item['text'] ?></a>
        </li>
    <?php
    endforeach;?>
    </ul>
</nav>