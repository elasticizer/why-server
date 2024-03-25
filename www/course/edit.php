<?php

$title = (isset($_GET['courseID']) ? '編輯' : '新增') . '課程';
$layout = './layout/layout.php';

$page = $_GET['page'] ?? 1;
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$limitPerpage = $_GET['limitPerpage'] ?? 5;
$courseID = $_GET['courseID'] ?? 0;

require '../arranger.php';
include find('./component/sidebar.php');

$allDomain = connect()->query('SELECT * FROM `Domain`;')->fetchAll(PDO::FETCH_ASSOC);
$allTeachers = connect()->query('SELECT Nickname,SN FROM `User` WHERE WhenQualified is not null;')->fetchAll(PDO::FETCH_ASSOC);
$r = connect()->query("SELECT * FROM Course WHERE SN= $courseID")->fetch(PDO::FETCH_ASSOC);
if (!empty($r['ThumbnailSN'])) {
	$r_file = connect()->query("select Filename,Extension from File where SN =" . $r['ThumbnailSN'])->fetch(PDO::FETCH_ASSOC);
	if (strpos($r_file['Filename'], 'https:') !== false) {
		$file_path = $r_file['Filename'];
	} else {
		$file_path = $r_file['Filename'] . $r_file['Extension'];
		$file_content = file_get_contents(__DIR__ . "/api/files/$file_path");
		$base64_file = base64_encode($file_content);
	} //icacls "C:\xampp\htdocs\why\why-server\www\course\api\files" /grant BUILTIN\Users:(OI)(CI)(M) /T
}


$states = (isset($r['ApproverSN']) ? (!empty($r['WhenLaunched']) ? ['btn-dark', '<i data-feather="corner-right-down"></i>下架', '<span class="text-success" >已上架</span>'] : ['btn-warning', '<i data-feather=upload></i>上架', '已下架']) : ['btn-success', ' <i data-feather="check-circle"></i>核准', '<span class ="text-danger">未審核</span>']);

?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.snow.css" rel="stylesheet" />
<style>
	.quillContent {
		height: 500px;
	}
