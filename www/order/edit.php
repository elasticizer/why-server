<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '訂單';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');

if (isset($_GET['sn'])) {
	$statement = connect()->prepare("SELECT * FROM `Order` WHERE sn=?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<style>
	.isInvalid {
		color: red;
		font-weight: bold;
	}
</style>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class='d-flex justify-content-between'>
					<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
					<a href="index.php" class="btn btn-primary m-1">回到列表頁</a>
				</div>
				<form action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST" id="form1">
					<!-- SN -->
					<div class="mb-3">
						<label for="SN" class="form-label">訂單序號</label>
						<input type="text" class="form-control" id="SN" name="SN" value="<?= isset($_GET['sn']) ? $row['SN'] : "" ?>" readonly>
					</div>
					<div class="mb-3">
						<label for="WhenCheckedOut" class="form-label">結帳時間</label>
						<input type="text" class="form-control" id="WhenCheckedOut" name="WhenCheckedOut" value="<?= isset($_GET['sn']) ? $row['WhenCheckedOut'] : "" ?>" readonly>
					</div>
					<!-- Name -->
					<div class="mb-3">
						<label for="WhenPaid" class="form-label">付款時間</label>
						<input type="text" class="form-control" id="WhenPaid" name="WhenPaid" value="<?= isset($_GET['sn']) ? $row['WhenPaid'] : "" ?>" readonly>
					</div>
					<!-- Explanation -->
					<div class="mb-3">
						<label for="LearnerSN" class="form-label">學員序號</label>
						<input type="text" class="form-control" id="LearnerSN" name="LearnerSN" value="<?= isset($_GET['sn']) ? $row['LearnerSN'] : "" ?>" readonly>
					</div>
					<!-- Explanation -->
					<div class="mb-3">
						<label for="CouponSN" class="form-label">優惠券序號</label>
						<input type="text" class="form-control" id="CouponSN" name="CouponSN" value="<?= isset($_GET['sn']) ? $row['CouponSN'] : "" ?>" readonly>
					</div>


					<button type="submit" class="btn btn-primary m-1"><?= isset($_GET['sn']) ? '確定修改' : '確定新增' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
