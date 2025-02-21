<?php

class pocketlistsProPluginSettings
{
    /**
     * @var waAppSettingsModel
     */
    private $model;

    /**
     * @var array
     */
    private $settings;

    public function __construct()
    {
        $this->model = new waAppSettingsModel();
    }

    public function updateSettings(array $settings): bool
    {
        $this->settings = null;
        $settings = array_merge($this->getSettings(), $settings);

        return $this->model->set(
            [pocketlistsHelper::APP_ID, 'pro'],
            'settings',
            json_encode($settings, JSON_UNESCAPED_UNICODE || JSON_UNESCAPED_SLASHES)
        );
    }

    public function getSettings(): array
    {
        if ($this->settings === null) {
            $settings = $this->model->get([pocketlistsHelper::APP_ID, 'pro'], 'settings', null);

            $this->settings = !is_null($settings) ? json_decode($settings, true) : [];
        }

        return $this->settings;
    }

    public function isRunLogs(): bool
    {
        return !empty($this->getSettings()['log_runs']);
    }
}
