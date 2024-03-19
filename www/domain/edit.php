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
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form
					action="<?= isset ($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>"
					method="POST"
				>
					<!-- <div class="mb-3">
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

					</div> -->
					<div class="mb-3">
						<label
							for="Name"
							class="form-label"
						>領域名稱</label>
						<input
							type="text"
							class="form-control required"
							id="Name"
							name="Name"
							placeholder='請輸入名稱'
							value='<?= isset ($_GET['sn']) ? $row['Name'] : "" ?>'

						>
						<div
							class="form-text"
							id="nameFault"
						></div>
					</div>
					<div class="mb-3">
						<label
							for="Intro"
							class="form-label"
						>簡介</label>
						<textarea
							type="text"
							class="form-control required"
							id="Intro"
							name="Intro"
							placeholder="請輸入內容"
							minlength="2"
							maxlength="10"
						><?= isset ($_GET['sn']) ? $row['Intro'] : "" ?></textarea>
						<div
							class="form-text"
							id="IntroFault"
						></div>
					</div>

					<button
						type="submit"
						class="btn btn-primary"
						id="editFinsh"
						data-bs-toggle="modal"
						data-bs-target="#staticBackdrop"
					><?= isset ($_GET['sn']) ? '修改完成' : '提交表單' ?></button>
					<button
						type="submit"
						class="btn btn-primary"
					><?= isset ($_GET['sn']) ? '返回上一頁' : '返回上一頁' ?></button>


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
	IntroEl.addEventListener('input', function () {
		if (IntroEl.value.length < 3 ) {
			IntroEl.style.border = "2px solid red";
			IntroFaultEl.textContent = '請輸入2~10個字';
			IntroFaultEl.style.color = "red";
		} else {
			IntroEl.style.border = "2px solid blue";
			IntroFaultEl.textContent = '';
		}
	})




</script>
