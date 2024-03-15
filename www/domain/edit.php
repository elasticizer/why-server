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
						>SN</label>
						<input
							type="INTEGER"
							class="form-control"
							id="exampleInputINTEGER"
							aria-describedby="INTEGERHelp"
						>
						<div class="mb-3">
							<label
								for="exampleInputName"
								class="form-label"
							>Name</label>
							<input
								type="TEXT"
								class="form-control"
								id="exampleInputName"
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputIntro"
								class="form-label"
							>Intro</label>
							<input
								type="TEXT"
								class="form-control"
								id="exampleInputIntro"
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputWhenCreated"
								class="form-label"
							>WhenCreated</label>
							<input
								type="DATETIME"
								class="form-control"
								id="exampleInputWhenCreated"
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputWhenLastEdited"
								class="form-label"
							>WhenLastEdited</label>
							<input
								type="DATETIME"
								class="form-control"
								id="exampleInputWhenLastEdited"
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputParentSN"
								class="form-label"
							>ParentSN</label>
							<input
								type="INTEGER"
								class="form-control"
								id="exampleInputParentSN"
							>
						</div>
						<div class="mb-3">
							<label
								for="exampleInputCreatorSN"
								class="form-label"
							>CreatorSN</label>
							<input
								type="INTEGER"
								class="form-control"
								id="exampleInputCreatorSN"
							>
						</div>
						<div class="mb-3 form-check">
							<input
								type="checkbox"
								class="form-check-input"
								id="exampleCheck1"
							>
							<label
								class="form-check-label"
								for="exampleCheck1"
							>Check me out</label>
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
