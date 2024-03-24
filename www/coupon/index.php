<?php
$title = '優惠券'; // 頁面標題
$layout = './layout/layout.php'; // 頁面排版

require '../arranger.php';

// 計算優惠券的總數去做分頁功能
$page = $_GET['page'] ?? 1;
$table = 'Coupon';
$statement = connect()->prepare("SELECT COUNT(*) FROM {$table} JOIN Staff ON {$table}.CreatorSN = Staff.SN WHERE {$table}.Name LIKE ?");
$statement->execute(['%' . ($_GET['keyword'] ?? '') . '%']);
$total = $statement->fetch(PDO::FETCH_NUM)[0];
$limit = 10; // 每頁10筆
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);
$sortValue = $_GET['sortValue'] ?? 'SN ASC'; // 排序
$options = [['依序號由小到大', 'SN ASC'], ['依序號由大到小', 'SN DESC'], ['依折扣比例由小到大', 'DiscountRate ASC'], ['依折扣比例由大到小', 'DiscountRate DESC']];
$columns = [
	"{$table}.SN" => '序號',
	'Identifier' => '識別碼',
	'Name' => '名稱',
	'Description' => '說明',
	'DiscountRate' => '折扣比率',
	'FirstName' => '建立者'
];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM %s JOIN Staff ON {$table}.CreatorSN = Staff.SN WHERE {$table}.Name LIKE ? ORDER BY {$table}.%s LIMIT ?, ?",
		implode(', ', array_keys($columns)),
		$table,
		$sortValue
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
						<!-- 排序 -->
						<select class="form-select mb-3" onchange="changeSort(this)">
							<?php foreach ($options as $option) : ?>
								<option <?= $sortValue == $option[1] ? 'selected' : '' ?> value="<?= $option[1] ?>"><?= $option[0] ?></option>
							<?php endforeach; ?>
						</select>
						<!-- 搜尋功能 -->
						<form action="<?= $_SERVER['PHP_SELF'] ?>" method="GET" id="searchForm"">
							<div class=" input-group mb-3">
							<input type="text" name="keyword" placeholder="請輸入欲查詢之優惠券" class="form-control" value="<?= $_GET['keyword'] ?? '' ?>">
							<button class="btn btn-outline-primary" type="submit"><i data-feather="search"></i></button>
					</div>
					</form>
					<!-- 分頁按鈕 & 新增按鈕 -->
					<div class='d-flex justify-content-between align-items-center'>
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
						<div><a href="./edit.php" class="btn btn-success"><i data-feather="file-plus"></i> 新增</a></div>
					</div>
					<!-- 列表 -->
					<div class="table-responsive">
						<table class="table text-nowrap mb-0 align-middle table-hover">
							<thead class="text-dark fs-4">
								<tr>
									<?php foreach (array_values($columns) as $column) : ?>
										<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
									<?php endforeach ?>
									<th class="border-bottom-0 fw-semibold mb-0 text-center">編輯</th>
									<th class="border-bottom-0 fw-semibold mb-0 text-center">刪除</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
									<tr>
										<?php foreach ($row as $column) : ?>
											<td class="border-bottom-0 mb-0 "><?= $column ?></td>
										<?php endforeach ?>
										<td class="border-bottom-0 text-center">
											<a href="edit.php?sn=<?= $row['SN'] ?>" class="btn btn-primary m-1"><i data-feather="edit"></i> 編輯</a>
										</td>
										<td class="border-bottom-0 text-center">
											<a href="javascript: deleteOne(<?= $row['SN'] ?>)" class="btn btn-danger m-1"><i data-feather="trash-2"></i> 刪除</a>
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

	function changeSort(select) {
		var selectedSort = select.value;
		var urlParams = new URLSearchParams(window.location.search);
		// window.location.search 取得目前造訪網頁查詢參數(搜索關鍵字)
		var keyword = urlParams.get('keyword');
		// 更新 URL 的參數和關鍵字
		urlParams.set('sortValue', selectedSort);
		// 重新載入頁面以應用新的排序
		window.location.href = window.location.pathname + '?' + urlParams.toString() + (keyword ? '&keyword=' + keyword : '');
		// window.location.pathname 取得目前造訪網頁的路徑(path)
	}
</script>
