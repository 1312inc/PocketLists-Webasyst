<?php

class pocketlistsTodosWidget extends waWidget
{
    public function defaultAction()
    {
        $this->display([
            'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID)
        ]);
    }
}
