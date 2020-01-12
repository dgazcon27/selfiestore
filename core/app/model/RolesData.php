<?php 

class RolesData {
	
	public static $tablename = "roles";

	function RolesData()
	{
		$this->name = "";
		$this->role = "";
	}

	public function getById($id)
	{
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ConfigurationData());
	}
}

?>