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
	'FirstName' => '建立者'
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
												<a class="btn btn-primary m-1">新增</a>
												<a class="btn btn-success m-1">編輯</a>
												<a class="btn btn-outline-danger m-1" href="delete.php?sn=<?= $row['SN'] ?>">刪除</a>
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
