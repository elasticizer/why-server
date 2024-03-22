<?php
$title = '登入';
$layout = './layout/layout.php';

require './arranger.php';

if (isset($_SESSION['sn'])) {
	header('Location: /index.php');

	exit;
}
?>

<style>
	form> :nth-child(2) {
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.25s linear;
	}

	form>.active:nth-child(2) {
		max-height: 10rem;
	}
</style>

<div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
	<div class="d-flex align-items-center justify-content-center w-100">
		<div class="row justify-content-center w-100">
			<div class="col-md-8 col-lg-6 col-xxl-3">
				<div class="card mb-0">
					<div class="card-body">
						<form
							action="login-api.php"
							method="POST"
						>
							<div class="mb-4">
								<label
									for="username"
									class="form-label"
								>使用者名稱或電子信箱地址</label>
								<input
									type="text"
									class="form-control"
									name="username"
									required
								/>
								<div class="invalid-feedback">帳戶不存在</div>
							</div>
							<div id="password-field">
								<label
									for="password"
									class="form-label"
								>密碼</label>
								<input
									type="password"
									class="form-control"
									name="password"
									disabled
									required
								/>
								<div class="invalid-feedback">密碼不正確</div>
							</div>
							<div>
								<button
									type="submit"
									class="btn btn-primary w-100 py-8 fs-4 rounded-2"
								>登入</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div
	class="modal fade"
	id="dialog"
	tabindex="-1"
>
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5"></h1>
				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="modal"
				></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button
					type="button"
					class="btn btn-primary"
					data-bs-dismiss="modal"
				>確定</button>
			</div>
		</div>
	</div>
</div>

<script>
	(function () {
		let loggedIn = false;
		const form = document.forms[0];
		const button = form.elements[2];
		const dialog = document.getElementById('dialog');

		button.addEventListener('click', validate);
		form.elements[0].addEventListener('input', recover);

		function recover() {
			if (this.pattern) {
				this.removeAttribute('pattern');
			}

			this.form.classList.remove('was-validated');
		}

		async function validate(e) {
			e.preventDefault();

			if (!form.elements[1].disabled && !form.checkValidity()) {
				return;
			}

			const init = {
				method: form.method,
				body: new URLSearchParams(new FormData(form))
			};
			const data = await fetch(form.action, init).then(
				r => r.status === 204
					? { code: '0000' }
					: r.json(),
				message => ({ summary: '', message })
			);

			if (data.code === '0000') {
				return location.replace('/');
			}

			if (data.code === '0001') {
				form.elements[0].pattern = '^$';

				return form.classList.add('was-validated');
			}

			if (data.code === '0002') {
				form.children[1].className = 'mb-4 active';
				form.elements[0].readOnly = true;
				form.elements[1].disabled = false;

				return form.classList.remove('was-validated');
			}

			dialog.getElementsByTagName('h1')[0].textContent = data.summary;
			dialog.getElementsByClassName('modal-body')[0].textContent = data.message;

			if (data.summary === '登入成功') {
				loggedIn = true;
			}

			new bootstrap.Modal(dialog).show();
		}
	})();
</script>
