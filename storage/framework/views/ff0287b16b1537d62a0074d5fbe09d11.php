<div class="w-full max-w-md mx-auto mt-10 p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <!-- Session Status -->
    <?php if(session('status')): ?>
        <div class="mb-4 text-sm text-green-600">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if($errors->any()): ?>
        <div class="mb-4 text-sm text-red-600">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold">Email:</label>
            <input type="email" id="email" name="email" class="w-full border rounded p-2 focus:ring focus:ring-blue-300" value="<?php echo e(old('email')); ?>" required autofocus>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-semibold">Password:</label>
            <input type="password" id="password" name="password" class="w-full border rounded p-2 focus:ring focus:ring-blue-300" required>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center">
            <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 focus:ring focus:ring-blue-300">
            <label for="remember" class="ml-2 text-gray-700 text-sm">Remember Me</label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:ring focus:ring-blue-300">
                Login
            </button>
        </div>
    </form>
</div>
<?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>