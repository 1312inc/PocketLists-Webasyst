<?php

class pocketlistsUserGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $result = [];
        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contactFactory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);
        $root_url = rtrim(wa()->getRootUrl(true), '/');

        /** @var pocketlistsContact $_teammate */
        foreach ((array) $teammates as $_teammate) {
            /** @var pocketlistsItemsCount $items_info */
            $items_info = $_teammate->getItemsInfo();
            $result[] = [
                'id'              => $_teammate->getId(),
                'name'            => $_teammate->getName(),
                'username'        => $_teammate->getUsername(),
                'photo_url'       => $root_url.$_teammate->getPhotoUrl(),
                'user_pic'        => $root_url.$_teammate->getUserPic(),
                'status'          => $_teammate->getStatus(),
                'team_role'       => $_teammate->getTeamrole(),
                'login'           => $_teammate->getLogin(),
                'me'              => $_teammate->isMe(),
                'exists'          => $_teammate->isExists(),
                'last_activity'   => $_teammate->getLastActivity(),
                'email'           => $_teammate->getEmail(),
                'locale'          => $_teammate->getLocale(),
                'list_activities' => array_values($_teammate->getListActivities()),
                'items_info'      => [
                    'count'              => $items_info->getCount(),
                    'count_priority'     => $items_info->getCountPriority(),
                    'max_priority'       => $items_info->getMaxPriority(),
                    'count_max_priority' => $items_info->getCountMaxPriority(),
                    'count_priorities'   => $items_info->getCountPriorities()
                ]
            ];
        }

        $this->response = [
            'offset' => 0,
            'limit'  => self::DEFAULT_LIMIT,
            'count'  => count($result),
            'data'   => $this->filterFields(
                $result,
                [
                    'id',
                    'name',
                    'username',
                    'photo_url',
                    'user_pic',
                    'status',
                    'team_role',
                    'login',
                    'me',
                    'exists',
                    'last_activity',
                    'list_activities',
                    'email',
                    'locale',
                    'items_info'
                ],
                [
                    'id' => 'int',
                    'me' => 'bool',
                    'exists' => 'bool',
                    'last_activity' => 'datetime',
                ]
            )
        ];
    }
}
