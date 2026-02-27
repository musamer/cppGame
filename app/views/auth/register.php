<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="w-full max-w-md mx-auto mt-12 bg-cardDark border border-[#30363d] rounded-2xl p-8 shadow-2xl relative overflow-hidden">
    <!-- Decorative Glow -->
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-brandPurple/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-brandGreen/20 rounded-full blur-3xl"></div>

    <div class="relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">استعد للقتال! 🥷</h1>
            <p class="text-textSecondary">قم بتسجيل حساب جديد لتبدأ رحلتك في C++ Ninjas</p>
        </div>

        <form action="<?= URLROOT; ?>/auth/register" method="POST">
            <div class="mb-4">
                <label class="block text-textSecondary font-bold mb-2">اسم النينجا (Username)</label>
                <input type="text" name="username" class="w-full bg-[#0d1117] border <?= (!empty($data['username_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl px-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['username']; ?>">
                <span class="text-brandRed text-sm mt-1 block"><?= $data['username_err']; ?></span>
            </div>

            <div class="mb-4">
                <label class="block text-textSecondary font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full bg-[#0d1117] border <?= (!empty($data['email_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl px-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['email']; ?>">
                <span class="text-brandRed text-sm mt-1 block"><?= $data['email_err']; ?></span>
            </div>

            <div class="mb-4">
                <label class="block text-textSecondary font-bold mb-2">كلمة المرور السرية</label>
                <input type="password" name="password" class="w-full bg-[#0d1117] border <?= (!empty($data['password_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl px-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['password']; ?>">
                <span class="text-brandRed text-sm mt-1 block"><?= $data['password_err']; ?></span>
            </div>

            <div class="mb-6">
                <label class="block text-textSecondary font-bold mb-2">تأكيد كلمة المرور</label>
                <input type="password" name="confirm_password" class="w-full bg-[#0d1117] border <?= (!empty($data['confirm_password_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl px-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['confirm_password']; ?>">
                <span class="text-brandRed text-sm mt-1 block"><?= $data['confirm_password_err']; ?></span>
            </div>

            <button type="submit" class="w-full bg-brandPurple hover:bg-brandPurpleHover text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brandPurple/20 mb-4">إنشاء الحساب وبدء التدريب</button>

            <p class="text-center text-textSecondary">لديك حساب بالفعل؟ <a href="<?= URLROOT; ?>/auth/login" class="text-brandPurple hover:text-white transition-colors">تسجيل الدخول هنا</a></p>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>