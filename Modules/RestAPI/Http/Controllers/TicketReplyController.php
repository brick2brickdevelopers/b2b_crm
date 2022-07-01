<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Files;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Modules\RestAPI\Entities\TicketFile;
use Modules\RestAPI\Entities\TicketReply;
use Modules\RestAPI\Http\Requests\TicketReply\IndexRequest;
use Modules\RestAPI\Http\Requests\TicketReply\CreateRequest;
use Modules\RestAPI\Http\Requests\TicketReply\ShowRequest;
use Modules\RestAPI\Http\Requests\TicketReply\UpdateRequest;
use Modules\RestAPI\Http\Requests\TicketReply\DeleteRequest;

class TicketReplyController extends ApiBaseController
{

    protected $model = TicketReply::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }

    public function ticketReplyFile()
    {
        $reply = new TicketReply();
        $reply->message = \request()->message ? \request()->message : '';
        $reply->ticket_id = \request()->ticket_id;
        $reply->user_id = api_user()->id; //current logged in user
        $reply->save();

        if (\request()->hasFile('file')) {
                $file = new TicketFile();
                $file->user_id = api_user()->id;
                $file->ticket_reply_id = $reply->id;
                $filename = Files::uploadLocalOrS3(\request()->file, 'ticket-files/'.$reply->id);
                $file->filename = $filename;
                $file->hashname = $filename;

                $file->size = \request()->file->getSize();
                $file->save();
        }
        return ApiResponse::make("Resource created successfully", [ "id" => $reply->id ]);
    }
}
