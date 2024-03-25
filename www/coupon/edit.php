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
				<div class='d-flex justify-content-between'>
					<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
					<a href="index.php" class="btn btn-primary m-1">回到列表頁</a>
				</div>
				<form id="form1" action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST">
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
						<div class="form-text" id="identifierError"></div>
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
					<?php
					$today = date("Y-m-d H:i:s");
					$tomorrow = date("Y-m-d H:i:s", strtotime($today . ' +1 day'));
					?>
					<div class="mb-3">
						<label for="WhenEnded" class="form-label">結束時間</label>
						<input type="datetime-local" class="form-control" id="WhenEnded" name="WhenEnded" value="<?= isset($_GET['sn']) ? $row['WhenEnded'] : $tomorrow ?>">
						<div class="form-text" id="whenEndedError"></div>
					</div>
					<button type="submit" class="btn btn-primary m-1"><?= isset($_GET['sn']) ? '確定修改' : '確定新增' ?></button>

				</form>
			</div>
		</div>
	</div>
</div>
<!-- 成功 Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5">成功</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert">
					<?= isset($_GET['sn']) ? '資料編輯成功' : '資料新增成功' ?>
				</div>
			</div>
			<div class="modal-footer">
				<a href="index.php" class="btn btn-primary">確定</a>
			</div>
		</div>
	</div>
</div>
<!-- 失敗 Modal -->
<div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5">失敗</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger" role="alert">
					<?= isset($_GET['sn']) ? '資料編輯失敗' : '資料新增失敗' ?>
				</div>
			</div>
			<div class="modal-footer">
				<a href="index.php" class="btn btn-primary">確定</a>
			</div>
		</div>
	</div>
</div>

<!-- JS 檢查資料格式的樣式與提醒設定 -->
<script>
	document.addEventListener('DOMContentLoaded', () => {
		const nameInput = document.getElementById('Name');
		const nameError = document.getElementById('nameError');

		const identifierInput = document.getElementById('Identifier');
		const identifierError = document.getElementById('identifierError');

		const discountInput = document.getElementById('DiscountRate');
		const discountError = document.getElementById('discountError');

		const whenEndedInput = document.getElementById('WhenEnded');
		const whenEndedError = document.getElementById('whenEndedError');

		// identifier
		identifierInput.addEventListener('input', () => {
			if (identifierInput.value.length > 9 || identifierInput.value.length < 3) {
				identifierError.textContent = '識別碼必須在三到九個字之間。';
				identifierError.classList.add('isInvalid');
				identifierInput.style.border = "1px solid red";
			} else {
				identifierError.textContent = '';
				identifierError.classList.remove('isInvalid');
				identifierInput.style.border = "1px solid #CCC";
			}
		});

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

<!-- JS 判斷是否有輸入值 成功或失敗會出現Modal -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const form1 = document.getElementById('form1');

		const identifierField = document.getElementById('Identifier');
		const nameField = document.getElementById('Name');
		const discountRateField = document.getElementById('DiscountRate');
		const whenEndedField = document.getElementById('WhenEnded');

		form1.addEventListener('submit', function(event) {
			identifierField.style.border = "1px solid #CCC";

			nameField.style.border = "1px solid #CCC";

			discountRateField.style.border = "1px solid #CCC";

			whenEndedField.style.border = "1px solid #CCC";

			event.preventDefault();

			let isPass = true;

			// TODO: 檢查資料的格式

			if (identifierField.value.length > 9 || identifierField.value.length < 3) {
				isPass = false;
				identifierField.style.border = "1px solid red";
			}
			if (nameField.value.length < 3) {
				isPass = false;
				nameField.style.border = "1px solid red";
			}

			const discount = parseInt(discountRateField.value);
			if (isNaN(discount) || discount % 10 !== 0 || discount < 10 || discount > 90) {
				isPass = false;
				discountRateField.style.border = "1px solid red";
			}

			const today = new Date();
			const endDate = new Date(whenEndedField.value);
			if (endDate <= today) {
				isPass = false;
				whenEndedField.style.border = "1px solid red";
			}

			if (isPass) {
				const fd = new FormData(form1);

				let apiURL = 'add-api.php';
				if (form1.getAttribute('action').includes('edit-api.php')) {
					apiURL = form1.getAttribute('action');
				}

				fetch(apiURL, {
						method: 'POST',
						body: fd
					})
					.then(r => r.json())
					.then(result => {
						console.log(result);
						if (result.success) {
							successModal.show();
						} else {
							if (result.error) {
								failureInfo.innerHTML = result.error;
							} else {
								failureInfo.innerHTML = '資料新增沒有成功';
							}
							failureModal.show();
						}
					})
					.catch(ex => {
						console.log(ex);
						failureInfo.innerHTML = '資料新增發生錯誤' + ex;
						failureModal.show();
					})
			}
		});

		const successModal = new bootstrap.Modal('#successModal');
		const failureModal = new bootstrap.Modal('#failureModal');
		const failureInfo = document.querySelector('#failureModal .alert-danger');
	});
</script>
