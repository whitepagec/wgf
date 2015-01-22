<?php

class LogError {

    const ERROR_DIR_GENERAL = '/home/wugufengcom/public_html/logs/error/';
	//const ERROR_DIR_GENERAL = '/home/bakemissionwpcde/public_html/logs/error/';

    public function write(Exception $objException)
    {
        try{
            date_default_timezone_set("Asia/Singapore");
            //$dtDatetime = date('d.m.Y h:i:s');
            $dtDatetime = date('Y-m-d h:i:s');
            $sLog = "[ " . $dtDatetime . " ] " . "Error details:\n" . self::combineTrace($objException) . "\n";
            $sFilePath = self::ERROR_DIR_GENERAL . "error_" . date('Ymd') . ".log";
            error_log($sLog, 3, $sFilePath);
        } catch (Exception $ex) {
        }
    }
    
    
    private function combineTrace(Exception $objException) {
        $trace = $objException->getTrace();

        $result = get_class($objException);
        $result .=  " '{$objException->getMessage()}' in {$objException->getFile()}({$objException->getLine()})";
        
        if (($trace[0]['function'] != '') || ($trace[0]['class'] != '')) $result .= ": ";
        
        if($trace[0]['class'] != '') {
          $result .= $trace[0]['class'];
          $result .= '->';
        }
        
        if($trace[0]['function'] != '') {
            $result .= $trace[0]['function'];
            $result .= "();";
        }
        
        $result .= "\n";

        return $result;
    }

}
?>