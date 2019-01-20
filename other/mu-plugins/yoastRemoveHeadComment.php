<?php
add_action('wp_head',function() { ob_start(function($html) {
    return preg_replace('/^<!--.*?[Yy]oast.*?-->$/mi', '', $html);
    }); },~PHP_INT_MAX);