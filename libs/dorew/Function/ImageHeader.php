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

class ImageHeader extends \Twig\Extension\AbstractExtension
{

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('file_header', [$this, 'file_header']),
            new \Twig\TwigFunction('img_farm', [$this, 'img_farm']),
            new \Twig\TwigFunction('img_percent', [$this, 'img_percent']),
        ];
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
        $vote_img = imageCreateFromGIF("/vote.gif");
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
}
