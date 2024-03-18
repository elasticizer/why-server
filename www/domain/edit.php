<?php
$title = (isset ($_GET['sn']) ? '編輯' : '新增') . '領域';
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
						<label
							for="exampleInputINTEGER"
							class="form-label"
						>序號</label>
						<input
							type="INTEGER"
							class="form-control"
							id="exampleInputINTEGER"
							aria-describedby="INTEGERHelp"
							placeholder='序號'
						>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputName"
								class="form-label"
							>領域名稱</label>
							<input
								type="TEXT"
								class="form-control"
								id="exampleInputName"
								placeholder='名稱'
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputIntro"
								class="form-label"
							>簡介</label>
							<input
								type="TEXT"
								class="form-control"
								id="exampleInputIntro"
								placeholder='簡介'
							>
						</div>
						
						<button
							type="submit"
							class="btn btn-primary"
						>提交表單</button>
				</form>
			</div>
		</div>
	</div>
</div>
