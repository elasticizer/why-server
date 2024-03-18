<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$sn = isset($_GET['sn']) ? intval($_GET['sn']) : 0;

if (!empty($sn)) {
	$statement = connect()->prepare("SELECT * FROM Category WHERE SN=?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
};

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form name="form1" action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST">
					<div class="mb-3">
						<label for="categoryName" class="form-label">分類名稱</label>
						<input type="text" class="form-control" id="categoryName" name="name" value="<?= isset($_GET['sn']) ? $row['Name'] : '' ?>">
					</div>
					<div class="mb-3">
						<label for="categoryIntro" class="form-label">簡介</label>
						<textarea type="text" class="form-control" id="categoryIntro" name="intro"><?= isset($_GET['sn']) ? $row['Intro'] : "" ?></textarea>
					</div>
					<div class="mb-3 form-check">
						<input type="checkbox" class="form-check-input" id="exampleCheck1" name="checkbox">
						<label class="form-check-label" for="exampleCheck1"><?= isset($_GET['sn']) ? '確定修改' : '確定新增' ?></label>
					</div>
					<button type="submit" class="btn btn-primary"><?= isset($_GET['sn']) ? '修改' : '提交表單' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
