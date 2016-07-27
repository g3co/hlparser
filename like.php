<?php include('lib/dblib.php');?>


	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
<?php


$i = 0;
echo "<table>";
foreach (QueryGet("SELECT OC.model FROM `oc_ocfilter_option_value_description` OVD LEFT JOIN oc_ocfilter_option_value_to_product OVP ON OVD.value_id = OVP.value_id LEFT JOIN oc_product OC ON OC.product_id=OVP.product_id WHERE OVD.name LIKE '" . $_GET[ 'name' ] . "'") as $row) {
	echo "<tr>";
	echo "<td>" . ++$i . "</td>";
	foreach ($row as $cell) {
		echo "<td>" . $cell . "</td>";
	}
	echo "</tr>";
	//     print_r($row);
}
echo "</table>";