</style>
<div class="body-wrapper">
	<?php include find('./component/header.php') ?>
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<a class="btn btn-primary me-3 " href="index.php?page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>"><i data-feather=arrow-left></i>回到上一頁</a>
				<?= $courseID == 0 ? '' : '<a class="btn btn-primary" href="javascript:location.reload()"><i data-feather=refresh-ccw></i>重製修改</a>' ?>
				<h5 class="card-title fw-semibold mb-4 pt-3 "><?= $title ?></h5>
				<div class="mb-3 hstack gap-3">
					<?php if ($courseID != 0) : ?>
						<label class="form-label text-nowrap">狀態</label>
						<label class="form-control"><?= $states[2] ?></label>

						<a class="btn btn-danger text-nowrap btn-lg" data-bs-toggle="modal" data-bs-target='#approveModal' onclick="javascript:deleteCourse(<?= $r['SN'] ?>)"><i data-feather=upload></i>刪除課程</a>
						<a class="btn btn-primary  text-nowrap btn-lg"><i data-feather=upload></i>促銷</a>
						<!-- Button trigger modal -->
						<a class="btn btn-lg text-nowrap <?= $states[0] ?>" onclick="javascript:approveCourse('<?= $r['SN'] ?>','<?= isset($r['ApproverSN'])?:0 ?>','<?= isset($r['WhenLaunched']) ?: 0 ?>')" data-bs-toggle="modal" data-bs-target="#approveModal"><?= $states[1] ?>
						</a>
					<?php endif; ?>
				</div>
				<form name="createCourse" onsubmit="sendData(event)" method="post">
					<?php if ($courseID != 0) : ?>
						<div class="mb-3">
							<label for="SN" class="form-label">編號</label>
							<input type="text" readonly class="form-control" id="SN" name="SN" value="<?= $courseID == 0 ?: $r['SN'] ?>">
						</div>
					<?php endif; ?>

					<div class="mb-3">
						<label for="Name" class="form-label">標題</label>
						<input type="text" class="form-control" id="Name" name="Name" value="<?php if ($courseID != 0) {
																									echo $r['Name'];
																								} ?>">
						<div class="form-text text-danger d-none">請輸入標題</div>
					</div>
					<div class="mb-3">
						<label for="DomainSN" class="form-label">課程類別</label>
						<select class="form-select" name="DomainSN" id="DomainSN">
							<?php if ($courseID == 0) : //新增
							?>
								<?php foreach ($allDomain as $Domain) : ?>
									<option value="<?= $Domain['SN'] ?>"> <?= $Domain['Name'] ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php if ($courseID != 0) : //編輯
							?>
								<option value="<?= $r['DomainSN'] ?>">
									<?php
									$CourseClass = connect()->query('SELECT `Name` FROM `Domain` WHERE SN=' . $r['DomainSN'] . ';')->fetch(PDO::FETCH_ASSOC);
									echo $CourseClass['Name'] ?>
								</option>

								<?php foreach ($allDomain as $Domain) : ?>
									<?php if ($Domain['SN'] != $r['DomainSN']) : ?>
										<option value="<?= $Domain['SN'] ?>"> <?= $Domain['Name'] ?> </option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="Intro" class="form-label">簡介</label>
						<textarea type="text" class="form-control" id="Intro" name="Intro" rows="3"><?php if ($courseID != 0) {
																										echo $r['Intro'];
																									} ?></textarea>
						<div class="form-text text-danger d-none">請輸入簡介</div>

					</div>

					<div class="mb-3">
						<label for="Price" class="form-label">價格</label>
						<input type="number" class="form-control" id="Price" name="Price" value="<?php if ($courseID != 0) {
																										echo $r['Price'];
																									}; ?>">
						<div class="form-text text-danger d-none">請輸入價格</div>

					</div>
					<div class="mb-3">
						<label for="TeacherSN" class="form-label">教師</label>
						<select class="form-select" name="TeacherSN" id="TeacherSN">
							<?php foreach ($allTeachers as $teacher) : ?>
								<option value="<?= $teacher['SN'] ?>"> <?= $teacher['Nickname'] ?> </option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label for="imgFile" class="form-label w-100">圖片<div class="form-control">上傳圖片</div></label>
						<input hidden class="form-control" type="file" id="imgFile" accept="image/jpeg,image/png,image/webp" multiple="false" onchange="showTemp(event)" name="imgFile" />

						<!-- 接受的檔案類型
						一個隱藏的input來放原始圖片ThumbnailSN-->
						<?php if ($courseID != 0 && !empty($r['ThumbnailSN'])) : ?>
							<input name="ThumbnailSN" type="text" hidden value="<?= $r['ThumbnailSN'] ?>">
						<?php endif; ?>
					</div>

					<div><!-- 空的img或原始圖片-->
						<img class="mb-3" src="<?php if ($courseID != 0) {
													if (strpos($r_file['Filename'], 'https:') !== false) {
														echo $file_path;
													} else {
														echo ('data:image/jpeg;base64,' . $base64_file);
													}
												} ?>" alt="" id="myimg" width="100%" />
					</div>
					<div class="mb-3">
						<label for="Syllabus" class="form-label">課綱</label>
						<?php if ($courseID != 0) : ?>
							<a class="btn btn-primary me-3 btn-sm" onclick="javascript:removeQuillContent();this.parentElement.lastElementChild.classList.remove('d-none');">展開</a>
							<a class="btn btn-primary me-3 btn-sm" onclick="javascript:addQuillContent();this.parentElement.lastElementChild.classList.add('d-none');">收起</a>
						<?php endif; ?>

						<div id="editor" class="<?php if ($courseID != 0) {
													echo "d-none";
												} ?>">
						</div>
						<textarea type="text" class="form-control" id="Syllabus" name="Syllabus" cols="30" rows="10" hidden></textarea>
						<div class="form-text text-danger d-none">請輸入課綱</div>
						<?php if ($courseID != 0) : ?>
							<a class="btn btn-primary me-3 btn-sm d-none " onclick="javascript:addQuillContent();this.classList.add('d-none');">收起</a>
						<?php endif; ?>

					</div>
					<?php if ($courseID == 0) : ?>
						<div class="mb-3 form-check">
							<input type="checkbox" class="form-check-input" id="check" name="check" value="approve">
							<label class="form-check-label" for="check">直接核准</label>
						</div>
					<?php endif; ?>
					<button type="submit" class="btn btn-primary"><?= $courseID == 0 ? '新增課程' : '更改課程資料' ?></button>
				</form>
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
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.js"></script>
<script>
	const toolbarOptions = [
		['bold', 'italic', 'underline', 'strike'], // toggled buttons
		['blockquote', 'code-block'],
		['link', 'image'],
		[{
			'header': 1
		}, {
			'header': 2
		}],
		[{
			'list': 'ordered'
		}, {
			'list': 'bullet'
		}, {
			'list': 'check'
		}],
		[{
			'header': [1, 2, 3, 4, 5, 6, false]
		}],
		[{
			'color': []
		}],
		[{
			'align': ''
		}, {
			'align': 'center'
		}, {
			'align': 'right'
		}],
	];

	let quill = new Quill('#editor', {
		theme: 'snow',
		modules: {
			toolbar: toolbarOptions
		}
	});


	let semanticHTML;

	quill.on('text-change', function(delta, oldDelta, source) {
		if (source === 'user') {
			semanticHTML = quill.getSemanticHTML();
		}
	});
	let quillContent = document.querySelector('#editor > div');
	// console.log(quillContent);
	quillContent.classList.add('quillContent');

	let SyllabusEl = document.querySelector('#Syllabus');

	<?php if ($courseID == 0) : ?>
		quillContent.parentElement.classList.remove('d-none');

	<?php endif; ?>

	<?php if ($courseID != 0) : ?>

		function delay(ms) {
			return new Promise(r => setTimeout(r, ms));
		}
		let Syllabus = `<?= $r['Syllabus']; ?>`;
		delay(2000).then(() => {
			quillContent.innerHTML = Syllabus;
			SyllabusEl.innerHTML = Syllabus;
			let videoEL = quillContent.querySelector('iframe');
			console.log(videoEL);
			if (videoEL) {
				console.log(1111);
				videoEL.onload = (e) => {
					quillContent.parentElement.classList.remove('d-none');
					console.log('22222');
				}
			} else {
				quillContent.parentElement.classList.remove('d-none');
			}
		});
	<?php endif; ?>

	function removeQuillContent() {
		quillContent.classList.remove('quillContent');
	}

	function addQuillContent() {
		if (!quillContent.classList.contains('quillContent')) {
			quillContent.classList.add('quillContent');
		}
	};
