<?php

namespace App\Http\Controllers\api;

use App\Cash;
use App\Event;
use App\Http\Controllers\Controller;
use App\Jobs\CashCode;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

/**
 * Class cashController.
 */
class cashController extends Controller
{
    public function getSumm()
    {
        //Отдаем сумму введеных купюр
        $order = new Order();
        $order = $order->getActive();
        $cash = $order->cash;
        $summ = 0;
        foreach ($cash as $item) {
            $summ += $item->value;
        }

        return $summ;
        //$cash = new Cash();
        //return $cash->summ();
    }

    public function pauseCash()
    {
        Cash::where('status', 'wait')->delete();
    }

    /*
     Запускает процедуру приема денег
    */
    public function startCash(Request $Request)
    {
        $this->dispatch(new CashCode());

        return 'true';
    }

    /*
     * Вывод информации о содержащихся в боксе купюрах
     */
    public function infoCash(Request $Request)
    {
        $needCheck = $Request->input('check');
        $inboxCash = Cash::where('status', 'inbox')->get();
        $inboxCashSumm = 0;
        if ($inboxCash) {
            foreach ($inboxCash as $cash) {
                $inboxCashSumm += $cash->value;
            }
        }

        $incass = Event::where('name', 'incass')->latest()->first();
        $lastIncass = ($incass) ? $incass->created_at->toDateTimeString() : 'Не проводилась';
        $orders_summ = 0;

        $orders = Order::where('created_at', '>', $incass->created_at->toDateTimeString())
          ->where('status', 'complete')
          ->get();
        if (!$orders) {
            return;
        }
        foreach ($orders as $order) {
            $orders_summ += $order->products->sum(function ($product) {
                return $product['count'] * $product['price'];
            });
        }

        $cashInfo = [
          'summ'         => number_format($inboxCashSumm, 2, ',', ' '),
          'count'        => count($inboxCash),
          'last_incass'  => $lastIncass,
          'orders_count' => count($orders),
          'orders_summ'  => number_format($orders_summ, 2, ',', ' '),
          'date'         => Carbon::now('Europe/Moscow')->toDateTimeString(),
          'id_term'      => strtoupper(env('ID_TERM')),
        ];

        //Четвертое число в размере бумаги это высота чека
        $pdf = PDF::loadView('check/cashInfo', $cashInfo)
          ->setPaper([0, 0, 218, 330], 'portrait');
        //return $pdf->stream();
        $pdf->save(resource_path('reciepts/cash-info.pdf'));
        $file = resource_path('reciepts/cash-info.pdf');
        `lp {$file}`;
    }

    /*
     * Инкассация
     */
    public function incass()
    {
        $inboxCash = Cash::where('status', 'inbox')->get();
        $inboxCashSumm = 0;
        foreach ($inboxCash as $cash) {
            $inboxCashSumm += $cash->value;
        }
        Cash::where('status', 'inbox')->update(['status' => 'extracted']);
        $cashInfo = [
          'summ'    => number_format($inboxCashSumm, 2, ',', ' '),
          'count'   => count($inboxCash),
          'date'    => Carbon::now('Europe/Moscow')->toDateTimeString(),
          'id_term' => strtoupper(env('ID_TERM')),
        ];
        Event::create(['name' => 'incass']);
        $pdf = PDF::loadView('check/incass', $cashInfo)
          ->setPaper([0, 0, 218, 250], 'portrait');
        //return $pdf->stream();
        $pdf->save(resource_path('reciepts/cash-incass.pdf'));
        $file = resource_path('reciepts/cash-incass.pdf');
        `lp {$file}`;
    }

    public function seed()
    {
        $Banknotes = [50, 100, 500, 1000];
        for ($i = 0; $i < 50; $i++) {
            Cash::create([
              'value'  => $Banknotes[array_rand($Banknotes, 1)],
              'status' => 'inbox',
            ]);
        }
        for ($i = 0; $i < 5; $i++) {
            Cash::create([
              'value'  => $Banknotes[array_rand($Banknotes, 1)],
              'status' => 'wait',
            ]);
        }
    }
}
