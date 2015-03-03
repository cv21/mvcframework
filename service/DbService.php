<?php
/**
 * @author Vitaly
 * Date: 24.04.12
 */

/*
 * Класс, работающий с СУБД
 */

class DbService
{
    private static $dbUnit; // singleton
    private $connection;

    function __construct()
    {
        $this->connection = mysql_pconnect(DB_SERVER, DB_USER, DB_PASSWORD);
        mysql_query("USE `" . DB_NAME . "`");
    }

    // вставляет ассоциативный массив $data в таблицу $tablename
    // возвращает id вставленной строки, или false

    function insert($tablename, $data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $query = "INSERT INTO `" . $tablename . "` (`" . implode("`, `", $fields) . "`) VALUES ('" . implode("', '", $values) . "')";
        if(DebugService::isEnabled())
        {
            DebugService::instance()->addQuery($query);
        }
        $result = mysql_query($query);
        if($result!=false)
            return mysql_insert_id($this->connection);
        else
            return false;
    }

    // делает запрос на выборку всех элементов из таблицы $tablename
    // $where     - (которые соответствуют $where)
    // $descOrder - (сортировка в обратном порядке)

    function select($tablename, $where = null, $descOreder = false, $leftLimit=null, $rightLimit = null)
    {
        $query = "SELECT * FROM `" . $tablename . "`" .
                  ((!empty($where)) ? " WHERE " . $where : '') .
                  (($descOreder) ? " ORDER BY time DESC " : '') .
                  ((!is_null($leftLimit)) ? (" LIMIT " . $leftLimit) : '') .
                  ((!is_null($rightLimit)) ? "," . $rightLimit : '');
        if(DebugService::isEnabled())
        {
            DebugService::instance()->addQuery($query);
        }
        return mysql_query($query);
    }

    // "расширенный" метод select

    function extSelect($columns, $tablename, $postQuery)
    {
        $query = "SELECT " . $columns . " FROM `" . $tablename . "`" . $postQuery;
        if(DebugService::isEnabled())
        {
            DebugService::instance()->addQuery($query);
        }
        return mysql_query($query);
    }

    //обновляет элементы таблицы $tablename

    function update($tablename, $set, $where = null)
    {
        $query = "UPDATE `" . $tablename . "` SET " . $set . ((!empty($where)) ? " WHERE " . $where : "");
        if(DebugService::isEnabled())
        {
            DebugService::instance()->addQuery($query);
        }
        return mysql_query($query);
    }

    // удаляет элементы соответствующие $where из таблицы $tablename

    function delete($tablename, $where)
    {
        $query = "DELETE FROM `" . $tablename . "` WHERE " . $where;
        if(DebugService::isEnabled())
        {
            DebugService::instance()->addQuery($query);
        }
        return mysql_query($query);
    }

    // получает массив ассоциативных массивов (строк) из ресурса

    function fetchAssoc($res)
    {
        $ret = array();
        while($row = mysql_fetch_assoc($res))
            $ret[] = $row;
        return $ret;
    }

    // получает ассоциативный массив (1 строку из ресурса)

    function fetchAssocOnce($res)
    {
        return mysql_fetch_assoc($res);
    }

    // получает singleton

    public static function instance()
    {
        if(DbService::$dbUnit == null)
            DbService::$dbUnit = new DbService();
        return DbService::$dbUnit;
    }
}
