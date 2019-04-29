<?php

/**
 * Class pocketlistsTodoAction
 */
class pocketlistsTodoAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     */
    public function runAction($params = null)
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
