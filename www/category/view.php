<?php
$title = '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$total = connect()->query("SELECT COUNT(*) FROM Category")->fetch()[0];
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = ['SN', 'Name', 'Intro', 'Implicit', 'WhenCreated', 'WhenLastEdited
', 'ParentSN', 'CreatorSN'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Category ORDER BY SN ASC LIMIT ?, ?",
		implode(', ', $columns)
	)
);



$statement->execute([$start, $limit]);

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>

						<!-- 分頁功能 -->
						<section>
							<nav>
								<ul class="pagination justify-content-center">
									<li class="page-item ">
										<a class="page-link <?= $page > 1 ? '' : 'disabled' ?>" href="?page=1">
											<i data-feather="chevrons-left"></i>
										</a>
									</li>
									<?php if ($page > 1) : ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?= $page - 1 ?>"><?= $page - 1 ?></a>
										</li>
									<?php endif ?>
									<li class="page-item active" aria-current="page">
										<a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
									</li>
									<?php if ($page < $pages) : ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?= $page + 1 ?>"><?= $page + 1 ?></a>
										</li>
									<?php endif ?>
									<li class="page-item">
										<a class="page-link <?= $page < $pages ? '' : 'disabled' ?>" href="?page=<?= $pages ?>">
											<i data-feather="chevrons-right"></i>
										</a>
									</li>
								</ul>
							</nav>
							<div><a href="./edit.php" class="btn btn-success m-1">新增</a></div>
						</section>

						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columns as $column) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $column) : ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0"><a href="delete.php?sn=<?= $row['SN'] ?>" class="btn btn-danger m-1">刪除</a>
											</td>
											<td class="border-bottom-0 mb-0"><a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-info m-1">編輯</a></td>
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
