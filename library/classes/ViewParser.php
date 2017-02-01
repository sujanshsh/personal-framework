<?php

class ViewParser
{
    public static function parse($filename,$values) {
        if(is_readable($filename)) {
            foreach($values as $k => $v) {
                $$k = $v;
            }
            ob_start();
            include $filename;
            return ob_get_clean();
        }
        else
            return '';
    }
}