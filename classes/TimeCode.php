<?php

// questa classe genera un token partendo dall'id dell'utente e i minuti correnti 
class TimeCode
{
    public static function getTime($id)
    {
        $time = date('i');
        $time= substr(strval($time),0,1);
        $time= strtoupper(substr(hash('md5',$time.$id),0,5));

        return $time;
    }

}

