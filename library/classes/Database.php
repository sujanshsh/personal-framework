<?php

// requires on library/safe_read.php <-- function safe_read()

class Database implements DatabaseInterface
{
	//private $current_table;
	public $result;
	public static $connection;
	public $database;
	public $num_rows;
	public $querry_error;
	public $success;
	public $prev_results = [];
	
	
	public function __construct($config_database_file='Database.php')
	{
        $config_database_path_file = '../config/'.$config_database_file;
        if(!is_readable($config_database_path_file)) {
            die('Database config file not found!');
        }
        include($config_database_path_file);
        $host = $config_database['host'];
        $user = $config_database['user'];
        $password = $config_database['password'];
        $database = $config_database['database'];
        $socket = safe_read($config_database,'socket');
        $port = safe_read($config_database,'port');
		$this->database=$database;
        if(Database::$connection[$database]) {
            return;
        }

		Database::$connection[$this->database] = mysqli_connect($host,$user,$password,$database);
		if (mysqli_connect_errno(Database::$connection[$this->database])) {
			die("Database Connection Error!");
		}
	}
    public function __get($name) {
        if($name=='connection') {
            return Database::$connection[$this->database];
        }
    }
	private function error($err)
	{
		echo $err.' '.mysqli_error(Database::$connection[$this->database]);
		$this->disconnect();
		exit();
	}

	private function push_result() {
		if(is_resource($this->result)) {
			array_push($this->prev_results,$this->result);
		}
	}
	public function execute_sql_query($q,$err='')
	{
		$this->push_result();
		$this->result=mysqli_query(Database::$connection[$this->database],$q);
		if($this->result===false)
		{
			$this->success=false;
            echo $q;
			$this->error($err);
		}
		$this->success=true;
		return $this->result;
	}
	private function execute_sql_query_nq($q,$err='')
	{
		$this->push_result();
		$this->result=mysqli_query(Database::$connection[$this->database],$q);
		if($this->result===false)
		{
			$this->success=false;
			if(isset($err))//if not null echo err message
				echo $err.' '.mysqli_error(Database::$connection[$this->database]);
		}
		$this->success=true;
		return $this->result;
	}
	public function create_temporary_table($query,$err="")
	{
		$this->success=false;
		$q="CREATE TEMPORARY TABLE ".$query;
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
			$this->success=true;
		return $this->result;
	}
	public function query($query,$err="")
	{
		$this->success=false;
		$this->push_result();
		$this->result=$this->execute_sql_query($query,$err);
		if($this->result!==false)
		{
			$this->success=true;
            if( gettype($this->result)!=='boolean') {
                $this->num_rows=mysqli_num_rows($this->result);
            }
		}
		else
			$this->num_rows=0;
		return $this->result;
	}
	public function insert_into($table,$values,$err="")
	{
		$this->success=false;
		try
		{
			$cols=count($values);
			if($cols==0)
				throw 0;
			else if($cols>30)
				throw 30;
			$colsm1=$cols-1;
			$q="INSERT INTO ".$table." VALUES (";
			for($i=0;$i<$cols;$i++)
			{
				$val=$values[$i];
				if(gettype($val)=='string')
					$q.="'$val'";
				else
					$q.=$val;
				if($i!=$colsm1)
					$q.=",";
				
			}
			$q.=")";
			
		}
		catch(integer $ce)
		{
			if($ce==0)
				echo "Error: database.insert_into(): No columns values supplied!";
			else
				echo "Error: database.insert_into(): More than 30 columns values supplied!";
			return false;
		}
		catch(Exception $ex)
		{
			echo $ex;
			return false;
		}
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
			$this->success=true;
		return $this->result;
	}
	public function replace_into($table,$values,$err="")
	{
		$this->success=false;
		try
		{
			$cols=count($values);
			if($cols==0)
				throw 0;
			else if($cols>30)
				throw 30;
			$colsm1=$cols-1;
			$q="REPLACE INTO ".$table." VALUES (";
			for($i=0;$i<$cols;$i++)
			{
				$val=$values[$i];
				if(gettype($val=="string"))
				{
					$q.="'".mysqli_escape_string(Database::$connection[$this->database],$val)."'";
				}
				else
					$q.=$val;
				if($i!=$colsm1)
					$q.=",";
				
			}
			$q.=")";
			
		}
		catch(integer $ce)
		{
			if($ce==0)
				echo "Error: database.insert_into(): No columns values supplied!";
			else
				echo "Error: database.insert_into(): More than 30 columns values supplied!";
			return false;
		}
		catch(Exception $ex)
		{
			echo $ex;
			return false;
		}
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
			$this->success=true;
		return $this->result;
	}
	
	public function update($table,$set,$where,$err="")
	{
		$this->success=false;
		$q="UPDATE ".$table." SET ".$set.' WHERE('.$where.')';
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
			$this->success=true;
		return $this->result;
	}
	public function select($cols,$from,$conditions,$err="")
	{
		$this->success=false;
		$q="SELECT ".$cols." FROM ".$from.' '.$conditions;
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
		{
			$this->success=true;
			$this->num_rows=mysqli_num_rows($this->result);
		}
		else
			$this->num_rows=0;
		return $this->result;
	}
    
	public function disconnect()
	{
		mysqli_close(Database::$connection[$this->database]);
	}
	
	public function chage_database($new_db,$err="")
	{
		$this->success=false;
		$q="USE ".$new_db;
		$this->push_result();
		$this->result=$this->execute_sql_query($q,$err);
		if($this->result!==false)
		{
			$this->database=$new_db;
			$this->success=true;
		}			
		return $this->result;
	}

	public function free_result() {
		if(is_resource($this->result)) {
			mysqli_free_result($this->result);
		}
	}
	public function __destruct()
	{
		foreach($this->prev_results as $result) {
			if(is_resource($result))
				mysqli_free_result($result);
		}
		foreach(Database::$connection as $cn) {
			if(is_resource($cn)) {
				mysqli_close($cn);
			}
		}
	}

}
