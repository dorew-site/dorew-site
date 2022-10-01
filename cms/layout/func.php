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

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';

function is_login()
{
    global $account_admin, $password_admin, $new_password;
    if ($_COOKIE[$account_admin] == $new_password) {
        return true;
    } else {
        return false;
    }
}

function display_layout()
{
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$arrUA = strtolower($ua);
	if (preg_match('/windows|ipod|ipad|iphone|android|webos|blackberry|midp/', $arrUA) && preg_match('/mobile/', $arrUA)) {
		return 'mobile';
	} elseif (preg_match('/mobile/', $arrUA)) return 'mobile';
	else return 'desktop';
}

function paging($url, $p, $max)
{
    $p = (int)$p;
    $max = (int)$max;
    $b = '';
    if ($max > 1) {
        $a = ' <a class="pagenav" href="' . $url;
        if ($p > $max) {
            $p = $max;
            $b .= 'a';
        }
        if ($p > 1) {
            $b .= $a . ($p - 1) . '">&laquo;</a> ';
        }
        if ($p > 3) {
            $b .= $a . '1">1</a>';
        }
        if ($p > 4) {
            $b .= ' <span class="disabled">...</span> ';
        }
        if ($p > 2) {
            $b .= $a . ($p - 2) . '">' . ($p - 2) . '</a> ';
        }
        if ($p > 1) {
            $b .= $a . ($p - 1) . '">' . ($p - 1) . '</a> ';
        }
        $b .= ' <span class="currentpage"><b>' . $p . '</b></span> ';
        if ($p < ($max - 1)) {
            $b .= $a . ($p + 1) . '">' . ($p + 1) . '</a> ';
        }
        if ($p < ($max - 2)) {
            $b .= $a . ($p + 2) . '">' . ($p + 2) . '</a> ';
        }
        if ($p < ($max - 3)) {
            $b .= ' <span class="disabled">...</span> ';
        }
        if ($p < $max) {
            $b .= $a . $max . '">' . $max . '</a> ';
        }
        if ($p < $max) {
            $b .= $a . ($p + 1) . '">&raquo;</a> ';
        }
        return $b;
    }
}

function fulltime_ago($time_in_thePast)
{
    $result = date('H:i, d/m/Y', $time_in_thePast);
    return $result;
}

function time_ago($time_in_thePast)
{
    if (!$time_in_thePast) {
        $time_in_thePast = time();
    }
    $countdown = date('U') - $time_in_thePast;
    $time_day = date('z') - date('z', $time_in_thePast);
    if ($time_day < 0) {
        $time_day = date('z', $time_in_thePast) - date('z');
    }
    if ($countdown < 60 && $time_day == 0) {
        if ($countdown == 0) {
            $result = 'vừa xong';
        } else {
            $result = $countdown . ' giây trước';
        }
    } elseif ($countdown >= 60 && $time_day <= 1) {
        if ($time_day == 0) {
            if ($countdown > 3600) {
                $result = 'Hôm nay, ' . date('H:i', $time_in_thePast);
            } else {
                $result = round(trim($countdown / 60), '0') . ' phút trước';
            }
        } else {
            $result = 'Hôm qua, ' . date('H:i', $time_in_thePast);
        }
    } else {
        if ($countdown > 31622400) {
            $result = date('H:i, d/m/Y', $time_in_thePast);
        } elseif ($countdown >= 2592000) {
            $result = round(trim($countdown / 2592000), '0') . ' tháng trước';
        } elseif ($countdown >= 604800) {
            $result = round(trim($countdown / 604800), '0') . ' tuần trước';
        } else {
            $day = round(trim($countdown / 86400), '0');
            if ($day == 7) {
                $result = '1 tuần trước';
            } else {
                $result = $day . ' ngày trước';
            }
        }
    }
    return $result;
}

