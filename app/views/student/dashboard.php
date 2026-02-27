<div class="w-full max-w-6xl mx-auto p-6 md:p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">مرحباً بك في ساحة التدريب يا بطل! 🥷</h1>
        <h3 class="text-xl text-textSecondary">اختر المرحلة للبدء:</h3>
    </div>

    <?php foreach ($worlds as $world): ?>
        <div class="mt-8 mb-4">
            <h2 class="text-2xl font-bold text-brandPurple mb-4"><?= $world['title'] ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($world['stages'] as $stage): ?>
                    <div class="bg-cardDark border border-[#30363d] rounded-xl p-6 text-center transition-all duration-300 <?= $stage['locked'] ? 'opacity-50 cursor-not-allowed grayscale' : 'cursor-pointer hover:-translate-y-1 hover:shadow-lg hover:shadow-brandPurple/20 hover:border-brandPurple' ?>" onclick="if(!<?= $stage['locked'] ? 'true' : 'false' ?>) window.location.href='<?= URLROOT ?>/student/exercise/<?= $world['id'] ?>/<?= $stage['id'] ?>'">
                        <h3 class="text-xl font-bold text-white mb-2"><?= $stage['title'] ?></h3>
                        <?php if ($stage['locked']): ?>
                            <p class="text-gray-400">🔒 مغلق</p>
                        <?php else: ?>
                            <p class="text-brandYellow font-semibold">⭐ 100 XP</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>