<?php

/**
 * Class pocketlistsCommentsAction
 */
class pocketlistsCommentsAction extends pocketlistsViewAction
{
    const DEFAULT_OFFSET = 50;

    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        }

        $last_activity = pocketlistsActivity::getUserActivity();
        $offset = waRequest::get('offset', 0);

        /** @var pocketlistsCommentFactory $commentFactory */
        $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);
        $comments = $commentFactory->findForPage($offset * self::DEFAULT_OFFSET, self::DEFAULT_OFFSET);

        /** @var pocketlistsComment $comment */
        foreach ($comments as $comment) {
            $comment->setRecentlyCreated($last_activity);
        }

        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->setTemplate(wa()->getAppPath(sprintf('templates/actions%s/comments/Comments.html', pl2()->getUI2TemplatePath())));

        $this->view->assign('comments', $comments);
    }
}
