<?php
/*********************
 * User: Vitaly
 * Date: 25.05.12
 *********************/
class DebugService
{
    private static $debugUnit;
    private $startTime;
    private $dbQueries;

    function __construct()
    {
        $this->startTime = microtime(true);
    }

    // добавляет строку SQL запроса

    function addQuery($query)
    {
        $this->dbQueries[] = $query;
    }

    // возвращает время с момента запуска скрипта

    function getTime()
    {
        return microtime(true) - $this->startTime;
    }

    // возвращает массив всех выполнявшихся в процессе работы скрипта запросов

    function getQueries()
    {
        return $this->dbQueries;
    }

    function disable()
    {
        self::$debugUnit = null;
    }

    // возвращает количество использованных скриптом байт

    function getMemoryUsage()
    {
        return memory_get_usage();
    }

    // возвращает true в случае, если скрипт выполняется в режиме Debug
    // противном случае false

    public static function isEnabled()
    {
        return (!empty(DebugService::$debugUnit));
    }

    // singleton

    public static function instance()
    {
        if(DebugService::$debugUnit == null)
            DebugService::$debugUnit = new DebugService();
        return DebugService::$debugUnit;
    }
}
