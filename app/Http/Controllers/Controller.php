<?php

namespace App\Http\Controllers;


class Controller
{

    public function index()
    {
        $arr = [65,23,35,67542,234,45323,2,23,1];
        $len = count($arr);
        for($i = 1; $i < $len; $i++) {
            for ($j = $i -1 ; $j >=0  ; $j--)
            {
                if($arr[$j+1] < $arr[$j])
                {
                    $tmp = $arr[$j + 1];
                    $arr[$j+1]=$arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }
        return $arr;
    }
}
