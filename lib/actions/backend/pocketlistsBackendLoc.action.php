<?php

/**
 * A list of localized strings to use in JS.
 */
class pocketlistsBackendLocAction extends waViewAction
{
    public function execute()
    {
        $strings = array();

        // Application locale strings
        foreach (array(
                     'Show all %d completed to-dos',
                     'This to-do is assigned to another person. Are you sure you want to mark this item as complete?',
                     'ALL TO-DO LISTS AND ITEMS FROM THIS PROJECT WILL BE DELETED PERMANENTLY. ARE YOU SURE?',
                     'My personal settings',
                     'Close',
                     'None',
                     'Logbook',
                     'Delete',
                     'delete',
                     'Are you sure you want to delete this file?',
                     'This will permanently delete this comment. Are you sure?',
                 ) as $s) {
            $strings[$s] = _w($s);
        }

        $this->view->assign('strings',
            $strings ? $strings : new stdClass()); // stdClass is used to show {} instead of [] when there's no strings

        $this->getResponse()->addHeader('Content-Type', 'text/javascript; charset=utf-8');
    }
}
