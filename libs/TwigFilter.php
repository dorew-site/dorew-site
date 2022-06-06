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

class TwigFilter extends \Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('md5', [$this, 'twig_md5']),
            new \Twig\TwigFilter('json_decode', [$this, 'twig_json_decode']),
            new \Twig\TwigFilter('truncate', [$this, 'twig_truncate']),
        ];
    }

    function twig_md5($text)
    {
        return md5($text);
    }
    function twig_json_decode($string)
    {
        return json_decode($string, true);
    }
    function twig_truncate($string, int $start = 0, int $length = 0)
    {
        if ($length == 0) {
            $length = $start;
            $start = 0;
        }
        return substr($string, $start, $length);
    }
}
