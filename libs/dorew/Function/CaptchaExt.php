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

class CaptchaExt extends \Twig\Extension\AbstractExtension
{

    public function __construct()
    {
        $this->site_key = '6LerhaYbAAAAAG5MsOgY6w7cjbvJjU61hGfPqSRU';
        $this->secret_key = '6LerhaYbAAAAAF_qswE64H5DdqoukhAhKnxd6nrQ';
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('captcha_base64', [$this, 'captcha_base64']),
            new \Twig\TwigFunction('get_captcha', [$this, 'get_captcha']),
            new \Twig\TwigFunction('check_captcha', [$this, 'check_captcha']),
        ];
    }

    /*
    -----------------------------------------------------------------
    Base64 Captcha
    -----------------------------------------------------------------
    */

    function captcha_base64($string = null)
    {
        /**
         * Captcha dạng ảnh
         * Xuất ra định dạng: PNG - base64
         */
        if (empty($string)) $string = 'dorew';
        require_once 'HunterObfuscator.php';
        $string = substr($string, 0, 6);

        $arr = str_split($string);
        $captcha = '******';
        $arr_captcha = [];
        for ($i = 0; $i < strlen($string); $i++) {
            $arr_captcha[$i] = substr_replace($captcha, $arr[$i], $i, 1);
        }

        $arr_base64 = [];
        foreach ($arr_captcha as $key => $value) {
            $img = imagecreate(90, 15);
            $background = imagecolorallocate($img, 0, 0, 0);
            $text_color = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 20, 15, 0, $value, $text_color);
            ob_start();
            imagepng($img);
            $image_data = ob_get_contents();
            ob_end_clean();
            $base64 = 'data:image/png;base64,' . base64_encode($image_data);
            //echo '<img src="' . $base64 . '" alt="captcha" /><br/>';
            $arr_base64[$key] = $base64;
            // destroy image
            imagedestroy($img);
        }
        shuffle($arr_base64);
        // tạo ảnh cho phần tử cuối của $arr_base64: ******
        $img = imagecreate(90, 15);
        $background = imagecolorallocate($img, 0, 0, 0);
        $text_color = imagecolorallocate($img, 255, 255, 255);
        imagestring($img, 20, 15, 0, $captcha, $text_color);
        ob_start();
        imagepng($img);
        $image_data = ob_get_contents();
        ob_end_clean();
        $base64 = 'data:image/png;base64,' . base64_encode($image_data);
        $arr_base64[6] = $base64;
        imagedestroy($img);
        //echo '<pre>' . print_r($arr_base64) . '</pre>';

        $obfuscate = false;
        $obfuscate .= "
        var arr_base64 = " . json_encode($arr_base64) . ";
        var i = 0;
        setInterval(function () {
        if (i == 7) i = 0;
        document.getElementById('captcha').src = arr_base64[i];
        i++;
        }, 1000);
        ";
        //echo $obfuscate;
        $sObfusationData = new HunterObfuscator($obfuscate);
        return [
            'js' => $sObfusationData->Obfuscate(),
            //'start' => $arr_base64[6]
            'start' => 'https://i.imgur.com/yxtZxmy.png'
        ]; 
    }

    /*
    -----------------------------------------------------------------
    reCaptcha Functions
    -----------------------------------------------------------------
    */

    function get_captcha()
    {
        if (isset($this->site_key)) {
            return '<div class="g-recaptcha" data-sitekey="' . $this->site_key . '"></div><script src="https://www.google.com/recaptcha/api.js"></script>';
        } else {
            return [
                'error' => true,
                'msg' => 'Khoá captcha không tồn tại'
            ];
        }
    }

    function check_captcha()
    {
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $secret = $this->secret_key;
            $captcha = $_POST['g-recaptcha-response'];
            $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
            $arr = json_decode($rsp, true);
            if($arr['success']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
