<div class="w-full max-w-5xl mx-auto p-6 md:p-10">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="<?= URLROOT ?>/admin/exercises" class="text-brandPurple hover:text-white transition-colors flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                العودة لقائمة التحديات
            </a>
            <h1 class="text-3xl font-bold text-white">تعديل التحدي ⚡</h1>
        </div>
        <?php if (isset($exercise->world_id) && isset($exercise->stage_id)): ?>
            <a href="<?= URLROOT ?>/student/exercise/<?= $exercise->world_id ?>/<?= $exercise->stage_id ?>" target="_blank" class="bg-[#30363d] hover:bg-[#484f58] text-white px-5 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-lg flex items-center gap-2 border border-gray-600 w-fit">
                <span>معاينة كطالب 👁️</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Edit Form -->
    <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 md:p-8 shadow-xl">
        <form action="<?= URLROOT ?>/admin/edit_exercise/<?= $exercise->id ?>" method="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-textSecondary text-sm font-bold mb-2">عنوان التحدي المثير</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($exercise->title) ?>" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors" required>
                </div>
                <div>
                    <label class="block text-brandYellow text-sm font-bold mb-2">مكافأة اجتياز التحدي (XP) ⭐</label>
                    <input type="number" name="xp_reward" value="<?= $exercise->xp_reward ?>" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandYellow transition-colors font-mono" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-brandPurple text-sm font-bold mb-2">السؤال التفصيلي وقصة المرحلة (يدعم Markdown/HTML)</label>
                <textarea name="content" rows="6" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors font-mono" required dir="ltr"><?= htmlspecialchars($exercise->content) ?></textarea>
                <p class="text-[11px] text-gray-500 mt-1">يُستحسن كتابة السؤال بأسلوب شيق كأنها تحدي بطل.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-textSecondary text-sm font-bold mb-2">نقطة البداية (Starter Code) 🖥️</label>
                    <textarea name="starter_code" rows="8" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-brandGreen focus:outline-none focus:border-brandGreen transition-colors font-mono text-sm" dir="ltr"><?= htmlspecialchars($exercise->starter_code) ?></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">الكود الأولي الذي سيراه الطالب متواجداً في المحرر مسبقاً.</p>
                </div>

                <div>
                    <label class="block text-brandYellow text-sm font-bold mb-2">حل المعلم الذكي (Solution Code) 💡</label>
                    <textarea name="solution_code" rows="8" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-brandYellow focus:outline-none focus:border-brandYellow transition-colors font-mono text-sm" dir="ltr"><?= htmlspecialchars($exercise->solution_code) ?></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">شكل الحل النموذجي ليستند إليه الذكاء الاصطناعي في الفهم والتقييم.</p>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-brandRed font-bold mb-2">حالات الاختبار (Test Cases JSON) 🧪</label>
                <textarea name="test_cases" rows="6" class="w-full bg-[#0d1117] border border-brandRed/30 rounded-lg px-4 py-3 text-gray-300 focus:outline-none focus:border-brandRed transition-colors font-mono text-xs" dir="ltr" required><?= htmlspecialchars($exercise->test_cases) ?></textarea>
                <p class="text-[11px] text-gray-500 mt-1">صيغة JSON صالحة لتزويد المعلم الذكي بمدخلات ومخرجات الفحص.</p>
            </div>

            <button type="submit" class="w-full bg-brandPurple hover:bg-brandPurpleHover text-white font-bold py-3.5 px-4 rounded-lg transition-colors shadow-lg shadow-brandPurple/20 flex justify-center items-center gap-2 text-lg">
                <span>تثبيت تغييرات التحدي</span>
                <span>✨</span>
            </button>
        </form>
    </div>
</div>