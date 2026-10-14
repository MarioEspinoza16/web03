<?php require appRoot . '/views/lab/header.php'; ?>
<div class="flex flex-wrap gap-3 mb-6">
    <a class="px-4 py-2 bg-indigo-600 text-white rounded-lg" href="<?php echo urlRoot; ?>/products/create">Agregar producto</a>
    <a class="px-4 py-2 bg-slate-200 rounded-lg" href="<?php echo urlRoot; ?>/products">Todos los productos</a>
    <a class="px-4 py-2 bg-slate-200 rounded-lg" href="<?php echo urlRoot; ?>/categories">Categorías</a>
</div>
<?php if ($data['message']): ?><p role="status" class="p-4 mb-4 bg-indigo-50 rounded-lg"><?php echo labEscape($data['message']); ?></p><?php endif; ?>
<div class="overflow-x-auto rounded-lg border border-slate-200">
<table class="min-w-full text-sm divide-y divide-slate-200">
<thead class="bg-slate-50 text-slate-600 uppercase"><tr>
<?php foreach (['Código','Nombre','Existencias','Precio','Categoría','Proveedor','Acciones'] as $label): ?><th class="p-4 text-left"><?php echo $label; ?></th><?php endforeach; ?>
</tr></thead>
<tbody class="divide-y divide-slate-200">
<?php foreach ($data['products'] as $product): ?>
<tr class="hover:bg-slate-50">
    <td class="p-4">#<?php echo (int) $product->ProductID; ?></td>
    <td class="p-4 font-medium"><?php echo labEscape($product->ProductName); ?><?php if ($product->Discontinued): ?><span class="block text-xs text-rose-600">Descontinuado</span><?php endif; ?></td>
    <td class="p-4"><?php echo (int) $product->UnitsInStock; ?></td>
    <td class="p-4"><?php echo labEscape($product->UnitPrice); ?></td>
    <td class="p-4"><?php echo labEscape($product->CategoryName); ?></td>
    <td class="p-4"><?php echo labEscape($product->CompanyName); ?></td>
    <td class="p-4 whitespace-nowrap">
        <a class="inline-block px-3 py-1 bg-amber-500 text-white rounded" href="<?php echo urlRoot; ?>/products/edit/<?php echo (int) $product->ProductID; ?>">Editar</a>
        <form class="inline-block" method="POST" action="<?php echo urlRoot; ?>/products/delete/<?php echo (int) $product->ProductID; ?>" onsubmit="return confirm('¿Eliminar este producto?');">
            <input type="hidden" name="csrf" value="<?php echo labToken(); ?>">
            <button class="px-3 py-1 bg-rose-600 text-white rounded" type="submit">Borrar</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (!$data['products']): ?><tr><td colspan="7" class="p-6 text-center text-slate-500">No hay productos para mostrar.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php require appRoot . '/views/lab/footer.php'; ?>
