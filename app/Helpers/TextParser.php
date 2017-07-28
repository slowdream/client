<?php

namespace App;


class TextParser{
	
	private $cur; //Позиция курсора
	public $str; //Пришедшая строка

	public static function app($str) {
	    return new self($str);
	}

	public function __construct($str) {
	    $this->str = $str;
	    $this->cur = 0;
	}

	public function moveTo($pattern) {
	    $res = strpos($this->str, $pattern, $this->cur);

	    if ($res === false)
	        return -1;

	    $this->cur = $res;
	    return true;
	}

	public function moveAfter($pattern) {
	    $res = strpos($this->str, $pattern, $this->cur);

	    if ($res === false)
	        return -1;

	    $this->cur = $res + strlen($pattern);
	    return true;
	}

	public function readTo($pattern) {
	    $res = strpos($this->str, $pattern, $this->cur);

	    if ($res === false)
	        return -1;

	    $out = substr($this->str, $this->cur, $res - $this->cur);
	    $this->cur = $res;
	    return $out;
	}


	public function readToPos($pos) {
	    $out = substr($this->str, $this->cur, $pos - $this->cur);
	    $this->cur = $pos;
	    return $out;
	}


	public function readFrom($pattern) {
	    $res = strpos($this->str, $pattern);
	    
	    if ( ($res === false) || ($res >= $this->cur) )
	        return -1;
	    
	    $out = substr($this->str, $res, $this->cur - $res);
	    return $out;
	}


	public function getPos($pattern) {
	    $res = strpos($this->str, $pattern, $this->cur);

	    if ($res === false)
	        return -1;

	    return $res;
	}


	public function subtag($start_pattern, $tag, $need_move = true) {
	    
	    $start_position = $this->cur;
	    
	    if ($this->moveTo($start_pattern) === -1)
	        return -1;
	    
	    $start = '<' . $tag;
	    $end = '</' . $tag . '>';
	    
	    // Позиция для начала поиска, чтобы не включать стартовый открывающий тег
	    $curpos = $this->cur + strlen($start);
	    
	    $data = false;
	    $run = 1;
	    $space = 0;
	    
	    while($run) {
	        $pos = strpos($this->str, $tag, $curpos);
	        $simbol = substr($this->str, $pos - 1, 1);
	        
	        if ($simbol == '<') {
	            $run++;
	            $curpos = $pos + strlen($tag);
	            continue;
	        }
	        
	        $space = 0;
	        while ($simbol == ' ') {
	            $space++;
	            $pos--;
	            $simbol = substr($this->str, $pos - 1, 1);
	        }
	        
	        if ($simbol == '/') {
	            $simbols = substr($this->str, $pos - 2, 2);
	            if ($simbols == '</') {
	                $run--;
	            }
	        }
	        
	        $curpos = $pos + strlen($tag) + $space;
	    }
	    
	    $data = $this->readToPos($curpos + 1);
	    
	    if (!$need_move)
	        $this->cur = $start_position;
	    
	    return $data;
	}

	public function subtag_inner($start_pattern, $tag, $need_move = true) {
	    
	    $_data = $this->subtag($start_pattern, $tag, $need_move = true);
	    if ($_data === -1)
	        return -1;
	    
	    $pos1 = strpos($_data, '>') + 1;
	    
	    if ($pos1 === false)
	        return -1;
	    
	    $pos2 = strpos($_data, '<', $pos1);
	    
	    if ($pos2 === false)
	        return -1;
	    
	    $data = substr($_data, $pos1, $pos2 - $pos1);
	    
	    return $data;
	    
	}

	public function subtag_inner2($start_pattern, $tag, $need_move = true) {
	    
	    $_data = $this->subtag($start_pattern, $tag, $need_move = true);
	    if ($_data === -1)
	        return -1;
	    
	    $p = $this->app($_data);
	    
	    if ($p->moveAfter('>') === -1)
	        return -1;
	    
	    $data = $p->readTo('<');
	    
	    unset($p, $_data);
	    
	    return $data;
	    
	}

	public function def() {
	    $this->cur = 0;
	}

}
