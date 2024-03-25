<?php
$title = '課程管理';
$layout = './layout/layout.php';
require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limitPerpage = $_GET['limitPerpage'] ?? 10;
$columnsName = [
	'編號',
	'標題',
	'教師',
	'現價',
	'上架狀態',
	'促銷狀態',
	'售出數量',
	'操作',
	'編輯',
];
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$options = [5, 10, 20];

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<div class="p-2 rounded-4 py-2 hstack justify-content-between ">
							<h5 class="card-title fw-semibold me-4 text-nowrap"><?= $title ?></h5>
							<a class="btn btn-primary text-nowrap" href="javascript:addCourse()"><i data-feather="edit"></i>新增課程</a>
						</div>
						<!-- 排序 -->
						<div class="row">
							<div class=" p-2 rounded-4 py-2 col-3 vstack justify-content-center gap-2">
								<div class="hstack">
									<h5 class="m-0">共</h5>
									<h5 id="totalRows" class="m-0"></h5>
									<h5 class="text-nowrap m-0">筆</h5>
								</div>
								<div class="hstack">
									<h5 class="text-nowrap m-0">每頁顯示</h5>

									<!-- <div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-outline-primary">Left</button>
									<button type="button" class="btn btn-outline-primary">Middle</button>
									<button type="button" class="btn btn-outline-primary">Right</button>
								</div> -->
									<form action="" name="limitPerpageForm">
										<ul class="btn-group btn-group-sm m-0 mx-3 " role="group" id="limitPerpageUl">
											<?php foreach ($options as $option) : ?>
												<input type="radio" class="btn-check" name="limitPerpageVa" value="<?= $option ?>" id="<?= $option ?>" autocomplete="off">
												<label class="btn btn-outline-primary" for="<?= $option ?>"><?= $option ?></label>
											<?php endforeach; ?>
										</ul>
									</form>
									<h5 class="m-0">筆</h5>
								</div>
							</div>
							<div class=" p-2 rounded-4 py-2 col-4 pe-5">
								<h5 class="text-nowrap">排序方法</h5>

								<select name="orderSelect" id="orderSelect" class="form-select">
									<?php $options = [['courseID由小到大', 'SN ASC'], ['courseID由大到小', 'SN DESC'], ["price由大到小", 'Price DESC'], ["price由小到大", 'Price ASC']];

									foreach ($options as $option) : ?>
										<option <?= $orderValue == $option[1] ? 'selected' : '' ?> value="<?= $option[1] ?>"><?= $option[0] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class=" p-2 rounded-4 py-2 col-5">
								<h5>篩選</h5>
								<div class="hstack gap-5 align-items-start ">
									<div id="filterApprove">
										<div class="form-check">
											<input class="form-check-input" type="radio" value="1" id="filter0" name="filterApprove" checked>
											<label class="form-check-label" for="filter0">
												所有課程
											</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="2" id="filter1" name="filterApprove">
											<label class="form-check-label" for="filter1">
												未審核
											</label>
										</div>
									</div>
									<div id="filterLaunch">
										<div class="form-check">
											<input class="form-check-input" type="radio" value="0" id="filter2" name="filter" checked>
											<label class="form-check-label" for="filter2">
												不限
											</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="1" id="filter3" name="filter">
											<label class="form-check-label" for="filter3">
												已上架
											</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="2" id="filter4" name="filter">
											<label class="form-check-label" for="filter4">
												已審核未上架
											</label>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="hstack  me-3 col-3"><i data-feather="search" class="me-2"></i>
							<input type="text" name="keyword" placeholder="搜索" class="form-control me-3" id="keyword">
						</div>
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columnsName as $c) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $c ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
							<!-- 選頁 -->
							<nav class="d-flex gap-3 page-nav align-items-center mt-3">
								<a id="previousPage" class="btn btn-primary rounded-5">
									<button class="page-link">上一頁</button>
								</a>
								<ul class="pagination -sm m-0" id="pageNumber" style="cursor: pointer;">
								</ul>

								<a class="btn btn-primary rounded-5" id="nextPage">
									<button class="page-link ">下一頁</button>
								</a>
								<span>共<span id="totalPages"></span>頁</span>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalTitle"></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" id="modalBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
				<a class="btn btn-primary" id="approveBtn"></a>
			</div>
		</div>
	</div>
