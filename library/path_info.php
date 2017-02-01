<?php


class PathInfo {
    
    public $path_info;
    public $no_path;
    public $path_info_array;
    public $number_of_paths;
    
    public function update() {
        // read request
        $this->path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        // ignore '/', assume empty for '/'
        if($this->path_info==='/')
            $this->path_info='';

        $this->no_path = ($this->path_info==='') ? true : false;

        $this->path_info_array = explode('/',$this->path_info);
        $this->number_of_paths = count($this->path_info_array)-1;
    }
    
    public function update_from($path_info) {
        $this->path_info = $path_info;
        // ignore '/', assume empty for '/'
        if($this->path_info==='/')
            $this->path_info='';

        $this->no_path = ($this->path_info==='') ? true : false;

        $this->path_info_array = explode('/',$this->path_info);
        $this->number_of_paths = count($this->path_info_array)-1;
    }

    
}