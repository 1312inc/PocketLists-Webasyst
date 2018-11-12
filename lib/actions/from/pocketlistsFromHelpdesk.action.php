<?php
class pocketlistsFromHelpdeskAction extends waViewAction
{
    public function execute()
    {
        wa('helpdesk');
        $request_id = waRequest::request('id', 0, 'int');
        $request_m = new helpdeskRequestModel();
        $request = $request_m->getById($request_id);

        $user_id = wa()->getUser()->getId();
        $us = new pocketlistsUserSettings();
        $list_id = 26;

        $item_model = new pocketlistsItemModel();
        $data['create_datetime'] = date("Y-m-d H:i:s");
        $data['due_date'] = waDateTime::date('Y-m-d', strtotime($data['create_datetime']));
        $data['list_id'] = $list_id;
        $data['contact_id'] = $user_id;
        $data['assigned_contact_id'] = $user_id;
        $data['name'] = pocketlistsNaturalInput::matchLinks($request['summary']);
        $data['note'] = pocketlistsNaturalInput::matchLinks(pocketlistsNaturalInput::removeTags($request['text'])) . "\n\n".sprintf('[%s](%s)',
            // Link text
            sprintf_wp('Original request: %s',
                htmlspecialchars(str_replace(array('[', ']'), ' ', $request['summary']))
            ),
            // Link href
            rtrim(wa()->getRootUrl(true), '/').'/'.ltrim(wa()->getAppUrl('helpdesk'), '/').'#/request/'.$request_id.'/'
        );
        $last_id = $item_model->insert($data, 1);

        $this->setTemplate(wa('pocketlists')->getAppPath('templates/actions/from/Reloader.html'));
        $this->view->assign('list_id', $list_id);
    }
}
