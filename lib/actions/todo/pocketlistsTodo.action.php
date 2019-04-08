<?php

/**
 * Class pocketlistsTodoAction
 */
class pocketlistsTodoAction extends pocketlistsViewAction
{
    public function execute()
    {
        $calendar_html = (new pocketlistsTodoMonthAction())->display();
        $this->view->assign(
            [
                'calendar_html' => $calendar_html,
                'user'          => $this->user,
            ]
        );
    }
}
