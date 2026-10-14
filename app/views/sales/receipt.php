<?php require appRoot . '/views/lab/header.php'; $order=$data['order']; ?>
<p role="status" class="p-4 mb-6 bg-emerald-50 text-emerald-700 rounded-lg">Pedido registrado correctamente. Las existencias fueron actualizadas.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
<p><strong>Modalidad:</strong> <?php echo $data['mode']==='assisted'?'Venta asistida':'Compra autogestionada'; ?></p>
<p><strong>Cliente:</strong> <?php echo labEscape($order->CustomerName); ?></p>
<p><strong><?php echo $data['mode']==='assisted'?'Empleado':'Responsable administrativo'; ?>:</strong> <?php echo labEscape(trim(($order->FirstName ?? '') . ' ' . ($order->LastName ?? '')) ?: 'Sin asignar'); ?></p>
<p><strong>Transportista:</strong> <?php echo labEscape($order->ShipperName); ?></p>
<p><strong>Fecha del pedido:</strong> <?php echo labEscape($order->OrderDate); ?></p>
<p><strong>Fecha de envío:</strong> <?php echo labEscape($order->ShippedDate); ?></p>
<p><strong>Fecha requerida:</strong> <?php echo labEscape($order->RequiredDate); ?></p>
<p class="md:col-span-2"><strong>Dirección de envío:</strong> <?php echo labEscape(implode(', ',array_filter([$order->ShipAddress,$order->ShipCity,$order->ShipRegion,$order->ShipPostalCode,$order->ShipCountry]))); ?></p>
</div>
<div class="overflow-x-auto border rounded-lg"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><?php foreach (['Producto','Cantidad','Precio','Descuento','Subtotal'] as $label): ?><th class="p-3 text-left"><?php echo $label; ?></th><?php endforeach; ?></tr></thead><tbody>
<?php foreach ($data['details'] as $detail): ?><tr class="border-t"><td class="p-3"><?php echo labEscape($detail->ProductName); ?></td><td class="p-3"><?php echo (int)$detail->Quantity; ?></td><td class="p-3"><?php echo labEscape($detail->UnitPrice); ?></td><td class="p-3"><?php echo labEscape(number_format((float)$detail->Discount*100,2)); ?>%</td><td class="p-3"><?php echo labEscape($detail->Subtotal); ?></td></tr><?php endforeach; ?>
</tbody></table></div>
<p class="text-xl font-bold text-right my-6">Total de productos: <?php echo labEscape($data['total']); ?></p>
<a class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg" href="<?php echo urlRoot; ?>/sales">Volver a ventas</a>
<?php require appRoot . '/views/lab/footer.php'; ?>
