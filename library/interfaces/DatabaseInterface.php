<?php

interface DatabaseInterface
{
    public function __get($name);   

	public function execute_sql_query($q,$err='');

	public function create_temporary_table($query,$err="");

	public function query($query,$err="");

	public function insert_into($table,$values,$err="");

	public function replace_into($table,$values,$err="");	

	public function update($table,$set,$where,$err="");

	public function select($cols,$from,$conditions,$err="");

    public function disconnect();	

	public function chage_database($new_db,$err="");

	public function free_result();    
}