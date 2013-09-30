<?php
/**
 * Класс для профилирования
 * Показывает время выполнения кода
 * 
 * $time = new Timer(true); //true - это включить режим отладки;
 * $time->startTime(); //Начало замера времени
 *  //some code
 * $time->stopTime('Страница сгенерирована за'); //Выводит время исполнения скрипта с меткой не по умолчанию 
 * @packege timer.class.php
 * @author Nick
 */ 
class Timer {
    private $tstart;
    private $debug;
    private $repPoint = 1;
    /**
     * Включить/выключить режим отладки
     * @param boolean
     */ 
     
    public function __construct($debug=true) {
        $this->debug=$debug;
    }
    
    public function startTime() {
        //Считываем текущее время
        $mtime = microtime();
        //Разделяем секунды и миллисекунды
        $mtime = explode(" ",$mtime);
        //Составляем одно число из секунд и миллисекунд
        $mtime = $mtime[1] + $mtime[0];
        //Записываем стартовое время в переменную
        $this->tstart = $mtime;
    }
    public function stopTime($msg = 'Page generated in') {
		
        //Делаем все то же самое, чтобы получить текущее время
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        //Записываем время окончания в другую переменную
        $tend = $mtime;
        //Вычисляем разницу
        $totaltime = ($tend - $this->tstart);
        //Выводим не экран
        if ($this->debug)
            printf ("<div>%s : <b>%f</b> sec!<br>Queries to DataBase: <b>". mysql::$count ."</b></div>", $msg,$totaltime); 
    }
    public function repTime($name = "") {
		
        //Делаем все то же самое, чтобы получить текущее время
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        //Записываем время окончания в другую переменную
        $tend = $mtime;
        //Вычисляем разницу
        $totaltime = ($tend - $this->tstart);
        //Выводим не экран
        if ($this->debug)  printf ("<div>%s time".$this->repPoint." : %f</div>",$name ,$totaltime); 
		$this->repPoint++;
    }
    /**
     * Включить/выключить режим отладки
     * @param boolean 
     */
    public function debug($debug) {
        $this->debug = $debug;
    }
}
?>