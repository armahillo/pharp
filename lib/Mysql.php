<?php
class Mysql {
	private $linkid;		// MySQL link identifier
	private $host;			// MySQL Host
	private $user;			// MySQL User
	private $pswd;			// MySQL Password
	private $db;			// MySQL database (to use)
	private $result;		// Query result
	private $querycount;		// Total Queries executed

	// Constructor //
	function __construct($host, $user, $pswd, $db)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pswd = $pswd;
		$this->db = $db;
	}
	
	// Connects to the MySQL database server //
	function connect() 
	{
		try
		{
			$this->linkid = @mysql_connect($this->host, $this->user, $this->pswd);
			if (! $this->linkid)
				throw new Exception("Could not connect to the MySQL Server");
		}
		catch (Exception $e)
		{
			die ($e->getMessage());
		}
	}
	// Select your database
	function select()
	{
		try {
			if (! @mysql_select_db($this->db, $this->linkid))
				throw new Exception("Could not connect to the MySQL database");
		}
		catch (Exception $e)
		{
			die ($e->getMessage());
		}
	}

	// Execute Query
	function query($query)
	{
		try 
		{
			$this->result = @mysql_query($query, $this->linkid);
			if (! $this->result)
				throw new Exception("The database query failed. : " . $query);
		}
		catch (Exception $e)
		{
			echo ($e->getMessage());
		}
		$this->querycount++;
		return $this->result;
	}
	
	// Determine number of rows affected by query
	function num_rows()
	{
		$count = @mysql_num_rows($this->result);
		return $count;
	}

	// Return query result row as an object
	function fetch_object() 
	{
		$row = @mysql_fetch_object($this->result);
		return $row;
	}

	// Return query result row as an indexed array
	function fetch_row() 
	{
		$row = @mysql_fetch_row($this->result);
		return $row;
	}

	// Return query result as an associative array
	function fetch_array() 
	{
		$row = @mysql_fetch_array($this->result);
		return $row;
	}


	// Return query result as an associative array
	function fetch_assoc() 
	{
		$row = @mysql_fetch_assoc($this->result);
		return $row;
	}

	// Return total number of queries executed during the lifetime of the object
	function num_queries()
	{
		return $this->querycount;
	}
	
	// Return last ID by auto_increment
	function last_id()
	{
		return mysql_insert_id($this->linkid);
	}	
}	
?>
