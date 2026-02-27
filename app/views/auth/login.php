<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="w-full max-w-md mx-auto mt-16 bg-cardDark border border-[#30363d] rounded-2xl p-8 shadow-2xl relative overflow-hidden">
    <!-- Decorative Glow -->
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-brandGreen/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-brandPurple/20 rounded-full blur-3xl"></div>

    <div class="relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">أهلاً بعودتك! ⛩️</h1>
            <p class="text-textSecondary">قم بتسجيل الدخول بأوراق الاعتماد للوصول لساحة التدريب</p>
        </div>

        <?php Session::flash('register_success'); ?>

        <form action="<?= URLROOT; ?>/auth/login" method="POST">
            <div class="mb-5">
                <label class="block text-textSecondary font-bold mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input type="email" name="email" class="w-full bg-[#0d1117] border <?= (!empty($data['email_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl pr-10 pl-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['email']; ?>" placeholder="ninja@example.com">
                </div>
                <span class="text-brandRed text-sm mt-1 block"><?= $data['email_err']; ?></span>
            </div>

            <div class="mb-6">
                <label class="block text-textSecondary font-bold mb-2">كلمة المرور</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" name="password" class="w-full bg-[#0d1117] border <?= (!empty($data['password_err'])) ? 'border-brandRed' : 'border-[#30363d]' ?> text-white rounded-xl pr-10 pl-4 py-3 outline-none focus:border-brandPurple transition-colors" value="<?= $data['password']; ?>" placeholder="••••••••">
                </div>
                <span class="text-brandRed text-sm mt-1 block"><?= $data['password_err']; ?></span>
            </div>

            <button type="submit" class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brandGreen/20 mb-4 flex justify-center items-center gap-2">
                دخول الساحة
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <p class="text-center text-textSecondary">مقاتل جديد؟ <a href="<?= URLROOT; ?>/auth/register" class="text-brandGreen hover:text-white transition-colors">سجل هنا مجاناً</a></p>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>