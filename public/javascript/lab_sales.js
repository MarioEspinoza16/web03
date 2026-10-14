(() => {
    const form = document.getElementById('saleForm');
    const rows = [...document.querySelectorAll('.product-row')];
    const discount = document.getElementById('Discount');
    function recalculate() {
        const percent = Math.min(100, Math.max(0, Number(discount?.value || 0)));
        let total = 0;
        for (const row of rows) {
            const quantity = Math.max(0, Number(row.querySelector('.quantity').value || 0));
            const subtotal = Number(row.dataset.price) * quantity * (1 - percent / 100);
            row.querySelector('.subtotal').textContent = subtotal.toFixed(4);
            total += subtotal;
        }
        document.getElementById('saleTotal').textContent = total.toFixed(4);
    }
    form.addEventListener('input', recalculate);
    document.getElementById('categoryFilter').addEventListener('change', event => {
        for (const row of rows) row.hidden = !!event.target.value && row.dataset.category !== event.target.value;
    });
    form.addEventListener('submit', event => {
        if (!rows.some(row => Number(row.querySelector('.quantity').value) > 0)) {
            event.preventDefault(); alert('Seleccione al menos un producto.'); return;
        }
        if (!confirm('¿Confirmar la compra con estas cantidades?')) event.preventDefault();
    });
    recalculate();
})();
