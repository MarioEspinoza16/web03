<nav class="bg-white border-b border-slate-200 shadow-sm rounded-xl mb-6">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16 items-center">
					<div class="flex items-center space-x-3">
						<div class="bg-indigo-600 text-white p-2 rounded-lg font-bold text-lg leading-none">
							<i class="fa-solid fa-code"></i>
						</div>
						<span class="font-bold text-slate-800 text-lg tracking-tight"><?php echo siteName; ?></span>
					</div>
					<div class="flex items-center space-x-2">
						<a href="<?php echo urlRoot; ?>/pages/index" class="px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors">Inicio</a>
                        <a href="<?php echo urlRoot; ?>/categories/index" class="px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors">Categorías</a>
                        <a href="<?php echo urlRoot; ?>/products" class="px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors">Productos</a>
                        <a href="<?php echo urlRoot; ?>/sales" class="px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors">Ventas</a>
						<a href="<?php echo urlRoot; ?>/pages/about" class="px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition-colors">Acerca de..</a>
						<?php if(isLoggedIn()): ?>
		                	<a href="<?php echo urlRoot; ?>/users/logout" class="ml-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 transition-colors shadow-sm">Salir</a>
                        <?php else: ?>
		                    <a href="<?php echo urlRoot; ?>/users/login" class="ml-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Ingresar</a>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</nav>
