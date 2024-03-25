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

    // 檢查資料的格式
    if (mb_strlen($_POST['Name']) < 3) {
        $output['error'] = '優惠券名稱必須要三個字以上。';
        $isPass = false;
    }

    if (mb_strlen($_POST['Identifier']) > 9 or mb_strlen($_POST['Identifier']) < 3) {
        $output['error'] = '識別碼必須在三到九個字之間。';
        $isPass = false;
    }

    $discountRate = intval($_POST['DiscountRate']);
    if ($discountRate % 10 !== 0 || $discountRate < 10 || $discountRate > 90) {
        $output['error'] = '折扣必須在 10% 到 90% 之間，間隔值為10。';
        $isPass = false;
    }

    if ($isPass) {
        // 檢查是否有重複的優惠券名稱
        $existingCouponIdentifier = checkCouponIdentifier($_POST['Identifier']);
        if ($existingCouponIdentifier) {
            $output['error'] = '該優惠券識別碼已經存在。';
            $isPass = false;
        }

        $existingCoupon = checkCoupon($_POST['Name']);
        if ($existingCoupon) {
            $output['error'] = '該優惠券名稱已經存在。';
            $isPass = false;
        }

        if ($isPass) {
            $table = 'Coupon';
            $statement = connect()->prepare("INSERT INTO {$table} (`Identifier`, `Name`, `Description`, `DiscountRate`, `WhenEnded`, `CreatorSN`) VALUES (?, ?, ?, ?, ?, ?)");
            $statement->execute([
                $_POST['Identifier'],
                $_POST['Name'],
                $_POST['Description'],
                $_POST['DiscountRate'],
                $_POST['WhenEnded'],
                $_SESSION['sn']
            ]);

            $output['success'] = boolval($statement->rowCount());
        }
    }
} else {
    $output['error'] = '優惠券名稱不能為空。';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

function checkCouponIdentifier($couponIdentifier)
{
    $table = 'Coupon';
    $statement = connect()->prepare("SELECT * FROM {$table} WHERE `Identifier` = ?");
    $statement->execute([$couponIdentifier]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}
function checkCoupon($couponName)
{
    $table = 'Coupon';
    $statement = connect()->prepare("SELECT * FROM {$table} WHERE `Name` = ?");
    $statement->execute([$couponName]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}
