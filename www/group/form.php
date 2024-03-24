<?php

require '../arranger.php';
include find('./component/sidebar.php');

$row = false;
$permissions = [];

if (isset($_GET['sn'])) {
	$sn = is_numeric($_GET['sn']) ? intval($_GET['sn']) : null;

	$statement = connect()->prepare(
		'SELECT * FROM `Group` WHERE SN = ?'
	);

	$statement->execute([$sn]);

	$row = $statement->fetch(PDO::FETCH_ASSOC);

	$statement = connect()->prepare(
		'SELECT * FROM GroupPermission WHERE GroupSN = ?'
	);

	$statement->execute([$sn]);

	$permissions = array_column(
		$statement->fetchAll(PDO::FETCH_ASSOC),
		'PermissionSN'
	);
}

$title = ($row ? '編輯' : '建立') . '群組資料';
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
							for="identifier"
							class="form-label"
						>識別碼</label>
						<input
							type="text"
							class="form-control"
							id="identifier"
							name="identifier"
							value="<?= $row ? $row['Identifier'] : '' ?>"
							required
						/>
						<div class="invalid-feedback">欄位未提供</div>
					</div>
					<div class="mb-3">
						<label
							for="name"
							class="form-label"
						>名稱</label>
						<input
							type="text"
							class="form-control"
							id="name"
							name="name"
							value="<?= $row ? $row['Name'] : "" ?>"
							required
						/>
						<div class="invalid-feedback">欄位未提供</div>
					</div>
					<div class="mb-3">
						<label
							for="description"
							class="form-label"
						>說明</label>
						<textarea
							type="text"
							class="form-control"
							id="description"
							name="description"
							value="<?= $row ? $row['Description'] : "" ?>"
						></textarea>
					</div>
					<div class="form-check mb-3">
						<input
							type="checkbox"
							class="form-check-input"
							id="implicit"
							name="implicit"
							value="1"
							<?= $row ? $row['Implicit'] === 1 ? 'checked' : '' : '' ?>
						/>
						<label
							for="implicit"
							class="form-check-label"
						>已隱藏</label>
					</div>
					<?php if ($row): ?>
						<div class="mb-3">
							<label class="form-label">建立時間</label>
							<input
								type="datetime-local"
								class="form-control"
								value="<?= localize($row['WhenCreated']) ?>"
								disabled
							/>
						</div>
						<div class="mb-3">
							<label class="form-label">最後更新時間</label>
							<input
								type="datetime-local"
								class="form-control"
								value="<?= localize($row['WhenLastEdited']) ?>"
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
								>許可權清單</button>
							</div>
							<div
								id="permission-menu"
								class="accordion-collapse collapse"
								data-bs-parent="#permission"
							>
								<div class="accordion-body">
									<div class="row g-0 row-gap-3">
										<?php foreach (
											connect()->query('SELECT * FROM Permission')->fetchAll() as $r
										): ?>
											<div
												class="form-check col-3"
												title="<?= $r['Description'] ?>"
											>
												<input
													type="checkbox"
													class="form-check-input"
													id="<?= $r['Code'] ?>"
													name="permissions[]"
													value="<?= $r['SN'] ?>"
													<?= in_array($r['SN'], $permissions) ? 'checked' : '' ?>
												/>
												<label
													for="<?= $r['Code'] ?>"
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
							type="button"
							name="submitter"
							class="btn btn-primary"
						>送出</button>
					</div>
				</form>
			</div>
		</div>
		<?php if ($row): ?>
			<?php

			$statement = connect()->prepare(
				<<< 'END'
					SELECT S.SN, S.FirstName
					FROM Staff AS S
					JOIN StaffGroup AS SG ON S.SN = SG.StaffSN
					JOIN `Group` AS G ON G.SN = SG.GroupSN
					WHERE G.SN = ?
					END
			);

			$statement->execute([$sn]);

			?>

			<div
				id="member"
				class="accordion"
			>
				<div class="accordion-item">
					<div class="accordion-header">
						<button
							type="button"
							class="accordion-button fw-semibold"
							data-bs-toggle="collapse"
							data-bs-target="#member-list"
						>成員列表</button>
					</div>
					<div
						id="member-list"
						class="accordion-collapse collapse"
						data-bs-parent="#member"
					>
						<div class="accordion-body">
							<div class="list-group">
								<?php while ($row = $statement->fetch()): ?>
									<a
										href="/staff/form.php?sn=<?= $row['SN'] ?>"
										class="list-group-item list-group-item-action"
									><?= $row['FirstName'] ?></a>
								<?php endwhile ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>

<script>
	(function () {
		const form = document.forms[0];
		const button = form.elements['submitter'];

		button.addEventListener('click', validate);

		function validate() {
			if (!form.checkValidity()) {
				return form.classList.add('was-validated');
			}

			form.submit();
		}
	})();
</script>
