<?php

class contact {
	public $dbh;
	public $Id; 
	public $FirstName; 
	public $LastName; 
	public $Country;
	public $City;
	public $Address;
	public $Email;
	private $tableName; 
	
	function __construct(){
		global $MyDb;
		$this->dbh = $MyDb->dbh;
		$this->tableName = 'Contacts';
	}
	
	function delete(){
		$stmt = $this->dbh->prepare("delete from ".$this->tableName." where Id = ?");
		return $stmt->execute(array($this->Id));
	}
	
	function create(){
		$stmt = $this->dbh->prepare("INSERT INTO ".$this->tableName." (FirstName,LastName,Country,City,Address,Email) VALUES (?,?,?,?,?,?)");
		
		if ($stmt->execute(array($this->FirstName, $this->LastName, $this->Country, $this->City, $this->Address, $this->Email))){
			$this->Id = $this->dbh->LastInsertId();
			return true;
		} else {
			return false;
		}
		
	}
	
	function update(){
		$stmt = $this->dbh->prepare("update ".$this->tableName." set FirstName = ? , LastName = ? , Country = ? , City = ? , Address = ? , Email = ? where Id = ?");
		return $stmt->execute(array($this->FirstName, $this->LastName, $this->Country, $this->City, $this->Address, $this->Email,$this->Id));
	}
	
}
