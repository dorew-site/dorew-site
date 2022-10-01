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

class BBcode extends \Twig\Extension\AbstractExtension
{

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('bb_code_system', [$this, 'bb_code']),
            new \Twig\TwigFunction('bb_url_system', [$this, 'bb_url']),
        ];
    }

    function bb_code($var)
    {
        $var = preg_replace_callback('#\[php\](.+?)\[\/php\]#s', function ($matches) {
            return self::load_bb_code('php', $matches[1]);
        }, $var);
        $var = preg_replace_callback('#\[code\](.+?)\[\/code\]#s', function ($matches) {
            return self::load_bb_code('code', $matches[1]);
        }, $var);
        $var = preg_replace_callback('#\[code=(.+?)\](.+?)\[\/code]#is', function ($matches) {
            return self::load_bb_code($matches[1], $matches[2]);
        }, $var);
        return $var;
    }

    private static function load_bb_code($type_code = null, $code = null)
    {
        $type_code =  mb_strtoupper($type_code);
        $code = str_replace(array('[', ']'), array('&#91;', '&#93;'), $code);
        $code = str_replace('/', '&#47;', $code);
        return '<div class="bbCodeBlock bbCodePHP"><div class="type"><div class="cbuttons"><div class="cbutton close"></div><div class="cbutton minimize"></div><div class="cbutton maximize"></div></div><div class="titCode">' . $type_code . '</div></div><div class="code" style="overflow-x: auto"><pre style="font-family:monospace;"><code contenteditable="true">' . $code . '</code></pre></div></div>';
    }

    public static function bb_url($text)
    {
        if (!function_exists('url_callback')) {
            function url_callback($type, $whitespace, $url, $relative_url)
            {
                $orig_url = $url;
                $orig_relative = $relative_url;
                $url = htmlspecialchars_decode($url);
                $relative_url = htmlspecialchars_decode($relative_url);
                $text = '';
                $chars = array('<', '>', '"');
                $split = false;
                foreach ($chars as $char) {
                    $next_split = strpos($url, $char);
                    if ($next_split !== false) {
                        $split = ($split !== false) ? min($split, $next_split) : $next_split;
                    }
                }
                if ($split !== false) {
                    $url = substr($url, 0, $split);
                    $relative_url = '';
                } else {
                    if ($relative_url) {
                        $split = false;
                        foreach ($chars as $char) {
                            $next_split = strpos($relative_url, $char);
                            if ($next_split !== false) {
                                $split = ($split !== false) ? min($split, $next_split) : $next_split;
                            }
                        }
                        if ($split !== false) {
                            $relative_url = substr($relative_url, 0, $split);
                        }
                    }
                }
                $last_char = ($relative_url) ? $relative_url[strlen($relative_url) - 1] : $url[strlen($url) - 1];
                switch ($last_char) {
                    case '.':
                    case '?':
                    case '!':
                    case ':':
                    case ',':
                        $append = $last_char;
                        if ($relative_url) {
                            $relative_url = substr($relative_url, 0, -1);
                        } else {
                            $url = substr($url, 0, -1);
                        }
                        break;
                    default:
                        $append = '';
                        break;
                }
                $short_url = (mb_strlen($url) > 40) ? mb_substr($url, 0, 30) . ' ... ' . mb_substr($url, -5) : $url;
                switch ($type) {
                    case 1:
                        $relative_url = preg_replace('/[&?]sid=[0-9a-f]{32}$/', '', preg_replace('/([&?])sid=[0-9a-f]{32}&/', '$1', $relative_url));
                        $url = $url . '/' . $relative_url;
                        $text = $relative_url;
                        if (!$relative_url) {
                            return $whitespace . $orig_url . '/' . $orig_relative;
                        }
                        break;
                    case 2:
                        $text = $url;
                        break;
                    case 4:
                        $text = $short_url;
                        $url = 'mailto:' . $url;
                        break;
                }
                $url = htmlspecialchars($url);
                $text = htmlspecialchars($text);
                $append = htmlspecialchars($append);

                return $whitespace . '<i class="fa fa-link fa-spin"></i><a href="' . $url . '" target="_blank">' . $text . '</a>' . $append;
            }
        }

        // Liên kết nội bộ
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])(' . preg_quote($GLOBALS['http_host'], '#') . ')/((?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(1, $matches[1], $matches[2], $matches[3]);
            },
            $text
        );

        // Liên kết thông thường
        $text = preg_replace_callback(
            '#(^|[\n\t (>.])([a-z][a-z\d+]*:/{2}(?:(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#iu',
            function ($matches) {
                return url_callback(2, $matches[1], $matches[2], '');
            },
            $text
        );

        return $text;
    }
}
