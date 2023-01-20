<?php

namespace Tripteki\Notification\Repositories\Eloquent\Admin;

use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification as NotificationModel;
use Tripteki\Notification\Contracts\Repository\Admin\INotificationRepository;
use Tripteki\RequestResponseQuery\QueryBuilder;

class NotificationRepository implements INotificationRepository
{
    /**
     * @param array $querystring|[]
     * @return mixed
     */
    public function all($querystring = [])
    {
        $querystringed =
        [
            "limit" => $querystring["limit"] ?? request()->query("limit", 10),
            "current_page" => $querystring["current_page"] ?? request()->query("current_page", 1),
        ];
        extract($querystringed);

        $content = QueryBuilder::for(NotificationModel::class)->
        defaultSort("-read_at")->
        allowedSorts([ "id", "type", "read_at", "updated_at", ])->
        allowedFilters([ "id", "type", "read_at", "updated_at", ])->
        paginate($limit, [ "*", ], "current_page", $current_page)->appends(empty($querystring) ? request()->query() : $querystringed);

        return $content;
    }

    /**
     * @param int|string $identifier
     * @param array $querystring|[]
     * @return mixed
     */
    public function get($identifier, $querystring = [])
    {
        $content = NotificationModel::findOrFail($identifier);

        return $content;
    }
};
