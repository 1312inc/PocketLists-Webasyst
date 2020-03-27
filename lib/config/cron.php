<?php

return array(
    'recap_mail'=>'42 7 * * * /usr/bin/php -q '.wa(pocketlistsHelper::APP_ID)->getConfig()->getPath('root').DIRECTORY_SEPARATOR.'cli.php pocketlists Recap',
);
