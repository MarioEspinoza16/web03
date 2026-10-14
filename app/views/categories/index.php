<!DOCTYPE html>
		<html lang="es">
		<head>
			<?php require_once(appRoot . '/views/includes/enca.php'); ?>
			<title><?php echo siteName; ?></title>
		</head>
		<body class="bg-slate-50 min-h-screen text-slate-800 flex flex-col justify-between">
		<div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
			<header>
				<?php require_once(appRoot . '/views/includes/menu.php'); ?>
			</header>

			<main class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 min-h-[300px]">

				<div class="max-w-6xl mx-auto my-8 p-6 bg-white rounded-xl shadow-lg border border-slate-100">
					<div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
						<div>
							<h1 class="text-2xl font-bold text-slate-800">Categorías de Productos</h1>
							<p class="text-sm text-slate-500">Mantenimiento general de la base de datos NorthWind</p>
						</div>
						<a href="<?php echo urlRoot; ?>/categories/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md flex items-center gap-2">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
							Agregar Categoría
						</a>
					</div>
				
					<div class="overflow-x-auto rounded-lg border border-slate-200">
						<table class="min-w-full divide-y divide-slate-200 text-sm">
							<thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
							<tr>
								<th class="px-6 py-3 text-left">Imagen</th>
								<th class="px-6 py-3 text-left">Código</th>
								<th class="px-6 py-3 text-left">Nombre</th>
								<th class="px-6 py-3 text-left">Descripción</th>
								<th class="px-6 py-3 text-center">Acciones</th>
							</tr>
							</thead>
							<tbody class="divide-y divide-slate-200 bg-white">
							<?php foreach ($data['categories'] as $cat) : ?>
								<tr class="hover:bg-slate-50 transition-colors">
									<td class="px-6 py-4 whitespace-nowrap">
										<img src="<?php echo urlRoot; ?>/categories/image/<?php echo $cat->CategoryID; ?>" class="w-12 h-12 object-cover rounded-md shadow-sm border border-slate-200" alt="Category Image">
									</td>
									<td class="px-6 py-4 font-mono text-indigo-600 font-semibold">
										<a href="<?php echo urlRoot; ?>/products/index/<?php echo $cat->CategoryID; ?>" class="hover:underline">#<?php echo $cat->CategoryID; ?></a>
									</td>
									<td class="px-6 py-4 font-medium text-slate-800">
										<?php echo htmlspecialchars($cat->CategoryName); ?>
									</td>
									<td class="px-6 py-4 text-slate-600 max-w-xs truncate">
										<?php echo htmlspecialchars($cat->Description); ?>
									</td>
									<td class="px-6 py-4 text-center space-x-2">
										<a href="<?php echo urlRoot; ?>/categories/edit/<?php echo $cat->CategoryID; ?>" class="inline-block px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded text-xs font-medium transition">Editar</a>
										<form action="<?php echo urlRoot; ?>/categories/delete/<?php echo $cat->CategoryID; ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
											<button type="submit" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-medium transition">Borrar</button>
										</form>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?php require_once(appRoot . '/views/includes/pie.php'); ?>
		</div>
		</body>
		</html>
