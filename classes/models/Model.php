<?php

class Model
{
	protected $pdo;

	public function __construct()
	{
        try {
		    $this->pdo = new PDO('mysql:host=localhost;dbname=jury', 'root', '');
		    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
        	echo 'Database error: ' . $e->getMessage();
        }
	}
}
        	