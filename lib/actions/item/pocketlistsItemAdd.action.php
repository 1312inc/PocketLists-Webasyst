<?php

/**
 * Class pocketlistsItemAddAction
 */
class pocketlistsItemAddAction extends pocketlistsViewItemAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     */
    public function runAction($params = null)
    {
        if (isset($this->params['teammate']) && $this->params['teammate'] instanceof pocketlistsContact) {
            $this->view->assign('assign_contact_photo', $this->params['teammate']->getUserpic());
        }
        $externalApp = $this->params['externalApp'] ?? null;

        /**
         * UI hook in item add compact mode
         *
         * @event backend_item_add.compact
         *
         * @param pocketlistsEventInterface $event Event with external flag in params array (external means called from non-pocketlists app)
         *
         * @return string html output
         */
        $event = new pocketlistsEvent(
            pocketlistsEventStorage::WA_BACKEND_ITEM_ADD_COMPACT,
            null,
            ['external' => !empty($this->params['external']), 'externalApp' => $externalApp]
        );
        $eventResult = pl2()->waDispatchEvent($event);
        $this->view->assign('backend_item_add', $eventResult);

        $template = pl2()->getUI2TemplatePath('templates/actions%s/item/ItemAdd.html', $externalApp);

        $this->setTemplate($template);
    }
}
