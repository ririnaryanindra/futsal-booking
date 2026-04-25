<h2>Tambah Lapangan</h2>

<form method="POST" action="<?php echo e(route('fields.store')); ?>">
    <?php echo csrf_field(); ?>

    Nama Lapangan:
    <input type="text" name="name" required><br><br>

    Harga per Jam:
    <input type="number" name="price_per_hour" required><br><br>

    <button type="submit">Simpan</button>
</form>

<a href="<?php echo e(route('fields.index')); ?>">Kembali</a>
<?php /**PATH E:\xampp\htdocs\futsal-booking\resources\views/fields/create.blade.php ENDPATH**/ ?>