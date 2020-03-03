<?php


// 14 de Abril del 2014
// Core.php
// @brief obtiene las configuraciones, muestra y carga los contenidos necesarios.
// actualizado [11-Aug-2016]
class Core {
	public static $user = null;
	public static $debug_sql = false;

	public static $email_user ="";
	public static $email_password ="";

	public static $pdf_footer = "Generado por el Sistema de Inventario";
	public static $email_footer = "Correo generado Automaticamente por el Sistema de Inventario";

	public static $pdf_table_fillcolor = "[60, 141, 188]";
	public static $black_color = "[0,0,0]";
	public static $blue_sky = "[60,170,188]";
	public static $pdf_table_column_fillcolor = "255";
	public static $send_alert_emails = true; // enviar correos de alerta (ventas,abastecimientos, etc) -> MailData->send()
	public $base_cell_report_global = 58;
	public $current_cell_position = 0;
	public $space_cell = 0;



	function setSpaceCell($space){
		$this->space_cell=$space;
	}

	function getSpaceCell()
	{
		return $this->space_cell;
	}

	function setCurrentCellPosition($position){
		$this->current_cell_position = $position;
	}

	function getCurrentCellPosition(){
		return $this->current_cell_position;
	}

	function getNextSpaceCell($space = 0){
		if ($space != 0) {
			$this->setCurrentCellPosition($this->current_cell_position+$space);	
		} else {
			$this->setCurrentCellPosition($this->current_cell_position+$this->space_cell);
		}
		return $this->getCurrentCellPosition();
	}


	public static function includeCSS(){
		$path = "res/css/";
		$handle=opendir($path);
		if($handle){
			while (false !== ($entry = readdir($handle)))  {
				if($entry!="." && $entry!=".."){
					$fullpath = $path.$entry;
				if(!is_dir($fullpath)){
						echo "<link rel='stylesheet' type='text/css' href='".$fullpath."' />";

					}
				}
			}
		closedir($handle);
		}

	}

	public static function alert($text){
		echo "<script>alert('".$text."');</script>";
	}

	public static function redir($url){
		echo "<script>window.location='".$url."';</script>";
	}

	public static function back(){
		echo "<a class='btn btn-default pull-right' onclick='return window.history.back()'>
				<span>REGRESAR</span>
			</a>";
	}

	

	public static function includeJS(){
		$path = "res/js/";
		$handle=opendir($path);
		if($handle){
			while (false !== ($entry = readdir($handle)))  {
				if($entry!="." && $entry!=".."){
					$fullpath = $path.$entry;
				if(!is_dir($fullpath)){
						echo "<script type='text/javascript' src='".$fullpath."'></script>";

					}
				}
			}
		closedir($handle);
		}

	}

}



?>