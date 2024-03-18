<?php

require '../arranger.php';

header('Location: view.php');
header('Content-Type: application/json');

$output = [
	'success' => false,
	'postData' => $_POST,
	'error' => '',
	'code' => 0,
];


if (!empty($_POST['Name'])) {

	$table = 'Coupon';


	$statement = connect()->prepare("INSERT INTO {$table}
	(`Name`, `Explanation`, `DiscountRate`, `WhenEnded`, `CreatorSN`) VALUES (?, ?, ?, ?, 10)");

	$statement->execute([
		$_POST['Name'],
		$_POST['Explanation'],
		$_POST['DiscountRate'],
		$_POST['WhenEnded']
	]);

	$output['success'] = boolval(($statement->rowCount()));
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);
