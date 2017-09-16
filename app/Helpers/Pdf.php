<?php
namespace App\Helpers;
use Dompdf\Dompdf;

class Pdf{


	private $Dompdf;

	private $name;
	private $summ;
	private $order_num;
	//private $barcode;

	function __construct(array $config)
	{
		$this->Dompdf = new Dompdf();

		$this->name = $config['name'];
		$this->order_num = $config['order_num'];
		$this->summ = $config['summ'];
	}

	private function makeTemplate()
	{
		//$this->barcode = $this->generateBarcode();
		return view('receipt',[
			'name' => $this->name,
			'order_num' => $this->order_num,
			'summ' => $this->summ
		]);
	}

	private function generateBarcode($value='')
	{
// $dompdf = new Dompdf([
// 	'fontDir' => '/var/www/my_site/fonts',//указываем путь к папке, в которой лежат скомпилированные шрифты
// 	'defaultFont' => 'dompdf_arial',//делаем наш шрифт шрифтом по умолчанию
// ]);
// $dompdf->load_html($html, 'UTF-8');
// $dompdf->render();
	}

	public function process()
	{
		$html = $this->makeTemplate();
		//$html = '123';
		$this->Dompdf->loadHtml($html);

		//$this->Dompdf->setPaper('my', 'portrait');
		//$this->Dompdf->set_option('fontDir', base_path('/libs/dompdf/lib/fonts'));
		$this->Dompdf->set_option('defaultFont', 'Roboto-Regular.ttf');

		$this->Dompdf->render();

		$val = $this->Dompdf->output();

		//file_put_contents(resource_path('reciepts/reciepts.pdf'), $val);

		return $val;
		
	}

}
