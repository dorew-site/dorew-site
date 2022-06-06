<?php
/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

defined('_DOREW') or die('Access denied');

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
require_once $root . '/cms/layout/func.php';

$uri_segments = explode('/', $request_uri);
if (!$conn && $uri_segments[1] != 'cms' && !in_array($uri_segments[2], ['index.php', '', null])) {
    header('Location: /cms');
    exit();
}

?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Thích Ngao Du">
    <meta property="og:site_name" content="Dorew">
    <meta name="theme-color" content="#22292F">
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">
    <meta name="google" content="notranslate">
    <meta name="format-detection" content="telephone=no">
    <link rel="dns-prefetch" href="https://i.imgur.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://raw.githack.com">
    <link rel="dns-prefetch" href="https://images.weserv.nl">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="shortcut icon" href="https://i.imgur.com/2pfDfoN.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="/cms/layout/assets/default.css" rel="stylesheet">
    <link href="/cms/layout/assets/main.css?t=<?php echo time(); ?>" rel="stylesheet">
    <title>
        <?php echo $title ? $title : 'Dorew'; ?>
    </title>
</head>

<body data-instant-allow-query-string>
    <div style="text-align: center;background:url(https://moleys.github.io/assets/patterns/body-bg7.png);background-color: #536162; color:#fff;padding:10px;">
        <a href="/cms"><img src="https://i.imgur.com/2CuN7pf.png" height="60" width="60"></a>
        <br>Dorew - Thích Ngao Du
    </div>
    <?php
    if (is_login() && $conn) {
        echo '<div class="phdr" style="text-align: center;" id="head"><a href="/cms"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a> • <a href="'. $url_phpmyadmin .'"><i class="fa fa-database fa-lg" aria-hidden="true"></i></a>  • <a href="/cms?act=logout"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i></a></div>';
    }