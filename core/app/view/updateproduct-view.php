<?php

if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);

	$product->barcode = $_POST["barcode"];
	$product->name = $_POST["name"];
	$product->price_in = $_POST["price_in"];
	$product->price_out = $_POST["price_out"];
	$product->unit = $_POST["unit"];


  $product->inventary_min = $_POST["inventary_min"];

  $product->brand_id=$_POST["brand_id"]!=""?$_POST["brand_id"]:"NULL";
  $product->category_id=$_POST["category_id"]!=""?$_POST["category_id"]:"NULL";
  $product->inventary_min=$_POST["inventary_min"]!=""?$_POST["inventary_min"]:"10";


	$product->user_id = $_SESSION["user_id"];
	$product->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/products/");
			if($image->processed){
				$product->image = $image->file_dst_name;
				$product->update_image();
			}
		}
	}

	setcookie("prdupd","true");
	print "<script>window.location='index.php?view=products';</script>";


}


?>