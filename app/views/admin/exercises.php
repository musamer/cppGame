<div class="w-full max-w-7xl mx-auto p-6 md:p-8">

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-white">إدارة التحديات السحرية ⚡ (الأسئلة)</h1>
            <p class="text-textSecondary">تحكم في محتوى التحديات البرمجية، الأكواد المبدئية، وحالات الاختبار (Test Cases).</p>
        </div>
        <div>
            <button class="bg-brandGreen hover:bg-brandGreenHover text-white px-5 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-lg flex items-center gap-2">
                <span>➕ تحدي جديد</span>
            </button>
        </div>
    </div>

    <!-- Feedback messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-brandGreen/20 border border-brandGreen text-white px-4 py-3 rounded-lg mb-6">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-brandRed/20 border border-brandRed text-white px-4 py-3 rounded-lg mb-6">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-cardDark border border-[#30363d] rounded-2xl shadow-xl overflow-hidden truncate">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-[#30363d] text-textSecondary text-sm">
                        <th class="p-4 font-bold">العالم والمرحلة</th>
                        <th class="p-4 font-bold">التحدي</th>
                        <th class="p-4 font-bold text-center">نقاط (XP)</th>
                        <th class="p-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#30363d]">
                    <?php if (empty($exercises)): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-textSecondary">لا توجد تحديات مسجلة.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($exercises as $ex): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-brandPurple/20 text-brandPurple text-xs font-bold px-2 py-0.5 rounded border border-brandPurple/30 w-fit">
                                            عالم: <?= htmlspecialchars($ex->world_title ?? 'غ/م') ?>
                                        </span>
                                        <span class="text-xs text-textSecondary">
                                            مرحلة #<?= $ex->stage_order ?>: <?= htmlspecialchars($ex->stage_title ?? 'غ/م') ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-white"><?= htmlspecialchars($ex->title) ?></td>
                                <td class="p-4 text-center font-mono text-brandYellow">⭐ <?= $ex->xp_reward ?></td>
                                <td class="p-4 text-center">
                                    <a href="<?= URLROOT ?>/admin/edit_exercise/<?= $ex->id ?>" class="inline-flex items-center justify-center bg-[#30363d] hover:bg-[#484f58] text-white px-3 py-1.5 rounded transition-colors text-sm border border-gray-600">
                                        تعديل ✏️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>