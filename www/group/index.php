<?php

$title = '群組';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');

$page = max(intval($_GET['page'] ?? 1), 1);
$limit = 10;
$offset = 2;
$outset = max($page - $offset, 1);
$implicit = ($_GET['implicit'] ?? '') === 'true';
$statement = connect()->prepare(
	sprintf(
		<<< 'END'
			SELECT COUNT(*) FROM `Group` AS T1
			JOIN Staff AS T2
				ON T1.CreatorSN = T2.SN
			WHERE %s AND (
				T1.Identifier LIKE ?
					OR T1.Name LIKE ?
					OR T1.Description LIKE ?
					OR T2.FirstName LIKE ?
			)
			END,
		$condition = $implicit ? '1' : 'T1.Implicit = 0'
	)
);
$statement->execute(
	$keyword = array_fill(0, 4, '%' . ($_GET['keyword'] ?? '') . '%')
);
$total = $statement->fetchColumn();
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = [
	'T1.SN' => '序號',
	'T1.Identifier' => '識別碼',
	'T1.Name' => '名稱',
	'T1.Description' => '說明',
	'T2.FirstName' => '建立者',
	'T1.Implicit' => '已隱藏'
];

$statement = connect()->prepare(
	sprintf(
		<<< 'END'
			SELECT %s FROM `Group` AS T1
			JOIN Staff AS T2
				ON T1.CreatorSN = T2.SN
			WHERE %s AND (
				T1.Identifier LIKE ?
					OR T1.Name LIKE ?
					OR T1.Description LIKE ?
					OR T2.FirstName LIKE ?
			)
			LIMIT ?, ?
			END,
		implode(', ', array_keys($columns)),
		$condition
	)
);
$statement->execute([
	...$keyword,
	$start,
	$limit
]);

?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
						<form
							class="d-flex gap-1 mb-3"
							method="GET"
							action="<?= $_SERVER['PHP_SELF'] ?>"
						>
							<div class="w-100">
								<div class="mb-1">
									<input
										type="search"
										name="keyword"
										placeholder="識別碼、名稱、說明、建立者名字"
										class="form-control me-3"
										value="<?= $_GET['keyword'] ?? '' ?>"
									/>
								</div>
								<div class="form-check">
									<input
										type="checkbox"
										name="implicit"
										value="true"
										<?= isset($_GET['implicit']) ? 'checked' : '' ?>
										class="form-check-input"
										id="implicit"
									/>
									<label
										class="form-check-label"
										for="implicit"
									>顯示已隱藏的群組</label>
								</div>
							</div>
							<div>
								<button
									type="submit"
									class="btn btn-primary"
								>
									<i data-feather="search"></i>
								</button>
							</div>
						</form>
						<div class="d-flex justify-content-between gap-1 mb-3">
							<div>
								<ul class="pagination mb-0">
									<li class="page-item">
										<a
											href="?<?= http_build_query([...$_GET, 'page' => 1]) ?>"
											class="page-link <?= $page > 1 ? '' : 'disabled' ?>"
										><i data-feather="chevrons-left"></i></a>
									</li>
									<?php for ($i = $outset; $i <= min($outset + $offset * 2, $pages); $i++): ?>
										<li class="page-item">
											<a
												href="?<?= http_build_query([...$_GET, 'page' => $i]) ?>"
												class="page-link <?= $i === $page ? 'active' : '' ?>"
											><?= $i ?></a>
										</li>
									<?php endfor ?>
									<li class="page-item">
										<a
											href="?<?= http_build_query([...$_GET, 'page' => $pages]) ?>"
											class="page-link <?= $page < $pages ? '' : 'disabled' ?>"
										><i data-feather="chevrons-right"></i></a>
									</li>
								</ul>
							</div>
							<?php if (authorize('GROUP_CREATE')): ?>
								<div>
									<a
										href="form.php"
										class="btn btn-outline-primary"
									><i data-feather="plus"></i></a>
								</div>
							<?php endif ?>
						</div>
						<form class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle table-striped">
								<thead class="text-dark">
									<tr class="text-center">
										<?php foreach ($columns as $column => $value): ?>
											<th><?= $value ?></th>
										<?php endforeach ?>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
										<tr data-sn="<?= $row['SN'] ?>">
											<?php foreach ($row as $column => $value): ?>
												<td class="border-bottom-0 mb-0 <?=
													in_array($column, ['SN', 'Implicit'])
														? 'text-center'
														: ''
												?>">
													<?php if ($column === 'Implicit'): ?>
														<?php if (authorize('GROUP_EDIT')): ?>
															<input
																type="checkbox"
																<?= $value === 0 ? '' : 'checked' ?>
																class="d-inline-block form-check-input mx-auto"
															/>
														<?php endif ?>
													<?php else: ?>
														<?= $value ?>
													<?php endif ?>
												</td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0 text-center">
												<?php if (authorize('GROUP_EDIT')): ?>
													<a
														href="form.php?sn=<?= $row['SN'] ?>"
														class="btn btn-primary m-1"
													>編輯</a>
												<?php endif ?>
												<?php if (authorize('GROUP_DELETE')): ?>
													<a
														href="delete.php?sn=<?= $row['SN'] ?>"
														class="btn btn-outline-danger m-1"
													>刪除</a>
												<?php endif ?>
											</td>
										</tr>
									<?php endwhile ?>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="toast-container p-3 bottom-0 end-0 position-fixed">
	<div class="toast">
		<div class="toast-header">
			<strong class="me-auto"></strong>
			<small></small>
			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="toast"
			></button>
		</div>
		<div class="toast-body"></div>
	</div>
</div>

<script>
	(function () {
		const table = document.forms[document.forms.length - 1];
		const field = document.forms[0].elements['implicit'];
		const toast = document.querySelector('.toast');

		table.addEventListener('change', submit);
		field.addEventListener('change', () => field.form.submit());

		async function submit(e) {
			const sn = e.target.closest('tr').dataset.sn;
			const time = new Date().toLocaleTimeString();
			const data = await fetch(`switch-api.php?sn=${sn}`).then(
				r => r.json(),
				message => ({ summary: '', message })
			);
			const copy = toast.parentNode.appendChild(
				toast.cloneNode(true)
			);

			if (data.code !== '0000') {
				e.target.checked = false;

				copy.querySelector('strong').classList.add('text-danger');
			}

			copy.querySelector('strong').textContent = data.summary;
			copy.querySelector('small').textContent = time;
			copy.querySelector('.toast-body').textContent = data.message;

			copy.addEventListener(
				'hidden.bs.toast',
				() => copy.remove()
			);

			new bootstrap.Toast(copy).show();
		}
	})();
</script>
