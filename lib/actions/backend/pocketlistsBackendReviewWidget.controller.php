<?php
class pocketlistsBackendReviewWidgetController extends waViewAction
{
    public function execute()
    {
        $widget = '';
        $widget_id = waRequest::get('id', '', waRequest::TYPE_STRING_TRIM);

        $installer_app = wa()->getView()->getHelper()->installer;
        if (!empty($widget_id) && $installer_app && method_exists($installer_app, 'reviewWidget')) {
            $widget = $installer_app->reviewWidget($widget_id);
        }

        echo $widget;
    }
}
