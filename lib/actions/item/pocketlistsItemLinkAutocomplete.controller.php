<?php

/**
 * Class pocketlistsItemLinkAutocompleteController
 */
class pocketlistsItemLinkAutocompleteController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $term = waRequest::get('term', '');
        $params = waRequest::get('params', waRequest::TYPE_ARRAY, []);

        if (!$term) {
            return;
        }

        $result = (new pocketlistsItemLinkAutocompleter())
            ->process($term, $params)
            ->getResult();

        $this->response = $result;
    }
}
