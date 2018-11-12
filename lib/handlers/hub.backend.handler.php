<?php
class pocketlistsHubBackendHandler extends waEventHandler
{
    public function execute(&$params)
    {
        if (!wa()->getUser()->getRights('pocketlists', 'backend')) {
            return null;
        }

        return array(
            'head' => $this->getHtml(),
        );
    }

    protected function getHtml() {
        $uniqid = str_replace('.', '-', uniqid('s', true));
        $menuname = htmlspecialchars(_w('Create task'));
        $pocketlists_url = wa()->getAppUrl('pocketlists');
        $wa_url = wa()->getRootUrl();
        return <<<EOF

            <!-- begin output from pocketlistsHubBackendHandler -->
            <ul style="display:none" id="{$uniqid}">
                <li><a href="{$pocketlists_url}#/from/hub/%TASK_ID%/"><i class="icon16" style="background-image:url('{$wa_url}wa-apps/pocketlists/img/pocketlists24.png');background-size:16px 16px"></i>{$menuname}</a></li>
            </ul>
            <script>(function() {
                var wrapper = $('#{$uniqid}');
                var li = wrapper.children('li').detach();
                wrapper.remove(); wrapper = null;
                $(window).on('wa_loaded', function() {
                    var matches = $.hub.helper.cleanHash().match(new RegExp('^#/topic/(\\\\d+)/$'));
                    if (matches) {
                        var cloned_li = li.clone().prependTo('#content .h-topic .details .float-right.block .menu-h');
                        var a = cloned_li.find('a')[0];
                        a.href = a.href.replace('%TASK_ID%', matches[1]);
                    }
                });
            })();</script>
            <!-- end output from pocketlistsHubBackendHandler -->
EOF;
    }
}

