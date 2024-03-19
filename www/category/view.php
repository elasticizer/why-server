<?php
$title = '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$total = connect()->query("SELECT COUNT(*) FROM Category")->fetch()[0];
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = ['C1.SN', 'C1.Name', 'C1.Intro', 'C1.Implicit', 'C2.Name AS ParentName', 'Staff.FirstName'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Category C1 LEFT OUTER JOIN Category C2 ON C1.ParentSN = C2.SN JOIN Staff ON C1.CreatorSN = Staff.SN WHERE C1.`Name` LIKE ? ORDER BY C1.SN ASC LIMIT ?, ?",
		implode(', ', $columns)
	)
);

$statement->execute(['%' . ($_GET['keyword'] ?? '') . '%', $start, $limit]);

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

						<!-- 搜尋關鍵字 -->
						<form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>" class="d-flex justify-content-between mb-3">
							<input type="text" name="keyword" placeholder="輸入名稱關鍵字" class="form-control me-3">
							<button type="submit" class="btn btn-primary">
								<i data-feather="search"></i>
							</button>
						</form>

						<!-- 分頁功能 -->
						<section class="d-flex justify-content-between">
							<nav>
								<ul class="pagination">
									<li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=1">
											<i data-feather="chevrons-left"></i>
											</svg>
										</a>
									</li>
									<li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=<?= $page - 1 ?>">
											<i data-feather="chevron-left"></i>
										</a>
									</li>
									<?php for ($i = $page - 5; $i <= $page + 5; $i++) : ?>
										<?php if ($i >= 1 and $i <= $pages) : ?>
											<li class="page-item <?= $i == $page ? 'active' : '' ?>">
												<a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
											</li>
										<?php endif ?>
									<?php endfor ?>
									<li class="page-item <?= $page == $pages ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=<?= $page + 1 ?>">
											<i data-feather="chevron-right"></i>
										</a>
									</li>
									<li class="page-item <?= $page == $pages ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=<?= $pages ?>">
											<i data-feather="chevrons-right"></i>
										</a>
									</li>
								</ul>
							</nav>
							<div>
								<a href="./edit.php" class="btn btn-success m-1">
									<i data-feather="file-plus"></i>
									新增</a>
							</div>
						</section>

						<!-- 表單內容 -->
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columns as $column) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
										<th class="border-bottom-0 fw-semibold mb-0 text-center">刪除</th>
										<th class="border-bottom-0 fw-semibold mb-0 text-center">編輯</th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
										<tr>
											<?php foreach ($row as $key => $value): ?>
												<td class="border-bottom-0 mb-0"><?= $key === 'Implicit' ? ($value === 1 ? '是' : '否') : $value ?></td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0">
												<a href="delete.php?sn=<?= $row['SN'] ?>" class="btn btn-danger m-1">
													<i data-feather="trash-2"></i>
												</a>
											</td>
											<td class="border-bottom-0 mb-0">
												<a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-info m-1">
													<i data-feather="edit"></i>
												</a>
											</td>
										</tr>
									<?php endwhile ?>
								</tbody>
							</table>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
