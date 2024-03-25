<?php
$title = '領域';
$layout = './layout/layout.php'; //頁面排版

require '../arranger.php';

$page = $_GET['page'] ?? 1; //獲取頁數 ??是 三元運算子的:意思
$table = 'Domain';
$statement = connect()->prepare(
	sprintf(
		"SELECT COUNT(*) FROM %s WHERE Identifier LIKE ? OR Name LIKE ? OR Intro LIKE ?",
		$table
	)
);
$statement->execute([$holder = '%' . ($_GET['keyword'] ?? "") . '%', $holder, $holder]);
$total = $statement->fetch(PDO::FETCH_NUM)[0]; //從直欄 0 開始，依照結果集中傳回的直欄號碼來傳回已編製索引的陣列。
$limit = 10; //每頁10筆
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$columns = [
	"{$table}.SN" => '序號',
	'Name' => '名稱',
	'Identifier' => '識別碼',
	'Intro' => '簡介',
	'FirstName' => '建立者',
];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM %s JOIN Staff ON {$table}.CreatorSN = Staff.SN WHERE Identifier LIKE ?OR Name LIKE ? OR Intro LIKE ? LIMIT ?, ?",

		implode(', ', array_keys($columns)),
		//implode('連接符號',陣列)，將陣列使用連結符號串成字串
		$table
	)
);
$statement->execute([$holder, $holder, $holder, $start, $limit]);


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
						<!-- 關鍵字查詢 -->
						<form
							method="GET"
							action="<?= $_SERVER['PHP_SELF'] ?>"
							class="d-flex justify-content-start mb-3"
						>

							<div class="d-flex  w-20">
								<input
									type="text"
									name="keyword"
									id="keyword"
									placeholder="請輸入關鍵字搜尋"
									class="form-control me-3 w-75"
									value="<?= $_GET['keyword'] ?? '' ?>"
								>
								<button
									type="submit"
									class="btn btn-primary me-2"
								>
									<i data-feather="search"></i>
								</button>
							</div>
							<button class="btn btn-danger <?= isset ($_GET['keyword']) ? '' : 'd-none' ?>">
								<a
									href="index.php"
									class="text-white text-decoration-none"
								>清除搜尋</a>
							</button>
						</form>

						<section class="d-flex justify-content-between align-items-center">

							<a
								href="./edit.php"
								class="btn btn-primary m-1"
							><i data-feather="plus-circle"></i>&ensp;新增領域</a>
						</section>
						<!-- 列表 -->
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover text-nowrap mb-0  fw-semibold align-middle text-center">
								<thead class="text-dark fs-4 border-bottom border border-2  ">
									<tr>
										<?php foreach (array_values($columns) as $column): ?>
											<th class="border-bottom-0 fw-semibold mb-0 "><?= $column ?></th>
										<?php endforeach ?>
										<th class="border-bottom-0 fw-semibold mb-0 text-center col-1">編輯</th>
										<th class="border-bottom-0 fw-semibold mb-0 text-center col-1">刪除</th>

									</tr>
								</thead>
								<tbody class="border-bottom border border-2">
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
										<tr>
											<?php foreach ($row as $column): ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0">
												<a
													href="edit.php?sn=<?= $row['SN'] ?>"
													class="btn btn-success m-1"
												> <i data-feather="edit"></i></a>
											</td>
											<td class="border-bottom-0 mb-0">
												<a
													href="delete.php?sn=<?= $row['SN'] ?>"
													class="btn btn-outline-danger m-1 bs-danger-rgb"
													onclick="event.preventDefault(), confirm('您確定要刪除第<?= $row['SN'] ?>筆資料嗎?') && (location.href = this.href)"
												><i data-feather="trash-2"></i></a>
											</td>

										</tr>
									<?php endwhile ?>
								</tbody>
							</table>
						</div>
						<!-- 分頁功能 -->
						<section class="d-flex justify-content-center align-items-center">
							<nav aria-label="...">
								<ul class="pagination">
									<!-- 回到第一頁 -->
									<li class="page-item ">
										<a
											class="page-link <?= $page > 1 ? '' : 'disabled' ?>"
											href="?<?= http_build_query([...$_GET, 'page' => 1]) ?>"
										>
											<i data-feather="chevrons-left"></i>
										</a>
									</li>
									<!-- 前一頁 -->
									<li class="page-item ">
										<a
											class="page-link <?= $page > 1 ? '' : 'disabled' ?>"
											href="?page=<?= $page - 1 ?>"
										>
											<i data-feather="chevron-left"></i>
										</a>
									</li>

									<?php for ($i = max(1, $page - 2); $i <= min($page + 2, $pages); $i++): ?>
										<li class="page-item <?= $page == $i ? 'active' : '' ?>">
											<a
												class="page-link"
												href="?<?= http_build_query([...$_GET, 'page' => $i]) ?>"
											><?= $i ?></a>
										</li>
									<?php endfor ?>
									<!-- 下一頁 -->
									<li class="page-item">
										<a
											class="page-link <?= $page < $pages ? '' : 'disabled' ?>"
											href="?page=<?= $page + 1 ?>"
										>
											<i data-feather="chevron-right"></i>
										</a>
									</li>
									<!-- 回到最後頁 -->
									<li class="page-item">
										<a
											class="page-link <?= $page < $pages ? '' : 'disabled' ?>"
											href="?<?= http_build_query([...$_GET, 'page' => $pages]) ?>"
										>
											<i data-feather="chevrons-right"></i>
										</a>
									</li>
								</ul>
							</nav>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
