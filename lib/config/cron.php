<?php

return [
    'recap_mail'=>'42 7 * * * /usr/bin/php -q '.wa(pocketlistsHelper::APP_ID)->getConfig()->getPath('root').DIRECTORY_SEPARATOR.'cli.php pocketlists Recap',
    'apply_delayed_automations' => sprintf(
        '*/10 * * * * /usr/bin/php -q %s%scli.php pocketlists ApplyDelayedAutomations',
        wa()->getConfig()->getPath('root'),
        DIRECTORY_SEPARATOR
    ),
];
