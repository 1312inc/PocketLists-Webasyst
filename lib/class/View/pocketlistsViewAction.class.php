<?php

/**
 * Class pocketlistsViewAction
 */
abstract class pocketlistsViewAction extends waViewAction
{
    use pocketlistsViewTrait;

    /**
     * @param null|array $params
     *
     * @return mixed
     */
    abstract public function runAction($params = null);

    /**
     * @param null $params
     *
     * @throws waException
     */
    public function execute($params = null)
    {
        try {
            $this->view->assign(pl2()->getDefaultViewVars());

            wa()->getStorage()->close();

            $this->runAction($params);
        } catch (pocketlistsException $ex) {
            $this->view->assign(
                'error',
                [
                    'code'    => $ex->getCode(),
                    'message' => $ex->getMessage(),
                ]
            );

            $this->setTemplate(wa()->getAppPath(pl2()->getUI2TemplatePath('templates/include%s/error.html')));
        }
    }
}
