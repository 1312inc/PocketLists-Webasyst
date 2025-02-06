<?php

/**
 * Class pocketlistsAutomationSettingsDto
 */
class pocketlistsAutomationSettingsDto
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
     * @var pocketlistsAutomation[]
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
