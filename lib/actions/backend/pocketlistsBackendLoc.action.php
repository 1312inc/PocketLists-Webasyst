<?php

/**
 * A list of localized strings to use in JS.
 */
class pocketlistsBackendLocAction extends waViewAction
{
    public function execute()
    {
        $strings = array(
            'Show all %d completed to-dos'
        );

        // Application locale strings
        foreach(array() as $s) {
            $strings[$s] = _w($s);
        }

        $this->view->assign('strings', $strings ? $strings : new stdClass()); // stdClass is used to show {} instead of [] when there's no strings

        $this->getResponse()->addHeader('Content-Type', 'text/javascript; charset=utf-8');
    }
}
