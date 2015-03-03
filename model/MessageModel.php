<?php
/**
 * @author Vitaly
 * Date: 27.04.12
 */

class MessageModel
{
    private $id;
    private $from;
    private $to;
    private $text;
    private $readed;
    private $time;

    function __construct($id = null, $from = null, $to = null, $text = null, $time = null)
    {
        $this->setId($id)
             ->setFrom($from)
             ->setTo($to)
             ->setText($text)
             ->setTime($time);
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setReaded($readed)
    {
        $this->readed = $readed;
        return $this;
    }

    public function getReaded()
    {
        return $this->readed;
    }
}


