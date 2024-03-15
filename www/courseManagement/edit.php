<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '課程';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form>
					<div class="mb-3">
						<label for="exampleInputEmail1" class="form-label">標題</label>
						<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						<div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
					</div>
					<div class="mb-3">
						<label for="exampleInputPassword1" class="form-label">類別</label>
						<input type="password" class="form-control" id="exampleInputPassword1">
					</div>
					<div class="mb-3 form-check">
						<input type="checkbox" class="form-check-input" id="exampleCheck1">
						<label class="form-check-label" for="exampleCheck1">Check me out</label>
					</div>
					<button type="submit" class="btn btn-primary">提交表單</button>
				</form>

				<form class="createCourse rounded-5" name="createCourse" onsubmit="sendData(event)" method="post">
					<div class="mb-3 hstack">
						<label for="title" class="form-label me-auto">標題</label>
						<input type="text" class="form-control" id="title" name="title">
						<div id="Help" class="form-text text-danger"><i class="fa-solid fa-circle-exclamation"></i>必填</div>
					</div>
					<div class="mb-3 hstack">
						<label for="courseClassSN" class="form-label me-auto">類別</label>
						<select class="form-select" name="courseClassSN" id="courseClassSN">
							<?php $allCourseClass  = $pdo->query('SELECT * FROM `courseclass`;')->fetchAll(PDO::FETCH_ASSOC); ?>
							<?php foreach ($allCourseClass as $CourseClass) : ?>
								<option value="<?= $CourseClass['courseClassSN'] ?>"> <?= $CourseClass['className'] ?> </option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3 hstack">
						<label for="intro" class="form-label me-auto">簡介</label>
						<textarea type="text" class="form-control" id="intro" name="intro" cols="30" rows="10"></textarea>
					</div>
					<div class="mb-3 hstack">
						<label for="syllabus" class="form-label me-auto">課綱</label>
						<input type="text" class="form-control" id="syllabus" name="syllabus">
					</div>
					<div class="mb-3 hstack">
						<label for="price" class="form-label me-auto">價格</label>
						<input type="number" class="form-control" id="price" name="price">
					</div>
					<div class="mb-3 hstack">
						<label for="teacherID" class="form-label me-auto">教師</label>
						<select class="form-select" name="userID" id="teacherID">
							<?php $allTeachers = $pdo->query('SELECT userName,userID FROM `user` WHERE isTeacher=1;')->fetchAll(PDO::FETCH_ASSOC); ?>
							<?php foreach ($allTeachers as $teacher) : ?>
								<option value="<?= $teacher['userID'] ?>"> <?= $teacher['userName'] ?> </option>
							<?php endforeach; ?>
						</select>
					</div>



					<div class="mb-3 hstack">
						<label for="formFile" class="hstack w-100">圖片<div class="form-control ms-auto">上傳圖片</div></label>
						<input hidden class="form-control" type="file" id="formFile" accept="image/jpeg,image/png,image/webp" multiple="false" onchange="showTemp(event)" name="imgFile" />
						<!-- 接受的檔案類型 -->
					</div>
					<div><!-- 空的img-->
						<img src="" alt="" id="myimg" width="100%" />
					</div>
					<button type="submit" class="btn btn-primary">新增課程</button>
				</form>
			</div>
		</div>
	</div>
</div>
