<div class="w-full max-w-7xl mx-auto p-6 md:p-8">

    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-white">لوحة تحكم المدرب 👑</h1>
            <p class="text-textSecondary">قم بمراقبة تقدم الفرسان (طلابك) وأداء ساحة التدريب بشكل حي.</p>
        </div>
        <div>
            <button class="bg-[#30363d] hover:bg-[#484f58] text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">تصدير تقرير (محاكاة)</button>
        </div>
    </div>

    <!-- Quick Stats Cards (The Big Picture) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brandPurple/20 rounded-full blur-2xl"></div>
            <div class="w-14 h-14 rounded-xl bg-brandPurple/10 text-brandPurple flex items-center justify-center text-3xl font-bold">
                🐎
            </div>
            <div>
                <p class="text-textSecondary text-sm font-bold mb-1">الفرسان (الطلاب)</p>
                <h3 class="text-3xl font-mono font-bold text-white"><?= number_format($stats['total_students']) ?></h3>
            </div>
        </div>

        <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brandGreen/20 rounded-full blur-2xl"></div>
            <div class="w-14 h-14 rounded-xl bg-brandGreen/10 text-brandGreen flex items-center justify-center text-3xl font-bold">
                ⚔️
            </div>
            <div>
                <p class="text-textSecondary text-sm font-bold mb-1">المحاولات (الكود المُرسل)</p>
                <h3 class="text-3xl font-mono font-bold text-white"><?= number_format($stats['total_submissions']) ?></h3>
            </div>
        </div>

        <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-lg flex items-center gap-4 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brandYellow/20 rounded-full blur-2xl"></div>
            <div class="w-14 h-14 rounded-xl bg-brandYellow/10 text-brandYellow flex items-center justify-center text-3xl font-bold">
                🎯
            </div>
            <div>
                <p class="text-textSecondary text-sm font-bold mb-1">متوسط الدرجات العام</p>
                <h3 class="text-3xl font-mono font-bold text-white"><?= $stats['avg_score'] ?>%</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Activity & Submissions -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Recent Real-time Submissions Feed -->
            <div class="bg-cardDark border border-[#30363d] rounded-2xl shadow-lg overflow-hidden">
                <div class="p-5 border-b border-[#30363d] bg-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">📡 البث الحي للمحاولات</h3>
                    <span class="text-xs text-brandGreen px-2 py-1 bg-brandGreen/10 rounded-full font-bold">مُتصل</span>
                </div>

                <div class="p-0">
                    <?php if (empty($recent_submissions)): ?>
                        <div class="p-8 text-center text-textSecondary">لم يتم إرسال أي أكواد بعد.</div>
                    <?php else: ?>
                        <ul class="divide-y divide-[#30363d]">
                            <?php foreach ($recent_submissions as $sub): ?>
                                <li class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-700 flex justify-center items-center text-lg">🐎</div>
                                        <div>
                                            <p class="font-bold text-white text-sm"><?= htmlspecialchars($sub->student_name) ?></p>
                                            <p class="text-xs text-textSecondary mt-1">تحدي: <?= htmlspecialchars($sub->exercise_title) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <?php if ($sub->status == 'passed'): ?>
                                                <span class="text-xs font-bold text-brandGreen bg-brandGreen/10 px-2 py-1 rounded">نجاح (<?= $sub->score ?>%)</span>
                                            <?php elseif ($sub->status == 'failed'): ?>
                                                <span class="text-xs font-bold text-brandRed bg-brandRed/10 px-2 py-1 rounded">إخفاق (<?= $sub->score ?>%)</span>
                                            <?php else: ?>
                                                <span class="text-xs font-bold text-brandYellow bg-brandYellow/10 px-2 py-1 rounded">قيد المعالجة</span>
                                            <?php endif; ?>
                                            <p class="text-[10px] text-gray-500 mt-1" dir="ltr"><?= date('h:i A', strtotime($sub->created_at)) ?></p>
                                        </div>
                                        <button class="text-gray-400 hover:text-white transition-colors" title="عرض تفاصيل الكود"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg></button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Struggling Concepts Tracker -->
            <div class="bg-cardDark border border-[#30363d] rounded-2xl shadow-lg overflow-hidden">
                <div class="p-5 border-b border-[#30363d] bg-white/5">
                    <h3 class="text-lg font-bold text-white">⚠️ تحديات يواجه فيها الطلاب صعوبة (أقل متوسط درجات)</h3>
                </div>

                <div class="p-5">
                    <?php if (empty($struggling_exercises)): ?>
                        <div class="text-center text-textSecondary py-4">جميع الطلاب متفوقين حالياً ولا توجد صعوبات ملحوظة!</div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($struggling_exercises as $struggle): ?>
                                <?php
                                // Calculate failure risk indicator (redness) based on score
                                $score = round($struggle->avg_score, 1);
                                $barColor = ($score < 40) ? 'bg-brandRed' : (($score < 70) ? 'bg-brandYellow' : 'bg-brandGreen');
                                ?>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-bold text-white"><?= htmlspecialchars($struggle->title) ?></span>
                                        <span class="text-xs text-gray-400">متوسط الدرجات: <?= $score ?>%</span>
                                    </div>
                                    <div class="w-full bg-[#0d1117] rounded-full h-2.5">
                                        <div class="<?= $barColor ?> h-2.5 rounded-full" style="width: <?= max(5, $score) ?>%"></div>
                                    </div>
                                    <div class="text-[11px] text-textSecondary mt-1 text-left" dir="ltr">Attempts: <?= $struggle->attempt_count ?> | Fails: <?= $struggle->failure_count ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Right Column: Top Students -->
        <div class="lg:col-span-1">
            <div class="bg-cardDark border border-[#30363d] rounded-2xl shadow-lg overflow-hidden sticky top-6">
                <div class="p-5 border-b border-[#30363d] bg-white/5">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">🏅 الفرسان الأوائل (عام)</h3>
                </div>

                <div class="p-0">
                    <?php if (empty($top_students)): ?>
                        <div class="p-8 text-center text-textSecondary">لا يوجد طلاب بعد.</div>
                    <?php else: ?>
                        <ul class="divide-y divide-[#30363d]">
                            <?php foreach ($top_students as $idx => $ts): ?>
                                <li class="p-4 flex items-center gap-3 hover:bg-white/5 transition-colors cursor-pointer" title="عرض الملف الشخصي">
                                    <div class="font-bold w-4 text-center text-gray-500 text-sm">#<?= $idx + 1 ?></div>
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex justify-center items-center text-base">🐎</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-white text-sm truncate"><?= htmlspecialchars($ts->username) ?></p>
                                        <p class="text-[11px] text-textSecondary truncate"><?= $ts->stages_completed ?> مراحل مكتملة</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-mono font-bold text-brandPurple bg-brandPurple/10 px-2 py-1 rounded"><?= number_format($ts->total_xp) ?> XP</span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>