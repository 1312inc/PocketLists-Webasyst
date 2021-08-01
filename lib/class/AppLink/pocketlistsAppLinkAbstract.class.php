<?php

/**
 * Class pocketlistsAppLinkAbstract
 */
abstract class pocketlistsAppLinkAbstract
{
    const LIMIT = 10;

    protected $enabled = null;

    protected $info = [];

    /**
     * @var waSmarty3View
     */
    protected $view;

    /**
     * @return waSmarty3View
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = new waSmarty3View(wa());
        }

        return $this->view;
    }

    /**
     * @param string $term
     * @param array  $params
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($term, $params = [], $count = 10)
    {
        $result = [];

        foreach ($this->getTypes() as $entityType) {
            $method = sprintf('autocomplete%s', ucfirst($entityType));
            if (method_exists($this, $method)) {
                $entities = $this->$method($term, $params, $count);
                if ($entities) {
                    $result[] = [
                        'app'      => $this->getApp(),
                        'type'     => $entityType,
                        'entities' => $entities,
                        'params'   => $params
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @param pocketlistsItemLinkModel $itemLinkModel
     *
     * @return $this
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $itemLinkModel)
    {
        $this->itemLinkModel = $itemLinkModel;

        return $this;
    }

    /**
     * pocketlistsItemLink constructor.
     */
    public function __construct()
    {
        try {
            wa($this->getApp());

            $this->info = wa()->getAppInfo($this->getApp());
            $this->enabled = true;
        } catch (waException $ex) {
            $this->enabled = false;
        }
    }

    /**
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countItems()
    {
        $app = $this->getApp();
        $key = "countItems_{$app}";

        $count = wa()->getCache()->get($key);
        if ($count === null) {
            $count = new pocketlistsItemsCount(pl2()->getModel(pocketlistsItem::class)->getCountForApp($app));
            wa()->getCache()->set($key, $count, 60);
        }

        return $count;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->enabled === null) {
            $this->enabled = wa()->appExists($this->getApp());
        }

        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getBannerHtml()
    {
        $template = wa()->getAppPath(
            sprintf(
                'templates/include%s/item_linked_entities/%s.banner.html',
                pl2()->getUI2TemplatePath(),
                $this->getApp()
            ),
            pocketlistsHelper::APP_ID
        );

        $render = '';

        if ($this->isEnabled() && file_exists($template)) {
            $this->getView()->assign('app', $this);
            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->info['name'];
    }

    public function getAppIcon()
    {
        return sprintf(
            '<i class="icon16 pl-wa-app-icon" style="background-image: url(%s%s); background-size: 16px 16px;"></i>',
            wa()->getRootUrl(),
            $this->info['icon'][48]
        );
    }

    /**
     * @param pocketlistsItemLink $itemLink
     * @param array               $params
     *
     * @return string
     * @throws waException
     */
    public function renderPreview(pocketlistsItemLink $itemLink, $params = [])
    {
        if (!$itemLink->getEntityId() || !$itemLink->getEntityType()) {
            return '';
        }

        $template = wa()->getAppPath(
            sprintf(
                'templates/include%s/item_linked_entities/%s.%s.preview.html',
                pl2()->getUI2TemplatePath(),
                $this->getApp(),
                $itemLink->getEntityType()
            ),
            pocketlistsHelper::APP_ID
        );

        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_ITEM_RENDER_LINKED, $this);
        pl2()->waDispatchEvent($event);
        $pluginRender = $event->getResponse();
        $render = !empty($pluginRender['preview']) ? $pluginRender['preview'] : '';

        if ($this->isEnabled() && !$render && file_exists($template)) {
            if (!$itemLink->getAppEntity()) {
                return '';
            }

            $this->getView()->clearAllAssign();
            $vars = [
                'link'   => $itemLink,
                'extra'  => $this->getExtraData($itemLink),
                'params' => $params,
            ];
            $this->getView()->assign($vars);

            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink)
    {
        $template = wa()->getAppPath(
            sprintf(
                'templates/include%s/item_linked_entities/%s.%s.autocomplete.html',
                pl2()->getUI2TemplatePath(),
                $this->getApp(),
                $itemLink->getEntityType()
            ),
            pocketlistsHelper::APP_ID
        );

//        $render = wa()->event('item.render_autocomplete', $this);

        if (file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $itemLink);

            $render = $this->getView()->fetch($template);
        } else {
            $render = (string)$this;
        }

        return $render;
    }
}
