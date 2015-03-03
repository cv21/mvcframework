<?php
/**
 * @author Vitaly
 * Date: 27.04.12
 */
class CheckService
{
    // призван исключать sql- и xss-инъекции

    static function checkArgs(&$request)
    {
        $args = array();
        foreach($request as $key=>&$value)
        {

            // в случае если принимаем массив - приводим все его значения к целому типу
            // т.к массив нужен здесь только для ajax'а, а в нем мы работаем только с id
            // иначе убираем все что можем

            if(is_array($value))
                foreach($value as $vals)
                    $args[$key][] = (int)$vals;
            else
                $args[$key] = mysql_real_escape_string(htmlspecialchars(str_replace('UNION', '', $value)));
        }
        return $args;
    }
}
