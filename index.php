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

define('_DOREW', 1);
$system_path = '';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/core.php';

if (defined('STDIN')) {
	chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
	$system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
	// Ensure there's a trailing slash
	$system_path = strtr(
		rtrim($system_path, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR
	) . DIRECTORY_SEPARATOR;
}

// Is the system path correct?
if (!is_dir($system_path)) {
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3); // EXIT_CONFIG
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory
define('BASEPATH', $system_path);
define('VIEWPATH', $dir_tpl);

require_once BASEPATH . 'libs/core.php';
require_once BASEPATH . 'libs/vendor/autoload.php';

$ext_path = explode('.', $pathTWIG);
if (count($ext_path) < 2) {
	$check_ext = 'html';
} else {
	$check_ext = array_pop($ext_path);
}
$check_ext = strtolower($check_ext);

function get_format($ext)
{
	$mime = array(
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'twig' => 'text/html',
		'txt' => 'text/plain',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'rss' => 'application/rss+xml',
		'png' => 'image/png',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'gif' => 'image/gif',
		'ico' => 'image/x-icon',
		'svg' => 'image/svg+xml',
		'bmp' => 'image/bmp',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'webp' => 'image/webp',
		'psd' => 'image/vnd.adobe.photoshop',
	);
	if ($mime[$ext]) {
		return $mime[$ext];
	} else {
		return 'text/html';
	}
}

header('Content-Type: ' . get_format($check_ext));

if (in_array($check_ext, $image_ext)) {
	readfile($dir_tpl . '/' . $pathTWIG);
	exit;
}

$loader = new \Twig\Loader\FilesystemLoader(VIEWPATH);
$twig = new \Twig\Environment($loader);
spl_autoload_register(function ($className) {
	$filepath = BASEPATH . "libs/" . $className . ".php";
	if (file_exists($filepath)) require_once $filepath;
	//echo $className;
});
$twig->addExtension(new TwigFunctions());
$twig->addExtension(new TwigFilter());
echo $twig->render($pathTWIG, [
	'dir' => ['css' => '/', 'js' => '/', 'img' => '/']
]);
