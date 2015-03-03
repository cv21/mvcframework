<?php

/**
 * @author Vitaly
 * @Date: 24.04.12
 */

class View
{
    private $content;
    private $header;
    private $footer;
    private $links;
    private $title;

    function draw()
    {
        include $this->header;
        include $this->content;
        include $this->footer;
    }

    //устанавливает шаблоны верхней, центральной и нижней частей
    //по умолчанию верхняя и нижняя части имеют шаблоны "header.phtml" и "footer.phtml"

    function setStructure($content, $header = 'header', $footer = 'footer')
    {
        $this->header  = VIEW_PATH . $header  . '.phtml';
        $this->content = VIEW_PATH . $content . '.phtml';
        $this->footer  = VIEW_PATH . $footer  . '.phtml';
        return $this;
    }

    function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }

    function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    function setFooter($footer)
    {
        $this->footer = $footer;
        return $this;
    }

    function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    function getLinks()
    {
        return $this->links;
    }

    function getHeader()
    {
        return $this->header;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getFooter()
    {
        return $this->footer;
    }

    function getContent()
    {
        return $this->content;
    }
}
