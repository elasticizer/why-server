<?php

require './arranger.php';

if (
	!isset(
		$_POST['password'],
		$_SESSION['username'],
		$_POST['token'],
		$_SESSION['token']
	)
	|| $_POST['token'] !== $_SESSION['token']
	|| strlen($_POST['password']) < 16
) {
	echo <<< 'END'
		<script>
			(function () {
				alert('密碼未設定。');
				location.replace(document.referrer);
			})();
		</script>
		END;

	exit;
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

connect()->prepare(
	'UPDATE Staff SET PasswordHash = ? WHERE Username = ?'
)->execute([$hash, $_SESSION['username']]);

$statement = connect()->prepare(
	'SELECT SN FROM Staff WHERE Username = ?'
);

$statement->execute([$_SESSION['username']]);

$_SESSION['sn'] = $statement->fetchColumn();

?>

<script>
	(function () {
		alert('密碼已設定。');
		location.replace('/');
	})();
</script>
