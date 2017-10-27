<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Server1C;
use App\Order;

class SendOrdersToServer implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  protected $id;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct ($id = 0)
  {
    $this->id = $id;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle ()
  {
    if ($this->id){
      $order = Order::find($this->id);
    } else {
      $order = Order::where('status', 'complete')
        ->orWhere(function ($query) {
          $query->where('status', 'canceled')
            ->whereNotNull('contacts');
        })
        ->first();
    }

    $orderProd = $order->products;
    $curl = new Server1C();
    $contacts = json_decode($order->contacts, true);
    $arr = [];

    $pay = 0;
    $cash = $order->cash;
    foreach ($cash as $item) {
      $pay += $item->value;
    }
    $arr[0] = [
      "type" => "0",
      "idterm" => strtoupper(env('ID_TERM', "test")),
      "IdOrder" => (string)$order->id,
      "date" => (string)date('YmdHis'),
      "telnumber" => (string)$contacts['tel'],
      "address" => (string)$contacts['address'],
      "Pay" => $pay,
      "reason" => $order->reason
    ];

    foreach ($orderProd as $product) {
      $arr[] = [
        "type" => "1",
        "name" => intval($product->product->guid),
        "count" => $product->count,
        "price" => $product->price,
        "sum" => $product->count * $product->price
      ];
    }
    if (count($arr) <= 1) {
      return;
    }
    $curl->post($arr);
    $response = $curl->request('crm/hs/Terminal/zakaz');
    $data = json_decode($response['html'], true);
    $order->status = 'sended';
    $order->save();
  }
}