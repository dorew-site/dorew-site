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

class ArraySort extends \Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('array_unique', [$this, 'twig_array_unique']),
            
            new \Twig\TwigFilter('get_keys', [$this, 'twig_get_keys']),
            new \Twig\TwigFilter('rsort', [$this, 'twig_rsort']),
            new \Twig\TwigFilter('asort', [$this, 'twig_asort']),
            new \Twig\TwigFilter('ksort', [$this, 'twig_ksort']),
            new \Twig\TwigFilter('arsort', [$this, 'twig_arsort']),
            new \Twig\TwigFilter('krsort', [$this, 'twig_krsort']),
            new \Twig\TwigFilter('shuffle', [$this, 'twig_shuffle']),

            new \Twig\TwigFilter('sum', [$this, 'sum']),
            new \Twig\TwigFilter('product', [$this, 'product']),
            new \Twig\TwigFilter('values', [$this, 'values']),
            new \Twig\TwigFilter('as_array', [$this, 'asArray']),
            new \Twig\TwigFilter('html_attr', [$this, 'htmlAttributes']),
        ];
    }
    
    function twig_array_unique($array)
    {
        return array_unique($array);
    }

    /* --- SORT --- */
    /**
     * sort and reverse are twig's two default filters
     * some other filters: get_keys, rsort, asort, ksort, arsort, krsort, shuffle
     */
     

    function twig_get_keys($array)
    {
        return array_keys($array);
    }

    function twig_rsort($array)
    {
        rsort($array);
        return $array;
    }

    function twig_asort($array)
    {
        asort($array);
        return $array;
    }

    function twig_ksort($array)
    {
        ksort($array);
        return $array;
    }

    function twig_arsort($array)
    {
        arsort($array);
        return $array;
    }

    function twig_krsort($array)
    {
        krsort($array);
        return $array;
    }

    function twig_shuffle($array)
    {
        shuffle($array);
        return $array;
    }

    /**
     * Calculate the sum of values in an array
     *
     * @param array $array
     * @return int
     */
    public function sum($array)
    {
        return isset($array) ? array_sum((array)$array) : null;
    }


    /**
     * Calculate the product of values in an array
     *
     * @param array $array
     * @return int
     */
    public function product($array)
    {
        return isset($array) ? array_product((array)$array) : null;
    }


    /**
     * Return all the values of an array or object
     *
     * @param array|object $array
     * @return array
     */
    public function values($array)
    {
        return isset($array) ? array_values((array)$array) : null;
    }


    /**
     * Cast value to an array
     *
     * @param object|mixed $value
     * @return array
     */
    public function asArray($value)
    {
        return is_object($value) ? get_object_vars($value) : (array)$value;
    }


    /**
     * Cast an array to an HTML attribute string
     *
     * @param mixed $array
     * @return string
     */
    public function htmlAttributes($array)
    {
        if (!isset($array)) {
            return null;
        }
        $str = "";
        foreach ($array as $key => $value) {
            if (!isset($value) || $value === false) {
                continue;
            }
            if ($value === true) {
                $value = $key;
            }
            $str .= ' ' . $key . '="' . addcslashes($value, '"') . '"';
        }
        return trim($str);
    }
}
