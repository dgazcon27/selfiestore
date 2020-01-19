<?php
class ProductData {
	public static $tablename = "product";

	public function ProductData(){
		$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->image = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";
	}

	public function getBrand(){ return BrandData::getById($this->brand_id);}
	public function getCategory(){ return CategoryData::getById($this->category_id);}
	

	public function add(){
		$sql = "insert into ".self::$tablename." (image,kind,code,brand_id,barcode,name,price_in,price_out,user_id,unit,category_id,inventary_min,created_at, expired_alert, expire_at) ";
		$sql .= "value (\"$this->image\",\"$this->kind\",\"$this->code\",$this->brand_id,\"$this->barcode\",\"$this->name\",\"$this->price_in\",\"$this->price_out\",$this->user_id,\"$this->unit\",$this->category_id,$this->inventary_min,NOW(), \"$this->expired_alert\", \"$this->expire_at\")";
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

// partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",price_in=\"$this->price_in\",price_out=\"$this->price_out\",category_id=$this->category_id,inventary_min=\"$this->inventary_min\",is_active=\"$this->is_active\",code=\"$this->code\",brand_id=$this->brand_id where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}
	
	public function del_brand(){
		$sql = "update ".self::$tablename." set brand_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_prices(){
		$sql = "update ".self::$tablename." set price_in=\"$this->price_in\",price_out=\"$this->price_out\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
	
	public static function getAllByBrandId($id){
		$sql = "select * from ".self::$tablename." where brand_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeResponsive($p){
		$sql = "select * from ".self::$tablename." where code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' LIMIT 0,10";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getTotalSearchResponsive($p) {
		$sql = "select id from ".self::$tablename." where code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeResponsivePaginate($p, $page){
		$min = $page*10;
		$max = $min+10;
		$sql = "select * from ".self::$tablename." where code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%' LIMIT $min,$max";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}




	public static function getLike2($p){
		$sql = "select * from ".self::$tablename." where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and kind=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}

?>