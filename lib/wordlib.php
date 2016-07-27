<?php

/**
 * @package		SiteMapGenerator
 * @subpackage          Функции работы со строками
 * @copyright           Copyright (C) 2013 Romantic-Toys.ru
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class WordsFunct
{
    public $file = NULL;
    public $xml = NULL;
    
    
    public function afgetcsv($f, $length, $d=";", $q='"') 
    {
        $list = array();
        $st = fgets($f, $length);
        if ($st === false || $st === null) return $st;
        while ($st !== "" && $st !== false) {
            if ($st[0] !== $q) {
                # Non-quoted.
                list ($field) = explode($d, $st, 2);
                $st = substr($st, strlen($field)+strlen($d));
            } else {
                # Quoted field.
                $st = substr($st, 1);
                $field = "";
                while (1) {
                    # Find until finishing quote (EXCLUDING) or eol (including)
                    preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                    $part = $p[1];
                    $partlen = strlen($part);
                    $st = substr($st, strlen($p[0]));
                    $field .= str_replace($q.$q, $q, $part);
                    if (strlen($st) && $st[0] === $q) {
                        # Found finishing quote.
                        list ($dummy) = explode($d, $st, 2);
                        $st = substr($st, strlen($dummy)+strlen($d));
                        break;
                    } else {
                        # No finishing quote - newline.
                        $st = fgets($f, $length);
                    }
                }
            }
            $list[] = $field;
        }
        return $list;
    }
        
    public function csv_parse($file = NULL)
    {
        if(!isset($file)) $file = $this->file;
        if (($handle = fopen($file, "r")) !== FALSE) 
        { 
            function code($input)
            {
                return iconv( "Windows-1251", "UTF-8", $input);
            }
            while (($data = $this->afgetcsv($handle, 1000, ";")) !== FALSE) 
            {
                $data = array_map("code",$data);
                $arr['id'] = $data[0];
                $arr['name'] = $data[2];
                $arr['category'] = explode(" # ", $data[3]);
                $arr['manufacturer'] = $data[4];
                $arr['country'] = $data[5];
                $arr['section'] = $data[6];
                $arr['quality'] = $data[7];
                $arr['sex'] = $data[8];
                $arr['color'] = $data[9];
                $arr['description'] = $data[10];
                $arr['kit'] = $data[11];
                $output[$data[0]] = $arr;
            }

            return($output);
        }
    }
   
    
    
    public function translitEN($str) 
    {
        $tr = array
        (
            'a'=>'э','b'=>'б','v'=>'в','g'=>'г','d'=>'д',
            'e'=>'е','jo'=>'ё','zh'=>'ж','z'=>'з','i'=>'ай',
            'j'=>'джей','k'=>'к','l'=>'л','m'=>'м','n'=>'н',
            'o'=>'о','p'=>'п','r'=>'р','s'=>'с','t'=>'т',
            'u'=>'у','f'=>'ф','h'=>'х','c'=>'ц','ch'=>'ч',
            'sh'=>'ш','shh'=>'щ','#'=>'ъ','y'=>'ы','\''=>'ь',
            'je'=>'э','ju'=>'ю','ja'=>'я','A'=>'Э','B'=>'Б',
            'V'=>'В','G'=>'Г','D'=>'Д','E'=>'Е','Jo'=>'Ё',
            'Zh'=>'Ж','Z'=>'З','I'=>'АЙ','J'=>'Й','K'=>'К',
            'L'=>'Л','M'=>'М','N'=>'Н','O'=>'О','P'=>'П',
            'R'=>'Р','S'=>'С','T'=>'Т','U'=>'У','F'=>'Ф',
            'H'=>'Х','Ch'=>'Ч','Sh'=>'Ш','Shh'=>'Щ',
            '##'=>'Ъ','Y'=>'Ы','\'\''=>'Ь','Je'=>'Э','Ju'=>'Ю',
            'Ja'=>'Я','\"'=>'','x'=>'кс','II'=>'2','III'=>'3','W'=>'В','w'=>'в',
            '&quot;'=>'','e '=>' '
        );
        return strtr($str,$tr);
    }

    public function translitRU($str) 
    {
        $tr = array(
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "Е"=>"e",
            "Ё"=>"e",
            "Ж"=>"j",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"y",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"h",
            "Ц"=>"ts",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sch",
            "Ъ"=>"",
            "Ы"=>"i",
            "Ь"=>"j",
            "Э"=>"e",
            "Ю"=>"yu",
            "Я"=>"ya",
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"j",
            "з"=>"z",
            "и"=>"i",
            "й"=>"y",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"h",
            "ц"=>"ts",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sch",
            "ъ"=>"y",
            "ы"=>"i",
            "ь"=>"j",
            "э"=>"e",
            "ю"=>"yu",
            "я"=>"ya",
            " "=> "_",
            "."=> "",
            "/"=> "_",
            ","=>"_",
            "-"=>"_",
            "("=>"",
            ")"=>"",
            "["=>"",
            "]"=>"",
            "="=>"_",
            "+"=>"_",
            "*"=>"",
            "?"=>"",
            "\""=>"",
            "'"=>"",
            "&"=>"",
            "%"=>"",
            "#"=>"",
            "@"=>"",
            "!"=>"",
            ";"=>"",
            "№"=>"",
            "^"=>"",
            ":"=>"",
            "~"=>"",
            "\\"=>""
        );
        return preg_replace('/\_+/','_',preg_replace('/\s+/','_',strtr($str,$tr)));
    }
    
	public function clearstr($str) 
    {
        $tr = array(
            "1"=>"",
            "2"=>"",
            "3"=>"",
            "4"=>"",
            "5"=>"",
            "6"=>"",
            "7"=>"",
            "8"=>"",
            "9"=>"",
            "0"=>"",
			"3 "=>"",
            "."=> "",
            "/"=> "",
            ","=>"",
            "-"=>"",
            "("=>"",
            ")"=>"",
            "["=>"",
            "]"=>"",
            "="=>"",
            "+"=>"",
            "*"=>"",
            "?"=>"",
            "\""=>"",
            "'"=>"",
            "&"=>"",
            "%"=>"",
            "#"=>"",
            "@"=>"",
            "!"=>"",
            ";"=>"",
            "№"=>"",
            "^"=>"",
            ":"=>"",
            "~"=>"",
            "\\"=>""
        );
        return preg_replace('/\s+/',' ',strtr($str,$tr));
    }	

	public function clearTitle($str) 
    {
        $tr = array(
            "."=> " ",
            ","=>" ",
            "-"=>" ",
            "("=>" ",
            ")"=>" ",
            "["=>" ",
            "]"=>" ",
            "="=>" ",
            "+"=>" ",
            "*"=>" ",
            "?"=>" ",
            "\""=>"",
            "'"=>"",
            "&"=>"",
            "%"=>"",
            "#"=>"",
            "@"=>"",
            "!"=>"",
            ";"=>"",
            "№"=>"",
            "^"=>"",
            ":"=>"",
            "~"=>"",
            "\\"=>" "
        );
        return preg_replace('/\s+/',' ',strtr($str,$tr));
    }    
    
    function title_generate($id=NULL,$xml)
    {
        $id['name'] = preg_replace('/&quot;/is','',$id['name']);
        $string = $xml->templates->template[rand(0,$xml->templates->count-1)];
        $string = preg_replace("/#title#/",$id['name'],$string);
        preg_match_all("/#mod:([a-z]+)#/",$string,$mods);
        if(isset($mods[1]))
        {
            foreach ($mods[1] as $index)
            {
                $mod = (array)$xml->mod;
                $mod = $mod[$index];
                $i = count($mod)-1;
                $repl_str = $mod[rand(0,$i)];               
                $string = preg_replace("/#mod:".$index."#/",$repl_str,$string);
            }
        }
        $string = preg_replace("/#rutitle#/",$this->translitEN($id['name']),$string);
        $string = preg_replace("/#color#/",$id['color'],$string);
        $string = preg_replace("/#manufacturer#/",$id['manufacturer'],$string);
        $cat = explode('>',$id['category'][0]);
        $string = preg_replace("/#category#/",$cat[0],$string);
        $string = preg_replace("/[\s]+/"," ",$string);
        return $string;
    }
    
    function get_xml($xml)
    {
        $file = fopen($xml, "r+");
        $buff = fread($file, 20000);
        $xml = new SimpleXMLElement($buff);
        return $xml;
    }
    
    function array_empty($arr) { 
        return array(); 
    }
	
	function cropStr($str, $size){
		return mb_substr($str,0,mb_strrpos(mb_substr($str,0,$size,'utf-8'),' ',utf-8),'utf-8');
	}
}

$wordlib = new WordsFunct;