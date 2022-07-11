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

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
$title = 'Cập nhật phiên bản';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    $act_update = 'update';
    require_once $root . '/cms/layout/act/version.php';
    $file_latest_version = 'https://github.com/dorew-site/dorew-site/archive/refs/heads/main.zip';

    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $root_backup = $root . '/backup';
        $backup_tpl = $root_backup . '/template';
        remove_dir($backup_tpl);
        mkdir($backup_tpl);

        /* BACKUP CURRENT DATA */
        move_dir($dir_tpl, $backup_tpl);
        $save_db_info = $db_host . '|' . $db_user . '|' . $db_pass . '|' . $db_name;
        file_put_contents($root_backup . '/core.txt', $save_db_info);

        /* DELETE OLD VERSION */
        $arr_dir = ['cms', 'libs'];
        foreach ($arr_dir as $old_dir) {
            $old_dir_path = $root . '/' . $old_dir;
            if (is_dir($old_dir_path)) {
                remove_dir($old_dir_path);
            }
        }
        $arr_file = ['.htaccess', 'index.php', 'license.txt', 'README.md', 'version.txt'];
        foreach ($arr_file as $old_file) {
            $old_file_path = $root . '/' . $old_file;
            if (is_file($old_file_path)) {
                unlink($old_file_path);
            }
        }

        /* DOWNLOAD LATEST VERSION */
        $file_new_version = $root . '/dorew-site.zip';
        if (file_exists($file_new_version)) {
            unlink($file_new_version);
        }
        if (is_dir($root . '/dorew-site-main')) {
            remove_dir($root . '/dorew-site-main');
        }
        if (!is_file($file_new_version)) {
            // get file from github
            $file_latest_version = file_get_contents($file_latest_version);
            file_put_contents($file_new_version, $file_latest_version);
            // open zip file
            $zip = new ZipArchive;
            $zip->open($file_new_version);
            // extract file to root
            $zip->extractTo($root);
            $zip->close();
            $dir_source = $root . '/dorew-site-main';
            foreach ($arr_dir as $new_dir) {
                $new_dir_path = $dir_source . '/' . $new_dir;
                rcopy($new_dir_path, $root . '/' . $new_dir);
            }
            foreach ($arr_file as $new_file) {
                $new_file_path = $dir_source . '/' . $new_file;
                rcopy($new_file_path, $root . '/' . $new_file);
            }
            // remove dorew-site-main and dorew-site.zip
            remove_dir($dir_source);
            unlink($file_new_version);
            $rmd = ['version.txt', 'README.md'];
            foreach ($rmd as $rm) {
                $rm_path = $root . '/' . $rm;
                if (is_file($rm_path)) {
                    unlink($rm_path);
                }
            }
        }

        /* UPDATE TEMPLATE */
        remove_dir($dir_tpl);
        mkdir($dir_tpl);
        rcopy($backup_tpl, $dir_tpl);

        /* UPDATE DB */
        $db_new_info = file_get_contents($root_backup . '/core.txt');
        $db_new_info = explode('|', $db_new_info);
        $new_db = array(
            'host' => $db_new_info[0],
            'user' => $db_new_info[1],
            'pass' => $db_new_info[2],
            'name' => $db_new_info[3]
        );
        $old_db_info = file_get_contents($root . '/cms/core.php');
        $new_config = str_replace('$db_host = \'' . $db_host . '\';', '$db_host = \'' . $new_db['host'] . '\';', $old_config);
        $new_config = str_replace('$db_user = \'' . $db_user . '\';', '$db_user = \'' . $new_db['user'] . '\';', $new_config);
        $new_config = str_replace('$db_pass = \'' . $db_pass . '\';', '$db_pass = \'' . $new_db['pass'] . '\';', $new_config);
        $new_config = str_replace('$db_name = \'' . $db_name . '\';', '$db_name = \'' . $new_db['name'] . '\';', $new_config);
        file_put_contents($root . '/cms/core.php', $new_config);
        unlink($root_backup . '/core.txt');

        /* GO TO CMS */
        header('Location: /cms');
        exit();
    }

    function update_form($array_ver, $array_note)
    {
        global $act_update;
        $versionC = $array_ver['current_version'];
        if (!empty($array_note['current_version_note'])) {
            $versionC = $array_ver['current_version'] . '-' . $array_note['current_version_note'];
        }
        $versionL = $array_ver['latest_version'];
        if (!empty($array_note['latest_version_note'])) {
            $versionL = $array_ver['latest_version'] . '-' . $array_note['latest_version_note'];
        }
        if ($act_update == 'update') {
            $form = '<div class="gmenu"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Thông tin</b><br/>- Phiên bản hiện tại: ' . $versionC . '<br/>- Mới nhất: ' . $versionL . '</div>
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b>Bạn có muốn cập nhật phiên bản mới nhất không?</b></p>
                    <p><p><button type="submit" class="button">Đồng ý</button></p></p>
                </form>
            </div>
            ';
            return $form;
        }
    }

    $update_content = update_form(
        [
            'current_version' => $current_version,
            'latest_version' => $latest_version,
        ],
        [
            'current_version_note' => $current_version_note,
            'latest_version_note' => $latest_version_note,
        ]
    );

    echo '<div class="phdr"><i class="fa fa-wrench" aria-hidden="true"></i> <b>Cập nhật phiên bản</b></div>';
    if (version_compare($latest_version, $current_version, '>=')) {
        if (version_compare($latest_version, $current_version, '>')) {
            echo $update_content;
        } else {
            $stageC = stage($current_version_note);
            $stageL = stage($latest_version_note);
            if ($stageC < $stageL) {
                echo $update_content;
            } else {
                $notice = 'Bạn đang sử dụng phiên bản mới nhất!';
            }
        }
    }
    if (!empty($notice)) {
        echo '<div class="rmenu"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> ' . $notice . '</div>';
    }
} else {
    header('Location: /cms');
}
include $root . '/cms/layout/footer.php';
