<?php

function get_lang($default_lang = 'ru')
{
    if (!isset($_GET['lang'])) {
        $lang = $default_lang;
    } else {
        $lang = $_GET['lang'];
    }
    return $lang;
}

function get_controller()
{
    if (isset($_GET['q'])) return $_GET['q'];
		else return 'default';
}

function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace('&amp;', '&', $url));
	exit();
}

function js_redirect($url) {
	if (DEVEL) echo	'<script type="text/javascript">alert("Redirect to '.$url.'");</script>';
	echo '<script type="text/javascript">
		if (document.getElementById("main-container")) _ajax.load("'.$url.'"); else window.location.href ="'.$url.'";
		</script>';
	exit();
}

function date_ch1($date) 
   { 
        
         $arr = array( 
                       "January"=>"Січня", "February"=>"Лютого", "March"=>"Березня", "April"=>"Квітня", "May"=>"Травня", "June"=>"Червня", "July"=>"Липня", "August"=>"Серпня", "September"=>"Вересня", "October"=>"Жовтня", "November"=>"Листопада", "December"=>"Грудня"
                     ); 
		
  
 		$date_ch=strtr($date, $arr);
        return $date_ch; 
   }  
   
   function date_ch2($date) 
   { 
        
         $arr = array( 
                       "January"=>"Января", "February"=>"Февраля", "March"=>"Марта", "April"=>"Апреля", "May"=>"Мая", "June"=>"Июня", "July"=>"Июля", "August"=>"Августа", "September"=>"Сентября", "October"=>"Октября", "November"=>"Ноября", "December"=>"Декабря"
                     ); 
		
  
 		$date_ch=strtr($date, $arr);
        return $date_ch; 
   } 
   
function transliterate($string) 
   { 
        
         $arr = array( 
					   'Q'=>'q', 	'W'=>'w',	 'E'=>'e', 	   'R'=>'r', 
					   'T'=>'t', 	'Y'=>'y', 	 'U'=>'u', 	   'I'=>'i', 
					   'O'=>'o', 	'P'=>'p', 	 'A'=>'a', 	   'S'=>'s', 
					   'D'=>'d', 	'F'=>'f', 	 'G'=>'g', 	   'H'=>'h', 
					   'J'=>'j', 	'K'=>'k', 	 'L'=>'l', 	   'Z'=>'z', 
					   'X'=>'x', 	'C'=>'c', 	 'V'=>'v',	   'B'=>'b', 
					   'N'=>'n', 	'M'=>'m',			 
                       'А' => 'a' , 'Б' => 'b' , 'В' => 'v'  , 'Г' => 'g', 
                       'Д' => 'd' , 'Е' => 'e' , 'Ё' => 'jo' , 'Ж' => 'zh', 
                       'З' => 'z' , 'И' => 'i' , 'Й' => 'j' , 'К' => 'k', 
                       'Л' => 'l' , 'М' => 'm' , 'Н' => 'N'  , 'О' => 'o', 
                       'П' => 'p' , 'Р' => 'r' , 'С' => 'S'  , 'Т' => 't', 
                       'У' => 'u' , 'Ф' => 'f' , 'Х' => 'kh' , 'Ц' => 'c', 
                       'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ъ' => '', 				   
                       'Ы' => 'y' , 'Ь' => '', 'Э' => 'eh' , 'Ю' => 'ju', 
                       'Я' => 'ja', 'І' => 'i', 'Ґ' => 'g', 'Є' => 'je', 'Ї' => 'i', 
                       'а' => 'a' , 'б' => 'b'  , 'в' => 'v' , 'г' => 'g', 'д' => 'd', 
                       'е' => 'e' , 'ё' => 'jo' , 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 
                       'й' => 'jj', 'к' => 'k'  , 'л' => 'l' , 'м' => 'm', 'н' => 'n', 
                       'о' => 'o' , 'п' => 'p'  , 'р' => 'r' , 'с' => 's', 'т' => 't', 
                       'у' => 'u' , 'ф' => 'f'  , 'х' => 'kh', 'ц' => 'c', 'ч' => 'ch', 
                       'ш' => 'sh', 'щ' => 'shh', 'ъ' => '' , 'ы' => 'y', 'ь' => '', 
                       'э' => 'eh', 'ю' => 'ju' , 'я' => 'ja', 'ґ' => 'g', 'є' => 'je', 
					   'і' => 'i', 'ї' => 'i', ' ' => '_', '\"' => '', '\'' => '', 
					   '-' => '_',   '+' => '_', '&' => '_', '?' => '_', 
					   '(' => '_', ')' => '_', '[' => '_', ']' => '_', '{' => '_', 
					   '}' => '_', '/' => '_', '\\' => '_', '*' => '_', '^' => '_', 
					   '%' => '_', '$' => '_', '#' => '_', '@' => '_', '!' => '_', 
					   '`' => '_', '~' => '_', '|' => '_', ':' => '_', ';' => '_', '.' => ''
                     ); 
		
        $key = array_keys($arr);
        $val = array_values($arr);
		$translate=strtr($string, $arr);
        return $translate; 
   }     
   
				function generate_password($number)
				  {
					$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0','.',',','(',')','[',']','!','?','&','^','%','@','*','$','<','>','/','|','+','-','{','}','`','~');
					$pass = "";
					for($i = 0; $i < $number; $i++)
					{
					  $index = rand(0, count($arr) - 1);
					  $pass .= $arr[$index];
					}
					return $pass;
				  }
	
function replace ($string)
{
        
        $string = " ".$string;

		$string = eregi_replace ("[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*","<a href=\"mailto:\\0\" class=dark>\\0</a>", $string);
        $string = eregi_replace ('([[:space:]]|\n|<br>)(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target="_blank" class=dark>\\2</a>', $string);
        $string = eregi_replace ('([[:space:]]|\n|<br>)(http://.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="\\2" target="_blank" class=dark>\\2</a>', $string);
        $string = eregi_replace ('(\[img\])(http://.[-a-zA-Z0-9@:%_\+.~#?&//=]+)(\[/img\])', '<img src="\\2">', $string);
       
        $lines = count ($autochange);
   
        return trim($string);
}

function chHTML($text)
	{
	$text = str_replace("<b>","[b]",$text);
	$text = str_replace("</b>","[/b]",$text);
	$text = str_replace("<i>","[i]",$text);
	$text = str_replace("</i>","[/i]",$text);
	$text = str_replace ("<br>","\n", $text);
	return $text;
	}

function getHTML($text)
	{
	$text = str_replace("[b]","<b>",$text);
	$text = str_replace("[/b]","</b>",$text);
	$text = str_replace("[i]","<i>",$text);
	$text = str_replace("[/i]","</i>",$text);
	$text = str_replace ("\n","<br>",  $text);
	return $text;
	}	

	
function getDatesByWeek($_week_number, $_year = null) {
        $year = $_year ? $_year : date('Y');
        $week_number = sprintf('%02d', $_week_number);
        $date_base = strtotime($year . 'W' . $week_number . '1 00:00:00');
        $date_limit = strtotime($year . 'W' . $week_number . '7 23:59:59');
        return array($date_base, $date_limit);
}