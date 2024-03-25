<?php

require '../arranger.php';

header('Content-Type: application/json');

$output = [
	'success' => false,
	'postData' => $_POST,
	'error' => '',
	'code' => 0,
];

if (!empty($_POST['Name'])) {
	$isPass = true;

	if (mb_strlen($_POST['Name']) < 3) {
		$isPass = false;
		$output['error'] = '優惠券名稱必須要三個字以上。';
	}

	if (mb_strlen($_POST['Identifier']) > 9 or mb_strlen($_POST['Identifier']) < 3) {
		$output['error'] = '識別碼必須在三到九個字之間。';
		$isPass = false;
	}

	$discountRate = intval($_POST['DiscountRate']);
	if ($discountRate % 10 !== 0 || $discountRate < 10 || $discountRate > 90) {
		$isPass = false;
		$output['error'] = '折扣必須在 10% 到 90% 之間，間隔值為10。';
	}

	$existingCouponIdentifier = checkCouponIdentifier($_POST['Identifier'], $_POST['SN']);
	if ($existingCouponIdentifier) {
		// 排除目前的
		if ($existingCouponIdentifier['SN'] != $_POST['SN']) {
			$isPass = false;
			$output['error'] = '該優惠券識別碼已經存在。';
		}
	}

	$existingCoupon = checkCoupon($_POST['Name'], $_POST['SN']);
	if ($existingCoupon) {
		if ($existingCoupon['SN'] != $_POST['SN']) {
			$isPass = false;
			$output['error'] = '該優惠券名稱已經存在。';
		}
	}

	if ($isPass) {
		$table = 'Coupon';
		$statement = connect()->prepare("UPDATE {$table} SET `Identifier`=?, `Name`=?, `Description`=?, `DiscountRate`=?, `WhenEnded`=? WHERE `SN`=?");
		$statement->execute([
			$_POST['Identifier'],
			$_POST['Name'],
			$_POST['Description'],
			$_POST['DiscountRate'],
			$_POST['WhenEnded'],
			$_POST['SN']
		]);

		$output['success'] = boolval(($statement->rowCount()));
	}
} else {
	$output['error'] = '優惠券名稱不能為空。';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

function checkCoupon($couponName, $currentCouponSN)
{
	// $couponName是要檢查的名稱，$currentCouponSN是當前要更新的序號
	$table = 'Coupon';
	$statement = connect()->prepare("SELECT * FROM {$table} WHERE `Name` = ?");
	$statement->execute([$couponName]);
	$coupons = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($coupons as $coupon) {
		if ($coupon['SN'] != $currentCouponSN) {
			// 用迭代找不同序號的優惠券是否跟當前優惠券是否重複
			return $coupon;
		}
	}
	return false;
}
function checkCouponIdentifier($couponIdentifier, $currentCouponSN)
{
	$table = 'Coupon';
	$statement = connect()->prepare("SELECT * FROM {$table} WHERE `Identifier` = ?");
	$statement->execute([$couponIdentifier]);
	$coupons = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($coupons as $coupon) {
		if ($coupon['SN'] != $currentCouponSN) {
			return $coupon;
		}
	}
	return false;
}
