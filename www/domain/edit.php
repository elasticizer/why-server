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
					<div class="mb-3">
						<label
							for="Number"
							class="form-label"
						>序號</label>
						<input
							type="number"
							class="form-control"
							id="Number"
							placeholder='請輸入數字'
							readonly
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
							class="form-control"
							id="Name"
							name="Name"
							placeholder='請輸入名稱'
							value='<?= isset ($_GET['sn']) ? $row['Name'] : "" ?>'
						>
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
							placeholder="請輸入內容"
						><?= isset ($_GET['sn']) ? $row['Intro'] : "" ?></textarea>
					</div>

					<button
						type="submit"
						class="btn btn-primary"
					><?= isset ($_GET['sn']) ? '修改完成' : '提交表單' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
