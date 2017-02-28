<?php

$ids = pocketlistsRBAC::getAccessContacts();
$us = new pocketlistsUserSettings();

foreach ($ids as $id) {
    $us->setContact($id);

    $us->set('created_by_others_in_shared_on', 1);
    $us->set('created_by_me_in_shared_on', 1);
    $us->set('created_by_me_in_shared', pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST);
    $us->set('created_by_others_in_shared', pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS);
}