</div>
<!-- JS -->
<script>
	<?php if (isset($_GET['d'])) : ?>
		alert('已刪除課程');
		location = "?page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>";
	<?php endif; ?>


	let page = <?= $page ?> || 1;

	let otherLimit = ['1', '0'];
	let approveModal = document.getElementById('approveModal');
	let approveBtn = document.getElementById('approveBtn');
	let modalTitle = document.getElementById('modalTitle');
	let modalBody = document.getElementById('modalBody');
	let limitPerpage;
	let totalPages;
	let pageNumberUl = document.getElementById("pageNumber");




	let loadData = (page, orderValue, otherLimit) => {
		const FD = new FormData(document.limitPerpageForm);
		let data = {
			'page': page,
			'orderValue': orderValue,
			'otherLimit': otherLimit,
		};
		//兩個一起包起來
		// FD.entries().forEach(v => {
		// 	console.log(v);
		// });
		for (const pair of FD.entries()) {
			data[pair[0]] = pair[1]; //左鍵右值丟進去，鍵仍是name
		}
		limitPerpage = (data['limitPerpageVa'] == undefined ? 5 : data['limitPerpageVa']);
		// console.log(data);

		// 重置
		tbody.innerHTML = '';
		pageNumberUl.innerHTML = '';
		previousPageBtn.classList.remove('disabled');
		nextPageBtn.classList.remove('disabled');

		fetch(`api/sort.php?`, {
			method: "POST",
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data),
		}).then((r) => {
			return r.json();
		}).then((output) => {


			console.log(output);
			totalPages = output['totalPages'];
			totalPagesEL.innerText = output['totalPages'] || 1;
			totalRowsEL.innerText = output['totalRows'];
			output['rows'].forEach((v, i, r) => {
				let approver = (v[4][2].includes('danger') ? 0 : 1);
				let available = (v[4][2].includes('success') ? 1 : 0);
				tbody.innerHTML += `<tr>
				<?php for ($i = 0; $i < 7; $i++) : ?>
					<td>
					<?php if ($i != 4) : ?>
					${v["<?= $i ?>"]}
					<?php endif; ?>
					<?php if ($i == 4) : ?>
					${v["<?= $i ?>"][2]}
					<?php endif; ?>
					</td>
				<?php endfor; ?>
				<td><a class = "btn ${v[4][0]}"
			onclick = "javascript:approveCourse(${v[0]},${approver},${available})"
			data-bs-toggle = "modal"
			data-bs-target = '#approveModal'>
			${v[4][1]}</a></td>
			<td><a class="btn btn-primary" href="edit.php?courseID=${v[0]}&page=${page}&orderValue=${orderValue}&limitPerpage=${limitPerpage}"><i data-feather="edit"></i>編輯</a></td>
			</tr>`;
			});

			if (page === 1) {
				previousPageBtn.classList.add('disabled')
			}
			if (page === output['totalPages']) {
				nextPageBtn.classList.add('disabled')
			}
			for (let i = page - 4; i <= page + 4; i++) {
				if (i >= 1 && i <= output['totalPages']) {
					if (i === page) {
						pageNumberUl.innerHTML +=
							`<li class = "page-item active" >
          <a class = "page-link" >${i}</a> </li>`
					} else {
						pageNumberUl.innerHTML +=
							`<li class = "page-item" >
          <a class = "page-link" >${i}</a> </li>`
					}
				}

			}



		})
	}



	function approveCourse(id, approver, available) {
		console.log(id, approver, available);
		let queryString = `?courseID=${id}&page=${page}&orderValue=${orderValue}&limitPerpage=${limitPerpage}`;
		let courseID = `課號 <span class="text-danger text-decoration-underline">${id}</span> ?`;
		if (approver === 0) {
			approveBtn.href = `api/approve.php${queryString}`; //跳轉到approve頁面
			modalTitle.innerHTML = '是否核准課程';
			modalBody.innerHTML = `操作不可逆。<br>確定核准課程 ${courseID}`;
			approveBtn.innerHTML = '核准';
		} else if (available != 0) {
			approveBtn.href = `api/launch.php${queryString}`;
			modalTitle.innerHTML = '是否下架課程';
			modalBody.innerHTML = `確定下架課程  ${courseID}`;
			approveBtn.innerHTML = '下架';
		} else {
			approveBtn.href = `api/launch.php${queryString}`;
			modalTitle.innerHTML = '是否上架課程';
			modalBody.innerHTML = `確定上架課程  ${courseID}`;
			approveBtn.innerHTML = '上架';
		}
	}


	let tbody = document.querySelector('tbody');
	let totalPagesEL = document.getElementById('totalPages');
	let totalRowsEL = document.getElementById('totalRows');


	let orderValueEl = document.getElementById("orderSelect");
	let orderValue = "<?= $orderValue ?>" || 'SN ASC';



	// orderValueEl.firstChild.checked = true;

	orderValueEl.onchange = (e) => {
		orderValue = orderValueEl.value;
		// console.log(orderValueEl);
		page = 1;
		loadData(page, orderValue, otherLimit);
	}

	let limitPerpageUl = document.getElementById('limitPerpageUl');
	// console.log(limitPerpageUl.children[0]);
	limitPerpageUl.children[0].checked = true;

	function delay(ms) {
		return new Promise(r => setTimeout(r, ms));
	}


	limitPerpageUl.onclick = (e) => {
		const selected = document.querySelector('input[name="limitPerpageVa"]:checked');
		selected.onchange = () => {
			page = 1;
			loadData(page, orderValue, otherLimit);
		}
	}

	let previousPageBtn = document.getElementById('previousPage');
	let nextPageBtn = document.getElementById('nextPage');
	previousPageBtn.onclick = (e) => {
		if (page > 1) {
			page--;
			loadData(page, orderValue, otherLimit);
		}
	};
	nextPageBtn.onclick = (e) => {
		if (page < totalPages) {
			page++;
			loadData(page, orderValue, otherLimit);
		}
	};
	pageNumberUl.onclick = (e) => {
		console.log(Number(e.target.innerHTML));
		page = Number(e.target.innerHTML);
		loadData(page, orderValue, otherLimit);
	}
	// 首次加載立即執行
	(function() {
		loadData(page, orderValue, otherLimit);
	})();

	function addCourse() {
		location = `edit.php?page=${page}&orderValue=${orderValue}&limitPerpage=${limitPerpage}`;
	};

	let filterLaunch = document.getElementById('filterLaunch');
	let filterApprove = document.getElementById('filterApprove');
	let filter2 = document.getElementById('filter2');
	filterApprove.onclick = (e) => {
		if (e.target.tagName === 'INPUT') {
			let selected = document.querySelector("input[name='filterApprove']:checked");
			console.log(selected.value);
			otherLimit[0] = selected.value;
			page = 1;
			if (selected.value === "1") {
				filterLaunch.classList.remove('d-none');
			} else {
				filterLaunch.classList.add('d-none');
				otherLimit[1] = "0";
				filter2.checked = true;
			}
			loadData(page, orderValue, otherLimit);

		}
		// selected.onchange=
	}

	filterLaunch.onclick = (e) => {
		if (e.target.tagName === 'INPUT') {
			let selected = document.querySelector("input[name='filter']:checked");
			console.log(selected.value);
			otherLimit[1] = selected.value;
			page = 1;
			loadData(page, orderValue, otherLimit);
		}

	}
	let timeoutId;
	let keyword = document.getElementById('keyword');
	keyword.onkeyup = (e) => {
		clearTimeout(timeoutId);
		timeoutId = setTimeout(() => {
			console.log(e.target.value);
			otherLimit[2] = e.target.value;
			page = 1;
			loadData(page, orderValue, otherLimit);
		}, 100);//debounce ，防止注音候選詞觸發兩次keyup
	}

</script>
