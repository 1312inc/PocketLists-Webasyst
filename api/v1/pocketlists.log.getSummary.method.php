<?php

class pocketlistsLogGetSummaryMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $starting_from = $this->get('starting_from');

        if (!isset($starting_from)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'starting_from'), 400);
        } elseif (!is_numeric($starting_from)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'starting_from'), 400);
        } elseif ($starting_from < 1) {
            throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
        }

        /** @var pocketlistsLogModel $log_model */
        $log_model = pl2()->getModel(pocketlistsLog::class);
        $log_summary = $log_model->query("
            SELECT entity_type, COUNT(entity_type) AS summ FROM pocketlists_log pl
            WHERE create_datetime >= s:starting_from
            GROUP BY entity_type
        ", [
            'starting_from' => date('Y-m-d H:i:s', $starting_from)
        ])->fetchAll('entity_type', 1);

        $this->response = [
            'starting_from' => (int) $starting_from,
            'ending_to'     => time(),
            'data'          => [
                'pockets'     => (int) ifempty($log_summary, pocketlistsLogContext::POCKET_ENTITY, 0),
                'lists'       => (int) ifempty($log_summary, pocketlistsLogContext::LIST_ENTITY, 0),
                'items'       => (int) ifempty($log_summary, pocketlistsLogContext::ITEM_ENTITY, 0),
                'comments'    => (int) ifempty($log_summary, pocketlistsLogContext::COMMENT_ENTITY, 0),
                'attachments' => (int) ifempty($log_summary, pocketlistsLogContext::ATTACHMENT_ENTITY, 0),
                'location'    => (int) ifempty($log_summary, pocketlistsLogContext::LOCATION_ENTITY, 0)
            ]
        ];
    }
}