<?php
class OperationData {
	public static $tablename = "operation";

	public function OperationData(){
		$this->name = "";
		$this->product_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->status = "1";
		$this->operation_type_id = "";
		$this->operation_from_id = "NULL";
		$this->created_at = "NOW()";
		$this->sell_id = "";
		$this->q_approved = "";
		$this->id = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (status, price_in,price_out,stock_id,product_id,q,operation_type_id,sell_id,operation_from_id,created_at) ";
		$sql .= "value ($this->status, $this->price_in,$this->price_out,$this->stock_id,\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,$this->operation_from_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_cotization(){
		$sql = "insert into ".self::$tablename." (price_in,price_out,is_draft,stock_id,product_id,q,operation_type_id,sell_id,created_at) ";
		$sql .= "value ($this->price_in,$this->price_out,$this->is_draft,$this->stock_id,\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function inProcessCotization($id){
		$sql = "update ".self::$tablename." set operation_type_id=8 where sell_id=$id";
		Executor::doit($sql);
	}

	public function confirmCotization($id){
		$sql = "update ".self::$tablename." set is_draft=0 where sell_id=$id";
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

	public function delete_by_sell_id($id){
		$sql = "delete from ".self::$tablename." WHERE sell_id=$id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public function set_draft($d){
		$sql = "update ".self::$tablename." set is_draft=\"$d\" where id=$this->id";
		Executor::doit($sql);
	}


	public function update_q(){
		$sql = "update ".self::$tablename." set q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public function cancel(){
		$sql = "update ".self::$tablename." set status=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function update_type(){
		$sql = "update ".self::$tablename." set operation_type_id=\"$this->operation_type_id\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_q_approved(){
		$sql = "update ".self::$tablename." set operation_type_id=7, q_approved=\"$this->q_approved\" where id=\"$this->id\" ";
		Executor::doit($sql);
	}

	public function update_status(){
		$sql = "update ".self::$tablename." set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new OperationData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());

	}

	public static function getAllByOT($p,$ot,$s){
		$sql = "select * from ".self::$tablename." where product_id=$p and operation_type_id=$ot and status=1 and stock_id=$s";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}



	public static function getAllByDateOfficial($stock,$start,$end){
 $sql = "select * from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and stock_id=$stock order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


public static function getPPByDateOfficial($start,$end){
 $sql = "select *,sum(q) as total from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and operation_type_id=2 group by product_id order by total desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByDateOfficialBP($stock,$product, $start,$end){
 $sql = "select * from ".self::$tablename." where (date(created_at) >= \"$start\" and date(created_at) <= \"$end\") and product_id=$product and stock_id=$stock order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public function getProduct(){ return ProductData::getById($this->product_id);}
	public function getOperationtype(){ return OperationTypeData::getById($this->operation_type_id);}



	public static function getQ($product_id){
		$q=0;
		$operations = self::getAllByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
				else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}



	public static function getQByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
				else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		return $q;
	}

	public static function getStockOfProductsAvailables() {
		$products = ProductData::getAll();
		$response = array();
		if (count($products)>0) {
			foreach ($products as $product) {
				$stock = StockData::getPrincipal()->id;
				$ope = OperationData::getQByStock($product->id, $stock);
				if ($ope > 0) {
					$product->q = $ope;
					$product->stock = $stock;
					array_push($response,$product);
				} elseif (Core::$user->kind == 8 || Core::$user->kind == 2) {
					$stock = Core::$user->stock_id;
					$ope = OperationData::getQByStock($product->id,$stock);
					$product->q = $ope;
					$product->stock = $stock;
					array_push($response,$product);
				}
			}
		}
		return $response;
	}

	public function updateOrderToSell($id){		
		$sql = "update ".self::$tablename." set operation_type_id=2 where sell_id=$id";
		Executor::doit($sql);
	}

	public function updateOrderToSellToSucursal($id){		
		$sql = "update ".self::$tablename." set operation_type_id=2, price_in=0,price_out=0 where sell_id=$id";
		Executor::doit($sql);
	}

	public static function getRByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada-pendiente")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	}

	public static function getDByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getAllByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("salida-pendiente")->id;
		foreach($operations as $operation){
				if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	}

	public static function getAllByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductIdAndStock($product_id,$stock_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and stock_id=$stock_id and is_draft=0 and status=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getAllByProductIdCutIdOficial($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
	}


	public static function getAllProductsBySellId($sell_id){
		$sql = "select * from ".self::$tablename." where sell_id=$sell_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getAllByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id order by created_at desc";
		return Model::many($query[0],new OperationData());
		return $array;
	}

////////////////////////////////////////////////////////////////////
	public static function getOutputQ($product_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutId($product_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($product_id){
		$q=0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQByStock($product_id,$stock_id){
		$q=0;
		$operations = self::getInputByProductIdAndStock($product_id,$stock_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
		}
		// print_r($data);
		return $q;
	}



	public static function getOutputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}


	public static function getOutputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

////////////////////////////////////////////////////////////////////
	public static function getInputQ($product_id,$cut_id){
		$q=0;
		return Model::many($query[0],new OperationData());
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operation){
			if($operation->operation_type_id==$input_id){ $q+=$operation->q; }
			else if($operation->operation_type_id==$output_id){  $q+=(-$operation->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getInputByProductIdCutId($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductId($product_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductIdAndStock($product_id,$stock_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and operation_type_id=1 and stock_id=$stock_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

	public static function getInputByProductIdCutIdYesF($product_id,$cut_id){
		$sql = "select * from ".self::$tablename." where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationData());
	}

////////////////////////////////////////////////////////////////////////////


}

?>