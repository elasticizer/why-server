<?php

$title = (isset($_GET['sn']) ? '編輯' : '新增') . '文章';
$layout = './layout/layout.php';
require '../arranger.php';

$sn = isset($_GET['sn']) ? intval($_GET['sn']) : 0;

if (!empty($sn)) {
	$statement = connect()->prepare("SELECT * FROM Article WHERE SN=?");
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
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label for="SN" class="form-label">SN</label>
							<input type="text" class="form-control disabled" id="Identifier" name="SN" value="<?= isset($_GET['sn']) ? $row['SN'] : '' ?>" disabled>
						</div>
					<?php endif ?>
					<div class="mb-3">
						<label for="Identifier" class="form-label">識別碼</label>
						<input type="text" class="form-control" id="Identifier" name='Identifier' value='<?= isset($_GET['sn']) ? $row['Identifier'] : "" ?>'>
					</div>
					<div class="mb-3">
						<label for="Title" class="form-label">標題</label>
						<input type="text" class="form-control" id="Title" name='Title' value='<?= isset($_GET['sn']) ? $row['Title'] : "" ?>'>
					</div>
					<?php if (!isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label for="Content" class="form-label">內容</label>
							<input type="text" class="form-control" id="Title" name='Content'>
						</div>
					<?php endif ?>
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label class="form-label">建立時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenCreated']) ?>" disabled>
						</div>
						<div class="mb-3">
							<label class="form-label">最後更新時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenLastEdited']) ?>" disabled>
						</div>
					<?php endif ?>
					<button type="submit" class="btn btn-primary"><?= isset($_GET['sn']) ? '修改' : '提交表單' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
