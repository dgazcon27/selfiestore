<?php 
	if (isset($_GET['id'])) {
		$sell = SellData::inProcessCotization($_GET['id']);
		$sell = OperationData::inProcessCotization($_GET['id']);
		print "<script>window.location='index.php?view=cotizations';</script>";
	}
?>