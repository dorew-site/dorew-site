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

class SomeFunctions extends \Twig\Extension\AbstractExtension
{

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('typetext', [$this, 'typetext']),
            new \Twig\TwigFunction('encrypt', [$this, 'encrypt']),
            new \Twig\TwigFunction('debug', [$this, 'debug']),
            new \Twig\TwigFunction('print_r', [$this, 'debug']),

            new \Twig\TwigFunction('ip', [$this, 'ip']),
            new \Twig\TwigFunction('user_agent', [$this, 'user_agent']),

            new \Twig\TwigFunction('json_decode', [$this, 'json_decode_']),
            new \Twig\TwigFunction('html_decode', [$this, 'html_decode']),
            new \Twig\TwigFunction('shuffle_array', [$this, 'shuffle_array']),
            new \Twig\TwigFunction('implode', [$this, 'implode_array']),
            new \Twig\TwigFunction('strip_tags', [$this, 'strip_tags_']),

            new \Twig\TwigFunction('rounding', [$this, 'rounding']),

            new \Twig\TwigFunction('file_header', [$this, 'file_header']),
            new \Twig\TwigFunction('img_farm', [$this, 'img_farm']),
            new \Twig\TwigFunction('img_percent', [$this, 'img_percent']),
            new \Twig\TwigFunction('captcha_base64', [$this, 'captcha_base64']),
        ];
    }

    /*
    -----------------------------------------------------------------
    Some other functions
    -----------------------------------------------------------------
    */

    function typetext($text = null, $type = null)
    {
        if (!$text) {
            return false;
        } else {
            switch ($type) {
                case 'is_array':
                    return is_array($text);
                    break;
                case 'is_bool':
                    return is_bool($text);
                    break;
                case 'is_float':
                    return is_float($text);
                    break;
                case 'is_int':
                    return is_int($text);
                    break;
                case 'is_null':
                    return is_null($text);
                    break;
                case 'is_numeric':
                    return is_numeric($text);
                    break;
                case 'is_object':
                    return is_object($text);
                    break;
                case 'is_string':
                    return is_string($text);
                    break;
                default:
                    return gettype($text);
                    break;
            }
        }
    }

    function encrypt($string = null, $type = null)
    {
        if (!$string || !$type) {
            return 'There is not string or type in encrypt()';
        } else {
            $type = strtolower($type);
            $supported_encrypt_functions = hash_algos();
            if (in_array($type, $supported_encrypt_functions)) {
                return hash($type, $string);
            } else {
                return 'An error occurred in function encrypt(): `type` referenced must be one of the following: ' . implode(', ', $supported_encrypt_functions);
            }
        }
    }

    function strip_tags_($string = null)
    {
        if (!$string) {
            return 'There is not string in strip_tags()';
        } else {
            return strip_tags($string);
        }
    }

    function ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }

    function debug($var)
    {
        print_r($var);
    }

    function user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function json_decode_($string)
    {
        return json_decode($string, true);
    }

    function html_decode($string)
    {
        return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }

    function shuffle_array($array)
    {
        shuffle($array);
        return $array;
    }

    function implode_array($key = null, $array = null)
    {
        if (!$key) {
            return 'There is not key or array in implode_array()';
        } else {
            if (is_array($array)) {
                return implode($key, $array);
            } else {
                return 'An error occurred in function implode_array(): `array` must be an array';
            }
        }
    }

    function rounding($type = null, $number = null)
    {
        if (!$type || !$number) {
            return 'There is not type or number in rounding()';
        } else {
            $type = strtolower($type);
            switch ($type) {
                case 'up':
                    return ceil($number);
                    break;
                case 'ceil':
                    return ceil($number);
                    break;
                case 'down':
                    return floor($number);
                    break;
                case 'floor':
                    return floor($number);
                    break;
                case 'round':
                    return round($number);
                    break;
                default:
                    return 'An error occurred in function rounding(): `type` referenced must be `up` (ceil), `down` (floor) or `round`';
                    break;
            }
        }
    }

    function file_header($string = null)
    {
        if (!$string) {
            return false;
        } else return header($string);
    }

    function img_farm()
    {
        header('Content-Type: image/png');
        $image_server = 'https://dorew-site.github.io/assets/farm/';
        // input
        $loaidat = $_GET['dat'] ? $_GET['dat'] : 0;
        $img_dat = $image_server . $loaidat . '.png';
        $giaidoan = $_GET['cay'];
        $img_cay = $image_server . 'plant/' . $giaidoan . '.png';
        $wco = $_GET['co'];
        $wsau = $_GET['sau'];
        // save data
        $im = imagecreatefrompng($img_dat);
        $stamp = imagecreatefrompng($img_cay);
        @imagesavealpha($im, true);
        // query
        $sx = @imagesx($stamp);
        $sy = @imagesy($stamp);
        @imagecopyresized($im, $stamp, @imagesx($im) - (@imagesx($im) / 2) - ($sx / 2), @imagesy($im) - $sy - 9, 0, 0, @imagesx($stamp), @imagesy($stamp), @imagesx($stamp), @imagesy($stamp));
        if ($wco == '1') {
            $co = @imagecreatefrompng($image_server . 'co.png');
            @imagecopyresized($im, $co, 4, 27, 0, 0, @imagesx($co), @imagesy($co), @imagesx($co), @imagesy($co));
        }
        if ($wsau == '1') {
            $sau = @imagecreatefrompng($image_server . 'sau.png');
            @imagecopyresized($im, $sau, 15, 39, 0, 0, @imagesx($sau), @imagesy($sau), @imagesx($sau), @imagesy($sau));
            @imagecopyresized($im, $sau, 29, 37, 0, 0, @imagesx($sau), @imagesy($sau), @imagesx($sau), @imagesy($sau));
        }
        // output
        @imagepng($im);
        @imagedestroy($im);
    }

    function img_percent($percent = null)
    {
        $vote = abs(intval($percent));
        if ($vote > 100) $vote = 100;
        header("Content-type: image/gif");
        $vote_img = imageCreateFromGIF("https://numeron.alwaysdata.net/vote.gif");
        $color = imagecolorallocate($vote_img, 234, 237, 237);
        $color2 = imagecolorallocate($vote_img, 227, 222, 222);
        $color3 = imagecolorallocate($vote_img, 204, 200, 200);
        $color4 = imagecolorallocate($vote_img, 185, 181, 181);
        $color5 = imagecolorallocate($vote_img, 197, 195, 195);
        imagefilledrectangle($vote_img, 1, 1, 100, 2, $color);
        imagefilledrectangle($vote_img, 1, 3, 100, 4, $color2);
        imagefilledrectangle($vote_img, 1, 5, 100, 6, $color3);
        imagefilledrectangle($vote_img, 1, 7, 100, 8, $color4);
        imagefilledrectangle($vote_img, 1, 9, 100, 10, $color5);

        if ($vote >= 0 && $vote <= 25) {
            $color = imagecolorallocate($vote_img, 190, 255, 140);
            $color2 = imagecolorallocate($vote_img, 111, 255, 0);
            $color3 = imagecolorallocate($vote_img, 100, 230, 0);
            $color4 = imagecolorallocate($vote_img, 40, 200, 0);
            $color5 = imagecolorallocate($vote_img, 100, 230, 0);
            $color6 = imagecolorallocate($vote_img, 0, 0, 0);

            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 3, $vote, 4, $color2);
            imagefilledrectangle($vote_img, 1, 5, $vote, 6, $color3);
            imagefilledrectangle($vote_img, 1, 7, $vote, 8, $color4);
            imagefilledrectangle($vote_img, 1, 9, $vote, 10, $color5);
        }

        if ($vote >= 26 && $vote <= 50) {
            $color = imagecolorallocate($vote_img, 220, 255, 100);
            $color2 = imagecolorallocate($vote_img, 200, 255, 0);
            $color3 = imagecolorallocate($vote_img, 180, 230, 60);
            $color4 = imagecolorallocate($vote_img, 160, 200, 0);
            $color5 = imagecolorallocate($vote_img, 180, 230, 60);
            $color6 = imagecolorallocate($vote_img, 0, 0, 0);


            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 3, $vote, 4, $color2);
            imagefilledrectangle($vote_img, 1, 5, $vote, 6, $color3);
            imagefilledrectangle($vote_img, 1, 7, $vote, 8, $color4);
            imagefilledrectangle($vote_img, 1, 9, $vote, 10, $color5);
        }

        if ($vote >= 51 && $vote <= 75) {
            $color = imagecolorallocate($vote_img, 255, 230, 150);
            $color2 = imagecolorallocate($vote_img, 255, 215, 120);
            $color3 = imagecolorallocate($vote_img, 255, 200, 60);
            $color4 = imagecolorallocate($vote_img, 255, 180, 0);
            $color5 = imagecolorallocate($vote_img, 255, 200, 60);
            $color6 = imagecolorallocate($vote_img, 0, 0, 0);

            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 3, $vote, 4, $color2);
            imagefilledrectangle($vote_img, 1, 5, $vote, 6, $color3);
            imagefilledrectangle($vote_img, 1, 7, $vote, 8, $color4);
            imagefilledrectangle($vote_img, 1, 9, $vote, 10, $color5);
        }

        if ($vote >= 76 && $vote <= 100) {
            $color = imagecolorallocate($vote_img, 255, 204, 204);
            $color2 = imagecolorallocate($vote_img, 255, 153, 153);
            $color3 = imagecolorallocate($vote_img, 255, 102, 102);
            $color4 = imagecolorallocate($vote_img, 255, 51, 51);
            $color5 = imagecolorallocate($vote_img, 255, 102, 102);
            $color6 = imagecolorallocate($vote_img, 0, 0, 0);

            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 1, $vote, 2, $color);
            imagefilledrectangle($vote_img, 1, 3, $vote, 4, $color2);
            imagefilledrectangle($vote_img, 1, 5, $vote, 6, $color3);
            imagefilledrectangle($vote_img, 1, 7, $vote, 8, $color4);
            imagefilledrectangle($vote_img, 1, 9, $vote, 10, $color5);
        }

        imageString($vote_img, 1, 78, 2, "$vote%", $color6);
        ImageGIF($vote_img);
    }

    function captcha_base64($text = null)
    {
        /**
         * Captcha dạng ảnh
         * Xuất ra định dạng: PNG - base64
         */
        $image_size = [
            'height' => 50,
            'width' => 190,
        ];
        $font_list = [
            'baby_blocks.ttf' => [
                'size' => 16,
                'case' => 0
            ],
            'betsy_flanagan.ttf' => [
                'size' => 30,
                'case' => 0
            ],
            'karmaticarcade.ttf' => [
                'size' => 20,
                'case' => 0
            ],
            'tonight.ttf' => [
                'size' => 28,
                'case' => 0
            ],
        ];
        $font = [
            'path' => '/assets/system/captcha/',
            'name' => array_rand($font_list)
        ];
        $text = trim($text) ? $text : 'dorew';
        $text = implode(' ', str_split($text));
        if ($font_list[$font['name']]['case'] == 1) {
            $text = strtoupper($text);
        } else $text = strtolower($text);
        $text_length = strlen($text);
        $font_size = $font_list[$font['name']]['size'];
        $text_width = $image_size['width'];
        $text_height = $image_size['height'];
        $image = imagecreatetruecolor($text_width, $text_height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefilledrectangle($image, 0, 0, $image_size['width'], $image_size['height'], $transparent);
        $text_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        if ($text_color == imagecolorallocate($image, 255, 255, 255)) {
            $text_color = imagecolorallocate($image, 0, 0, 0);
        }
        imagettftext($image, $font_size, 0, 10, 38, $text_color, $font['path'] . $font['name'], $text);
        ob_start();
        imagepng($image);
        $image_data = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        return 'data:image/png;base64,' . base64_encode($image_data);
    }
}
