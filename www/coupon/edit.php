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
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST" id="form1">
					<!-- SN -->
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label for="Name" class="form-label">優惠券序號</label>
							<input type="text" class="form-control" id="SN" name="SN" value="<?= isset($_GET['sn']) ? $row['SN'] : "" ?>" readonly>
						</div>
					<?php endif; ?>
					<!-- Identifier -->
					<div class="mb-3">
						<label for="Identifier" class="form-label">識別碼</label>
						<input type="text" class="form-control" id="Identifier" name="Identifier" value="<?= isset($_GET['sn']) ? $row['Identifier'] : "" ?>">
					</div>
					<!-- Name -->
					<div class="mb-3">
						<label for="Name" class="form-label">優惠券名稱</label>
						<input type="text" class="form-control" id="Name" name="Name" value="<?= isset($_GET['sn']) ? $row['Name'] : "" ?>">
						<div class="form-text" id="nameError"></div>
					</div>
					<!-- Description -->
					<div class="mb-3">
						<label for="Description" class="form-label">說明</label>
						<div class="form-floating">
							<textarea class="form-control" placeholder="Leave a comment here" id="Description" name="Description"><?= isset($_GET['sn']) ? $row['Description'] : "" ?></textarea>
							<label for="Description">請輸入優惠券說明</label>
						</div>
					</div>
					<!-- DiscountRate -->
					<div class="mb-3">
						<label for="DiscountRate" class="form-label">折扣</label>
						<div class="input-group mb-3">
							<input type="number" class="form-control" placeholder="請輸入折扣比率10-90%" id="DiscountRate" name="DiscountRate" step="10" value="<?= isset($_GET['sn']) ? $row['DiscountRate'] : "" ?>">
							<span class="input-group-text">%</span>
						</div>
						<div class="form-text" id="discountError"></div>
					</div>
					<!-- WhenIssued -->
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label class="form-label">建立時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenCreated']) ?>" readonly>
						</div>
						<div class="mb-3">
							<label class="form-label">開始時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenStarted']) ?>" readonly>
						</div>
					<?php endif ?>
					<!-- WhenEnded -->
					<div class="mb-3">
						<label for="WhenEnded" class="form-label">結束時間</label>
						<?php
						$today = date("Y-m-d H:i:s");
						?>
						<input type="datetime-local" class="form-control" id="WhenEnded" name="WhenEnded" value="<?= isset($_GET['sn']) ? $row['WhenEnded'] : $today ?>">
						<div class="form-text" id="whenEndedError"></div>
					</div>
					<button type="submit" class="btn btn-primary m-1"><?= isset($_GET['sn']) ? '確定修改' : '確定新增' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const nameInput = document.getElementById('Name');
		const nameError = document.getElementById('nameError');

		const discountInput = document.getElementById('DiscountRate');
		const discountError = document.getElementById('discountError');

		const whenEndedInput = document.getElementById('WhenEnded');
		const whenEndedError = document.getElementById('whenEndedError');

		const form = document.getElementById('form1');
		const successModal = document.getElementById('successModal');
		const failureModal = document.getElementById('failureModal');

		// Name
		nameInput.addEventListener('input', () => {
			if (nameInput.value.length < 3) {
				nameError.textContent = '優惠券名稱必須要三個字以上。';
				nameError.classList.add('isInvalid');
				nameInput.style.border = "1px solid red";
			} else {
				nameError.textContent = '';
				nameError.classList.remove('isInvalid');
				nameInput.style.border = "1px solid #CCC";
			}
		});

		// Explanation 可填可不填
		// Discount
		discountInput.addEventListener('input', function() {
			const discount = parseInt(discountInput.value);
			if (isNaN(discount) || discount % 10 !== 0 || discount < 10 || discount > 90) {
				discountError.textContent = '折扣必須在 10% 到 90% 之間，間隔值為10。';
				discountError.classList.add('isInvalid');
				discountInput.style.border = "1px solid red";
			} else {
				discountError.textContent = '';
				discountError.classList.remove('isInvalid');
				discountInput.style.border = "1px solid #CCC";
			}
		});

		// WhenEnded
		whenEndedInput.addEventListener('input', function() {
			const today = new Date();
			const endDate = new Date(whenEndedInput.value);
			if (endDate <= today) {
				whenEndedError.textContent = '結束時間必須在當日之後。';
				whenEndedError.classList.add('isInvalid');
				whenEndedInput.style.border = "1px solid red";
			} else {
				whenEndedError.textContent = '';
				whenEndedError.classList.remove('isInvalid');
				whenEndedInput.style.border = "1px solid #CCC";
			}
		});



	})
</script>
