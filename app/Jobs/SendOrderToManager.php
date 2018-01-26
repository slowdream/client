<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\order;
use Curl;
use Mail;
use App\Mail\OrderInfoForManager;

class SendOrderToManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $emailData = [
          "idterm" => strtoupper(env('ID_TERM', "test")),
          'orderId' => $data[0]["IdOrder"],
          "name" => $data[0]['name'],
          "telnumber" => $data[0]["telnumber"],
          "address" => $data[0]['address'],
          "orderDate" => $data[0]["orderDate"],
          "timeRange" => $data[0]["timeRange"],
          "pay" => $data[0]["Pay"],
          "summ" => $data[0]["Summ"],
          "delivery" => $data[0]["Delivery"],
          "products" => []
        ];

        $order = Order::find($data[0]["IdOrder"]);
        foreach ($order->products as $product) {
            $emailData['products'][] = [
              'guid' => $product->product->guid,
              'name' => $product->product->name,
              'count' => $product->count,
              'unit' => $product->product->unit,
              'price' => $product->product->price
            ];
        }
        $this->data = $emailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to("suponina@pmc34.ru")->cc("slowdream@yandex.ru")->send(new OrderInfoForManager($this->data));

//        $text = view('other_massages.OrderInfoChat', $this->data)->render();
//
//        $curl = new Curl('https://api.flock.com/hooks/sendMessage/' . env("FLOCK_API"));
//        $curl->config_load('trip.cfg');
//        $curl->clear_headers();
//        $curl->add_header("Content-Type: application/json");
//        $postData['text'] = $text;
//        $postData = json_encode($postData, JSON_UNESCAPED_UNICODE);
//        $curl->post($postData, false);
//        $response = $curl->request('/');
//        $data = json_decode($response['html'], true);
    }
}
