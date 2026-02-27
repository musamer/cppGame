<div class="w-full max-w-6xl mx-auto p-6 md:p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">📊 لوحة تحكم المدرب (Analytics Dashboard)</h1>
    </div>

    <div class="flex flex-col md:flex-row gap-6 mt-8">
        <div class="bg-cardDark p-6 rounded-xl flex-1 text-center border border-[#30363d] shadow-lg">
            <h3 class="text-textSecondary text-xl m-0">عدد المتدربين</h3>
            <h1 class="text-brandPurple text-5xl font-bold mt-4 mb-2"><?= $students_count ?></h1>
        </div>
        <div class="bg-cardDark p-6 rounded-xl flex-1 text-center border border-[#30363d] shadow-lg">
            <h3 class="text-textSecondary text-xl m-0">متوسط درجات المنصة</h3>
            <h1 class="text-brandGreen text-5xl font-bold mt-4 mb-2"><?= $average_score ?>%</h1>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 mt-8">
        <!-- Struggling Concepts -->
        <div class="flex-1 bg-cardDark p-6 rounded-xl border border-[#30363d]">
            <h3 class="text-brandYellow text-xl font-bold mb-4">⚠️ أكثر المفاهيم صعوبة (Spaced Reinforcement Alerts)</h3>
            <ul class="list-none p-0 m-0">
                <?php foreach ($struggling_concepts as $concept): ?>
                    <li class="p-4 border-b border-[#30363d] flex justify-between items-center last:border-0 hover:bg-[#30363d]/30 transition-colors">
                        <strong class="text-white"><?= $concept['name'] ?></strong>
                        <span class="text-brandRed font-bold bg-brandRed/10 px-3 py-1 rounded-full"><?= $concept['failure_rate'] ?> رسوب</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="w-full mt-6 bg-brandPurple hover:bg-brandPurpleHover text-white px-6 py-3 rounded-lg font-bold transition-colors shadow-lg shadow-brandPurple/20">استخراج تقرير PDF 📑</button>
        </div>

        <!-- Recent Submissions -->
        <div class="flex-[2] bg-cardDark p-6 rounded-xl border border-[#30363d] overflow-x-auto">
            <h3 class="text-textPrimary text-xl font-bold mb-4">📝 أحدث التسليمات للـ AI Engine</h3>
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#30363d]">
                        <th class="p-3 text-textSecondary">الطالب</th>
                        <th class="p-3 text-textSecondary">التمرين</th>
                        <th class="p-3 text-textSecondary">التقييم</th>
                        <th class="p-3 text-textSecondary">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_submissions as $sub): ?>
                        <tr class="border-b border-[#30363d] hover:bg-[#30363d]/30 transition-colors">
                            <td class="p-3 text-white"><?= $sub['student'] ?></td>
                            <td class="p-3 text-white"><?= $sub['exercise'] ?></td>
                            <td class="p-3 text-white font-mono"><?= $sub['score'] ?>/100</td>
                            <td class="p-3">
                                <?php if ($sub['status'] == 'passed'): ?>
                                    <span class="bg-brandGreen/20 text-brandGreen px-3 py-1 rounded-md font-semibold text-sm border border-brandGreen/30">✅ اجتياز</span>
                                <?php else: ?>
                                    <span class="bg-brandRed/20 text-brandRed px-3 py-1 rounded-md font-semibold text-sm border border-brandRed/30">❌ فشل</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>