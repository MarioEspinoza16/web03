<?php require appRoot . '/views/lab/header.php'; ?>
<p class="mb-6 text-slate-600">Seleccione cómo desea registrar la compra.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="p-6 rounded-xl border border-slate-200 bg-slate-50"><h2 class="text-xl font-bold mb-3">Venta asistida</h2><p class="mb-5">Un empleado selecciona el cliente, los productos y las cantidades.</p><a class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg" href="<?php echo urlRoot; ?>/sales/assisted">Registrar venta</a></div>
<div class="p-6 rounded-xl border border-slate-200 bg-slate-50"><h2 class="text-xl font-bold mb-3">Compra autogestionada</h2><p class="mb-5">El cliente selecciona su registro de cliente y completa su propia compra.</p><a class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg" href="<?php echo urlRoot; ?>/sales/selfservice">Realizar compra</a></div>
</div>
<?php if ($data['receipts']): ?>
<h2 class="text-lg font-bold mt-8 mb-3">Pedidos registrados en esta sesión</h2>
<ul class="space-y-2"><?php foreach (array_reverse($data['receipts'],true) as $id=>$mode): ?><li><a class="text-indigo-600 hover:underline" href="<?php echo urlRoot; ?>/sales/receipt/<?php echo (int)$id; ?>">Pedido #<?php echo (int)$id; ?> — <?php echo $mode === 'assisted' ? 'Venta asistida' : 'Compra autogestionada'; ?></a></li><?php endforeach; ?></ul>
<?php endif; ?>
<?php require appRoot . '/views/lab/footer.php'; ?>
