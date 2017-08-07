<?
namespace App\Helpers;
require_once base_path().'/app/libs/dompdf/autoload.php';
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
		# code...
	}

	public function process()
	{
		$html = $this->makeTemplate();
		//$html = '123';
		$this->Dompdf->loadHtml($html);

		$this->Dompdf->setPaper('my', 'portrait');

		$this->Dompdf->render();

		$val = $this->Dompdf->output();

		//file_put_contents(resource_path('reciepts/reciepts.pdf'), $val);

		return $val;
		
	}

}
