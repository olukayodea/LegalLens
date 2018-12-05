<?php
	header('Content-disposition: attachment; filename=Format.csv');
	header('Content-type: application/csv');
	readfile('Format.csv');
?>