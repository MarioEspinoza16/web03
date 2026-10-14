<footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-auto">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
				<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

					<!-- Columna 1: Info de la Institución/Proyecto -->
					<div class="md:col-span-2 space-y-4">
						<div class="flex items-center space-x-3">
							<div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold text-lg">
								P
							</div>
							<span class="text-lg font-bold text-white">Desarrollo Web Avanzado</span>
						</div>
						<p class="text-sm text-slate-400 max-w-sm leading-relaxed">
							Proyecto de demostración para el aprendizaje de PHP 8.5, Composer, arquitectura modular y Tailwind CSS.
						</p>
					</div>

					<!-- Columna 2: Enlaces Rápidos -->
					<div>
						<h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Recursos</h4>
						<ul class="space-y-2 text-sm">
							<li><a href="https://www.php.net" target="_blank" class="hover:text-white transition-colors">PHP.net Manual</a></li>
							<li><a href="https://tailwindcss.com" target="_blank" class="hover:text-white transition-colors">Tailwind CSS Docs</a></li>
							<li><a href="https://getcomposer.org" target="_blank" class="hover:text-white transition-colors">Composer</a></li>
						</ul>
					</div>

					<!-- Columna 3: Estado del Sistema -->
					<div>
						<h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Servidor</h4>
						<div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-medium">
							<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
							<span>Localhost Activo</span>
						</div>
					</div>

				</div>

				<div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 space-y-4 sm:space-y-0">
					<p>&copy; <?= date('Y') ?> Universidad Técnica Nacional. Todos los derechos reservados.</p>
					<p>Construido con PHP 8.5 & Tailwind CSS</p>
				</div>
			</div>
		</footer>
