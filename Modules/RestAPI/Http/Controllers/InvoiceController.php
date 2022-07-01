<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Events\NewInvoiceEvent;
use App\Events\PaymentReminderEvent;
use App\User;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Invoice;
use Modules\RestAPI\Http\Requests\Invoice\IndexRequest;
use Modules\RestAPI\Http\Requests\Invoice\CreateRequest;
use Modules\RestAPI\Http\Requests\Invoice\ShowRequest;
use Modules\RestAPI\Http\Requests\Invoice\UpdateRequest;
use Modules\RestAPI\Http\Requests\Invoice\DeleteRequest;

class InvoiceController extends ApiBaseController
{

    protected $model = Invoice::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }

    public function sendInvoice($invoiceID)
    {
        app()->make($this->indexRequest);
        $invoice = \App\Invoice::with(['project', 'project.client'])->findOrFail($invoiceID);
        if ($invoice->project_id != null && $invoice->project_id != '') {
            $notifyUser = $invoice->project->client;
        } elseif ($invoice->client_id != null && $invoice->client_id != '') {
            $notifyUser = $invoice->client;
        }
        if (!is_null($notifyUser)) {
            event(new NewInvoiceEvent($invoice, $notifyUser));
        }

        $invoice->send_status = 1;
        if ($invoice->status == 'draft') {
            $invoice->status = 'unpaid';
        }
        $invoice->save();
        return ApiResponse::make(__('messages.invoiceSentSuccessfully'));
    }

    public function remindForPayment($taskID)
    {
        $invoice = \App\Invoice::with(['project', 'project.client'])->findOrFail($taskID);
        // Send reminder notification to user

        $userClient = User::findOrFail($invoice->project ? $invoice->project->client->user_id : $invoice->client_id);
        $notifyUser = $userClient;
        if (!is_null($notifyUser)) {
            event(new PaymentReminderEvent($invoice, $notifyUser));
        }
        return ApiResponse::make(__('messages.reminderMailSuccess'));
    }
}
