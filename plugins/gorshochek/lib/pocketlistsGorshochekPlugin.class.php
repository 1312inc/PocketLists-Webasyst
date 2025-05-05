<?php

class pocketlistsGorshochekPlugin extends waPlugin
{
    public function pocketlistsGorshochekPluginHookHandlerSettings()
    {
        $wa_url = wa_url();
        $li = <<<STR
<li>
    <a href="#/settings/plugin/gorshochek/cook">
        <span class="icon"><i class="icon size-20" style="background-image: url('{$wa_url}wa-apps/pocketlists/plugins/gorshochek/img/gorshochek.png');"></i></span>
        <span>Горшочек</span>
    </a>
</li>
STR;


        return ['sidebar_li' => $li];
    }
}
