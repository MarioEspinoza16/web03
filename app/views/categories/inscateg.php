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
        <div class="max-w-2xl mx-auto my-10 p-8 bg-white rounded-xl shadow-lg border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-6 pb-2 border-b">
                Insertar Nueva Categoría
            </h2>

            <form
                action="<?php echo urlRoot; ?>/categories/create"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                <div>
                    <label for="txtNombre" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="txtNombre"
                        name="txtNombre"
                        value="<?php echo htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        required
                    >

                    <span class="text-xs text-rose-500">
                        <?php echo htmlspecialchars($data['name_err'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div>
                    <label for="txtDescrip" class="block text-sm font-semibold text-slate-700 mb-1">
                        Descripción
                    </label>

                    <textarea
                        id="txtDescrip"
                        name="txtDescrip"
                        rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    ><?php echo htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div>
                    <label for="txtArchi" class="block text-sm font-semibold text-slate-700 mb-1">
                        Imagen (Guardada en BD)
                    </label>

                    <input
                        type="file"
                        id="txtArchi"
                        name="txtArchi"
                        accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >

                    <p id="errorImagen" class="text-xs text-rose-500 mt-2" role="alert"></p>
                </div>

                <div id="contenedorVistaPrevia" hidden>
                    <p class="text-sm font-semibold text-slate-700 mb-2">
                        Vista previa de la imagen
                    </p>

                    <div class="flex justify-center p-4 bg-slate-50 border border-slate-200 rounded-lg">
                        <img
                            id="vistaPrevia"
                            alt="Vista previa de la imagen seleccionada"
                            class="max-w-full max-h-64 object-contain rounded-lg"
                        >
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a
                        href="<?php echo urlRoot; ?>/categories"
                        class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition"
                    >
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php require_once(appRoot . '/views/includes/pie.php'); ?>
</div>

<script>
    const archivoImagen = document.getElementById('txtArchi');
    const vistaPrevia = document.getElementById('vistaPrevia');
    const contenedor = document.getElementById('contenedorVistaPrevia');
    const errorImagen = document.getElementById('errorImagen');

    let urlImagen = null;

    function limpiarVistaPrevia() {
        contenedor.hidden = true;
        vistaPrevia.removeAttribute('src');

        if (urlImagen) {
            URL.revokeObjectURL(urlImagen);
            urlImagen = null;
        }
    }

    archivoImagen.addEventListener('change', function () {
        limpiarVistaPrevia();
        errorImagen.textContent = '';

        const archivo = this.files[0];

        if (!archivo) {
            return;
        }

        if (!archivo.type.startsWith('image/')) {
            errorImagen.textContent = 'Seleccioná un archivo de imagen.';
            this.value = '';
            return;
        }

        urlImagen = URL.createObjectURL(archivo);
        vistaPrevia.src = urlImagen;
    });

    vistaPrevia.addEventListener('load', function () {
        contenedor.hidden = false;
    });

    vistaPrevia.addEventListener('error', function () {
        limpiarVistaPrevia();
        archivoImagen.value = '';
        errorImagen.textContent = 'No se pudo mostrar la imagen. Probá con un archivo JPG o PNG.';
    });
</script>
</body>
</html>