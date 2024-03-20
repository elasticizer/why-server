<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$sn = isset($_GET['sn']) ? intval($_GET['sn']) : 0;

if (!empty($sn)) {
	$statement = connect()->prepare("SELECT * FROM Category WHERE SN = ?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
};

$all = connect()->query('SELECT * FROM `Category`');

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form name="form1" action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST">
					<div class="mb-3 <?= isset($_GET['sn']) ? '' : 'd-none' ?>">
						<label for="categoryName" class="form-label">SN</label>
						<input type="text" class="form-control" id="sn" name="sn" value="<?= $row['SN'] ?>" disabled>
					</div>
					<div class="mb-3">
						<label for="categoryName" class="form-label">分類名稱</label>
						<input type="text" class="form-control" id="categoryName" name="name" value="<?= isset($_GET['sn']) ? $row['Name'] : '' ?>">
						<div></div>
					</div>
					<div class="mb-3">
						<label for="categoryIntro" class="form-label">簡介</label>
						<textarea type="text" class="form-control" id="categoryIntro" name="intro"><?= isset($_GET['sn']) ? $row['Intro'] : "" ?></textarea>
						<div></div>
					</div>
					<div class="mb-3">
						<label for="parent" class="form-label">父類別</label>
						<input list="parents" name="parent" id="parent" class="form-control" value="<?= isset($_GET['sn']) ? $row['ParentSN'] : "" ?>">
						<datalist id="parents">
							<?php while ($r = $all->fetch(PDO::FETCH_ASSOC)) : ?>
								<option value="<?= $r['SN'] ?>"><?= $r['Name'] ?></option>
							<?php endwhile ?>
						</datalist>
					</div>
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label for="whenCreated" class="form-label">建立時間</label>
							<input type="text" class="form-control" id="whenCreated" name="whenCreated" value="<?= localize($row['WhenCreated']) ?>" disabled>
						</div>
						<div class="mb-3">
							<label for="whenLastEdited" class="form-label">最後編輯時間</label>
							<input type="text" class="form-control" id="whenCreated" name="whenLastEdited" value="<?= localize($row['WhenLastEdited']) ?>" disabled>
						</div>
					<?php endif ?>
					<div class="mb-3 form-check">
						<input type="checkbox" class="form-check-input" id="exampleCheck1" name="checkbox">
						<label class="form-check-label" for="exampleCheck1">隱藏</label>
					</div>
					<button type="submit" class="btn btn-primary"><?= isset($_GET['sn']) ? '編輯完成' : '提交表單' ?></button>
					<a href="view.php" class="btn btn-danger m-1">
						<?= isset($_GET['sn']) ? '取消編輯' : '取消新增' ?>
					</a>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	const {
		name: nameField,
		intro: introField,
	} = form1

	form1.addEventListener('submit', validate);

	function validate(e) {
		e.preventDefault();
		if (!nameField.value) {
			nameField.nextElementSibling.innerHTML = '請輸入名稱';
			nameField.style.border = '2px solid red';
			return;
		} else {
			nameField.nextElementSibling.innerHTML = '';
			nameField.style.border = '';
		}

		if (!introField.value) {
			introField.nextElementSibling.innerHTML = '請輸入簡介';
			introField.style.border = '2px solid red';
			return;
		} else {
			introField.nextElementSibling.innerHTML = '';
			introField.style.border = '';
		}
		form1.submit();
	}
</script>
