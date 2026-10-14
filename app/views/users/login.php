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

			<main class="flex justify-center my-8">
				<div class="w-full max-w-md">
					<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
						<div class="mb-6">
							<h4 class="text-xl font-bold text-slate-800">Autenticación</h4>
							<p class="text-sm text-slate-500">Ingrese sus credenciales para acceder</p>
						</div>

						<form action="<?php echo urlRoot; ?>/users/login" method="post" class="space-y-4">
							<div>
								<label class="block text-sm font-medium text-slate-700 mb-1">Usuario</label>
								<input type="text" name="txtUsua" value="<?php echo htmlspecialchars($data['usuario'] ?? ''); ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm outline-none transition-all" required>
								<?php if(!empty($data['userError'])): ?>
									<span class="text-xs text-rose-500 mt-1 block font-medium"><?php echo $data['userError']; ?></span>
								<?php endif; ?>
							</div>

							<div>
								<label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
								<input type="password" name="txtContra" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm outline-none transition-all" required>
								<?php if(!empty($data['passError'])): ?>
									<span class="text-xs text-rose-500 mt-1 block font-medium"><?php echo $data['passError']; ?></span>
								<?php endif; ?>
							</div>

							<div class="pt-2">
								<button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm text-sm transition-colors">
									Aceptar
								</button>
							</div>
						</form>

						<div class="mt-6 text-center text-sm border-t border-slate-100 pt-4">
							<span class="text-slate-500">¿No tiene cuenta?</span>
							<a href="<?php echo urlRoot; ?>/users/register" class="text-indigo-600 hover:underline font-medium ml-1">Registrar usuario</a>
						</div>
					</div>
				</div>
			</main>

			<?php require_once(appRoot . '/views/includes/pie.php'); ?>
		</div>
		</body>
		</html>
