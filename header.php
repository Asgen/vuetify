<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>

</head>

<body>

    <div id="vue-wordpress-app">
        <header class="main-header">

            <div class="main-header__logo-wrapper">
                <a href="/"><img src="<?= get_field('header_logo','option'); ?>" alt="Algorythm of Success logo">
                </a>
            </div>

            <?php
            set_query_var( 'vw_nav_menu', 'main' );
            get_template_part('template-parts/nav-menu');?>

            <div class="main-header__contacts-wrapper">
                <div class="main-header__contacts">
                    <a href="tel:+<?= get_field('header_number','option'); ?>">
                        <?= get_field('header_number','option'); ?>
                    </a>
                    <a href="mailto: <?= get_field('header_email','option'); ?>">
                        <?= get_field('header_email','option'); ?>
                    </a>
                </div>

                <button>Заказать звонок</button>
            </div>
        </header>
