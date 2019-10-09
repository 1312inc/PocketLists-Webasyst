<?php

/**
 * Class pocketlistsProPluginAutomationSettingsDto
 */
class pocketlistsProPluginAutomationSettingsDto
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var pocketlistsProPluginAutomation[]
     */
    public $automations = [];

    /**
     * @var array
     */
    public $automationRulesHtml = [];

    /**
     * @var array
     */
    public $automationActionsHtml = [];
}
