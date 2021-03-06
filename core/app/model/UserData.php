<?php
class UserData {
	public static $tablename = "user";

	public function getStock(){ return StockData::getById($this->stock_id); }

	public function Userdata(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into user (comision,name,lastname,username,email,image,kind,stock_id,password,created_at) ";
		$sql .= "value ($this->comision,\"$this->name\",\"$this->lastname\",\"$this->username\",\"$this->email\",\"$this->image\",\"$this->kind\",$this->stock_id,\"$this->password\",$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto UserData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email=\"$this->email\",lastname=\"$this->lastname\",stock_id=$this->stock_id,image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;		
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getSellers(){
		$sql = "select * from ".self::$tablename." where kind=5 or kind=1 or kind=3 or kind=8";		
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getClients(){
		$sql = "select * from ".self::$tablename." where kind=4";		
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getBranchs(){
		$sql = "select * from ".self::$tablename." where kind=8";		
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public function checkUsername($username){
		$sql = "select id from ".self::$tablename." where username='$username'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

}

?>