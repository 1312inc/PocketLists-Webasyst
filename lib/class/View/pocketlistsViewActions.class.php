<?php

/**
 * Class pocketlistsViewAction
 */
abstract class pocketlistsViewActions extends waViewActions
{
    use pocketlistsViewTrait;

    /**
     * @param null $params
     */
    public function run($params = null)
    {
        try {
            parent::run($params);
        } catch (waException $ex) {
            $this->view->assign(
                'error',
                [
                    'code'    => $ex->getCode(),
                    'message' => $ex->getMessage(),
                ]
            );

            $this->setTemplate('templates/include/error.html');
        }
    }

}
