<div class="w-full max-w-3xl mx-auto p-6 md:p-10">
    <div class="mb-8">
        <a href="<?= URLROOT ?>/admin/stages" class="text-brandPurple hover:text-white transition-colors flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            العودة للمراحل
        </a>
        <h1 class="text-3xl font-bold text-white">تعديل المرحلة ⚙️</h1>
    </div>

    <!-- Edit Form -->
    <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-xl">
        <form action="<?= URLROOT ?>/admin/edit_stage/<?= $stage->id ?>" method="POST">

            <div class="mb-6">
                <label class="block text-textSecondary text-sm font-bold mb-2">العالم (المستوى التابع)</label>
                <select name="world_id" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors" required>
                    <?php foreach ($worlds as $w): ?>
                        <option value="<?= $w->id ?>" <?= $w->id == $stage->world_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($w->title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-textSecondary text-sm font-bold mb-2">عنوان المرحلة</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($stage->title) ?>" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors" required>
                </div>
                <div>
                    <label class="block text-textSecondary text-sm font-bold mb-2">رقم الترتيب الافتراضي</label>
                    <input type="number" name="order_index" value="<?= $stage->order_index ?>" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors font-mono" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-textSecondary text-sm font-bold mb-2">وصف المرحلة للطلاب</label>
                <textarea name="description" rows="4" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandPurple transition-colors resize-none" required><?= htmlspecialchars($stage->description) ?></textarea>
            </div>

            <div class="mb-8 p-4 bg-brandYellow/10 border border-brandYellow/30 rounded-lg">
                <label class="block text-brandYellow text-sm font-bold mb-2">مكافأة نقاط الخبرة 🌟 (XP)</label>
                <input type="number" name="xp_reward" value="<?= $stage->xp_reward ?>" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-brandYellow transition-colors font-mono text-xl" required>
            </div>

            <button type="submit" class="w-full bg-brandPurple hover:bg-brandPurpleHover text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-brandPurple/20 flex justify-center items-center gap-2">
                <span>حفظ التعديلات السحرية</span>
                <span>✨</span>
            </button>
        </form>
    </div>
</div>