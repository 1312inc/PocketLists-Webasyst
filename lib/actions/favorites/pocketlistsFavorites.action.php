<?php

/**
 * Class pocketlistsFavoritesAction
 */
class pocketlistsFavoritesAction extends waViewAction
{
    public function execute()
    {
        $calendar_html = (new pocketlistsFavoritesMonthAction())->display(false);
        $this->view->assign('calendar_html', $calendar_html);
    }
}
