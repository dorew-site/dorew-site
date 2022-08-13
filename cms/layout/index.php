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

ini_set('display_errors', 0);
require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    include $root . '/cms/layout/header.php';
?>
    <div class="phdr"><a href="/cms" title="Quản lý tập tin" style="font-weight:700"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a></div>
    <?php
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $check_db = htmlspecialchars($_POST['check_database']);
        if (in_array($check_db, $list_type_db)) {
            $save_type_db = str_replace($list_type_db, $get_type_db, $check_db);
            $old_config = file_get_contents($root . '/cms/config.php');
            $new_config = str_replace('$type_db = \\' . $type_db . ';', '$type_db = \'' . $save_type_db . '\';', $old_config);
            file_put_contents($root . '/cms/config.php', $new_config); //save new config
    ?>
            <div class="gmenu">Thay đổi thành công!</div>
    <?php
            header('Refresh: 3; url=/cms/layout');
        } else echo '
        <div class="rmenu">Thay đổi không thành công. Chỉ hỗ chợ MySQL và SQLite!</div>';
    }
    ?>
    <div class="menu" style="text-align:center">
        <p><b>Bạn đang thực hiện chỉnh sửa các tập tin cho giao diện sử dụng <span style="color:red"><?php echo $current_type_db ?></span>.</b><br /> Bạn có muốn thay đổi không?</p>
        <p>
        <form action="" method="post">
            <select name="check_layout">
                <?php
                foreach ($list_type_db as $key => $value) {
                    if ($get_type_db[$key] == $type_db) {
                        echo '<option value="' . $get_type_db[$key] . '" selected>' . $value . '</option>';
                    } else {
                        echo '<option value="' . $get_type_db[$key] . '">' . $value . '</option>';
                    }
                }
                ?>
            </select>
            <br /><button type="submit" class="button">Thay đổi</button>
        </form>
        </p>
    </div>
<?php
    include $root . '/cms/layout/footer.php';
} else {
    header('Location: /cms');
    exit();
}
