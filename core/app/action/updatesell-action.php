<?php

$sell = SellData::getById($_POST["id"]);

$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";
$sell->f_id = $_POST["f_id"];
$sell->refe = $_POST["refe"];
$sell->efe = $_POST["efe"];
$sell->tra = $_POST["tra"];
$sell->zel = $_POST["zel"];
$sell->total = $_POST["total"];

$sell->invoice_code = $_POST["invoice_code"];





$sell->comment  =$_POST["comment"];

$sell->invoice_file = "";

  if(isset($_FILES["invoice_file"])){
    $image = new Upload($_FILES["invoice_file"]);
    if($image->uploaded){
      $image->Process("storage/invoice_files/");
      if($image->processed){
        $sell->invoice_file = $image->file_dst_name;
      }
    }
  }

$sell->update();

Core::redir("./?view=onesell&id=".$_POST["id"]);
?>