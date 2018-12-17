<?php

class pocketlistsTodoAction extends pocketlistsViewAction
{
    public function execute()
    {
        $calendar_html = wao(new pocketlistsTodoMonthAction())->display();
        $this->view->assign('calendar_html', $calendar_html);
        $this->view->assign('user', $this->user);
    }
}
