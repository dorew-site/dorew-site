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

if (!$db || is_login()) {
    header('Location: /cms');
    exit;
}
?>
<!DOCTYPE html
    PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html>

<head>
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <style>@import "https://fonts.googleapis.com/css?family=Numans";html,body{background-image:url(https://i.imgur.com/MqrnVAQ.jpg);background-size:cover;background-repeat:no-repeat;height:100%;font-family:'Numans',sans-serif}.container{height:100%;align-content:center}.card{height:370px;margin-top:auto;margin-bottom:auto;width:400px;background-color:rgba(0,0,0,0.5)!important}.social_icon span{font-size:60px;margin-left:10px;color:#FFC312}.social_icon span:hover{color:#fff;cursor:pointer}.card-header h3{color:#fff}.social_icon{position:absolute;right:20px;top:-45px}.input-group-prepend span{width:50px;background-color:#FFC312;color:#000;border:0!important}input:focus{outline:0 0 0 0!important;box-shadow:0 0 0 0!important}.remember{color:#fff}.remember input{width:20px;height:20px;margin-left:15px;margin-right:5px}.login_btn{color:#000;background-color:#FFC312;width:100px}.login_btn:hover{color:#000;background-color:#fff}.links{color:#fff}.links a{margin-left:4px}</style>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="https://i.imgur.com/2pfDfoN.png" />
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>CMS</h3>
                </div>
                <div class="card-body">
                    <?php
                    $user = strtolower($_POST['user']);
                    $pass = $_POST['pass'];
                    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                        if ($user == $account_admin && $pass == $password_admin) {
                            $div = '#fff';
                            $result = 'Đăng nhập thành công';
                            setcookie($account_admin, $new_password, time() + 31536000);
                            header('Location: /cms');
                            exit();
                        } else {
                            $div = 'red';
                            $result = 'Thông tin đăng nhập sai cmnr!';
                        }
                    }
                    if (isset($result)) {
                        echo '<p style="text-align:center;color:' . $div . '">' . $result . '</p>';
                    }
                    ?>
                    <form method="post">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Tên tài khoản" name="user" />

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Mật khẩu" name="pass" />
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Đăng nhập" class="btn float-right login_btn">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        <a href="/category/21-dore-site">Powered By DorewSite</a>
                    </div>
                    <div class="d-flex justify-content-center" style="color:#fff">
                        Version <?php echo $current_version ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>