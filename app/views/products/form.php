<?php require appRoot . '/views/lab/header.php'; ?>
<?php if ($data['errors']): ?><ul role="alert" class="p-4 mb-6 bg-rose-50 text-rose-700 rounded-lg"><?php foreach (array_unique($data['errors']) as $error): ?><li><?php echo labEscape($error); ?></li><?php endforeach; ?></ul><?php endif; ?>
<form method="POST" action="<?php echo urlRoot . ($data['ProductID'] === null ? '/products/create' : '/products/edit/' . (int) $data['ProductID']); ?>" class="max-w-3xl mx-auto space-y-5">
<input type="hidden" name="csrf" value="<?php echo labToken(); ?>">
<?php foreach (['ProductName'=>['Nombre',40],'QuantityPerUnit'=>['Presentación (ejemplo: caja de 12 unidades)',20]] as $field=>$info): ?>
<div><label class="block font-semibold mb-1" for="<?php echo $field; ?>"><?php echo $info[0]; ?></label>
<input class="w-full p-3 border border-slate-300 rounded-lg" id="<?php echo $field; ?>" name="<?php echo $field; ?>" maxlength="<?php echo $info[1]; ?>" value="<?php echo labEscape($data[$field]); ?>" required></div>
<?php endforeach; ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<?php foreach (['CategoryID'=>['Categoría','categories','CategoryName'],'SupplierID'=>['Proveedor','suppliers','CompanyName']] as $field=>$info): ?>
<div><label class="block font-semibold mb-1" for="<?php echo $field; ?>"><?php echo $info[0]; ?></label>
<select class="w-full p-3 border border-slate-300 rounded-lg" id="<?php echo $field; ?>" name="<?php echo $field; ?>" required>
<option value="">Seleccione una opción</option>
<?php foreach ($data[$info[1]] as $row): ?><option value="<?php echo (int) $row->$field; ?>" <?php echo (string) $data[$field] === (string) $row->$field ? 'selected' : ''; ?>><?php echo labEscape($row->{$info[2]}); ?></option><?php endforeach; ?>
</select></div>
<?php endforeach; ?>
<?php foreach (['UnitPrice'=>'Precio unitario','UnitsInStock'=>'Existencias','UnitsOnOrder'=>'Unidades en pedido','ReorderLevel'=>'Nivel de reposición'] as $field=>$label): ?>
<div><label class="block font-semibold mb-1" for="<?php echo $field; ?>"><?php echo $label; ?></label>
<input class="w-full p-3 border border-slate-300 rounded-lg" type="number" min="0" <?php echo $field === 'UnitPrice' ? 'step="0.0001" max="999999999999999"' : 'step="1" max="32767"'; ?> id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo labEscape($data[$field]); ?>" required></div>
<?php endforeach; ?>
</div>
<label class="flex gap-2 items-center"><input type="checkbox" name="Discontinued" value="1" <?php echo $data['Discontinued'] ? 'checked' : ''; ?>> Producto descontinuado</label>
<div class="flex justify-end gap-3"><a href="<?php echo urlRoot; ?>/products" class="px-4 py-2 bg-slate-200 rounded-lg">Cancelar</a><button class="px-4 py-2 bg-indigo-600 text-white rounded-lg" type="submit">Guardar</button></div>
</form>
<?php require appRoot . '/views/lab/footer.php'; ?>
