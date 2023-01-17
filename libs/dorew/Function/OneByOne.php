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

class OneByOne extends \Twig\Extension\AbstractExtension
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
            new \Twig\TwigFunction('layout', [$this, 'display_layout']),

            new \Twig\TwigFunction('json_decode', [$this, 'json_decode_']),
            new \Twig\TwigFunction('html_decode', [$this, 'html_decode']),
            new \Twig\TwigFunction('shuffle_array', [$this, 'shuffle_array']),
            new \Twig\TwigFunction('implode', [$this, 'implode_array']),
            new \Twig\TwigFunction('strip_tags', [$this, 'strip_tags_']),

            new \Twig\TwigFunction('rounding', [$this, 'rounding']),
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

    function display_layout()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $arrUA = strtolower($ua);
        if (preg_match('/windows|ipod|ipad|iphone|android|webos|blackberry|midp/', $arrUA) && preg_match('/mobile/', $arrUA)) {
            return 'mobile';
        } elseif (preg_match('/mobile/', $arrUA)) return 'mobile';
        else return 'desktop';
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
}
