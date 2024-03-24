<?php
$title = '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$page = is_numeric($page) ? intval($page) : 1;
$limit = 10;
$statement = connect()->prepare("SELECT COUNT(*) FROM Category C1 LEFT OUTER JOIN Category C2 ON C1.ParentSN = C2.SN JOIN Staff ON C1.CreatorSN = Staff.SN WHERE C1.`Name` LIKE ?");
$statement->execute(['%' . ($_GET['keyword'] ?? '') . '%']);
$total = $statement->fetch(PDO::FETCH_NUM)[0];
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = [
	'C1.SN' => '序號',
	'C1.Identifier' => '識別碼',
	'C1.Name' => '分類名稱',
	'C1.Intro' => '簡介',
	'C1.Implicit' => '是否隱藏',
	'C2.Name AS ParentName' => '父類別',
	'Staff.FirstName' => '建立者'
];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Category C1 LEFT OUTER JOIN Category C2 ON C1.ParentSN = C2.SN JOIN Staff ON C1.CreatorSN = Staff.SN WHERE C1.`Name` LIKE ? ORDER BY C1.SN ASC LIMIT ?, ?",
		implode(', ', array_keys($columns))
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
							<div class="d-flex w-75">
								<input type="text" name="keyword" placeholder="輸入分類名稱關鍵字" class="form-control me-3" value="<?= $_GET['keyword'] ?? '' ?>">
								<button type="submit" class="btn btn-primary me-2">
									<i data-feather="search"></i>
								</button>
							</div>
							<button class="btn btn-info <?= isset($_GET['keyword']) ? '' : 'd-none' ?>">
								<a href="index.php" class="text-white text-decoration-none">清除搜尋</a>
							</button>
						</form>

						<!-- 分頁功能 -->
						<section class="d-flex justify-content-between">
							<nav>
								<ul class="pagination">
									<li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
										<a class="page-link" href="?<?= http_build_query([...$_GET, 'page' => 1]) ?>">
											<i data-feather="chevrons-left"></i>
											</svg>
										</a>
									</li>
									<?php for ($i = $page - 5; $i <= $page + 5; $i++) : ?>
										<?php if ($i >= 1 and $i <= $pages) : ?>
											<li class="page-item <?= $i === $page ? 'active' : '' ?>">
												<a class="page-link" href="?<?= http_build_query([...$_GET, 'page' => $i]) ?>"><?= $i ?></a>
											</li>
										<?php endif ?>
									<?php endfor ?>
									<li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
										<a class="page-link" href="?<?= http_build_query([...$_GET, 'page' => $pages]) ?>">
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
										<th class="border-bottom-0 fw-semibold mb-0 text-center">編輯</th>
										<th class="border-bottom-0 fw-semibold mb-0 text-center">刪除</th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $key => $value) : ?>
												<td class="border-bottom-0 mb-0"><?= $key === 'Implicit' ? ($value === 1 ? '是' : '否') : $value ?></td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0 text-center">
												<a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-info m-1">
													<i data-feather="edit"></i>
												</a>
											</td>
											<td class="border-bottom-0 mb-0 text-center">
												<a href="delete.php?sn=<?= $row['SN'] ?>" class="btn btn-danger m-1" onclick="event.preventDefault(), confirm('是否確定刪除？') && (location.href = this.href)">
													<i data-feather="trash-2"></i>
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
