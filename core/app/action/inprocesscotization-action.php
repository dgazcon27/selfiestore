<?php
	if (isset($_GET['id'])) {
		$sell = SellData::inProcessCotization($_GET['id']);
	}
?>