<?php

class pocketlistsLinkDeterminer
{
    protected $link;

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return pocketlistsLinkDeterminer
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param $link
     *
     * @return array|false
     */
    public function getAppTypeId($link)
    {
        $this->setLink($link);

        $apps = [
            'shop' => [
                'order' => [
                    '.*/shop/\?action=orders.*id=(\d+).*',
                    '.*/shop/\?action=orders#/orders/edit/(\d+)/',
                ],
            ],
        ];

        foreach ($apps as $app => $types) {
            foreach ($types as $type => $regexs) {
                foreach ($regexs as $regex) {
                    if (preg_match('|'.$regex.'|iu', $link, $matches)) {
                        return [
                            'app'         => $app,
                            'entity_type' => $type,
                            'entity_id'   => $matches[1],
                        ];
                    }
                }
            }
        }

        return false;
    }


    /**
     * @param $app
     *
     * @return string
     */
    public function getAppIcon($app)
    {
        switch ($app) {
            case 'shop':
                return '<i class="icon16" style="background-image: url(https://www.shop-script.ru/favicon.ico); background-size: 16px 16px;"></i>';
        }

        return '';
    }
}
