<?php

namespace App\Helpers;


class Curl{

	private $ch;  // экземляр курла
	private $host;   // хост - базовая часть урла без слеша на конце
	private $options; // массив с настройками курла
	
	//
	// Инициализация класса для конкретного домена
	//
	// public static function app($host) 
	// {
	//     return new self($host);
	// }

	public function __construct($host) 
	{
	    $this->ch = curl_init();
	    $this->host = $host;
	    $this->options = array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => array());
	    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}

	public function __destruct() {
	    curl_close($this->ch);
	}

	/**
	 * Устанавливает опцию курла и записывает в массив с опциями
	 * 
	 * @param mixed $name
	 * Константа (название) или номер опции курла
	 * 
	 * @param mixeds $value
	 * Значение опции для установки
	 */
	public function set($name, $value) 
	{
	    $this->options[$name] = $value;
	    curl_setopt($this->ch, $name, $value);
	    return $this;
	}

	/**
	 * Отображает текущее состояние опции
	 * 
	 * @param mixed $name
	 * Константа (название) или номер опции курла
	 */
	public function get($name) 
	{
	    return $this->options[$name];
	}

	/**
	 * Устанавливает настройки куков
	 * 
	 * @param string $file
	 * Относительный путь до файла для сохранения кук
	 */
	public function cookie($path) 
	{
	    $this->set(CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . '/' . COOKIE_DIR .  $path);
	    $this->set(CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . '/' . COOKIE_DIR . $path);
	    return $this;
	}

	/**
	 * Включает или выключает возможность обращаться к HTTPS страницам
	 * 
	 * @param int $act
	 * 1 - https разрешено, 0 - https запрещено
	 */
	public function ssl($act)
	{
	    $this->set(CURLOPT_SSL_VERIFYPEER, $act);
	    $this->set(CURLOPT_SSL_VERIFYHOST, $act);
	    return $this;
	}

	/**
	 * Включает или выключает заголовки ответа
	 * 
	 * @param int $act
	 * 1 - есть, 0 - нет
	 */
	public function headers($act)
	{
	    $this->set(CURLOPT_HEADER, $act);
	    return $this;
	}

	/**
	 * Устанавливает, следовать ли за перенаправлением
	 * 
	 * @param bool $param
	 * TRUE - следовать
	 * FALSE - не следовать
	 */
	public function follow($param)
	{
	    $this->set(CURLOPT_FOLLOWLOCATION, $param);
	    return $this;
	}

	/**
	 * Устанавливает реферер
	 * 
	 * @url string $url
	 */
	public function referer($url)
	{
	    $this->set(CURLOPT_REFERER, $url);
	    return $this;
	}

	/**
	 * Устанавливает браузер
	 * 
	 * @agent string $agent
	 */
	public function agent($agent)
	{
	    $this->set(CURLOPT_USERAGENT, $agent);
	    return $this;
	}

	/**
	 * Настройка конфигурации для метода POST
	 * 
	 * @param mixed $post
	 * array - ассоциативный массив с параметрами
	 * false - отлючить обращение методом POST
	 */
	public function post($data, $decode = true)
	{
	    if ($data === false) {
	        $this->set(CURLOPT_POST, false);
	        return $this;
	    }
	    if($decode){
	    	$data = http_build_query($data);
	    }
	    $this->set(CURLOPT_POST, true);
	    $this->set(CURLOPT_POSTFIELDS, $data);
	    return $this;
	}

	/**
	 * Добавить 1 произвольный http-заголовок к запросу
	 * 
	 * @param string $header
	 */
	public function add_header($header)
	{
	    $this->options[CURLOPT_HTTPHEADER][] = $header;
	    $this->set(CURLOPT_HTTPHEADER, $this->options[CURLOPT_HTTPHEADER]);
	    return $this;
	}

	/**
	 * Добавить несколько произвольных http-заголовоков к запросу
	 * 
	 * @param array $headers
	 */
	public function add_headers($headers)
	{
	    foreach ($headers as $h)
	        $this->options[CURLOPT_HTTPHEADER][] = $h;

	    $this->set(CURLOPT_HTTPHEADER, $this->options[CURLOPT_HTTPHEADER]);
	    return $this;
	}

	/**
	 * Очиситить массив произвольных http-заголовков
	 */
	public function clear_headers()
	{
	    $this->options[CURLOPT_HTTPHEADER] = array();
	    $this->set(CURLOPT_HTTPHEADER, $this->options[CURLOPT_HTTPHEADER]);
	    return $this;
	}

	/**
	 * Загрузить конфигурацию из файла
	 * 
	 * @param string $file
	 */
	public function config_load($file)
	{
	    $data = file_get_contents(app_path('/Helpers/').$file);
	    $data = json_decode($data, 1);
	    
	    curl_setopt_array($this->ch, $data);
	    
	    foreach ($data as $key => $val) {
	        $this->options[$key] = $val;
	    }
	    return $this;
	}

	/**
	 * Сохранить конфигурацию в файл
	 * 
	 * @param string $file
	 */
	public function config_save($file)
	{
	    $data = json_encode($this->options);
	    file_put_contents('config/' . $file, $data);
	    return $this;
	}

	/**
	 * Выполнить запрос на конкретный урл
	 * 
	 * @param string $url
	 */
	public function request($url='')
	{
	    curl_setopt($this->ch, CURLOPT_URL, $this->make_url($url));
	    $data = curl_exec($this->ch);
	    return $this->process_result($data);
	}

	/**
	 * Создает правильный URL
	 * 
	 * @param string $url
	 * адрес страницы без домена
	 */
	private function make_url($url)
	{
	    if ($url[0] != '/')
	        $url = '/' . $url;

	    return $this->host . $url;
	}

	/**
	 * Переводит полученную курлом страницу в человеческий вид
	 * 
	 * @param string $data
	 * Результат полученный функцией curl_exec
	 * (т.е. заголовок и содержимое страницы)
	 * 
	 * @return array
	 * Возвращает распарсенный массив с информацией
	 * [
	 * 'headers' => заголовки,
	 * 'headers['start']' => первая строка HTTP заголовка со статус-кодом,
	 * 'html' => содержимое страницы
	 * ]
	 */
	private function process_result($data)
	{
	    /* Если HEADER отключен */
	    if (!isset($this->options[CURLOPT_HEADER]) || !$this->options[CURLOPT_HEADER]) {
	        return array(
	            'headers' => array(),
	            'html' => $data
	        );
	    }

	    /* Разделяем ответ на headers и body */
	    $info = curl_getinfo($this->ch);

	    $headers_part = trim(substr($data, 0, $info['header_size'])); // trim - чтобы обрезать перенос строки в конце
	    $body_part = substr($data, $info['header_size']);

	    /* Определяем символ переноса строки */
	    $headers_part = str_replace("\r\n", "\n", $headers_part); // винда в никсовую
	    $headers = str_replace("\r", "\n", $headers_part); // мак в никсовую

	    /* Берем последний headers */
	    $headers = explode("\n\n", $headers);
	    $headers_part = end($headers);

	    /* Извлекаем путь редиректа */
	    $redirects = array();
	    foreach ($headers as $value) {
	        $start = stripos($value, 'Location:');

	        if (!$start)
	            continue;

	        $start += strlen('Location:') + 1;
	        $loc = substr($value, $start);
	        $end = ($end = stripos($loc, "\n")) ? $end : strlen($value);
	        $redirects[] = substr($value, $start, $end);
	    }

	    /* Парсим headers */
	    $lines = explode("\n", $headers_part);
	    $headers = array();

	    $headers['start'] = $lines[0];
	    $headers['redirects'] = $redirects;

	    for ($i = 1; $i < count($lines); $i++) {
	        $del_pos = strpos($lines[$i], ':');
	        $name = substr($lines[$i], 0, $del_pos);
	        $value = substr($lines[$i], $del_pos + 2);
	        $headers[$name] = $value;
	    }

	    return array(
	        'headers' => $headers,
	        'html' => $body_part
	    );
	}	
}
