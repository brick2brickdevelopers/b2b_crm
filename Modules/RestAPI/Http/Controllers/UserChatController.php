<?php

namespace Modules\RestAPI\Http\Controllers;

use App\User;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\UserChat;
use Modules\RestAPI\Http\Requests\UserChat\IndexRequest;
use Modules\RestAPI\Http\Requests\UserChat\CreateRequest;
use Modules\RestAPI\Http\Requests\UserChat\ShowRequest;
use Modules\RestAPI\Http\Requests\UserChat\UpdateRequest;
use Modules\RestAPI\Http\Requests\UserChat\DeleteRequest;

class UserChatController extends ApiBaseController
{

    protected $model = UserChat::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }

    public function storing(UserChat $userChat)
    {
        $userChat->from = api_user()->id;
        $userChat->to = request()->to;
        $userChat->user_one = api_user()->id;
        $userChat->user_id = request()->to;
        return $userChat;
    }

    public function userList()
    {
        app()->make($this->indexRequest);

        $query = $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering()
            ->addPaging()
            ->getQuery();

        $user = api_user();
        $userId = $user->id;

        $toUser = UserChat::where('from', $userId)->groupBy('to')->pluck('to')->toArray();
        $fromUser = UserChat::where('to', $userId)->groupBy('from')->pluck('from')->toArray();
        $toUser = array_merge($toUser, $fromUser);
        $toUser = array_unique($toUser);
        $userLists = User::whereIn('id', $toUser)->get()->toArray();

        foreach ($userLists as $key => $userList) {
            $receiverId = $userList['id'];
            $lastMessage = UserChat::where(function ($q) use ($userId, $receiverId) {
                $q->where('from', $receiverId)->where('to', $userId)
                    ->orwhere(function ($q) use ($receiverId, $userId) {
                        $q->where('to', $receiverId)
                            ->where('from', $userId);
                    });
            })
                ->orderBy('created_at', 'desc')
                ->first();
            $userLists[$key]['created_at'] = $lastMessage->created_at;
            $userLists[$key]['last_message'] = $lastMessage->message;
            $messageSeen = $lastMessage->to === $userId ? $lastMessage->message_seen : 'yes';
            $userLists[$key]['message_seen'] = $messageSeen;
            $userLists[$key]['message_time'] = $lastMessage->created_at->diffForHumans();
        }

        usort($userLists, array( $this, 'sortDate' ));

        // Load employees relation, if not loaded
        $relations = $query->getEagerLoads();
        $relationRequested = true;

        $query->setEagerLoads($relations);

        /** @var Collection $results */
        $results = $this->getResults();


        $results = $results->toArray();

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $userLists, $meta);
    }

    public function sortDate($a, $b)
    {
        if (strtotime($a['created_at']) === strtotime($b['created_at'])) {
            return 0;
        }
        return (strtotime($a['created_at']) > strtotime($b['created_at'])) ? -1 : 1;
    }

    public function messageSetting()
    {
        app()->make($this->indexRequest);

        $query = $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering()
            ->addPaging()
            ->getQuery();

        $messageSetting = \App\MessageSetting::first()->toArray();

        // Load employees relation, if not loaded
        $relations = $query->getEagerLoads();
        $relationRequested = true;

        $query->setEagerLoads($relations);

        /** @var Collection $results */
        $results = $this->getResults();


        $results = $results->toArray();

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $messageSetting, $meta);
    }

    public function getMessages($toUserId)
    {
        app()->make($this->indexRequest);

        $query = $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering()
            ->addPaging()
            ->getQuery();

        $user = api_user();
        $userId = $user->id;

        $query->where(function ($q) use ($userId, $toUserId) {
            $q->where('from', $toUserId)->where('to', $userId)
                ->orwhere(function ($q) use ($toUserId, $userId) {
                    $q->where('to', $toUserId)
                        ->where('from', $userId);
                });
        });

        // Load employees relation, if not loaded
        $relations = $query->getEagerLoads();
        $relationRequested = true;

        $query->setEagerLoads($relations);

        /** @var Collection $results */
        $results = $this->getResults();


        $results = $results->toArray();

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $results, $meta);
    }
}
