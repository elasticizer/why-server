<?php
$title = '訂單';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$table = '`Order`';
$total = connect()->query("SELECT COUNT(*) FROM {$table}")->fetch()[0];
$limit = 10;
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columnNames = ['序號', '結帳時間', '付款時間', '學員序號', '優惠券序號'];
$columns = ['SN', 'WhenCheckedOut', 'WhenPaid', 'LearnerSN', 'CouponSN'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM `Order` LIMIT ?, ?",
		implode(', ', $columns)
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
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columnNames as $column) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $column) : ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>

											<td>
												<a href="javascript: deleteOne(<?= $row['SN'] ?>)">
													<button type="button" class="btn btn-outline-danger m-1">刪除</button>
												</a>
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
<script>
		function deleteOne(SN) {
			if (confirm(`是否要刪除序號為${SN}的項目?`)) {
				location.href = `delete.php?sn=${SN}`;
			}
		}
	</script>
