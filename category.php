<?php
include('lib/dblib.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1">
<style>
tr:nth-child(odd) {
	 background: #C7C7C7;
}
table {
	border:2px;
}

td {
	//border:1px solid #000;
        min-width: 60px;
        
}
</style>
</head>
<body style="width:900px">
	<table style="width:100%">
		<?php foreach(QueryGet('SELECT PC.*,count(PTC.category_id) as counter FROM `oc_parcer_category` PC LEFT JOIN oc_product_to_category PTC ON PC.id = PTC.category_id GROUP BY PC.id ORDER BY PC.next DESC, PC.parent ASC') as $product):?>
			<tr>
				<td><?php echo $product['id']?></td>
				<td><?php echo $product['target_id']?></td>
				<td><?php echo $product['name']?></td>
				<td><?php echo $product['next']?></td>
				<td><?php echo $product['parent']?></td>
				<td><?php echo $product['margin']?></td>
				<td><?php echo $product['quantity']?></td>
				<td><?php echo $product['counter']?></td>
			</tr>
		<?php endforeach;?>
	</table>
</body>
</html>