<?php
include __DIR__ . '/../../arranger.php';

$dir = __DIR__ . '/files/'; # 存放檔案的資料夾
$exts = [   # 檔案類型的篩選 給附檔名
	'image/jpeg' => '.jpg',
	'image/png' =>  '.png',
	'image/webp' => '.webp',
];

#先寫好要回應給用戶端的東西格式
$output = [
	'success' => false,
	'file' => '', //成功後儲存的檔案名稱
	'postData' => $_POST, #除錯用
	'error' => '',
	'code' => 0,
	'id' => '',
];

$Approver = isset($_POST['check']) ? 2 : null;

# 保存檔案，確保有上傳檔案，並且有imgFile 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['imgFile']) and $_FILES['imgFile']['error'] == 0) {
	# 如果類型有對應到副檔名
	$type = $_FILES['imgFile']['type'];
	if (!empty($exts[$type])) { # 如果上船的檔案類型有對應到$exts裡的值
		$ext = $exts[$type]; # 副檔名
		$f = sha1($_FILES['imgFile']['name'] . uniqid()); # 隨機的主檔名
		if (move_uploaded_file($_FILES['imgFile']['tmp_name'], $dir . $f . $ext)) {
			$output['file'] = $f . $ext;


			//開始寫進file表
			$hash = str_pad($f, 64, 0);

			$sqlFile = "INSERT INTO `File`(`Filename`,`Extension`,ContentType,`ContentHash`,`UploaderSN`)
VALUES(?,?,?,?,?)";
			$stmtFile = connect()->prepare($sqlFile);
			$stmtFile->execute([$f, $ext, $_FILES['imgFile']['type'], $hash, 2]);

			//開始寫進Course表
			$ThumbnailSN = connect()->query(sprintf("select SN from File order by SN DESC limit 1"))->fetch(PDO::FETCH_ASSOC);
			$sql =
				"INSERT INTO `Course`(`Name`, `Intro`,`Identifier`, `Syllabus`, `TeacherSN`, DailySN,`ThumbnailSN`,`Price`,`DomainSN`,`WhenCreated`,ApproverSN)
VALUES (
?,?,?,?,
?,?,
?,?,?,
datetime('now'),
?)";
			$stmt = connect()->prepare($sql);
			$stmt->execute([
				$_POST['Name'],
				$_POST['Intro'],
				strval(uniqid()),
				$_POST['Syllabus'],
				$_POST['TeacherSN'],
				1,
				$ThumbnailSN['SN'],
				$_POST['Price'],
				$_POST['DomainSN'],
				$Approver,
			]);

			$output['success'] = 'Course成功的筆數' . $stmt->rowCount() . 'File成功的筆數' . $stmtFile->rowCount();
			$id = connect()->query(sprintf("select SN from Course order by SN DESC limit 1"))->fetch(PDO::FETCH_ASSOC);
			$output['id'] = $id['SN'];
		}
	}
} else {
	$output['error'] = '未上傳圖片';
}





header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
