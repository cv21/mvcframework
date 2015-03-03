<?php
/**
 * @author Vitaly
 * Date: 03.05.12
 */

/*
 * Служит "проcлойкой" между контроллером и DbService
 * НЕ РАБОТАЕТ С КОНКРЕТНОЙ МОДЕЛЬЮ
 */

class AdminMapper
{
    private $connection; // хранит ссылку на singleton экземпляр DbServide

    function __construct()
    {
        $this->connection = DbService::instance();
    }

    // производит выборку по всем юзерам
    // возвращает массив с юзерами и их данными

    function selectAll()
    {
        return $this->connection->fetchAssoc($this->connection->select('users'));
    }
}
