<?php
class PersonData {
	public static $tablename = "person";


	public function PersonData(){
		$this->name = "";
		$this->lastname = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
		$this->credit_limit = "NULL";
	}

	public function add_client(){
		$sql = "insert into person (no,ci,name,lastname,email1,address2,phone1,phone2,company,rif,is_active_access,kind,credit_limit,has_credit,created_at, user_id)";
		$sql .= "value (\"$this->no\",\"$this->ci\",\"$this->name\",\"$this->lastname\",\"$this->email1\",\"$this->address2\",\"$this->phone1\",\"$this->phone2\",\"$this->company\",\"$this->rif\", \"$this->is_active_access\",$this->kind,\"$this->credit_limit\",$this->has_credit,$this->created_at, $this->user_id)";
		Executor::doit($sql);
	}

	function add_solo_client()	{
		$sql = "insert into person (no,ci,name,lastname,address1,phone1,is_active_access,kind,created_at)";

		$sql .= "value (\"$this->no\",\"$this->ci\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->phone1\",$this->is_active_access,4,NOW())";
		Executor::doit($sql);
	}

	public function add_provider(){
		$sql = "insert into person (no,name,address1,phone1,kind,created_at, specialties) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->address1\",\"$this->phone1\",7,$this->created_at, \"$this->specialties\")";
		Executor::doit($sql);
	}


	public function add_contact(){
		$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",3,$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto PersonData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_solo_client(){
		$sql = "update ".self::$tablename." set no=\"$this->no\", name=\"$this->name\",address1=\"$this->address1\",phone1=\"$this->phone1\", credit_limit=$this->credit_limit, is_active_access=$this->is_active_access, has_credit=$this->has_credit where id=$this->id";
		Executor::doit($sql);
	}

	public function update_client(){
		$sql = "update ".self::$tablename." set rif=\"$this->rif\", ci=\"$this->ci\", company=\"$this->company\", name=\"$this->name\",email1=\"$this->email1\",address2=\"$this->address2\", lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_provider(){
		$sql = "update ".self::$tablename." set no=\"$this->no\",name=\"$this->name\",address1=\"$this->address1\",phone1=\"$this->phone1\", specialties=".$this->specialties." where id=$this->id";
		Executor::doit($sql);
	}

	public function update_contact(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}


	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}

	public static function getByUserId($id){
		$sql = "select * from ".self::$tablename." where user_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

	public static function getClients(){
		$sql = "select * from ".self::$tablename." where kind=4 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	function getClientsToSell(){
		$sql = "select * from ".self::$tablename." where kind=4 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getClientsWithCredit(){
		$sql = "select * from ".self::$tablename." where kind=4 and has_credit=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getContacts(){
		$sql = "select * from ".self::$tablename." where kind=3 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getProviders(){
		$sql = "select * from ".self::$tablename." where kind=7 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

	public static function getBranchs() {
		$sql = "select * from ".self::$tablename." where kind = 4 or kind = 8";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}


}

?>