function rschar($string)
{
    $string = str_replace(" ", "-", str_replace("..", ".", $string));
    $string = str_replace("@", "a", str_replace("!", "i", $string));
    $string = str_replace("+", "cong", str_replace("&", "va", $string));
    $string = str_replace("(", "", str_replace(")", "", $string));
    $string = str_replace("^", "", str_replace("#", "", $string));
    $string = str_replace("*", "", str_replace("|", "", $string));
    $string = str_replace("=", "", str_replace("~", "", $string));
    $string = str_replace("/", "-", str_replace("\\", "-", $string));
    $string = str_replace(":", "", str_replace(",", "", $string));
    $string = str_replace("--", "-", $string);
    $string = str_replace("--", "-", $string);
    $string = str_replace("%", "", $string);
    $string = str_replace("$", "", $string);
    $string = str_replace(";", "", $string);
    $string = str_replace("'", "", $string);
    $string = str_replace('"', "", $string);
    $string = str_replace("?", "", $string);
    $string = str_replace("[", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("`", "", $string);
    $string = str_replace("•", "", $string);
    $string = str_replace("√", "", $string);
    $string = str_replace("π", "", $string);
    $string = str_replace("÷", "", $string);
    $string = str_replace("×", "", $string);
    $string = str_replace("¶", "", $string);
    $string = str_replace("∆", "", $string);
    $string = str_replace("£", "", $string);
    $string = str_replace("¢", "", $string);
    $string = str_replace("¥", "", $string);
    $string = str_replace(",", "", $string);
    $string = str_replace("°", "", $string);
    $string = str_replace("=", "", $string);
    $string = str_replace("{", "", $string);
    $string = str_replace("}", "", $string);
    $string = str_replace("…", "", $string);
    $string = str_replace("©", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("]", "", $string);
    $string = str_replace("<", "", $string);
    $string = str_replace(">", "", $string);
    $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
    $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
    $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
    $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
    $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
    $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
    $string = preg_replace("/(đ)/", 'd', $string);
    $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
    $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
    $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
    $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
    $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
    $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
    $string = preg_replace("/(Đ)/", 'D', $string);
    $string = mb_strtolower($string, 'utf8');
    return $string;
}

function rwurl($string)
{
    $patterns = array("/ắ/", "/ằ/", "/ẳ/", "/ẵ/", "/ặ/", "/ấ/", "/ầ/", "/ẩ/", "/ẫ/", "/ậ/", "/ố/", "/ồ/", "/ổ/", "/ỗ/", "/ộ/", "/ớ/", "/ờ/", "/ở/", "/ỡ/", "/ợ/", "/ứ/", "/ừ/", "/ử/", "/ữ/", "/ự/", "/á/", "/à/", "/ả/", "/ã/", "/ạ/", "/ó/", "/ò/", "/ỏ/", "/õ/", "/ọ/", "/é/", "/è/", "/ẻ/", "/ẽ/", "/ẹ/", "/ế/", "/ề/", "/ể/", "/ễ/", "/ệ/", "/í/", "/ì/", "/ỉ/", "/ĩ/", "/ị/", "/ý/", "/ỳ/", "/ỷ/", "/ỹ/", "/ỵ/", "/ú/", "/ù/", "/ủ/", "/ũ/", "/ụ/", "/Ắ/", "/Ằ/", "/Ẳ/", "/Ẵ/", "/Ặ/", "/Ấ/", "/Ầ/", "/Ẩ/", "/Ẫ/", "/Ậ/", "/Ố/", "/Ồ/", "/Ổ/", "/Ỗ/", "/Ộ/", "/Ớ/", "/Ờ/", "/Ở/", "/Ỡ/", "/Ợ/", "/Ứ/", "/Ừ/", "/Ử/", "/Ữ/", "/Ự/", "/Á/", "/À/", "/Ả/", "/Ã/", "/Ạ/", "/Ó/", "/Ò/", "/Ỏ/", "/Õ/", "/Ọ/", "/É/", "/È/", "/Ẻ/", "/Ẽ/", "/Ẹ/", "/Ế/", "/Ề/", "/Ể/", "/Ễ/", "/Ệ/", "/Í/", "/Ì/", "/Ỉ/", "/Ĩ/", "/Ị/", "/Ý/", "/Ỳ/", "/Ỷ/", "/Ỹ/", "/Ỵ/", "/Ú/", "/Ù/", "/Ủ/", "/Ũ/", "/Ụ/");
    $replacements = array("ắ", "ằ", "ẳ", "ẵ", "ặ", "ấ", "ầ", "ẩ", "ẫ", "ậ", "ố", "ồ", "ổ", "ỗ", "ộ", "ớ", "ờ", "ở", "ỡ", "ợ", "ứ", "ừ", "ử", "ữ", "ự", "á", "à", "ả", "ã", "ạ", "ó", "ò", "ỏ", "õ", "ọ", "é", "è", "ẻ", "ẽ", "ẹ", "ế", "ề", "ể", "ễ", "ệ", "í", "ì", "ỉ", "ĩ", "ị", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "ú", "ù", "ủ", "ũ", "ụ", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Á", "À", "Ả", "Ã", "Ạ", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ế", "Ề", "Ể", "Ễ", "Ệ", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "Ú", "Ù", "Ủ", "Ũ", "Ụ");
    $string = preg_replace($patterns, $replacements, $string);
    return rschar($string);
}

function remove_dir($dir)
{
    if ($handle = opendir($dir)) {
        while (false !== ($item = readdir($handle))) {
            if ($item != '.' && $item != '..') {
                if (is_dir($dir . '/' . $item)) {
                    remove_dir($dir . '/' . $item);
                } else {
                    unlink($dir . '/' . $item);
                }
            }
        }
        closedir($handle);
        rmdir($dir);
    }
}

function move_dir($old_dir, $new_dir)
{
    $handler = scandir($old_dir);
    foreach ($handler as $file) {
        if ($file != "." && $file != "..") {
            if (is_dir($old_dir . "/" . $file)) {
                if (!is_dir($new_dir . "/" . $file)) {
                    mkdir($new_dir . "/" . $file);
                }
                move_dir($old_dir . "/" . $file, $new_dir . "/" . $file);
            } else {
                copy($old_dir . "/" . $file, $new_dir . "/" . $file);
                unlink($old_dir . "/" . $file);
            }
        }
    }
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
            if ($file != "." && $file != "..") rrmdir("$dir/$file");
        rmdir($dir);
    } else if (file_exists($dir)) unlink($dir);
}

function rcopy($src, $dst)
{
    if (file_exists($dst))
        rrmdir($dst);
    if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rcopy("$src/$file", "$dst/$file");
    } else if (file_exists($src))
        copy($src, $dst);
}

function file_size($byte)
{
    if ($byte >= '1073741824') {
        $result = round(trim($byte / 1073741824), '2') . ' GB';
    } else if ($byte >= '1048576') {
        $result = round(trim($byte / 1048576), '2') . ' MB';
    } else if ($byte >= '1024') {
        $result = round(trim($byte / 1024), '2') . ' KB';
    } else {
        $result = round($byte, '2') . ' Bytes';
    }
    return $result;
}

