<?php

class pocketlistsUsersGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (!pocketlistsRBAC::canAssign()) {
            throw new pocketlistsApiException(_w('Access denied'), 403);
        }
        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
            if (empty($ids)) {
                throw new pocketlistsApiException(_w('Users not found'), 404);
            }
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($offset < 0) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        list($result, $count) = $this->getTeammates($ids, $offset, $limit);

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $count
        ];
        $this->response['data'] = $this->responseListWrapper(
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
                'email',
                'locale',
                'items_info'
            ], [
                'id' => 'int',
                'me' => 'bool',
                'exists' => 'bool',
                'last_activity' => 'datetime',
            ]
        );
    }
}
