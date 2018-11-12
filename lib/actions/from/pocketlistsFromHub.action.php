<?php
class tasksFromHubAction extends waViewAction
{
    public function execute()
    {
        wa('hub');
        $topic_id = waRequest::request('id', 0, 'int');

        $topic_model = new hubTopicModel();
        $topic = $topic_model->getById($topic_id);
        if (!$topic) {
            throw new waException(_w('Not found'), 404);
        }

        $task = new tasksTask();
        $task['name'] = $topic['title'];
        $task['text'] = strip_tags($topic['content'])."\n\n".sprintf('[%s](%s)',
            // Link text
            sprintf_wp('Original topic: %s',
                htmlspecialchars(str_replace(array('[', ']'), ' ', $topic['title']))
            ),
            // Link href
            rtrim(wa()->getRootUrl(true), '/').'/'.ltrim(wa()->getAppUrl('hub'), '/').'#/topic/'.$topic_id.'/'
        );
        //$task['params'] = array( // !!! makes sense when/if tasks will actually have params implemented
        //    'hub_topic_id' => $topic_id,
        //);

        $this->setTemplate(wa('tasks')->getAppPath('templates/actions/from/Reloader.html'));
        $this->view->assign(array(
            'task' => $task,
        ));
    }
}
