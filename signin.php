<?php
include_once('config/configs.php');

session_start();

if (isset($_POST['user']) && isset($_POST['password'])) {
	if ($_POST['user'] == 'admin' && $_POST['password'] == 'admin') {
		$_SESSION['REST_id_user']   = '1';
		$_SESSION['REST_type_user'] = 'ADMINISTRADOR';
		$_SESSION['REST_name_user'] = $_POST['user'];
		header("Location: " . LOCALHOST . "/ui/select_contract.php");
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= WEB_TITLE ?></title>
	<link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
	<link rel="stylesheet" href="assets/css/tailwind.css">
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-100">
	<div class="bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-md w-full border border-gray-700">
		<div class="text-center mb-8">
			<div class="mx-auto h-18 w-18 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
				<img src="assets/img/logo.png" class="h-18 w-18 object-contain" alt="logo">
			</div>
			<h2 class="text-3xl font-bold text-white uppercase">Contratos Automatizados</h2>
			<p class="text-gray-400 mt-2">Por favor inicia sesión para continuar.</p>
		</div>

		<form action="signin.php" method="post" class="space-y-6">
			<div class="space-y-2">
				<label class="block text-sm font-medium text-gray-300">Usuario</label>
				<input name="user" autocomplete="current-user" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ingresa tu usuario" type="text" required>
			</div>
			<div class="space-y-2">
				<label class="block text-sm font-medium text-gray-300">Contraseña</label>
				<input name="password" autocomplete="current-password" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ingresa tu contraseña" type="password" required>
			</div>
			<button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
				Iniciar sesión
			</button>
		</form>

		<div class="mt-8 text-center text-sm text-gray-400 space-y-2">
			<p><a href="#" class="text-blue-400 hover:underline">¿Has olvidado tu contraseña?</a></p>
			<p>Contactar al administrador: <a target="_blank" href="https://api.whatsapp.com/send?phone=59176424515" class="text-blue-400 hover:underline font-medium">+591 76424515</a></p>
		</div>
	</div>
</body>

</html>
