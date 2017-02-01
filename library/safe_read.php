<?php

function safe_read(&$array,$key,$default='')
{
    if(isset($array[$key]))
        return $array[$key];
    else
        return $default;
}