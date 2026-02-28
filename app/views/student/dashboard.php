<div class="w-full max-w-6xl mx-auto p-6 md:p-8">
    <!-- Rank and XP Dashboard Card -->
    <div class="mb-8 bg-cardDark border border-[#30363d] rounded-2xl p-6 md:p-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -right-20 -top-20 w-56 h-56 bg-brandPurple/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-56 h-56 bg-brandYellow/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="z-10 flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[#30363d] to-[#161b22] border border-[#30363d] flex justify-center items-center text-4xl shadow-inner shadow-black/50">
                🐎
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1"><?= __('welcome_hero') ?></h1>
                <p class="text-textSecondary flex items-center gap-2">
                    <?= __('current_rank') ?>
                    <span class="text-brandPurple font-bold px-2 py-0.5 bg-brandPurple/20 rounded border border-brandPurple/30"><?= $rank_title ?></span>
                </p>
            </div>
        </div>

        <div class="z-10 w-full md:w-1/3 min-w-[250px] bg-[#0d1117] p-4 rounded-xl border border-[#30363d]">
            <div class="flex justify-between items-end mb-2">
                <div>
                    <span class="text-xs text-textSecondary block"><?= __('experience_points') ?></span>
                    <span class="text-lg font-bold text-brandYellow font-mono">⭐ <?= number_format($_SESSION['user_xp']) ?></span>
                </div>
                <div class="text-[<?php echo isset($_SESSION['lang']) && $_SESSION['lang'] == 'en' ? 'left' : 'right'; ?>]">
                    <span class="text-[10px] text-gray-500 block"><?= __('next_goal') ?></span>
                    <span class="text-xs font-bold text-gray-300"><?= $next_rank_info['next_rank'] ?></span>
                </div>
            </div>

            <?php
            $progress_percentage = 100; // max default
            if ($next_rank_info['total_needed'] > $_SESSION['user_xp']) {
                // Quick math: Current XP / Target XP. (Normally needs baseline deduction to be accurate per level, but simple works for gamification here).
                $baseline = $next_rank_info['total_needed'] - $next_rank_info['xp_needed'];
                $progress_percentage = (($_SESSION['user_xp'] - $baseline) / ($next_rank_info['total_needed'] - $baseline)) * 100;
            }
            ?>
            <div class="w-full bg-[#161b22] rounded-full h-2.5 overflow-hidden ring-1 ring-inset ring-white/10">
                <div class="bg-gradient-to-r from-brandPurple to-[#8a63f2] h-2.5 rounded-full relative" style="width: <?= max(5, $progress_percentage) ?>%">
                    <div class="absolute inset-0 bg-white/20 animate-pulse rounded-full"></div>
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-2 text-center">
                <?= $next_rank_info['xp_needed'] > 0 ? str_replace(':points', number_format($next_rank_info['xp_needed']), __('need_more_points')) : __('max_rank_reached') ?>
            </p>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-xl text-textSecondary"><?= __('choose_stage') ?></h3>
    </div>

    <?php foreach ($worlds as $world): ?>
        <?php if (!$world['locked']): ?>
            <div class="mt-8 mb-4">
                <h2 class="text-2xl font-bold text-brandPurple mb-4"><?= $world['title'] ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($world['stages'] as $stage): ?>
                        <div class="bg-cardDark border <?= $stage['is_solved'] ? 'border-brandGreen' : 'border-[#30363d]' ?> rounded-xl p-6 text-center transition-all duration-300 <?= $stage['locked'] ? 'opacity-50 cursor-not-allowed grayscale' : ($stage['is_solved'] ? 'cursor-default opacity-90' : 'cursor-pointer hover:-translate-y-1 hover:shadow-lg hover:shadow-brandPurple/20 hover:border-brandPurple') ?> relative overflow-hidden" onclick="if(!<?= $stage['locked'] || $stage['is_solved'] ? 'true' : 'false' ?>) window.location.href='<?= URLROOT ?>/student/exercise/<?= $world['id'] ?>/<?= $stage['id'] ?>'">

                            <?php if (!$stage['locked'] && !$stage['is_solved']): ?>
                                <div class="absolute -top-10 -right-10 w-20 h-20 bg-brandPurple/20 rounded-full blur-2xl pointer-events-none"></div>
                            <?php endif; ?>

                            <?php if ($stage['is_solved']): ?>
                                <div class="absolute top-2 right-2 text-brandGreen">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-xl font-bold text-white mb-2"><?= $stage['title'] ?></h3>
                            <?php if ($stage['locked']): ?>
                                <p class="text-gray-400"><?= __('locked') ?></p>
                            <?php elseif ($stage['is_solved']): ?>
                                <p class="text-brandGreen font-bold"><?= str_replace(':xp', $stage['xp_reward'], __('completed')) ?></p>
                            <?php else: ?>
                                <p class="text-brandYellow font-semibold">
                                    ⭐ <?= $stage['xp_reward'] ?> <?= __('xp') ?>
                                    <?php if ($stage['max_score'] > 0): ?>
                                        <span class="text-sm text-gray-400 block mt-1"><?= str_replace(':score', $stage['max_score'], __('your_progress')) ?></span>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- World is locked, hide completely or show a locked banner -->
            <div class="mt-8 mb-4 relative rounded-xl overflow-hidden border border-[#30363d] opacity-50 select-none hidden">
                <div class="bg-cardDark p-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-500 mb-2">🔒 <?= $world['title'] ?></h2>
                    <p class="text-textSecondary"><?= __('world_locked_msg') ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>