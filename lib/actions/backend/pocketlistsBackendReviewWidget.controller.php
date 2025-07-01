<?php
class pocketlistsBackendReviewWidgetController extends waViewAction
{
    public function execute()
    {
        $widget = '';
        $widget_id = waRequest::get('id', '', waRequest::TYPE_STRING_TRIM);
        $ui = waRequest::get('ui', '2.0', waRequest::TYPE_STRING_TRIM);
        $ui = (in_array($ui, array('1.3', '2.0')) ? $ui : '2.0');
        waRequest::setParam('force_ui_version', $ui);

        $installer_app = wa()->getView()->getHelper()->installer;
        if (!empty($widget_id) && $installer_app && method_exists($installer_app, 'reviewWidget')) {
            $widget = $installer_app->reviewWidget($widget_id);
        }

        echo $widget;
    }
}
