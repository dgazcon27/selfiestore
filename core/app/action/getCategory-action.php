<?php 
	if (isset($_GET['category'])) {
		$var = $_GET['category'];
		$cat = CategoryData::getCategoryByName('"'.$var.'"');
		if (count($cat) > 0) {
			echo json_encode($cat);
		} else {
			$category = new CategoryData();
			$category->name = $var;
			$category->created_at = "NOW()";
			$id = $category->add();
			echo json_encode(array('id'=>$id[1]));
		}
	} else if(isset($_GET['brand'])) {
		$var = $_GET['brand'];
		$cat = BrandData::getBrandByName("'".$var."'");
		if (count($cat) > 0) {
			echo json_encode($cat);
		} else {
			$brand = new BrandData();
			$brand->name = $var;
			$brand->created_at = "NOW()";
			$id = $brand->add();
			$cat = array('id'=>$id[1]);
			echo json_encode($cat);
		}
	}
?>