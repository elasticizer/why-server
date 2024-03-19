<?php
$title = '分類';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$total = connect()->query("SELECT COUNT(*) FROM Category")->fetch()[0];
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = ['SN', 'Name', 'Intro', 'Implicit', 'ParentSN', 'CreatorSN'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Category WHERE `Name` LIKE ? ORDER BY SN ASC LIMIT ?, ?",
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
							<input type="text" name="keyword" placeholder="輸入關鍵字" class="form-control">
							<button type="submit" class="btn btn-primary">搜尋</button>
						</form>

						<!-- 分頁功能 -->
						<section class="d-flex justify-content-between">
							<nav>
								<ul class="pagination">
									<li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=1">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<polyline points="11 17 6 12 11 7" />
												<polyline points="18 17 13 12 18 7" />
											</svg>
										</a>
									</li>
									<li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=<?= $page - 1 ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<polyline points="15 18 9 12 15 6" />
											</svg>
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
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<polyline points="9 18 15 12 9 6" />
											</svg>
										</a>
									</li>
									<li class="page-item <?= $page == $pages ? 'disabled' : '' ?>">
										<a class="page-link" href="?page=<?= $pages ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<polyline points="13 17 18 12 13 7" />
												<polyline points="6 17 11 12 6 7" />
											</svg>
										</a>
									</li>
								</ul>
							</nav>
							<div>
								<a href="./edit.php" class="btn btn-success m-1">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
										<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
										<polyline points="14 2 14 8 20 8" />
										<line x1="12" x2="12" y1="18" y2="12" />
										<line x1="9" x2="15" y1="15" y2="15" />
									</svg>
									新增</a>
							</div>
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
											<td class="border-bottom-0 mb-0">
												<a href="delete.php?sn=<?= $row['SN'] ?>" class="btn btn-danger m-1">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
														<polyline points="3 6 5 6 21 6" />
														<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
														<line x1="10" x2="10" y1="11" y2="17" />
														<line x1="14" x2="14" y1="11" y2="17" />
													</svg>
													刪除</a>
											</td>
											<td class="border-bottom-0 mb-0">
												<a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-info m-1">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
														<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
														<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
													</svg>
													編輯</a>
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
