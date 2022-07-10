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

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/core.php';

$get_latest_version = file_get_contents('https://dorew-site.github.io/dorew-site/version.txt');
$latest_version = $get_latest_version ? $get_latest_version : $current_version;

if (strpos($latest_version, '-') !== false) {
    $arr_latest_version = explode('-', $latest_version);
    $latest_version = $arr_latest_version[0];
    $latest_version_note = $arr_latest_version[1];
}

if (strpos($current_version, '-') !== false) {
    $arr_current_version = explode('-', $current_version);
    $current_version = $arr_current_version[0];
    $current_version_note = $arr_current_version[1];
}

function update_note($array_ver, $array_note)
{
    $versionC = $array_ver['current_version'];
    if (!empty($array_note['current_version_note'])) {
        $versionC = $array_ver['current_version'] . '-' . $array_note['current_version_note'];
    }
    $versionL = $array_ver['latest_version'];
    if (!empty($array_note['latest_version_note'])) {
        $versionL = $array_ver['latest_version'] . '-' . $array_note['latest_version_note'];
    }
    $icon_update = '<i class="fa fa-download" aria-hidden="true"></i>';
    $notice_update = '- Có phiên bản mới (<b>' . $versionL . '</b>). <a href="/cms/update.php">' . $icon_update . ' Cập nhật bản vá</a>.<br/>- <b>Phiên bản hiện tại:</b> ' . $versionC;
    return $notice_update;
}

function stage($version_note)
{
    $arr_stage = explode('RC', $version_note);
    return $arr_stage[1];
}

$update_note_content = update_note(
    [
        'current_version' => $current_version,
        'latest_version' => $latest_version,
    ],
    [
        'current_version_note' => $current_version_note,
        'latest_version_note' => $latest_version_note,
    ]
);
$update_note_content = '<div class="phdr"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <b>Tin tức</b></div>
<div class="menu">' . $update_note_content . '</div>
<div class="menu">- Nếu bạn cảm thấy phiền khi thấy các thông báo này, bạn có thể tắt chúng trong mục <b>Cài đặt</b>.</div>';

if (empty($act_update)) {
    if (version_compare($latest_version, $current_version, '>=')) {
        if (version_compare($latest_version, $current_version, '>')) {
            echo $update_note_content;
        } else {
            $stageC = stage($current_version_note);
            $stageL = stage($latest_version_note);
            if ($stageC < $stageL) {
                echo $update_note_content;
            }
        }
    }
}
