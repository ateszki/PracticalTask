<?php

class contactList {
	public $dbh;
	
	public $pk;
	public $fields;
	private $tableName;
	
	function __construct(){
		global $MyDb;
		$this->dbh = $MyDb->dbh;
		$this->tableName = 'contacts';
		$this->pk = "Id";
		$this->fields = array("Id","FirstName","LastName","Country","City","Address","Email");
	}
	
	function listPage($page=1,$rowsPerPage=10,$orderby='Id'){
		try {
			if(!$this->checkOrderBy($orderby)){var_dump($orderby);return false;}	
			$limit = ($page-1)*$rowsPerPage.",".$rowsPerPage;
			$stmt = $this->dbh->prepare("select ".implode(",",$this->fields)." from ".$this->tableName." order by $orderby limit $limit");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $ex) {
		    die("An Error occured! ".$ex->getMessage()); //user friendly message
		}		
	}	

	function countPages($rowsPerPage=10){
		$stmt = $this->dbh->query("select * from ".$this->tableName);
		$rowCount = $stmt->rowCount();
		return ceil($rowCount/$rowsPerPage);
		
	}
	
	function checkOrderBy($orderby){
		$out = false;	
		foreach($this->fields as $f){
			if (strpos($orderby,$f)!== false){
					$out = true;
			}
		}	
		return $out;	
	} 
}