</script>
<!-- form -->
<script>
	let imgFile = document.getElementById('imgFile');

	function sendData(e) {
		e.preventDefault();
		// 防止送出表單重整

		let frontCheck = true;
		SyllabusEl.innerHTML = semanticHTML;
		const editCourseFD = new FormData(document.createCourse);

		// log看一下editCourse表單送出的資料
		for (let i of editCourseFD.entries()) {
			console.log(i);
		};

		for (let v of editCourseFD.entries()) {
			if (v[1] == false || v[1] == 'undefined') {
				let element = document.getElementById(v[0]);
				element.nextElementSibling.classList.remove('d-none');
				frontCheck = false;
			}

		}

		// 發ajax
		<?php if ($courseID != 0) : ?>
			// 編輯

			fetch('api/edit.php', {
					method: "POST",
					body: editCourseFD,
				})
				.then((r) => r.json())
				.then((result) => {
					if (result.success) {
						alert('已修改課程');
						window.scroll({
							top: 0,
							left: 0,
							behavior: 'smooth'
						});

					} else {
						alert("修改失敗");
					}
				});
		<?php endif ?>
		<?php if ($courseID == 0) : ?>
			// 新增
			if (frontCheck) {
				fetch('api/add.php', {
						method: "POST",
						body: editCourseFD,
					})
					.then((r) => r.json())
					.then((result) => {
						if (result.success) {
							alert('已新增課程');
							location = `?courseID=${result.id}&page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>`;
							window.scroll({
								top: 0,
								left: 0,
								behavior: 'smooth'
							});
						} else {
							alert(`新增失敗，${result.error}`);
						}
					})
			} else {
				window.scroll({
					top: 0,
					left: 0,
					behavior: 'smooth'
				});
				alert('尚有資料未輸入');
			}
		<?php endif ?>
	}

	function showTemp(e) {
		let selectedFile = imgFile.files[0]
		let fr = new FileReader();
		fr.readAsDataURL(selectedFile);
		fr.onload = (e) => {
			myimg.src = e.target.result;
		}
	}
	let approveModal = document.getElementById('approveModal');
	let approveBtn = document.getElementById('approveBtn');
	let modalTitle = document.getElementById('modalTitle');
	let modalBody = document.getElementById('modalBody');

	function approveCourse(id, approver, available) {
		console.log(id, approver, available);
		let queryString = `?courseID=${id}&page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>`;
		let courseID = `課號 <span class="text-danger text-decoration-underline">${id}</span> ?`;
		if (approver === '0') {
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

	function deleteCourse(id) {
		let courseID = `課號 <span class="text-danger text-decoration-underline">${id}</span> ?`;
		modalTitle.innerHTML = '是否刪除課程'
		modalBody.innerHTML = '確認刪除課程?'
		approveBtn.innerHTML = '刪除';
		// approveBtn.classList.add('btn-danger');

		approveBtn.href = "api/delete.php?courseID=" + id + "&page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>";
	}
</script>
