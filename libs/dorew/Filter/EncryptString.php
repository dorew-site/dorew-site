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

class EncryptString extends \Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('md5', [$this, 'twig_md5']),
            new \Twig\TwigFilter('sha1', [$this, 'twig_sha1']),
            new \Twig\TwigFilter('htmlspecialchars', [$this, 'twig_htmlspecialchars']),
            
            new \Twig\TwigFilter('truncate', [$this, 'twig_truncate']),
            
            new \Twig\TwigFilter('json_decode', [$this, 'twig_json_decode']),
            new \Twig\TwigFilter('url_decode', [$this, 'twig_url_decode']),
            new \Twig\TwigFilter('ju_encode', [$this, 'twig_ju_encode']),
            new \Twig\TwigFilter('ju_decode', [$this, 'twig_ju_decode']),
        ];
    }

    /* --- ENCRYPTION --- */

    function twig_md5($text)
    {
        return md5($text);
    }

    function twig_sha1($text)
    {
        return sha1($text);
    }
    
    function twig_htmlspecialchars($text)
    {
        return htmlspecialchars($text);
    }
    
    /* --- TRUNCATE --- */

    function twig_truncate($string, int $start = 0, int $length = 0)
    {
        if ($length == 0) {
            $length = $start;
            $start = 0;
        }
        return substr($string, $start, $length);
    }

    /* --- JSON --- */

    function twig_json_decode($string)
    {
        return json_decode($string, true);
    }

    /* --- URL --- */

    function twig_url_decode($string)
    {
        return urldecode($string);
    }

    /* --- URL AND JSON --- */

    function twig_ju_encode($string)
    {
        return urlencode(json_encode($string));
    }

    function twig_ju_decode($string)
    {
        return json_decode(urldecode($string));
    }
}
