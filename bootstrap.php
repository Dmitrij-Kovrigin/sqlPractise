<?php
session_start();
function getSetting($option)
{
    $settings = require __DIR__ . '/app/settings.php';
    return $settings[$option] ?? null;
}

function S($name, $value) 
{
    if (isset($_GET[$name]) && $_GET[$name] == $value) {
        return ' selected';
    }
    return '';
}

function pager($page) 
{
    $get = $_GET;
    $get['page'] = $page;
    return '?'.http_build_query($get);
}

require __DIR__ . '/vendor/autoload.php';

define('URL', getSetting('url'));
define('INSTALL_DIR', getSetting('dir'));
define('DIR', __DIR__ .'/');