<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '分類';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form>
					<div class="mb-3">
						<label for="categoryName" class="form-label">分類名稱</label>
						<input type="text" class="form-control" id="categoryName" aria-describedby="emailHelp">
						<div class="mb-3">
							<label for="categoryIntro" class="form-label">簡介</label>
							<textarea type="text" class="form-control" id="categoryIntro"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">確定<?= $title ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
