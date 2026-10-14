<!DOCTYPE html>
		<html lang="es">
		<head>
			<?php require_once(appRoot . '/views/includes/enca.php'); ?>
			<title><?php echo siteName; ?></title>
		</head>
		<body class="bg-slate-50 min-h-screen text-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
			<header>
				<?php require_once(appRoot . '/views/includes/menu.php'); ?>
			</header>

			<main class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 min-h-[300px]">
				<h3 class="text-2xl font-bold text-slate-800 mb-2">Acerca de.....!</h3>
				<p class="text-slate-600">Demostración del patrón de arquitectura MVC en PHP sin dependencias externas.</p>
			</main>

			<?php require_once(appRoot . '/views/includes/pie.php'); ?>
		</div>
		</body>
		</html>	

