<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '優惠券';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');

if (isset($_GET['sn'])) {
	$statement = connect()->prepare("SELECT * FROM Coupon WHERE sn=?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST">
					<!-- SN -->
					<?php if(isset($_GET['sn'])) : ?>
					<div class="mb-3">
						<label for="Name" class="form-label">優惠券序號</label>
						<input type="text" class="form-control" id="SN" name="SN" value="<?= isset($_GET['sn']) ? $row['SN'] : "" ?>" readonly>
					</div>
					<?php endif; ?>
					<!-- Name -->
					<div class="mb-3">
						<label for="Name" class="form-label">優惠券名稱</label>
						<input type="text" class="form-control" id="Name" name="Name" value="<?= isset($_GET['sn']) ? $row['Name'] : "" ?>">
					</div>
					<!-- Explanation -->
					<div class="mb-3">
						<label for="Explanation" class="form-label">說明</label>
						<div class="form-floating">
							<textarea class="form-control" placeholder="Leave a comment here" id="Explanation" name="Explanation"><?= isset($_GET['sn']) ? $row['Explanation'] : "" ?></textarea>
							<label for="Explanation">請輸入優惠券說明</label>
						</div>
					</div>
					<!-- DiscountRate -->
					<div class="mb-3">
						<label for="DiscountRate" class="form-label">折扣</label>
						<div class="input-group mb-3">
							<input type="number" class="form-control" placeholder="請輸入折扣比率10-90% (例：9折請輸入90)" min="10" max="90" step="10" id="DiscountRate" name="DiscountRate" value="<?= isset($_GET['sn']) ? $row['DiscountRate'] : "" ?>">
							<span class="input-group-text">%</span>
						</div>
					</div>
					<!-- WhenEnded -->
					<div class="mb-3">
						<label for="WhenEnded" class="form-label">結束時間</label>
						<?php
						$today = date("Y-m-d");
						?>
						<input type="date" class="form-control" id="WhenEnded" name="WhenEnded" value="<?= isset($_GET['sn']) ? $row['WhenEnded'] : $today ?>">
					</div>

					<button type="submit" class="btn btn-primary"><?= isset($_GET['sn']) ? '確定修改' : '確定新增' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
