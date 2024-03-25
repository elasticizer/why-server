<?php

$title = (isset($_GET['sn']) ? '編輯' : '新增') . '文章';
$layout = './layout/layout.php';
require '../arranger.php';

$sn = isset($_GET['sn']) ? intval($_GET['sn']) : 0;

if (!empty($sn)) {
	$statement = connect()->prepare("SELECT * FROM Article WHERE SN=?");
	$statement->execute([$_GET['sn']]);
	$row = $statement->fetch(PDO::FETCH_ASSOC);
};

include find('./component/sidebar.php');
?>

<style>
	.form-control:user-invalid {
		border: 1px solid red;
	}
</style>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form id="form1" name="form1" action="<?= isset($_GET['sn']) ? 'edit-api.php?sn=' . $_GET['sn'] : 'add-api.php' ?>" method="POST">
					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label for="SN" class="form-label">SN</label>
							<input type="text" class="form-control disabled" id="Identifier" name="SN" value="<?= isset($_GET['sn']) ? $row['SN'] : '' ?>" disabled>
						</div>
					<?php endif ?>
					<div class="mb-3">
						<label for="Identifier" class="form-label">識別碼</label>
						<input type="text" class="form-control" id="Identifier" name='Identifier' value='<?= isset($_GET['sn']) ? $row['Identifier'] : "" ?>' required>
						<div class="invalid-feedback">
							請填入識別碼
						</div>
					</div>
					<div class="mb-3">
						<label for="Title" class="form-label">標題</label>
						<input type="text" class="form-control" id="Title" name='Title' value='<?= isset($_GET['sn']) ? $row['Title'] : "" ?>' required>
						<div class="invalid-feedback">
							請填入標題
						</div>
					</div>


					<div class="mb-3">
						<label for="Content" class="form-label">內容</label>
						<textarea name="Content" id="Content" cols="30" rows="10" class="d-none"></textarea>
						<div id="editor" style="height: 300px;">
							<?= isset($_GET['sn']) ? $row['Content'] : "" ?>
						</div>
					</div>

					<?php if (isset($_GET['sn'])) : ?>
						<div class="mb-3">
							<label class="form-label">建立時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenCreated']) ?>" disabled>
						</div>
						<div class="mb-3">
							<label class="form-label">最後更新時間</label>
							<input type="text" class="form-control" value="<?= localize($row['WhenLastEdited']) ?>" disabled>
						</div>
					<?php endif ?>
					<button type="submit" class="btn btn-primary"><?= isset($_GET['sn']) ? '修改' : '提交表單' ?></button>
				</form>
			</div>
		</div>
	</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.js"></script>
<script>
	const toolbarOptions = [
		['bold', 'italic', 'underline', 'strike'], // toggled buttons
		['blockquote', 'code-block'],
		['link', 'image', 'video', 'formula'],

		[{
			'header': 1
		}, {
			'header': 2
		}], // custom button values
		[{
			'list': 'ordered'
		}, {
			'list': 'bullet'
		}, {
			'list': 'check'
		}],
		[{
			'script': 'sub'
		}, {
			'script': 'super'
		}], // superscript/subscript
		[{
			'indent': '-1'
		}, {
			'indent': '+1'
		}], // outdent/indent
		[{
			'direction': 'rtl'
		}], // text direction

		[{
			'size': ['small', false, 'large', 'huge']
		}], // custom dropdown
		[{
			'header': [1, 2, 3, 4, 5, 6, false]
		}],

		[{
			'color': []
		}, {
			'background': []
		}], // dropdown with defaults from theme
		[{
			'font': []
		}],
		[{
			'align': []
		}],

		['clean'] // remove formatting button
	];

	const quill = new Quill('#editor', {
		modules: {
			toolbar: toolbarOptions
		},
		theme: 'snow'
	});

	const editor = document.querySelector('#editor');

	document.getElementById('form1').addEventListener('submit', function() {
		const editorContent = quill.root.innerHTML; // 使用 quill 而不是 editor.quill
		const contentTextarea = document.getElementById('Content');
		contentTextarea.value = editorContent; // Set Quill content to Content textarea value
	});
</script>
