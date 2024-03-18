<?php
$title = '領域';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$table = 'Domain';
$total = connect()->query("SELECT COUNT(*) FROM {$table}")->fetch()[0];
$limit = 10;
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = [
	"{$table}.SN" => '序號',
	'Name' => '名稱',
	'Intro' => '簡介',
	'FirstName' => '建立者',
];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM %s JOIN Staff ON {$table}.CreatorSN = Staff.SN LIMIT ?, ?",
		implode(', ', array_keys($columns)),
		$table
	)
);

$statement->execute([$start, $limit]);

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<!-- 分頁功能 -->
		<nav aria-label="...">
			<ul class="pagination">
				<li class="page-item ">
					<a
						class="page-link <?= $page > 1 ? '' : 'disabled' ?>"
						href="?page=1"
					>
						<i data-feather="chevrons-left"></i>
					</a>
				</li>
				<?php if ($page > 1): ?>
					<li class="page-item">
						<a
							class="page-link"
							href="?page=<?= $page - 1 ?>"
						><?= $page - 1 ?></a>
					</li>
				<?php endif ?>
				<li
					class="page-item active"
					aria-current="page"
				>
					<a
						class="page-link"
						href="?page=<?= $page ?>"
					><?= $page ?></a>
				</li>
				<?php if ($page < $pages): ?>
					<li class="page-item">
						<a
							class="page-link"
							href="?page=<?= $page + 1 ?>"
						><?= $page + 1 ?></a>
					</li>
				<?php endif ?>
				<li class="page-item">
					<a
						class="page-link <?= $page < $pages ? '' : 'disabled' ?>"
						href="?page=<?= $pages ?>"
					>
						<i data-feather="chevrons-right"></i>
					</a>
				</li>
			</ul>
		</nav>
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
						<div><a
								href="./edit.php"
								class="btn btn-primary m-1"
							>新增</a></div>
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach (array_values($columns) as $column): ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
										<tr>
											<?php foreach ($row as $column): ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
											<td>
												<a
													href="edit.php?sn=<?= $row['SN'] ?>"
													class="btn btn-success m-1"
												>編輯</a>
												<a
													href="delete.php?sn=<?= $row['SN'] ?>"
													class="btn btn-outline-danger m-1 "
												><svg
														xmlns="http://www.w3.org/2000/svg"
														width="16"
														height="16"
														fill="currentColor"
														class="bi bi-trash3-fill"
														viewBox="0 0 16 16"
													>
														<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
													</svg></a>
											</td>

										</tr>
									<?php endwhile ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
