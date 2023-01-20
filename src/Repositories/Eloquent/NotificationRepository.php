<?php

namespace Tripteki\Notification\Repositories\Eloquent;

use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Tripteki\Notification\Events\Marking;
use Tripteki\Notification\Events\Marked;
use Tripteki\Notification\Events\Clearing;
use Tripteki\Notification\Events\Cleared;
use Tripteki\Repository\AbstractRepository;
use Tripteki\Notification\Contracts\Repository\INotificationRepository;
use Tripteki\RequestResponseQuery\QueryBuilder;

class NotificationRepository extends AbstractRepository implements INotificationRepository
{
    /**
     * @param array $querystring|[]
     * @return mixed
     */
    public function all($querystring = [])
    {
        $querystringed =
        [
            "type" => $querystring["type"] ?? request()->query("type", null),
            "limit" => $querystring["limit"] ?? request()->query("limit", 10),
            "current_page" => request()->query("current_page", 1),
        ];
        extract($querystringed);

        $content = $this->user;
        $notification = $content->notifications();

        if ($type) {

            $notification = $notification->where("type", $type);
        }

        $content = $content->setRelation("notifications",
            QueryBuilder::for($notification)->
            defaultSort("-read_at")->
            allowedSorts([ "id", "type", "read_at", "updated_at", ])->
            allowedFilters([ "id", "type", "read_at", "updated_at", ])->
            paginate($limit, [ "*", ], "current_page", $current_page)->appends(empty($querystring) ? request()->query() : $querystringed));
        $content = $content->loadCount(
        [
            "notifications",
            "unreadNotifications",
            "readNotifications",
        ]);

        return collect($content)->only([ "notifications_count", "unread_notifications_count", "read_notifications_count", "notifications", ]);
    }

    /**
     * @param int|string|null $identifier
     * @param array $data
     * @return mixed
     */
    public function update($identifier, $data)
    {
        $content = $this->user;

        if ($identifier != null) $content = $content->unreadNotifications()->where("id", $identifier)->firstOrFail();
        else $content = $content->unreadNotifications;

        DB::beginTransaction();

        try {

            $content->markAsRead();

            DB::commit();

            event(new Marked($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string|null $uuid
     * @param array $data
     * @return mixed
     */
    public function markAsRead($uuid = null)
    {
        return $this->update($uuid, null);
    }

    /**
     * @param int|string|null $identifier
     * @return mixed
     */
    public function delete($identifier)
    {
        $content = $this->user;

        if ($identifier != null) $content = $content->notifications()->where("id", $identifier)->firstOrFail();
        else $content = $content->notifications();

        DB::beginTransaction();

        try {

            $content->delete();

            DB::commit();

            event(new Cleared($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string|null $uuid
     * @return mixed
     */
    public function clear($uuid = null)
    {
        return $this->delete($uuid);
    }
};
