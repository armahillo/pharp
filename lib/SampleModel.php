<?php
// schema: sample_models
// id : int, pk
// name : varchar
// keyword: varchar
// size : int


class SampleModel extends ActiveRecord {
	protected static $table_name = "sample_models";	
	private $data_from_sample_association = array();
	
	function sample_association($reload = false) {
		if (!$reload && !empty($this->data_from_sample_association)) return $this->data_from_sample_association;
		$sql = "SELECT * FROM sample_association WHERE sample_association_id=" . $this->id . " ORDER BY fieldname ASC";
		static::$dbh->query($sql);
		while ($row = static::$dbh->fetch_assoc()) {
			$this->data_from_sample_association[] = new SampleAssociation(array(
			                                             "id"=>$row['sample_association_id'],
			                                             "name"=>$row['name'],
			                                             "created_at"=>$row['sample_association_created_at'],
			                                             "updated_at"=>$row['sample_association_updated_at'],
			                                             ));
		}
		return $this->data_from_sample_association;
	}
}

?>