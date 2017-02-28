<?php

class pocketlistsFavoritesAction extends waViewAction
{
    public function execute()
    {
        $calendar_html = wao(new pocketlistsFavoritesMonthAction())->display();
        $this->view->assign('calendar_html', $calendar_html);
    }
}
