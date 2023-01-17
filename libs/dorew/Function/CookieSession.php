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

class CookieSession extends \Twig\Extension\AbstractExtension
{
    private $login;

    public function __construct()
    {
        global $default_login;
        $this->core_cookie = $default_login;
        $this->db = new QuerySQL();
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('is_login', [$this, 'is_login']),

            new \Twig\TwigFunction('set_cookie', [$this, 'set_cookie']),
            new \Twig\TwigFunction('delete_cookie', [$this, 'delete_cookie']),
            new \Twig\TwigFunction('get_cookie', [$this, 'get_cookie']),

            new \Twig\TwigFunction('session', [$this, 'dsession']),
            new \Twig\TwigFunction('set_session', [$this, 'set_session']),
            new \Twig\TwigFunction('delete_session', [$this, 'delete_session']),
            new \Twig\TwigFunction('get_session', [$this, 'get_session']),
        ];
    }

    /* === Cookies === */

    function is_login()
    {
        static $login;
        if (isset($login)) return $login;
        $table_name = 'users';
        $core_cookie = $this->core_cookie;
        $token = $this->get_cookie($core_cookie) ? $this->get_cookie($core_cookie) : 'bot';
        $data = $this->db->select_table_row_data($table_name, 'auto', $token);
        $login = false;
        if ($data) $login = $data['nick'];
        return $login;
    }

    function set_cookie($name, $value)
    {
        setcookie($name, $value, time() + 3600 * 24 * 365, '/');
        return;
    }

    function delete_cookie($name)
    {
        setcookie($name, '', time() - 3600 * 24 * 365, '/');
        unset($_COOKIE[$name]);
        return;
    }

    function get_cookie($name)
    {
        if (!$_COOKIE[$name]) return false;
        return $_COOKIE[$name];
    }

    /* === Sessions === */

    function dsession($name = null, $value = null, $type = null)
    {
        switch ($type) {
            case 'start':
                session_start();
                break;
            case 'destroy':
                session_destroy();
                break;
            case 'set':
                $_SESSION[$name] = $value;
                break;
            case 'delete':
                unset($_SESSION[$name]);
                break;
            case 'get':
                return $_SESSION[$name];
                break;
            default:
                if (!$_SESSION[$name] || !$name) return false;
                return $_SESSION[$name];
        }
    }

    function set_session($name, $value = null)
    {
        if (is_array($name) && is_null($value) && count($name) == 1) {
            if (isset($name['start']) && $name['start'] == true && !isset($name['destroy'])) {
                session_start();
            }
            if (isset($name['destroy']) && $name['destroy'] == true && !isset($name['start'])) {
                session_destroy();
            }
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if ($value == null) {
                unset($_SESSION[$name]);
            } else {
                $_SESSION[$name] = $value;
            }
        }
    }

    function get_session($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return false;
    }

    function delete_session($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
}
