<?php
include __DIR__ . '/../../arranger.php';
$dir = __DIR__ . '/files/'; # 存放檔案的資料夾
$exts = [   # 檔案類型的篩選 給附檔名
	'image/jpeg' => '.jpg',
	'image/png' =>  '.png',
	'image/webp' => '.webp',
];

$output = [
	'success' => false,
	'file' => '', //成功後儲存的檔案名稱
	'postData' => $_POST, #除錯用
	'error' => '',
	'code' => 0,
	'info' => ''  //是否移除舊檔
];
$ThumbnailSN = $_POST['ThumbnailSN'] ?? 0;

# 若有上傳檔案，並且有imgFile 欄位，並且沒有錯誤，就保存檔案
if (!empty($_FILES) and !empty($_FILES['imgFile']) and $_FILES['imgFile']['error'] == 0) {
	$type = $_FILES['imgFile']['type'];
	if (!empty($exts[$type])) {
		$ext = $exts[$type]; # 副檔名
		$f = sha1($_FILES['imgFile']['name'] . uniqid()); # 隨機的主檔名
		if (move_uploaded_file($_FILES['imgFile']['tmp_name'], $dir . $f . $ext)) { // 確定新檔案上傳完成後，檢查原本是否有圖

			$output['file'] = $f . $ext;
			$hash = str_pad($f, 64, 0);

			if (!empty($_POST['ThumbnailSN'])) { //原本有圖，不動ThumbnailSN，把新資料寫入FILE表，舊的圖片刪掉
				$r = connect()->query("select Filename,Extension from File where SN =" . $_POST['ThumbnailSN'])->fetch(PDO::FETCH_ASSOC);
				$file_path = $r['Filename'] . $r['Extension'];
				if (file_exists($file_path)) { //檢查原檔案是否存在
					unlink($file_path);
					$output['info'] = '已移除舊檔';
				}
				//開始寫進file表
				$sqlFile = "UPDATE `File` set `Filename` =?,Extension=?,ContentType=?,ContentHash=?,UploaderSN=? where SN=" . $_POST['ThumbnailSN'];
				$stmtFile = connect()->prepare($sqlFile);
				$stmtFile->execute([$f, $ext, $type, $hash, 2]);
				//開始寫進Course表
				$sql = "UPDATE `Course` SET`Name`=?,`Intro`=?,`Syllabus`=?,`TeacherSN`=?,`DomainSN`=?,`Price`=? WHERE `SN`=?";
				$stmt = connect()->prepare($sql);
				$stmt->execute([
					$_POST['Name'],
					$_POST['Intro'],
					$_POST['Syllabus'],
					$_POST['TeacherSN'],
					$_POST['DomainSN'],
					$_POST['Price'],
					$_POST['SN'],
				]);
			}

			if (empty($_POST['ThumbnailSN'])) { //原本無圖，新增檔案並在Course表填入ThumbnailSN
				//開始寫進file表
				$sqlFile = "INSERT INTO `File`(`Filename`,`Extension`,ContentType,`ContentHash`,`UploaderSN`)VALUES(?,?,?,?,?)";
				$stmtFile = connect()->prepare($sqlFile);
				$stmtFile->execute([$f, $ext, $type, $hash, 2]);

				//開始寫進Course表
				$ThumbnailSN = connect()->query(sprintf("select SN from File order by SN DESC limit 1"))->fetch(PDO::FETCH_ASSOC);
				$sql = "UPDATE `Course` SET`Name`=?,`Intro`=?,`Syllabus`=?,`TeacherSN`=?,`DomainSN`=?,`Price`=?,ThumbnailSN=? WHERE `SN`=?";

				$stmt = connect()->prepare($sql);
				$stmt->execute([
					$_POST['Name'],
					$_POST['Intro'],
					$_POST['Syllabus'],
					$_POST['TeacherSN'],
					$_POST['DomainSN'],
					$_POST['Price'],
					$ThumbnailSN['SN'],
					$_POST['SN'],
				]);
			}

			$output['success'] = 'Course成功的筆數' . $stmt->rowCount() . 'File成功的筆數' . $stmtFile->rowCount();
		}
	}
} else {
	//若沒有上傳修改圖片
	//開始寫進Course表
	$sql = "UPDATE `Course` SET`Name`=?,`Intro`=?,`Syllabus`=?,`TeacherSN`=?,`DomainSN`=?,`Price`=? WHERE `SN`=?";

	$stmt = connect()->prepare($sql);
	$stmt->execute([
		$_POST['Name'],
		$_POST['Intro'],
		$_POST['Syllabus'],
		$_POST['TeacherSN'],
		$_POST['DomainSN'],
		$_POST['Price'],
		$_POST['SN'],
	]);
	$output['success'] = 'Course成功的筆數' . $stmt->rowCount() . 'File成功的筆數' .'沒有更動File';
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
