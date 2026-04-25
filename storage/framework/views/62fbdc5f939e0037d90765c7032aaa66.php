<?php $__env->startSection('content'); ?>
    <h2 class="mb-4">Dashboard</h2>

    <div class="row">

        <!-- Total Lapangan -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>Total Lapangan</h5>
                    <h2><?php echo e($totalFields); ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Booking -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>Total Booking</h5>
                    <h2><?php echo e($totalBookings); ?></h2>
                </div>
            </div>
        </div>

        <!-- Booking Hari Ini -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>Booking Hari Ini</h5>
                    <h2><?php echo e($todayBookings); ?></h2>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\futsal-booking\resources\views/dashboard.blade.php ENDPATH**/ ?>