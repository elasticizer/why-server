<?php

require '../arranger.php';

header('Location: index.php');
header('Content-Type: application/json');

$output = [
	'success' => false,
	// 'postData' => $_POST,
	'error' => '',
	// 'code' => 0,
];

if (!empty($_POST['Name'])) {
	$isPass = true;

	if (mb_strlen($_POST['Name']) < 3) {
		$isPass = false;
		$output['error'] = '優惠券名稱必須要三個字以上。';
	}

	$discountRate = intval($_POST['DiscountRate']);
	if ($discountRate % 10 !== 0 || $discountRate < 10 || $discountRate > 90) {
		$isPass = false;
		$output['error'] = '折扣必須在 10% 到 90% 之間，間隔值為10。';
	}

	$existingCoupon = checkCoupon($_POST['Name']);
	if ($existingCoupon) {
		$isPass = false;
		$output['error'] = '該優惠券名稱已經存在。';
		$output['showModal'] = true;
	}

	if ($isPass) {
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
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);

function checkCoupon($couponName)
{
	$table = 'Coupon';
	$statement = connect()->prepare("SELECT * FROM {$table} WHERE `Name` = ?");
	$statement->execute([$couponName]);
	return $statement->fetch(PDO::FETCH_ASSOC);
}
