<?php

/**
 * Class pockstlistsProPluginAutomationSaveRuleController
 */
class pocketlistsProPluginAutomationSaveRuleController extends pocketlistsJsonController
{
    public function execute()
    {
        $ruleData = waRequest::post('automation');
    }
}
