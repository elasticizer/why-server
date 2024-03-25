<?php
$title = (isset ($_GET['sn']) ? '編輯' : '新增') . '領域';
$layout = './layout/layout.php';

require '../arranger.php';

if (isset ($_GET['sn'])) {
	$statement = connect()->prepare("SELECT * FROM Domain WHERE SN=?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
}
;

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between">
					<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
					<div>
						<a
							type="submit"
							href="index.php"
							class="btn btn-primary <?= isset ($_GET['sn']) ? '' : '' ?>"
						>回到列表頁</a>
					</div>
				</div>
				<form
					action="<?= isset ($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>"
					method="POST"
				>

					<div class="mb-3 <?= isset ($_GET['sn']) ? '' : 'd-none' ?>">
						<label
							for="Number"
							class="form-label"
						>序號</label>
						<input
							type="number"
							class="form-control"
							id="Number"
							readonly
							disabled
							value='<?= isset ($_GET['sn']) ? $row['SN'] : "" ?>'
						>

					</div>
					<div class="mb-3">

						<label
							for="Name"
							class="form-label"
						>領域名稱</label>

						<input
							type="text"
							class="form-control "
							id="Name"
							name="Name"
							placeholder='請輸入名稱'
							required
							value='<?= isset ($_GET['sn']) ? $row['Name'] : "" ?>'
						>
						<div
							class="form-text"
							id="nameFault"
						></div>

					</div>
					<div class="mb-3">
						<label
							for="Identifier"
							class="form-label"
						>識別碼</label>
						<input
							type="text"
							class="form-control"
							id="Identifier"
							name="Identifier"
							placeholder="請輸入2~10個字"
							minlength="2"
							maxlength="10"
							required
							value="<?= isset ($_GET['sn']) ? $row['Identifier'] : "" ?>"
						>
						<div
							class="form-text"
							id="IdentifierFault"
						></div>
					</div>
					<div class="mb-3">
						<label
							for="Intro"
							class="form-label"
						>簡介</label>
						<textarea
							type="text"
							class="form-control"
							id="Intro"
							name="Intro"
							placeholder="請輸入20個字以內"
							minlength="2"
							maxlength="20"
							required
						><?= isset ($_GET['sn']) ? $row['Intro'] : "" ?></textarea>
						<div
							class="form-text"
							id="IntroFault"
						></div>
					</div>
					<?php if (isset ($_GET['sn'])): ?>
						<div class="mb-3">
							<label
								for="whenCreated"
								class="form-label"
							>建立時間</label>
							<input
								type="text"
								class="form-control"
								id="whenCreated"
								name="whenCreated"
								value="<?= localize($row['WhenCreated']) ?>"
								disabled
							>
						</div>
						<div class="mb-3">
							<label
								for="whenLastEdited"
								class="form-label"
							>最後編輯時間</label>
							<input
								type="text"
								class="form-control"
								id="whenCreated"
								name="whenLastEdited"
								value="<?= localize($row['WhenLastEdited']) ?>"
								disabled
							>
						</div>
					<?php endif ?>
					<div class="d-flex  justify-content-center">
						<button
							type="submit"
							class="btn btn-primary "
							id="editFinsh"
							onclick="event.preventDault(), confirm=('您確定要新增該筆資料嗎?') && (location.href = this.href)"
						><?= isset ($_GET['sn']) ? '確定修改' : '確定新增' ?></button>
						<!-- <br /> -->
						<button
							type="reset"
							class="btn btn-danger  <?= isset ($_GET['sn']) ? 'd-none' : '' ?>"
						><i data-feather="rotate-cw"></i>重新填寫
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	const nameEl = document.getElementById('Name');
	const nameFaultEl = document.getElementById('nameFault');
	nameEl.addEventListener('input', function () {
		if (nameEl.value.length < 2) {
			nameEl.style.border = "2px solid red";
			nameFaultEl.textContent = '請輸入2個字以上';
			nameFaultEl.style.color = "red";
		} else {
			nameEl.style.border = "2px solid blue";
			nameFaultEl.textContent = '';
		}
	})
	const IntroEl = document.getElementById('Intro');
	const IntroFaultEl = document.getElementById('IntroFault');
	const IdentifierEl = document.getElementById('Identifier');
	const IdentifierFaultEl = document.getElementById('IdentifierFault');

	IntroEl.addEventListener('input', function () {
		if (IntroEl.value.length < 3) {
			IntroEl.style.border = "2px solid red";
			IntroFaultEl.textContent = '請輸入20個字以內';
			IntroFaultEl.style.color = "red";
		} else {
			IntroEl.style.border = "2px solid blue";
			IntroFaultEl.textContent = '';

		}
	})
	IdentifierEl.addEventListener('input', function () {
		if (IdentifierEl.value.length < 2) {
			IdentifierEl.style.border = "2px solid red";
			IdentifierFaultEl.textContent = '請輸入2~10個字';
			IdentifierFaultEl.style.color = "red";
		} else {
			IdentifierEl.style.border = "2px solid blue";
			IdentifierFaultEl.textContent = '';
		}
	})

	document.addEventListener("DOMContentLoaded", function () {
		// 找到修改完成按鈕
		var editFinishButton = document.getElementById("editFinsh");

		// 添加點擊事件監聽器
		editFinishButton.addEventListener("click", function (event) {
			// 找到名稱和辨識碼和簡介欄位
			var nameField = document.getElementById("Name");
			var introField = document.getElementById("Intro");
			var IdentifierField = document.getElementById("Identifier");

			// 添加點擊事件監聽器
			editFinishButton.addEventListener("click", function (event) {
				// 找到名稱和簡介欄位
				var nameField = document.getElementById("Name");
				var introField = document.getElementById("Intro");
				var IdentifierField = document.getElementById("Identifier");


				// 檢查名稱和簡介欄位是否為空
				if (nameField.value.trim() === "" || introField.value.trim() === "" || IdentifierField.value.trim() === "") {
					// 如果有任一欄位為空，阻止表單提交
					event.preventDefault();
					// 請求用戶填寫所有必填欄位
					alert("名稱、辨識碼、簡介欄位不得為空！");
				} else {
					alert("資料送出成功");
				}
			});
		})
	});



</script>
