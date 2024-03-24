<?php

require '../arranger.php';
include find('./component/sidebar.php');

$row = false;
$groups = [];

if (isset($_GET['sn'])) {
	$statement = connect()->prepare(
		'SELECT * FROM Staff WHERE SN = ?'
	);

	$statement->execute([
		intval($_GET['sn']) ?: null
	]);

	$row = $statement->fetch(PDO::FETCH_ASSOC);

	$statement = connect()->prepare(
		'SELECT GroupSN FROM StaffGroup WHERE StaffSN = ?'
	);

	$statement->execute([$sn]);

	$groups = array_column(
		$statement->fetchAll(PDO::FETCH_ASSOC),
		'GroupSN'
	);
}

$title = ($row ? '編輯' : '建立') . '職員資料';
$layout = './layout/layout.php';

?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form
					action="<?= $row ? "edit.php?sn={$sn}" : 'add.php' ?>"
					method="POST"
				>
					<?php if ($row): ?>
						<div class="mb-3">
							<label
								for="sn"
								class="form-label"
							>序號</label>
							<input
								type="number"
								class="form-control"
								id="sn"
								name="sn"
								value="<?= $row ? $row['SN'] : '' ?>"
								readonly
							/>
						</div>
					<?php endif ?>
					<div class="mb-3">
						<label
							for="email"
							class="form-label"
						>電子信箱地址</label>
						<input
							type="email"
							class="form-control"
							id="email"
							name="email"
							value="<?= $row ? $row['E-mail'] : "" ?>"
							required
						/>
						<div class="invalid-feedback">欄位未提供</div>
					</div>
					<div class="mb-3">
						<label
							for="firstname"
							class="form-label"
						>名字</label>
						<input
							type="text"
							class="form-control"
							id="firstname"
							name="firstname"
							value="<?= $row ? $row['FirstName'] : '' ?>"
							required
						/>
						<div class="invalid-feedback">欄位未提供</div>
					</div>
					<div class="mb-3">
						<label
							for="lastname"
							class="form-label"
						>姓氏</label>
						<input
							type="text"
							class="form-control"
							id="lastname"
							name="lastname"
							value="<?= $row ? $row['LastName'] : "" ?>"
							required
						/>
						<div class="invalid-feedback">欄位未提供</div>
					</div>
					<?php if ($row): ?>
						<div class="mb-3">
							<label class="form-label">建立時間</label>
							<input
								type="datetime-local"
								class="form-control"
								value="<?= localize($row['WhenRegistered']) ?>"
								disabled
							/>
						</div>
					<?php endif ?>
					<fieldset
						id="permission"
						class="accordion mb-3"
					>
						<div class="accordion-item">
							<div class="accordion-header">
								<button
									type="button"
									class="accordion-button fw-semibold"
									data-bs-toggle="collapse"
									data-bs-target="#permission-menu"
								>群組清單</button>
							</div>
							<div
								id="permission-menu"
								class="accordion-collapse collapse"
								data-bs-parent="#permission"
							>
								<div class="accordion-body">
									<div class="row g-0 row-gap-3">
										<?php foreach (
											connect()->query('SELECT * FROM `Group`')->fetchAll() as $r
										): ?>
											<div
												class="form-check col-3"
												title="<?= $r['Description'] ?>"
											>
												<input
													type="checkbox"
													class="form-check-input"
													id="<?= $r['Identifier'] ?>"
													name="groups[]"
													value="<?= $r['SN'] ?>"
													<?= in_array($r['SN'], $groups) ? 'checked' : '' ?>
												/>
												<label
													for="<?= $r['Identifier'] ?>"
													class="form-check-label"
												><?= $r['Name'] ?></label>
											</div>
										<?php endforeach ?>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<div>
						<button
							type="submit"
							class="btn btn-primary"
						>送出</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
