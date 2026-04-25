<!DOCTYPE html>
<html>

<head>
    <title>Futsal Booking</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional style -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background: #343a40;
            color: white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .sidebar a:hover {
            background: #495057;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <h4>⚽ Futsal</h4>
                <hr>

                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a href="<?php echo e(route('fields.index')); ?>">Lapangan</a>
                <a href="<?php echo e(route('bookings.index')); ?>">Booking</a>
                <a href="<?php echo e(route('members.index')); ?>">Member</a>

                <hr>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-danger w-100">Logout</button>
                </form>
            </div>

            <!-- Content -->
            <div class="col-md-10 p-4">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\futsal-booking\resources\views/layouts/app.blade.php ENDPATH**/ ?>