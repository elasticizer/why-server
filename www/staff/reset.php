<?php

require '../arranger.php';

if (!isset($_GET['sn'])) {
	echo <<<'END'
		<script>
			(function () {
				alert('密碼未重設。');
				location.replace(document.referrer);
			})();
		</script>
		END;

	exit;
}

connect()->prepare(
	'UPDATE Staff SET PasswordHash = NULL WHERE SN = ?'
)->execute([
	is_numeric($_GET['sn']) ? intval($_GET['sn']) : null
]);

?>

<script>
	(function () {
		alert('密碼已重設。');
		location.replace(document.referrer);
	})();
</script>
