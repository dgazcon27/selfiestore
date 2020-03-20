<?php
class SellData {
	public static $tablename = "sell";

	public function SellData(){
		$this->created_at = "NOW()";
		$this->ref_id=NULL;
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}
	public function getSellUser($id){ return UserData::getById($id);}
	public function getP(){ return PData::getById($this->p_id);}
	public function getD(){ return DData::getById($this->d_id);}
	public function getStockFrom(){ return StockData::getById($this->stock_from_id);}
	public function getStockTo(){ return StockData::getById($this->stock_to_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (invoice_code,invoice_file,comment,ref_id,person_id,stock_to_id,iva,f_id,p_id,d_id,total,discount,cash,user_id,created_at,refe,efe,tra,zel,pun,receive_by, is_official,change_sell,type_change)";
		$sql .= "value (\"$this->invoice_code\",\"$this->invoice_file\",\"$this->comment\",$this->ref_id,$this->person_id,$this->stock_to_id,$this->iva,$this->f_id,$this->p_id,$this->d_id,$this->total,$this->discount,$this->cash,$this->user_id,$this->created_at,\"$this->refe\",$this->efe,$this->tra,$this->zel,$this->pun,$this->receive_by,0,$this->change_sell, $this->type_change)";
		return Executor::doit($sql);
	}
	public function add_traspase(){
		$sql = "insert into ".self::$tablename." (stock_to_id,stock_from_id,operation_type_id,iva,p_id,d_id,total,discount,user_id,created_at) ";
		$sql .= "value ($this->stock_to_id,$this->stock_from_id,6,$this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}


	public function add_cotization(){
		$sql = "insert into ".self::$tablename." (is_draft,p_id,d_id,user_id,created_at, is_cotization) ";
		$sql .= "value (1,2,2,$this->user_id,$this->created_at, $this->is_cotization)";
		return Executor::doit($sql);
	}

	public function add_cotization_by_client(){
		$sql = "insert into ".self::$tablename." (is_draft,p_id,d_id,person_id,created_at, is_cotization, user_id) ";
		$sql .= "value (1,2,2,$this->person_id,$this->created_at, $this->is_cotization, $this->user_id)";
		return Executor::doit($sql);
	}

	function changeSell(){
		$sql = "update ".self::$tablename." set person_id=$this->person_id, f_id=$this->f_id, refe=\"$this->refe\" where id = $this->id";
		return Executor::doit($sql);
	}

	public function update_cotization($id)	{
		$sql = "update ".self::$tablename." set total=$this->total where id=$id";
		Executor::doit($sql);
	}

	public function updateImei(){
		$sql = "update ".self::$tablename." set comment=\"$this->comment\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function add_de(){
		$sql = "insert into ".self::$tablename." (status,stock_to_id,sell_from_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (0,$this->stock_to_id,$this->sell_from_id,$this->user_id,5,$this->created_at)";
		return Executor::doit($sql);
	}


	public function add_re(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,total,p_id,d_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (\"$this->invoice_code\",$this->f_id,$this->ref_id, $this->person_id,$this->stock_to_id,$this->total,$this->p_id,$this->d_id,$this->user_id,1,$this->created_at)";
		return Executor::doit($sql);
	}


public function add_with_client(){	
		$sql = "insert into ".self::$tablename." (iva,p_id,d_id,total,discount,person_id,user_id,created_at) ";
		$sql .= "value ($this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->person_id,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_re_with_client(){
		$sql = "insert into ".self::$tablename." (p_id,d_id,person_id,operation_type_id,user_id,created_at) ";
		$sql .= "value ($this->p_id,$this->d_id,$this->person_id,1,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}

	public function del(){
		$sql = "update ".self::$tablename." set d_id=8 where id=$this->id";
		Executor::doit($sql);
	}

	public function process_cotization(){
		$sql = "update ".self::$tablename." set ref_id=$this->ref_id, stock_to_id=$this->stock_to_id,d_id=$this->d_id,iva=$this->iva,total=$this->total,is_draft=0,is_cotization=0,person_id=$this->person_id, f_id=$this->f_id, receive_by=$this->receive_by where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set refe=$this->refe,efe=$this->efe,tra=$this->tra,zel=$this->zel,pun=$this->pun,total=$this->total,f_id=$this->f_id,person_id=$this->person_id,invoice_code=\"$this->invoice_code\",invoice_file=\"$this->invoice_file\",comment=\"$this->comment\",discount=\"$this->discount\", created_at=$this->created_at where id=$this->id";
		Executor::doit($sql);
	}


	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_d(){
		$sql = "update ".self::$tablename." set d_id=$this->d_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_status(){
		$sql = "update ".self::$tablename." set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public function update_p(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id where id=$this->id";
		Executor::doit($sql);
	}
	public function update_date(){
		$sql = "update ".self::$tablename." set created_at=NOW() where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}


	public function cancel(){
		$sql = "update ".self::$tablename." set d_id=3,p_id=3 where id=$this->id";
		Executor::doit($sql);
	}

	public function getCancelsCotizacion(){
		$sql = "select * from ".self::$tablename." where d_id=3 and is_cotization=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function getCancelsCotizacionByUser($id){
		$sql = "select * from ".self::$tablename." where (d_id=3 or d_id=8) and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function getDeleteCotizacion(){
		$sql = "select * from ".self::$tablename." where d_id=8 and is_cotization=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function getDeleteCotizacionByUser($id){
		$sql = "select * from ".self::$tablename." where d_id=8 and is_cotization=1 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function getOrdersDelete(){
		$sql = "select * from ".self::$tablename." where d_id=8 and is_cotization=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function getOrdersCancels(){
		$sql = "select * from ".self::$tablename." where d_id=3 and is_cotization=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getCotizations(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and (d_id=2 or d_id=4) order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCotizationsForManager(){
		$sql = "select * from ".self::$tablename." where d_id=4 or d_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function inProcessCotization($id){
		$sql = "update ".self::$tablename." set d_id=4, is_draft=0 where id=$id";
		Executor::doit($sql);
	}

	public function updateOrderToSell(){

		$sql = "update ".self::$tablename." set is_official=0, p_id=$this->p_id,f_id=$this->f_id,refe=$this->refe,efe=$this->efe,pun=$this->pun,tra=$this->tra,zel=$this->zel,discount=$this->discount,total=$this->total,cash=$this->money where id=$this->id";
		Executor::doit($sql);
	}

	public function updateOrderToSellToSucursal(){
		
		$sql = "update ".self::$tablename." set is_official=0, p_id=$this->p_id,f_id=$this->f_id,refe=$this->refe,efe=$this->efe,pun=$this->pun,tra=$this->tra,zel=$this->zel,discount=$this->discount,total=0,cash=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function setStatusSell($id, $status)	{
		$sql = "update ".self::$tablename." set d_id=$status where id=$id";
		Executor::doit($sql);
	}

	public static function getOrdersApproved(){
		$sql = "select * from ".self::$tablename." where d_id=5 or d_id=7 or d_id=9 or d_id=10 or d_id=11 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getOrdersApprovedForManager(){
		$sql = "select * from ".self::$tablename." where is_official=1 and (d_id=5 or d_id=7 or d_id=9 or d_id=10 or d_id=11) and is_official=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getOrdersForManager(){
		$sql = "select * from ".self::$tablename." where d_id=5 or d_id=7 or d_id=11 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getOrdersApprovedByUser($id){
		$data_person = $id;
		if (isset(PersonData::getByUserId($id)->id)) {
			$data_person = PersonData::getByUserId($id)->id;
		}
		$sql = "select * from ".self::$tablename." where (user_id=$id or person_id=$data_person) and (d_id=5 or d_id=7 or d_id=1 or d_id=9 or d_id=10 or d_id=11) order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCotizatiosByUser($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCotizationsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and (d_id=2 || d_id=4) and (person_id=$id or user_id=$id) order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSells(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and (d_id=1 or d_id=11) and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsForManager(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and is_official=0 and (d_id != 3 or d_id != 8) order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getCredits(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsToDeliver(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliverByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliverByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsToDeliverByClient($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCob(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToCobByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 and is_official=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPay(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPayByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToReceive(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToReceiveByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateOp($start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOpCredit($start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=4 and payments != \"NULL\" order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOpByUserId($user,$start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and receive_by=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


		public static function getGroupByDateOp($start,$end,$op){
  $sql = "select id,sum(total) as tot,discount,sum(total-discount) as t,count(*) as c from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateBCOpByUserId($user,$clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and and d_id=1 and receive_by=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	/* Actualizar abonos de la venta */
	public function updateSellPaymentById($id,$payments){
		$sql = "update ".self::$tablename." set payments=$payments where id=$id";
		Executor::doit($sql);
	}

}

?>