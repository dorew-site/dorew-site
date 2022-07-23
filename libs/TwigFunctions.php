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

class TwigFunctions extends \Twig\Extension\AbstractExtension
{
    public function __construct()
    {
        global $db_host, $db_user, $db_pass, $db_name, $default_login;
        $this->core_cookie = $default_login;
        $this->db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $this->conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (empty($this->db) || empty($this->conn)) {
            //throw new Exception('Connect database failed');
            header('Location: /cms');
            exit();
        }
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('create_table', [$this, 'create_table']),
            new \Twig\TwigFunction('create_table_with_column', [$this, 'create_table_with_column']),
            new \Twig\TwigFunction('drop_table', [$this, 'drop_table']),
            new \Twig\TwigFunction('rename_table', [$this, 'rename_table']),
            new \Twig\TwigFunction('get_table_count', [$this, 'get_table_count']),

            new \Twig\TwigFunction('create_column_table', [$this, 'create_column_table']),
            new \Twig\TwigFunction('drop_column_table', [$this, 'drop_column_table']),

            new \Twig\TwigFunction('insert_row_table', [$this, 'insert_row_table']),
            new \Twig\TwigFunction('insert_row_array_table', [$this, 'insert_row_array_table']),
            new \Twig\TwigFunction('update_row_table', [$this, 'update_row_table']),
            new \Twig\TwigFunction('update_row_array_table', [$this, 'update_row_array_table']),
            new \Twig\TwigFunction('delete_row_table', [$this, 'delete_row_table']),

            new \Twig\TwigFunction('select_table_data', [$this, 'select_table_data']),
            new \Twig\TwigFunction('select_table_row_data', [$this, 'select_table_row_data']),
            new \Twig\TwigFunction('select_table_where_data', [$this, 'select_table_where_data']),

            new \Twig\TwigFunction('cancel_xss', [$this, 'cancel_xss']),
            new \Twig\TwigFunction('is_login', [$this, 'is_login']),
            new \Twig\TwigFunction('bb_default', [$this, 'bb_default']),
            new \Twig\TwigFunction('rwurl', [$this, 'rwurl']),
            new \Twig\TwigFunction('request_method', [$this, 'request_method']),
            new \Twig\TwigFunction('ip', [$this, 'ip']),
            new \Twig\TwigFunction('debug', [$this, 'debug']),
            new \Twig\TwigFunction('get_uri_segments', [$this, 'get_uri_segments']),
            new \Twig\TwigFunction('redirect', [$this, 'redirect']),
            new \Twig\TwigFunction('get_post', [$this, 'get_post']),
            new \Twig\TwigFunction('get_get', [$this, 'get_get']),
            new \Twig\TwigFunction('user_agent', [$this, 'user_agent']),
            new \Twig\TwigFunction('set_cookie', [$this, 'set_cookie']),
            new \Twig\TwigFunction('delete_cookie', [$this, 'delete_cookie']),
            new \Twig\TwigFunction('get_cookie', [$this, 'get_cookie']),
            new \Twig\TwigFunction('json_decode', [$this, 'json_decode_']),
            new \Twig\TwigFunction('html_decode', [$this, 'html_decode']),
            new \Twig\TwigFunction('current_url', [$this, 'current_url']),
        ];
    }

    /*
    -----------------------------------------------------------------
    Action with tables in database
    -----------------------------------------------------------------
    */

    /* --- QUERY AND PROCESS DATA IN TABLE --- */

    private function error($msg)
    {
        if (!empty($msg)) {
            throw new Exception($msg);
            return true;
        }
        return false;
    }

    function query($sql)
    {
        if (empty($sql)) {
            $this->error('Invalid query string');
            return false;
        }
        $result = $this->db->query($sql);
        if (!$result || $result === false) {
            $this->error('Query failed');
            return false;
        }
        if ($result !== true) {
            $this->$result = $result;
        }
        return true;
    }

    function table_exists($table_name)
    {
        $sql = "SHOW TABLES LIKE '$table_name'";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* --- MANIPULATING TABLES --- */

    /* TABLE */

    function create_table_with_column($table_name = null, $array_column = null)
    {
        if (!$table_name || !$array_column) {
            return 'There is not table_name or columns in create_table_with_column()';
        } else {
            if (!$this->table_exists($table_name)) {
                $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
                `id` int(10) UNSIGNED NULL AUTO_INCREMENT,
                ";
                if (!$array_column) {
                    $sql .= "`time` int(11) NOT NULL,
                    ";
                } else {
                    foreach ($array_column as $key => $value) {
                        $sql .= "`$key` $value, ";
                    }
                }
                $sql .= "UNIQUE KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` created with your columns';
            } else {
                return 'Table `' . $table_name . '` already exists';
            }
        }
    }

    function create_table($table_name)
    {
        if (!$table_name) {
            return 'There is not table_name in create_table()';
        } else {
            if ($this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` already exists';
            } else {
                $column = ["time" => "`time` int(11) NOT NULL"];
                return $this->create_table_with_column($table_name, $column);
            }
        }
    }

    function drop_table($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in drop_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "DROP TABLE `$table_name`";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` dropped';
            }
        }
    }

    function rename_table($table_name = null, $new_table_name = null)
    {
        if (!$table_name || !$new_table_name) {
            return 'There is not table_name or new_table_name in rename_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "RENAME TABLE `$table_name` TO `$new_table_name`";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` renamed to `' . $new_table_name . '`';
            }
        }
    }

    function get_table_count($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_data_count_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT COUNT(*) FROM `$table_name`";
                $result = mysqli_query($this->conn, $sql);
                $row = mysqli_fetch_row($result);
                return $row[0] ? $row[0] : 0;
            }
        }
    }

    /* COLUMN */

    function create_column_table($table_name = null, $column_name = null, $column_type = null)
    {
        if (!$table_name || !$column_name || !$column_type) {
            return 'There is not table_name or column_name or column_type in create_column_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "ALTER TABLE $table_name ADD $column_name $column_type";
                mysqli_query($this->conn, $sql);
                return 'Column ' . $column_name . ' in table `' . $table_name . '` created with type ' . $column_type;
            }
        }
    }


    function drop_column_table($table_name = null, $column_name = null)
    {
        if (!$table_name || !$column_name) {
            return 'There is not table_name or column_name in drop_column_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "ALTER TABLE $table_name DROP $column_name";
                mysqli_query($this->conn, $sql);
                return 'Column ' . $column_name . ' in table `' . $table_name . '` dropped';
            }
        }
    }

    /* --- ROW --- */

    function insert_row_array_table($table_name = null, $array_row = null)
    {
        if (!$table_name || !$array_row) {
            return 'There is not table_name or array_row in create_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "INSERT INTO `$table_name` SET ";
                foreach ($array_row as $key => $value) {
                    $sql .= "`$key` = '$value', ";
                }
                $sql = substr($sql, 0, -2);
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` created';
            }
        }
    }

    function insert_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in create_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->insert_row_array_table($table_name, $array);
            }
        }
    }

    function update_row_array_table($table_name = null, $array_row = null, $where_column_name = null, $where_column_value = null)
    {
        if (!$table_name || !$array_row || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or array_row or where_column_name or where_column_value in update_row_array_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                //{"column_name1":"column_value1","column_name2":"column_value2"}
                $sql = "UPDATE $table_name SET ";
                foreach ($array_row as $key => $value) {
                    $sql .= "`$key` = '$value', ";
                }
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE `$where_column_name` = '$where_column_value'";
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` updated';
            }
        }
    }

    function update_row_table($table_name = null, $column_name = null, $column_value = null, $where_column_name = null, $where_column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or column_name or column_value or where_column_name or where_column_value in update_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->update_row_array_table($table_name, $array, $where_column_name, $where_column_value);
            }
        }
    }

    function delete_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in delete_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "DELETE FROM $table_name WHERE $column_name = $column_value";
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` deleted';
            }
        }
    }

    /* --- COLUMN AND ROW --- */

    function select_table_row_data($table_name =  null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or row or column_name or column_value in select_table_row_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name WHERE $column_name = '$column_value'";
                $query = mysqli_query($this->conn, $sql);
                $row = mysqli_fetch_assoc($query);
                return $row;
            }
        }
    }

    function select_table_data($table_name = null, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name in select_table_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name ORDER BY $order $sort";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
    }

    function select_table_where_data($table_name, $where_column_name, $where_column_value, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or where_column_name or where_column_value in select_table_where_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name WHERE $where_column_name = '$where_column_value' ORDER BY $order $sort";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                $total = ['total' => $rows ? count($rows) : 0];
                return array_merge($total, $rows);
            }
        }
    }

    /*
    -----------------------------------------------------------------
    Additional Functions for TWIG
    -----------------------------------------------------------------
    */

    function cancel_xss($string)
    {
        $string = preg_replace('/\\\\/', '\\', $string);
        $string = str_replace('\&quot;', '&quot;', $string);
        $string = str_replace('\"', '&quot;', $string);
        $string = str_replace("\'", '&#039;', $string);
        return $string;
    }

    function is_login()
    {
        $table_name = 'users';
        $core_cookie = $this->core_cookie;
        $token = $this->get_cookie($core_cookie) ? $this->get_cookie($core_cookie) : 'bot';
        $data = $this->select_table_row_data($table_name, 'auto', $token);
        if ($data) {
            return $data['nick'];
        } else {
            return false;
        }
    }

    function bb_default($text)
    {
        //$text = htmlspecialchars($text);
        //$text = nl2br($text);
        $text = preg_replace("/\[b\](.*?)\[\/b\]/is", "<strong>$1</strong>", $text);
        $text = preg_replace("/\[i\](.*?)\[\/i\]/is", "<em>$1</em>", $text);
        $text = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>$1</u>", $text);
        $text = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/is", "<span style=\"color:$1\">$2</span>", $text);
        $text = preg_replace("/\[size=(.*?)\](.*?)\[\/size\]/is", "<span style=\"font-size:$1\">$2</span>", $text);
        $text = preg_replace("/\[quote\](.*?)\[\/quote\]/is", "<blockquote>$1</blockquote>", $text);
        $text = preg_replace('/\[code\](.*?)\[\/code\]/is', '<divCode><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i></divCode><pre><code contenteditable="true">$1</code></pre>', $text);
        return $text;
    }

    function request_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    function debug($var)
    {
        print_r($var);
    }

    function get_uri_segments()
    {
        global $uri_path;
        $uri_segments = explode('/', $uri_path);
        return $uri_segments;
    }

    function redirect($url)
    {
        return header('Location: ' . $url);
    }

    function get_post($string)
    {
        return htmlspecialchars(addslashes($_POST[$string]));
    }

    function get_get($string)
    {
        return htmlspecialchars(addslashes($_GET[$string]));
    }

    function user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
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

    function json_decode_($string)
    {
        return json_decode($string, true);
    }

    function html_decode($string)
    {
        return html_entity_decode($string, true);
    }

    function current_url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    function rwurl($string)
    {
        $string = strtolower($string);
        //bỏ dấu tiếng việt
        $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
        $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
        $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
        $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
        $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
        $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
        $string = preg_replace("/(đ)/", 'd', $string);
        $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
        $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
        $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
        $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
        $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
        $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
        $string = preg_replace("/(Đ)/", 'D', $string);
        //xoá toàn bộ ký tự đặc biệt
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //xoá khoảng trắng thừa
        $string = trim(preg_replace("/[\s-]+/", " ", $string));
        //thay thế khoảng trắng bằng ký tự -
        $string = preg_replace("/[\s-]/", "-", $string);
        $string = mb_strtolower($string, 'utf8');
        return $string;
    }
}
