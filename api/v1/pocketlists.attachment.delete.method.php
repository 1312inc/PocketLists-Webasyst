<?php

class pocketlistsAttachmentDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $attachment_ids = $this->get('id');

        if (empty($attachment_ids)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id', 400));
        } elseif (!is_array($attachment_ids)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
        }

        /** @var pocketlistsAttachmentFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsAttachment::class);
        $attachment_ids = array_unique($attachment_ids);
        $attachments = $plf->findByFields('id', $attachment_ids, true);
        foreach ($attachments as $_attachment) {
            $plf->delete($_attachment);
        }
    }
}
