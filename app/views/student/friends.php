<div class="w-full max-w-5xl mx-auto p-6 md:p-8">

    <div class="mb-8 flex justify-between items-center bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-xl relative overflow-hidden">
        <!-- Abstract decoration -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brandPurple/20 rounded-full blur-3xl pointer-events-none"></div>

        <div>
            <h1 class="text-3xl font-bold mb-2 text-white"><?= __('friends_alliance') ?> 🐎⚔️</h1>
            <p class="text-textSecondary"><?= __('friends_subtitle') ?></p>
        </div>

        <div class="text-center bg-[#0d1117] px-6 py-4 rounded-xl border border-brandPurple/30 relative group">
            <span class="block text-sm text-gray-400 mb-1"><?= __('your_knight_code') ?></span>
            <span class="text-xl font-mono font-bold text-brandYellow tracking-wider cursor-pointer" onclick="navigator.clipboard.writeText('<?= $my_code ?>'); alert('<?= __('copied') ?>');" title="<?= __('click_to_copy') ?>"><?= $my_code ?></span>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Add Friend Form -->
        <div class="md:col-span-1">
            <div class="bg-cardDark border border-[#30363d] rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold mb-4 text-white"><?= __('add_friend') ?></h3>
                <p class="text-sm text-textSecondary mb-6"><?= __('add_friend_subtitle') ?></p>
                <form action="<?= URLROOT ?>/student/friends" method="POST">
                    <div class="mb-4">
                        <input type="text" name="friend_code" class="w-full bg-[#0d1117] border border-[#30363d] rounded-lg px-4 py-3 text-white font-mono placeholder-gray-600 focus:outline-none focus:border-brandPurple focus:ring-1 focus:ring-brandPurple uppercase" placeholder="KNIGHT-XXXXXX" required>
                    </div>
                    <button type="submit" class="w-full bg-brandPurple hover:bg-brandPurpleHover text-white font-bold py-3 rounded-lg transition-colors shadow-lg shadow-brandPurple/20">
                        <?= __('send_invite') ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Leaderboard -->
        <div class="md:col-span-2">
            <div class="bg-cardDark border border-[#30363d] rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-[#30363d] bg-white/5">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <?= __('alliance_ranking') ?>
                    </h3>
                </div>

                <div class="p-0">
                    <?php if (empty($leaderboard)): ?>
                        <div class="p-8 text-center text-textSecondary">
                            <?= __('no_friends_yet') ?>
                        </div>
                    <?php else: ?>
                        <ul class="divide-y divide-[#30363d]">
                            <?php foreach ($leaderboard as $index => $hero): ?>
                                <li class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors <?= ($hero->id == $_SESSION['user_id']) ? 'bg-brandPurple/5 border-l-4 border-brandPurple' : '' ?>">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 flex justify-center">
                                            <?php if ($index == 0): ?>
                                                <span class="text-2xl" title="<?= __('rank_1') ?>">🥇</span>
                                            <?php elseif ($index == 1): ?>
                                                <span class="text-2xl" title="<?= __('rank_2') ?>">🥈</span>
                                            <?php elseif ($index == 2): ?>
                                                <span class="text-2xl" title="<?= __('rank_3') ?>">🥉</span>
                                            <?php else: ?>
                                                <span class="text-gray-500 font-bold">#<?= $index + 1 ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="w-10 h-10 rounded-full bg-gray-700 flex justify-center items-center text-xl <?= ($hero->id == $_SESSION['user_id']) ? 'ring-2 ring-brandPurple' : '' ?>">
                                            🥷
                                        </div>

                                        <div>
                                            <p class="font-bold text-white">
                                                <?= $hero->username ?>
                                                <?php if ($hero->id == $_SESSION['user_id']): ?> <span class="text-xs text-brandPurple bg-brandPurple/20 px-2 py-0.5 rounded ml-2"><?= __('you_indicator') ?></span> <?php endif; ?>
                                            </p>
                                            <p class="text-xs text-textSecondary mt-1"><?= str_replace(':count', $hero->stages_completed, __('completed_stages')) ?></p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xl font-bold text-brandYellow font-mono"><?= $hero->total_xp ?> <span class="text-sm text-gray-500">XP</span></p>
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