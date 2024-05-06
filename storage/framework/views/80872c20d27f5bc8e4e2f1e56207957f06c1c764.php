<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <!-- Fonts -->



<!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldContent('extracss'); ?>
    <?php echo $__env->yieldContent('extrajs'); ?>

</head>
<body>
<div id="app">
    


    <main-navbar prop-user='<?php echo e(auth()->user()); ?>'></main-navbar>

    <div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>


</div>
</body>
</html>

<?php /**PATH C:\Users\eshen\Desktop\Github\bh_reservation_locator\resources\views/layouts/main-navbar.blade.php ENDPATH**/ ?>