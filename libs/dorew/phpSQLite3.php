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

class phpSQLite3 extends \Twig\Extension\AbstractExtension
{

    public function __construct()
    {
        global $config_db, $default_login;
        $this->core_cookie = $default_login;
        $path_sqlite = $config_db['phpSQLite3']['directory'] . $config_db['phpSQLite3']['path'];
        if (!file_exists($path_sqlite)) {
            file_put_contents($path_sqlite, '');
        }
        // kết nối với csdl sqlite
        $this->db = new SQLite3($path_sqlite);
        // dung lượng của path_sqlite
        $this->db_size = filesize($path_sqlite);
        // font uft8
        $this->db->query('PRAGMA encoding = "UTF-8"');
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('table_exists', [$this, 'table_exists']),
            new \Twig\TwigFunction('column_exists', [$this, 'column_exists']),

            new \Twig\TwigFunction('get_size_db', [$this, 'get_size_db']),
            new \Twig\TwigFunction('get_table_count', [$this, 'get_table_count']),
            new \Twig\TwigFunction('get_row_count', [$this, 'get_row_count']),
            new \Twig\TwigFunction('get_list_column', [$this, 'get_list_column']),

            new \Twig\TwigFunction('query_select_table', [$this, 'query_select_table']),
            new \Twig\TwigFunction('query_update_table', [$this, 'query_update_table']),

            new \Twig\TwigFunction('create_table_with_column', [$this, 'create_table_with_column']),
            new \Twig\TwigFunction('create_table', [$this, 'create_table']),
            new \Twig\TwigFunction('drop_table', [$this, 'drop_table']),
            new \Twig\TwigFunction('rename_table', [$this, 'rename_table']),

            new \Twig\TwigFunction('create_columns_table', [$this, 'create_columns_table']),
            new \Twig\TwigFunction('create_column_table', [$this, 'create_column_table']),
            new \Twig\TwigFunction('drop_column_table', [$this, 'drop_column_table']),
            new \Twig\TwigFunction('edit_type_column_table', [$this, 'edit_type_column_table']),
            new \Twig\TwigFunction('insert_row_array_table', [$this, 'insert_row_array_table']),
            new \Twig\TwigFunction('insert_row_table', [$this, 'insert_row_table']),
            new \Twig\TwigFunction('select_table_limit_offset', [$this, 'select_table_limit_offset']),
            new \Twig\TwigFunction('select_table_data', [$this, 'select_table_data']),

            new \Twig\TwigFunction('update_rows_table', [$this, 'update_rows_table']),
            new \Twig\TwigFunction('update_row_array_table', [$this, 'update_row_array_table']),
            new \Twig\TwigFunction('update_row_table', [$this, 'update_row_table']),

            new \Twig\TwigFunction('delete_rows_table', [$this, 'delete_rows_table']),
            new \Twig\TwigFunction('delete_row_table', [$this, 'delete_row_table']),

            new \Twig\TwigFunction('select_table', [$this, 'select_table']),
            new \Twig\TwigFunction('select_table_offset', [$this, 'select_table_offset']),
            new \Twig\TwigFunction('select_table_where_data_limit_offset', [$this, 'select_table_where_data_limit_offset']),
            new \Twig\TwigFunction('select_table_row_data_by_where', [$this, 'select_table_row_data_by_where']),
            new \Twig\TwigFunction('select_table_row_data', [$this, 'select_table_row_data']),
            new \Twig\TwigFunction('select_table_where_array_data', [$this, 'select_table_where_array_data']),
            new \Twig\TwigFunction('select_table_where_data', [$this, 'select_table_where_data']),

            new \Twig\TwigFunction('search_key_in_table', [$this, 'search_key_in_table']),

            new \Twig\TwigFunction('set_cookie', [$this, 'set_cookie']),
            new \Twig\TwigFunction('delete_cookie', [$this, 'delete_cookie']),
            new \Twig\TwigFunction('get_cookie', [$this, 'get_cookie']),
            new \Twig\TwigFunction('is_login', [$this, 'is_login']),
        ];
    }

    /*
    -----------------------------------------------------------------
    EXISTS CHECK AND COUNT
    -----------------------------------------------------------------
    */

    /* --- EXISTS CHECK --- */

    function table_exists($table_name)
    {
        // kiểm tra xem table có tồn tại hay không
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='$table_name'";
        $result = $this->db->query($sql);
        if ($result->fetchArray()) {
            return true;
        } else {
            return false;
        }
    }

    function column_exists($table_name = null, $column_name = null)
    {
        if (!$table_name || !$column_name) {
            return 'There is not table_name or column_name in column_exists()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "PRAGMA table_info($table_name)";
                $result = $this->db->query($sql);
                while ($row = $result->fetchArray()) {
                    if ($row['name'] == $column_name) {
                        return true;
                    }
                }
                return false;
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    /* --- COUNT --- */

    function get_size_db()
    {
        $size_db = $this->db_size ? $this->db_size : 0;
        return $size_db;
    }

    function get_table_count($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_table_count()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT COUNT(*) FROM $table_name";
                $result = $this->db->query($sql);
                $row = $result->fetchArray();
                return $row[0] ? $row[0] : 0;
            }
        }
    }

    function get_row_count($table_name = null, $where = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_row_count()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT COUNT(*) FROM `$table_name`";
                $sql_operator = ['>=', '<=', '>', '<', '=', '!='];
                if ($where) {
                    //where: {'column': 'value', 'column2': 'value2','operator': '>='}
                    $operator = $where['operator'] ? $where['operator'] : '=';
                    if (!in_array($operator, $sql_operator)) {
                        $operator = '=';
                    }
                    $sql .= " WHERE ";
                    $where_new = [];
                    foreach ($where as $key => $value) {
                        if ($key !== 'operator') {
                            $where_new[$key] = $value;
                        }
                    }
                    foreach ($where_new as $key => $value) {
                        $sql .= "`$key` " . $operator . " '$value'";
                        //thêm AND nếu còn thêm điều kiện
                        if (next($where_new)) {
                            $sql .= " AND ";
                        }
                        //return $sql;
                    }
                }
                $result = $this->db->query($sql);
                $row = $result->fetchArray();
                return $row[0] ? $row[0] : 0;
            }
        }
    }

    function get_list_column($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_list_column()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "PRAGMA table_info($table_name)";
                $result = $this->db->query($sql);
                $columns = [];
                while ($row = $result->fetchArray()) {
                    $columns[] = $row['name'] . ' (' . $row['type'] . ')';
                }
                return $columns;
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    /*
    -----------------------------------------------------------------
    Generic SQL Query with Entire Table
    -----------------------------------------------------------------
    */

    /* --- QUERY_COMMAND_TABLE --- */

    function query_select_table($table_name = null, $column = null, $other_sql = null)
    {
        if (!$table_name) {
            return 'There is not table_name in query_select_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (!$column) {
                    $sql = "SELECT * FROM $table_name";
                } else {
                    $sql = "SELECT $column FROM $table_name";
                }
                if ($other_sql) {
                    $sql .= " $other_sql";
                }
                $result = $this->db->query($sql);
                $data = [];
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            }
        }
    }

    function query_update_table($table_name = null, $array_row = null, $other_sql = null)
    {
        if (!$table_name) {
            return 'There is not table_name in query_update_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (is_array($array_row)) {
                    $sql = "UPDATE $table_name SET ";
                    foreach ($array_row as $key => $value) {
                        $sql .= "`$key` = '$value',";
                    }
                    $sql = rtrim($sql, ',');
                    if ($other_sql) {
                        $sql .= " $other_sql";
                    }
                    $this->db->query($sql);
                    return 'Rows in table `' . $table_name . '` updated';
                } else {
                    return 'There is not array_row in query_update_table()';
                }
            }
        }
    }

    /* --- COMMAND_TABLE --- */

    function create_table_with_column($table_name = null, $columns = null)
    {
        /**
         * Tạo bảng với các trường, columns có input là json, id là trường chính
         * Ví dụ: {% set columns = {"name": "TEXT", "msg": "TEXT", "time": "INTEGER"} %}
         */
        if (!$table_name || !$columns) {
            return 'There is not table_name or columns in create_table_with_column()';
        } else {
            if (!$this->table_exists($table_name)) {
                $sql = "CREATE TABLE $table_name (";
                $sql .= 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
                foreach ($columns as $key => $value) {
                    $sql .= $key . ' ' . $value . ',';
                }
                $sql = substr($sql, 0, -1);
                $sql .= ')';
                $this->db->exec($sql);
                return 'Table `' . $table_name . '` created with your columns';
            } else {
                return 'Table `' . $table_name . '` already exists';
            }
        }
    }

    function create_table($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in create_table()';
        } else {
            if ($this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` already exists';
            } else {
                $column = ['time' => 'INTEGER'];
                return $this->create_table_with_column($table_name, $column);
            }
        }
    }

    function drop_table($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in drop_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "DROP TABLE IF EXISTS $table_name";
                $this->db->query($sql);
                return 'Table `' . $table_name . '` dropped';
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function rename_table($current_table = null, $new_table = null)
    {
        if (!$current_table || !$new_table) {
            return 'There is not table_name in rename_table()';
        } else {
            if ($this->table_exists($current_table)) {
                $sql = "ALTER TABLE $current_table RENAME TO $new_table";
                $this->db->query($sql);
                return 'Table `' . $current_table . '` renamed to `' . $new_table . '`';
            } else {
                return 'Table `' . $current_table . '` does not exist';
            }
        }
    }

    /* --- COMMND_COLUMN_TABLE --- */

    function create_columns_table($table = null, $columns = null)
    {
        /**
         * Tạo các trường cho bảng, columns có input là json
         * Ví dụ: {% set columns = {"name": "TEXT", "msg": "TEXT", "time": "INTEGER"} %}
         */
        if (!$table || !$columns) {
            return 'There is not table_name or columns in create_columns_table()';
        } else {
            if ($this->table_exists($table)) {
                $sql = "ALTER TABLE $table ADD COLUMN ";
                foreach ($columns as $key => $value) {
                    $sql .= "$key $value, ";
                }
                $sql = substr($sql, 0, -2);
                $this->db->query($sql);
                return 'Columns added to table `' . $table . '`';
            } else {
                return 'Table `' . $table . '` does not exist';
            }
        }
    }

    function create_column_table($table_name = null, $column_name = null, $column_type = null)
    {
        if (!$table_name || !$column_name || !$column_type) {
            return 'There is not table_name or column_name or column_type in create_column_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $column = [$column_name => $column_type];
                return $this->create_columns_table($table_name, $column);
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function drop_column_table($table_name = null, $column_name = null)
    {
        if (!$table_name || !$column_name) {
            return 'There is not table_name or column_name in drop_column_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "ALTER TABLE $table_name DROP COLUMN $column_name";
                $this->db->query($sql);
                return 'Column `' . $column_name . '` dropped from table `' . $table_name . '`';
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function edit_type_column_table($table_name = null, $column_name = null, $current_type = null, $new_type = null)
    {
        /**
         * Sửa kiểu dữ liệu của trường trong bảng
         */
        if (!$table_name || !$column_name || !$current_type || !$new_type) {
            return 'There is not table_name or column_name or current_type or new_type in edit_type_column_table()';
        } else {
            if ($this->table_exists($table_name)) {
                if ($this->column_exists($table_name, $column_name)) {
                    $sql = "ALTER TABLE $table_name CHANGE $column_name $column_name $new_type";
                    $this->db->query($sql);
                    return 'Column `' . $column_name . '` changed type from `' . $current_type . '` to `' . $new_type . '` in table `' . $table_name . '`';
                } else {
                    return 'Column `' . $column_name . '` does not exist in table `' . $table_name . '`';
                }
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function insert_row_array_table($table_name = null, $rows = null)
    {
        /**
         * Thêm dữ liệu vào bảng, rows có input là json
         * Ví dụ: {% set rows = {"name": "valedrat", "msg": "hello world!", "time": "123456789"} %}
         */
        if (!$table_name || !$rows) {
            return 'There is not table_name or rows in insert_row_array_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $error = null;
                $sql = "INSERT INTO $table_name (";
                foreach ($rows as $key => $value) {
                    // kiểm tra xem cột key có tồn tại hay không
                    if ($this->column_exists($table_name, $key)) {
                        $sql .= "$key, ";
                    } else {
                        $error[] = $key;
                    }
                }
                if ($error) {
                    $notice = 'The mentioned data columns do not exist, those are: ';
                    foreach ($error as $key => $value) {
                        $notice .= $value;
                        if ($key < count($error) - 1) {
                            $notice .= ', ';
                        } else {
                            $notice .= '.';
                        }
                    }
                    return $notice;
                } else {
                    $sql = substr($sql, 0, -2);
                    $sql .= ") VALUES (";
                    foreach ($rows as $key => $value) {
                        $sql .= "'$value', ";
                    }
                    $sql = substr($sql, 0, -2);
                    $sql .= ")";
                    $this->db->query($sql);
                    return 'Rows added to table `' . $table_name . '`';
                }
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function insert_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in insert_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->insert_row_array_table($table_name, $array);
            }
        }
    }

    function select_table_limit_offset($table_name = null,  $limit, $offset, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name or limit or offset in select_table_limit_offset()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "ORDER BY $order $sort LIMIT $limit OFFSET $offset";
                $this->query_select_table($table_name, '*', $sql);
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
                $sql = "ORDER BY $order $sort";
                $this->query_select_table($table_name, '*', $sql);
            }
        }
    }

    /*
    -----------------------------------------------------------------
    SQL queries with WHERE conditions compare equals
    -----------------------------------------------------------------
    */

    /* --- UPDATE --- */

    public function update_rows_table($table_name = null, $columns = null, $where = null)
    {
        /**
         * Cập nhật dữ liệu vào bảng, columns và where có input là json
         * Ví dụ:
         * {% set columns = {"name": "valedrat", "msg": "hello", "time": "123456789"} %}
         * {% set where = {"id": "1"} %}
         */
        if (!$table_name || !$columns || !$where) {
            return 'There is not table_name or columns or where in update_rows_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "UPDATE `$table_name` SET ";
                foreach ($columns as $key => $value) {
                    $sql .= "$key = '$value', ";
                }
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= "$key = '$value' AND ";
                }
                $sql = substr($sql, 0, -5);
                $this->db->query($sql);
                return 'Row updated to table `' . $table_name . '`';
            } else {
                return 'Table `' . $table_name . '` does not exist';
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
                $columns = $array_row;
                $where = [$where_column_name => $where_column_value];
                $this->update_rows_table($table_name, $columns, $where);
            }
        }
    }

    function update_row_table($table_name = null, $column_name = null, $column_value = null, $where_column_name = null, $where_column_value = null)
    {
        if (!$table_name || !$column_name || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or column_name or where_column_name or where_column_value in update_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $columns = [$column_name => $column_value];
                $where = [$where_column_name => $where_column_value];
                $this->update_rows_table($table_name, $columns, $where);
            }
        }
    }

    /* --- DELETE --- */

    public function delete_rows_table($table_name = null, $where = null)
    {
        /**
         * Xóa dữ liệu của bảng, where có input là json
         * Ví dụ: {% set where = {"id": "1"} %}
         */
        if (!$table_name || !$where) {
            return 'There is not table_name or where in delete_rows_table()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "DELETE FROM `$table_name` WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= "$key = '$value' AND ";
                }
                $sql = substr($sql, 0, -5);
                $this->db->query($sql);
                return 'Row deleted from table `' . $table_name . '`';
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    public function delete_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in delete_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->delete_rows_table($table_name, $array);
            }
        }
    }

    /* --- SELECT --- */

    function select_table($table_name = null, $column = null, $where = null, $order = null, $sort = null, $limit = null, $count = null)
    {
        if (!$table_name) {
            return 'There is not table_name in select_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (!$column) {
                    $column = '*';
                }
                $sql = '';
                if ($where) {
                    // where = {'column1': 'value1', 'column2': 'value2'}
                    if (is_array($where)) {
                        $sql .= " WHERE ";
                        foreach ($where as $key => $value) {
                            $sql .= "$key = '$value' AND ";
                        }
                        $sql = substr($sql, 0, -4);
                    } else {
                        return 'An error occurred in the function select_table(): `where` referenced is not an array.';
                        exit;
                    }
                }
                if (!$order) $order = 'id';
                if (!$sort) $sort = 'asc';
                $sql .= " ORDER BY $order $sort";
                if ($limit) {
                    $error_limit = 'An error occurred in the function select_table(): `limit` referenced must be a numberic, or an array containing 2 elements `start` and `end`.';
                    if (is_array($limit)) {
                        // limit = {'start':1,'end':10}
                        if (count($limit) == 2) {
                            if (is_numeric($limit['start']) && is_numeric($limit['end'])) {
                                $sql .= " LIMIT " . $limit['start'] . "," . $limit['end'];
                            } else {
                                return $error_limit;
                                exit;
                            }
                        } else {
                            return $error_limit;
                            exit;
                        }
                    } else {
                        if (is_numeric($limit)) {
                            $sql .= " LIMIT " . $limit;
                        } else {
                            return $error_limit;
                            exit;
                        }
                    }
                }
                $rows = $this->query_select_table($table_name, $column, $sql);
                if ($count == 'count') {
                    return count($rows) ? count($rows) : 0;
                } else {
                    return $rows;
                }
            }
        }
    }

    function select_table_offset($table_name = null, $column = null, $where = null, $order = null, $sort = null, $limit = null, $offset = null, $count = null)
    {
        if (!$table_name) {
            return 'There is not table_name in select_table_offset()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (!$column) {
                    $column = '*';
                }
                $sql = '';
                if ($where) {
                    // where = {'column1': 'value1', 'column2': 'value2'}
                    if (is_array($where)) {
                        $sql .= " WHERE ";
                        foreach ($where as $key => $value) {
                            $sql .= "$key = '$value' AND ";
                        }
                        $sql = substr($sql, 0, -4);
                    } else {
                        return 'An error occurred in the function select_table_offset(): `where` referenced is not an array.';
                        exit;
                    }
                }
                if (!$order) $order = 'id';
                if (!$sort) $sort = 'asc';
                $sql .= " ORDER BY $order $sort";
                if ($offset) {
                    $error_offset = 'An error occurred in the function select_table_offset(): `offset` referenced must be a numberic.';
                    if (is_numeric($offset)) {
                        $sql .= " OFFSET " . $offset;
                    } else {
                        return $error_offset;
                        exit;
                    }
                }
                if ($limit) {
                    $error_limit = 'An error occurred in the function select_table_offset(): `limit` referenced must be a numberic, or an array containing 2 elements `start` and `end`.';
                    if (is_array($limit)) {
                        // limit = {'start':1,'end':10}
                        if (count($limit) == 2) {
                            if (is_numeric($limit['start']) && is_numeric($limit['end'])) {
                                $sql .= " LIMIT " . $limit['start'] . "," . $limit['end'];
                            } else {
                                return $error_limit;
                                exit;
                            }
                        } else {
                            return $error_limit;
                            exit;
                        }
                    } else {
                        if (is_numeric($limit)) {
                            $sql .= " LIMIT " . $limit;
                        } else {
                            return $error_limit;
                            exit;
                        }
                    }
                }
                $rows = $this->query_select_table($table_name, $column, $sql);
                if ($count == 'count') {
                    return count($rows) ? count($rows) : 0;
                } else {
                    return $rows;
                }
            }
        }
    }

    function select_table_where_data_limit_offset($table_name = null, $where_column_name, $where_column_value, $limit, $offset, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name in select_table_where_data_limit_offset()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "WHERE $where_column_name = $where_column_value ORDER BY $order $sort LIMIT $limit OFFSET $offset";
                $this->query_select_table($table_name, '*', $sql);
            }
        }
    }

    public function select_table_row_data_by_where($table_name =  null, $where = null)
    {
        /**
         * Lấy dữ liệu của 1 dòng trong bảng, where có input là json
         * Ví dụ: {% set where = {"id": "1"} %}
         */
        if (!$table_name || !$where) {
            return 'There is not table_name or where in select_row_data_by_where()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "SELECT * FROM `$table_name` WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= "$key = '$value' AND ";
                }
                $sql = substr($sql, 0, -5);
                $result = $this->db->query($sql);
                return $result->fetchArray(SQLITE3_ASSOC);
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    public function select_table_row_data($table_name =  null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in select_table_row_data()';
        } else {
            if ($this->table_exists($table_name)) {
                $where = [$column_name => $column_value];
                return $this->select_table_row_data_by_where($table_name, $where);
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    public function select_table_where_array_data($table_name = null, $where = null, $order = null, $sort = null)
    {
        /**
         * Lấy danh sách dữ liệu của bảng, where có input là json
         * Ví dụ: {% set where = {"id": "1"} %}
         */
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name in select_table_where_array_data()';
        } else {
            if ($this->table_exists($table_name)) {
                $sql = "SELECT * FROM `$table_name` WHERE ";
                foreach ($where as $key => $value) {
                    $sql .= "$key = '$value' AND ";
                }
                $sql = substr($sql, 0, -5);
                $sql .= " ORDER BY `$order` $sort";
                $result = $this->db->query($sql);
                $data = [];
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return 'Table `' . $table_name . '` does not exist';
            }
        }
    }

    function select_table_where_data($table_name = null, $where_column_name = null, $where_column_value = null, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or where_column_name or where_column_value in select_table_where_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $where = [$where_column_name => $where_column_value];
                $rows = $this->select_table_where_array_data($table_name, $where, $order, $sort);
                $total = ['total' => $rows ? count($rows) : 0];
                return array_merge($total, $rows);
            }
        }
    }

    /*
    -----------------------------------------------------------------
    SQL queries related to the search
    -----------------------------------------------------------------
    */

    function search_key_in_table($table_name = null, $column = null, $string = null, $random = null)
    {
        if (!$table_name || !$column || !$string) {
            return 'There is not table_name or column or string (or keyword) in search_key_in_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = explode(' ', $string);
                $array = array_unique($array);
                $count = count($array);
                if ($count > 1) {
                    foreach ($array as $key => $value) {
                        $array[$key] = '%' . $value . '%';
                    }
                    $sql = "WHERE `$column` LIKE '" . implode("' OR `title` LIKE '", $array) . "'";
                } else {
                    $sql = "WHERE `$column` LIKE '%" . $string . "%'";
                }
                if (is_numeric($random)) {
                    $sql .= " ORDER BY RAND() LIMIT $random";
                }
                $this->query_select_table($table_name, '*', $sql);
            }
        }
    }

    /*
    -----------------------------------------------------------------
    Manipulation of Cookies
    -----------------------------------------------------------------
    */

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
}
