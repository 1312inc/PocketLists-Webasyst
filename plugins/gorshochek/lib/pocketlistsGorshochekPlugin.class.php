<?php

class pocketlistsGorshochekPlugin extends waPlugin
{
    public function pocketlistsGorshochekPluginHookHandlerSettings()
    {
        $li = <<<STR
<li>
    <a href="#/settings/plugin/gorshochek/cook">
        <i class="icon size-20" style="background-image: url('/wa-apps/pocketlists/plugins/gorshochek/img/gorshochek.png'); background-size: 16px 16px;"></i>
        <span>Горшочек</span>
    </a>
</li>
STR;


        return ['sidebar_li' => $li];
    }
}
