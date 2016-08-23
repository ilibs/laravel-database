<?php
/**
 * Created by PhpStorm.
 * User: 蔡旭东 caixudong@verystar.cn
 * Date: 1/19/16 4:51 PM.
 */

namespace Very\Database;

use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;

class MysqlConnection extends IlluminateMySqlConnection
{
    public function selectLimit($sql, $limit, $limit_from = 0, $params = array())
    {
        $limit      = (int)$limit;
        $limit_from = (int)$limit_from;
        $sql .= " limit $limit_from,$limit";
        return $this->getAll($sql, $params);
    }

    //返回数据以及是否含有下一页
    public function getPager($sql, $page, $num = 0, $params = array())
    {
        $limit      = (int)$num + 1;
        $limit_from = ($page - 1) * (int)$num;
        $sql .= " limit $limit_from,$limit";

        $ret   = $this->getAll($sql, $params);
        $count = count($ret);
        $count > $num && array_pop($ret);

        return array(
            'rs'   => $ret,
            'next' => $count > $num ? true : false,
        );
    }

    public function getAll($sql, $params = array())
    {
        $this->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $this->select($sql, $params);
        $this->setFetchMode(\PDO::FETCH_OBJ);
        return $result;
    }

    public function getOneAll($sql, $params = array(), $column_number = 0)
    {
        $this->setFetchMode(\PDO::FETCH_NUM);
        $all    = array();
        $result = $this->select($sql, $params);
        foreach ($result as $row) {
            $all[] = $row[$column_number];
        }
        $this->setFetchMode(\PDO::FETCH_OBJ);
        return $all;
    }

    public function getRow($sql, $params = array())
    {
        $this->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $this->selectOne($sql, $params);
        $this->setFetchMode(\PDO::FETCH_OBJ);
        return $result;
    }

    public function getOne($sql, $params = array(), $column_number = 0)
    {
        $this->setFetchMode(\PDO::FETCH_NUM);
        $result = $this->selectOne($sql, $params);
        $this->setFetchMode(\PDO::FETCH_OBJ);
        return isset($result[$column_number]) ? $result[$column_number] : false;
    }

    public function makeUpdate($table, $where, $params = array(), $where_field = array())
    {
        if (strpos($where, '=') < 1 || !$params) {
            return false;
        }

        $sets = array();
        foreach ($params as $k => $v) {
            if (!in_array($k, $where_field)) {
                $sets[] = ' `' . $k . '` =:' . $k;
            }
        }

        $set = implode(',', $sets);
        $sql = "update {$table} set $set where $where";

        $ret = $this->update($sql, $params);

        if ($this->getPdo()->errorCode() != '00000') {
            return false;
        }

        return $ret;
    }

    public function makeInsert($table, $params = array())
    {
        if (!$params) {
            return false;
        }

        $keys         = array_keys($params);
        $fileds       = '`' . implode('`,`', $keys) . '`';
        $filed_values = ':' . implode(',:', $keys);

        $sql = "insert into {$table}($fileds) values($filed_values)";
        $ret = $this->insert($sql, $params);
        if ($ret) {
            return $this->getPdo()->lastInsertId();
        } else {
            return false;
        }
    }

    public function makeDelete($table, $where, $params)
    {
        if (!$params) {
            return false;
        }

        $sql = "delete from {$table} where $where";
        $ret = $this->delete($sql, $params);

        if ($this->getPdo()->errorCode() != '00000') {
            return false;
        }

        return $ret;
    }
}