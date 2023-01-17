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

/*---------------------------------------------------*/
session_start();

// Thiết lập số lượng yêu cầu cho phép trong một khoảng thời gian
$max_requests = 250;
$time_window = 30; // tính bằng đơn vị giây

// Lấy địa chỉ IP của người dùng
$user_ip = get_ip();

// Kiểm tra xem IP có tồn tại trong session hay chưa
if (!isset($_SESSION[$user_ip])) {
    // Nếu chưa tồn tại, gán giá trị ban đầu
    $_SESSION[$user_ip] = [
        'start_time' => time(),
        'request_count' => 1
    ];
} else {
    // Nếu tồn tại, cập nhật số lượng yêu cầu và thời gian
    $_SESSION[$user_ip]['request_count']++;
    $_SESSION[$user_ip]['start_time'] = time();
   
    // Kiểm tra xem số lượng yêu cầu đã vượt quá giới hạn chưa
    if ($_SESSION[$user_ip]['request_count'] > $max_requests) {
        // Kiểm tra xem khoảng thời gian đã hết chưa
        if (time() - $_SESSION[$user_ip]['start_time'] > $time_window) {
            // Nếu hết, reset session
            unset($_SESSION[$user_ip]);
            $_SESSION = [];
        } else {
            // Nếu chưa hết, chặn yêu cầu và hiển thị thông báo
            header("HTTP/1.1 503 Service Unavailable");
            echo "You have exceeded the maximum number of requests per time window.";
            exit;
        }
    }
}

function get_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
}

//exit(json_encode($_SESSION));
/*---------------------------------------------------*/

header('Content-Type: ' . get_format($check_ext));

if (in_array($check_ext, $image_ext)) {
	readfile($dir_tpl . '/' . $pathTWIG);
	exit;
}

$loader = new \Twig\Loader\FilesystemLoader(VIEWPATH);
$twig = new \Twig\Environment($loader);
spl_autoload_register(function ($className) {
        # Function
	$filepath_function = BASEPATH . "libs/dorew/Function/" . $className . ".php";
	if (file_exists($filepath_function)) require_once $filepath_function;

        # Filter
	$filepath_filter = BASEPATH . "libs/dorew/Filter/" . $className . ".php";
	if (file_exists($filepath_filter)) require_once $filepath_filter;
});

# Function
$GET_FormURI = new FormURI();
$GET_OneByOne = new OneByOne();
$GET_CookieSession = new CookieSession();
$METHOD_QuerySQL = new QuerySQL();

$twig->addExtension(new TextMarkup());
$twig->addExtension(new CaptchaExt());
$twig->addExtension(new ImageHeader());
$twig->addExtension($GET_FormURI);
$twig->addExtension($GET_CookieSession);
$twig->addExtension($METHOD_QuerySQL);
$twig->addExtension($GET_OneByOne);

# Filter
$twig->addExtension(new MatchRegex());
$twig->addExtension(new ArraySort());
$twig->addExtension(new EncryptString());

echo $twig->render($pathTWIG, [
    'login' => $GLOBALS['GET_CookieSession']->is_login(),
    'current_url' => $GLOBALS['GET_FormURI']->current_url(),
    'layout' => $GLOBALS['GET_OneByOne']->display_layout(),
    'dir' => ['css' => '/', 'js' => '/', 'img' => '/'],
    'api' => [
        'is_login' => $GLOBALS['GET_CookieSession']->is_login(),
        'uri' => [
            'segments' => $GLOBALS['GET_FormURI']->get_uri_segments(),
            'current' => $GLOBALS['GET_FormURI']->current_url()
        ],
        'browser' => [
            'ip' => $GLOBALS['GET_OneByOne']->ip(),
            'user_agent' => $GLOBALS['GET_OneByOne']->user_agent()
        ]
    ],
    'SERVER_HTTPS' => $_SERVER['HTTPS'],
    'SERVER_HTTP_HOST' => $_SERVER['HTTP_HOST'],
    'SERVER_HTTP_CONNECTION' => $_SERVER['HTTP_CONNECTION'],
    'SERVER_HTTP_CACHE_CONTROL' => $_SERVER['HTTP_CACHE_CONTROL'],
    'SERVER_HTTP_SEC_CH_UA' => $_SERVER['HTTP_SEC_CH_UA'],
    'SERVER_HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
    'SERVER_HTTP_ACCEPT' => $_SERVER['HTTP_ACCEPT'],
    'SERVER_HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
    'SERVER_REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
    'SERVER_SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'],
    'SERVER_REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
    'SERVER_QUERY_STRING' => $_SERVER['QUERY_STRING'],
    'SERVER_REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'SERVER_REQUEST_TIME_FLOAT' => $_SERVER['REQUEST_TIME_FLOAT'],
    'SERVER_REQUEST_TIME' => $_SERVER['REQUEST_TIME'],
    'GET' => $_GET,
    'POST' => $_POST,
    'SESSION' => $_SESSION,
    'COOKIE' => $_COOKIE,
]);