<?php
$title = '優惠券'; // 頁面標題
$layout = './layout/layout.php'; // 頁面排版

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$table = 'Coupon';
$total = connect()->query("SELECT COUNT(*) FROM {$table}")->fetch()[0];
$limit = 10; // 每頁10筆
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = [
	"{$table}.SN" => '序號',
	'Name' => '名稱',
	'DiscountRate' => '折扣比率',
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

	
		<!-- 分頁按鈕 & 新增按鈕 -->
		<div class='d-flex justify-content-between align-items-center'>
			<nav aria-label="...">
				<ul class="pagination">
					<li class="page-item ">
						<a class="page-link <?= $page > 1 ? '' : 'disabled' ?>" href="?page=1">
							<i data-feather="chevrons-left"></i>
						</a>
					</li>
					<?php
					for ($i = max(1, $page - 2); $i <= min($page + 2, $pages); $i++) {
					?>
						<li class="page-item <?= $i == $page ? 'active' : '' ?>">
							<a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
						</li>
					<?php
					}
					?>
					<li class="page-item">
						<a class="page-link <?= $page < $pages ? '' : 'disabled' ?>" href="?page=<?= $pages ?>">
							<i data-feather="chevrons-right"></i>
						</a>
					</li>
				</ul>
			</nav>
			<div><a href="./edit.php" class="btn btn-success m-1">新增</a></div>
		</div>

		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach (array_values($columns) as $column) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $column) : ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
											<td class="text-end">
												<a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-primary m-1">編輯</a>
												<a href="javascript: deleteOne(<?= $row['SN'] ?>)" class="btn btn-danger m-1">刪除</a>
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
	<script>
		function deleteOne(SN) {
			if (confirm(`是否要刪除序號為${SN}的項目?`)) {
				location.href = `delete.php?sn=${SN}`;
			}
		}
	</script>
</div>
