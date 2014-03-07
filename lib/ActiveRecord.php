<?php

abstract class ActiveRecord {
	protected static $dbh;
	protected static $table_name;
	protected $data = array();
	protected $dirty_data = array();
	
	function __construct($data) {
	    $this->data = $data;
	    if (!isset($data['id'])) { $this->dirty_data = $data; }
	}
	
	static function find($id) {
		$id = mysql_real_escape_string($id);
		self::$dbh->query("SELECT * FROM " . static::$table_name . " WHERE id=$id LIMIT 1");
		return new static(self::$dbh->fetch_assoc());
	}
	
	static function first($field = "created_at") {
		self::$dbh->query("SELECT * FROM " . static::$table_name . " ORDER BY $field ASC LIMIT 1");
		return new static(self::$dbh->fetch_assoc());
	}
	
	static function last($field = "created_at") {
		self::$dbh->query("SELECT * FROM " . static::$table_name . " ORDER BY $field DESC LIMIT 1");
		return new static(self::$dbh->fetch_assoc());
	}
	
	static function all($limit = 50, $page = 1) {
		self::$dbh->query("SELECT * FROM " . static::$table_name . " LIMIT $page,$limit");
		$result = array();
		while($row = self::$dbh->fetch_assoc()) {
			$result[] = new static($row);
		}
		return $result;
	}
	
	function save() {
		if (empty($this->dirty_data)) { return true; }
		$success = false;
		if (isset($this->data['id'])) {
		  $query = "UPDATE " . static::$table_name . " SET ";
		  $fields = "";
		  foreach($this->dirty_data as $field => $value) {
			$fields .= "`$field`='$value', ";
		  }
		  $fields .= "`updated_at`=CURRENT_TIMESTAMP ";
		  $query .= $fields . "WHERE id={$this->id}";
		  $success = self::$dbh->query($query);
		} else {
			$query = "INSERT INTO " . static::$table_name . " (`";
			$field_names = array_keys($this->dirty_data);
			$field_data = array_values($this->dirty_data);
			$query .= join("`,`",$field_names);
			$query .= "`, `updated_at`) VALUES ('" . join("`,`", $field_data) . "`, CURRENT_TIMESTAMP)";
			$success = self::$dbh->query($query);
			if ($success) { $this->data['id'] = self::$dbh->last_id(); }
		}
		if ($success) {
		  $this->data = array_merge($this->data, $this->dirty_data);
	    }
	    return $success;
	}
	
	function __get($field) {
		$field = strtolower($field);
		if (isset($this->dirty_data[$field])) return $this->dirty_data[$field];
		elseif (isset($this->data[$field])) return $this->data[$field];
		else return NULL;
	}
	
	function __set($field, $value) {
		$field = strtolower($field);
		if ($field != "id" 
		    && $field != "created_at"
		    && $field != "updated_at"
		    && isset($this->data[$field])) { $this->dirty_data[$field] = $value; return true; }
		return false;
	}

	function __toString() {
		return var_dump(array_merge($this->data, $this->dirty_data));
	}
	
	static final public function set_db($dbh) {
		if (empty(self::$dbh)) {
		  self::$dbh = $dbh;
		}
	}
}

?>
