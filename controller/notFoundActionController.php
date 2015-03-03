<?php
/**
 * @author Vitaly
 * Date: 25.04.12
 */

/*
 * Обрабатывает данные в том случае, если какой-либо контроллер не найден
 */

class notFoundActionController extends ActionController
{
    function action()
    {
        $this->view->setStructure('notFound');
        $this->view->setTitle('Page not found');
        $this->view->draw();
    }
}
