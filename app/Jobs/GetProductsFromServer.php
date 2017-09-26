<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Product;
use App\Category;
use Server1C;

class GetProductsFromServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $curl = new Server1C();
      $categorys = $curl->request('crm/hs/Terminal/?action=group');
      $data = json_decode($categorys['html'], true);

      $category = new Category;
      foreach ($data as $val) {
        $category::firstOrCreate([
          'guid' => $val['groupid'],
          'name' => $val['group'],
          'parent_id' => $val['parentid']
        ]);
      }

      $items = $curl->request('crm/hs/Terminal/?action=Goods');
      $data = json_decode($items['html'], true);
      foreach ($data as $val) {
        $category = $category::firstOrCreate([
          'guid' => $val['groupid'],
          'name' => $val['group'],
        ]);
        $category->update([
          'items_parent' => true
        ]);
        $product = Product::firstOrNew([
          'guid' => $val['id'],
        ]);
        $product->fill([
          'name' => $val['name'],
          'image' => 'image.tyt',
          'description' => str_replace("\n", '<br>', $val['descr']),
          'price' => (int)$val['price'],
          'count' => (int)$val['mount'],
          'unit' => $val['unit']
        ]);
        $category->products()->save($product);
      }
    }
}
