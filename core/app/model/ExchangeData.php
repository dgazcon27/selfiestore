<?php 

	class ExchangeData {
		public static $tablename = "exchange";

		public function ExchangeData(){
			$this->description = "";
			$this->value = 0;
			$this->created_at = "NOW()";
		}

		public function add(){
			$sql = "insert into ".self::$tablename." (description,value, created_at) value (\"$this->description\",$this->value, $this->created_at)";
			return Executor::doit($sql);
		}

		public static function delById($id){
			$sql = "delete from ".self::$tablename." where id=$id";
			Executor::doit($sql);
		}
		
		public function update(){
			$sql = "update ".self::$tablename." set description=\"$this->description\", value=$this->value, created_at=$this->created_at where id=$this->id";
			Executor::doit($sql);
		}

		public static function getById($id){
			$sql = "select * from ".self::$tablename." where id=$id";
			$query = Executor::doit($sql);
			return Model::one($query[0],new ExchangeData());
		}

		public static function getAll(){
			$sql = "select * from ".self::$tablename;
			$query = Executor::doit($sql);
			return Model::many($query[0],new ExchangeData());
		}
	}

?>