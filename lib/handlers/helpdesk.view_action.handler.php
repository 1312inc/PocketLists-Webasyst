<?php
class pocketlistsHelpdeskView_actionHandler extends waEventHandler
{
    public function execute(&$params)
    {
        if (!ifset($params['action']) instanceof helpdeskRequestsInfoAction) {
            return null;
        }
        if (!wa()->getUser()->getRights('pocketlists', 'backend')) {
            return null;
        }

        return $this->getHtml($params['action']->request_id);
    }

    protected function getHtml($request_id) {
        $uniqid = str_replace('.', '-', uniqid('s', true));
        $menuname = htmlspecialchars(_w('Create task from this'));
        $pocketlists_url = wa()->getAppUrl('pocketlists');
        $wa_url = wa()->getRootUrl();
        return <<<EOF

            <!-- begin output from pocketlistsHelpdeskView_actionHandler -->
            <ul style="display:none" id="{$uniqid}">
                <li><a href="{$pocketlists_url}#/from/helpdesk/{$request_id}/" target="_blank"><i class="icon16" style="background-image:url('{$wa_url}wa-apps/pocketlists/img/pocketlists24.png');background-size:16px 16px"></i>{$menuname}</a></li>
            </ul>
            <script>(function() {
                var wrapper = $('#{$uniqid}');
                var dropdown = $('#h-request-operations .menu-v');
                wrapper.children('li').insertBefore(dropdown.children('li.hr:last'));
                wrapper.remove();
            })();</script>
            <!-- end output from pocketlistsHelpdeskView_actionHandler -->
EOF;
    }
}
