<?php
class query {
	private $rows_affected = 0;
	private $result;
	private $statement = null;
	private $pdo = null;
	public function __construct($con = null,$sql = null)
	{

		$this->pdo = $con;
		if($sql != null)
			$this->setQuery($sql);
	}
	public function __destruct()
	{
		$this->statement->closeCursor();
	}
	
	public function setQuery($sql)
	{
		$this->statement = $this->pdo->prepare($sql);
		if($this->statement->execute())
		{
			$this->rows_affected = $this->statement->rowCount();
			if($this->rows_affected > 0)
				return true;
			else
			{
				$this->rows_affected = 0;
				return false;
			}
		}else
		{
			$this->rows_affected = 0;
			return false;
		}

	}

	public function fetchAll($tipo = 0)
	{
		if($this->rows_affected > 0)
		{
			if($tipo == 0) //todos
				return $this->statement->fetchAll(PDO :: FETCH_BOTH);
			else
			if($tipo == 1)//penas os nomes dos campos
				return $this->statement->fetchAll(PDO :: FETCH_ASSOC);
			else
			if($tipo == 2)//apenas como classe
				return $this->statement->fetchAll(PDO::FETCH_CLASS);
		}else
			return false;
	}


	public function fetch($tipo = 0)
	{
		if($this->rows_affected > 0)
		{
			if($tipo == 0) //todos
				return $this->statement->fetch(PDO :: FETCH_BOTH);
			else
			if($tipo == 1)//penas os nomes dos campos
				return $this->statement->fetch(PDO :: FETCH_ASSOC);
		}else
			return false;

	}

	public function rows_affected()
	{
		return $this->statement->rowCount();
	}


}	