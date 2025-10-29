<?php $__env->startSection('title', 'Nový kupón - Admin Panel'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="<?php echo e(route('admin.coupons.index')); ?>" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Zpět na kupóny
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Nový slevový kupón</h1>
        <p class="text-gray-600 mt-1">Vytvořte nový kupón pro objednávky nebo předplatné</p>
    </div>

    <!-- Form -->
    <form action="<?php echo e(route('admin.coupons.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('admin.coupons._form', ['coupon' => new \App\Models\Coupon()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/coupons/create.blade.php ENDPATH**/ ?>