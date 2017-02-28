<?php

class pocketlistsTodoAction extends waViewAction
{
    public function execute()
    {
        $calendar_html = wao(new pocketlistsTodoMonthAction())->display();
        $this->view->assign('calendar_html', $calendar_html);
    }
}
