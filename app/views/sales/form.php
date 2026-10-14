<?php require appRoot . '/views/lab/header.php'; ?>
<?php if ($data['errors']): ?><ul role="alert" class="p-4 mb-6 bg-rose-50 text-rose-700 rounded-lg"><?php foreach (array_unique($data['errors']) as $error): ?><li><?php echo labEscape($error); ?></li><?php endforeach; ?></ul><?php endif; ?>
<form method="POST" action="<?php echo urlRoot; ?>/sales/<?php echo $data['mode']; ?>" id="saleForm" class="space-y-6">
<input type="hidden" name="csrf" value="<?php echo labToken(); ?>"><input type="hidden" name="sale_nonce" value="<?php echo $data['nonce']; ?>">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div><label class="block font-semibold mb-1" for="CustomerID"><?php echo $data['mode']==='assisted' ? 'Cliente' : 'Seleccione su registro de cliente'; ?></label>
<select id="CustomerID" name="CustomerID" class="w-full p-3 border rounded-lg" required><option value="">Seleccione un cliente</option>
<?php foreach ($data['customers'] as $row): ?><option value="<?php echo labEscape($row->CustomerID); ?>" <?php echo (string)$row->CustomerID===$data['CustomerID']?'selected':''; ?>><?php echo labEscape($row->CompanyName . ' — ' . $row->ContactName); ?></option><?php endforeach; ?></select></div>
<?php if ($data['mode']==='assisted'): ?>
<div><label class="block font-semibold mb-1" for="EmployeeID">Empleado que atiende</label><select id="EmployeeID" name="EmployeeID" class="w-full p-3 border rounded-lg" required><option value="">Seleccione un empleado</option>
<?php foreach ($data['employees'] as $row): ?><option value="<?php echo (int)$row->EmployeeID; ?>" <?php echo (string)$row->EmployeeID===$data['EmployeeID']?'selected':''; ?>><?php echo labEscape($row->FirstName . ' ' . $row->LastName); ?></option><?php endforeach; ?></select></div>
<?php endif; ?>
<div><label class="block font-semibold mb-1" for="ShipVia">Transportista</label><select id="ShipVia" name="ShipVia" class="w-full p-3 border rounded-lg" required><option value="">Seleccione un transportista</option>
<?php foreach ($data['shippers'] as $row): ?><option value="<?php echo (int)$row->ShipperID; ?>" <?php echo (string)$row->ShipperID===$data['ShipVia']?'selected':''; ?>><?php echo labEscape($row->CompanyName); ?></option><?php endforeach; ?></select></div>
<div><label class="block font-semibold mb-1" for="RequiredDate">Fecha requerida</label><input class="w-full p-3 border rounded-lg" type="date" id="RequiredDate" name="RequiredDate" min="<?php echo date('Y-m-d'); ?>" value="<?php echo labEscape($data['RequiredDate']); ?>" required></div>
<div><label class="block font-semibold mb-1" for="ShippedDate">Fecha de envío</label><input class="w-full p-3 border rounded-lg" type="date" id="ShippedDate" name="ShippedDate" min="<?php echo date('Y-m-d'); ?>" value="<?php echo labEscape($data['ShippedDate']); ?>" required></div>
<?php if ($data['mode']==='assisted'): ?><div><label class="block font-semibold mb-1" for="Discount">Descuento (%)</label><input class="w-full p-3 border rounded-lg" id="Discount" name="Discount" type="number" min="0" max="100" step="1" value="<?php echo labEscape($data['Discount']); ?>" required></div><?php endif; ?>
</div>
<p class="text-sm text-slate-500">El envío utilizará la dirección registrada del cliente. El flete se registra en cero; no se procesa un pago bancario.</p>
<div><label class="block font-semibold mb-1" for="categoryFilter">Filtrar productos por categoría</label><select id="categoryFilter" class="p-3 border rounded-lg"><option value="">Todas las categorías</option><?php foreach ($data['categories'] as $row): ?><option value="<?php echo (int)$row->CategoryID; ?>"><?php echo labEscape($row->CategoryName); ?></option><?php endforeach; ?></select><p class="text-sm text-slate-500 mt-2">Los productos seleccionados se conservan al cambiar de categoría.</p></div>
<div class="overflow-x-auto border rounded-lg"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><?php foreach (['Producto','Categoría','Precio','Existencias','Cantidad','Subtotal'] as $label): ?><th class="p-3 text-left"><?php echo $label; ?></th><?php endforeach; ?></tr></thead><tbody>
<?php foreach ($data['products'] as $product): ?>
<tr class="border-t product-row" data-category="<?php echo (int)$product->CategoryID; ?>" data-price="<?php echo labEscape($product->UnitPrice); ?>">
<td class="p-3 font-medium"><?php echo labEscape($product->ProductName); ?></td><td class="p-3"><?php echo labEscape($product->CategoryName); ?></td><td class="p-3"><?php echo labEscape($product->UnitPrice); ?></td><td class="p-3"><?php echo (int)$product->UnitsInStock; ?></td>
<td class="p-3"><input aria-label="Cantidad de <?php echo labEscape($product->ProductName); ?>" class="quantity w-24 p-2 border rounded" type="number" name="quantity[<?php echo (int)$product->ProductID; ?>]" min="0" max="<?php echo (int)$product->UnitsInStock; ?>" step="1" value="<?php echo labEscape($data['quantities'][$product->ProductID] ?? '0'); ?>"></td><td class="p-3 subtotal">0.0000</td>
</tr>
<?php endforeach; ?>
<?php if (!$data['products']): ?><tr><td colspan="6" class="p-6 text-center">No hay productos disponibles.</td></tr><?php endif; ?>
</tbody></table></div>
<div class="p-5 bg-indigo-50 rounded-lg"><p class="font-semibold">Total estimado: <span id="saleTotal">0.0000</span></p><p class="text-sm mt-1">Revise las cantidades antes de confirmar. El comprobante mostrará el total definitivo.</p></div>
<div class="flex justify-end gap-3"><a class="px-4 py-2 bg-slate-200 rounded-lg" href="<?php echo urlRoot; ?>/sales">Cancelar</a><button class="px-4 py-2 bg-indigo-600 text-white rounded-lg" type="submit">Confirmar compra</button></div>
</form>
<script src="<?php echo urlRoot; ?>/public/javascript/lab_sales.js"></script>
<?php require appRoot . '/views/lab/footer.php'; ?>
