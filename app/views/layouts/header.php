<?php
$dir = ($_SESSION['lang'] === 'ar') ? 'rtl' : 'ltr';
$lang = $_SESSION['lang'] ?? 'ar';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('site_title'); ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bgDark: '#0d1117',
                        cardDark: '#161b22',
                        textPrimary: '#e6edf3',
                        textSecondary: '#7d8590',
                        brandPurple: '#8b5cf6',
                        brandPurpleHover: '#7c3aed',
                        brandGreen: '#238636',
                        brandGreenHover: '#2ea043',
                        brandYellow: '#d29922',
                        brandRed: '#da3633',
                        borderCode: '#30363d'
                    },
                    fontFamily: {
                        sans: ['Segoe UI', 'Tahoma', 'sans-serif'],
                        mono: ['Courier New', 'Courier', 'monospace']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= URLROOT; ?>/css/style.css">
</head>

<body class="bg-bgDark text-textPrimary font-sans flex flex-col min-h-screen m-0 p-0">

    <header class="bg-cardDark border-b border-[#30363d] px-8 py-4 flex flex-col md:flex-row justify-between items-center relative gap-4 z-50">
        <a href="<?= URLROOT ?>" class="text-2xl font-bold tracking-tight text-brandPurple decoration-none"><?= __('site_title') ?></a>

        <div class="flex items-center gap-4 flex-wrap justify-center">

            <!-- Language Switcher -->
            <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'ar'): ?>
                <a href="<?= URLROOT ?>/lang/switch/en" class="text-sm font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-1 border border-[#30363d] rounded-full px-3 py-1 bg-white/5">
                    🌐 English
                </a>
            <?php else: ?>
                <a href="<?= URLROOT ?>/lang/switch/ar" class="text-sm font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-1 border border-[#30363d] rounded-full px-3 py-1 bg-white/5 font-sans">
                    🌐 العربية
                </a>
            <?php endif; ?>

            <?php if (Session::isLoggedIn()): ?>
                <span class="hidden sm:inline-block bg-brandPurple/20 text-[#a78bfa] px-4 py-1.5 rounded-full font-semibold text-sm border border-brandPurple/30 relative overflow-hidden group hover:cursor-default">
                    <span class="relative z-10">⭐ <?= $_SESSION['user_xp'] ?? '0' ?> XP</span>
                    <span class="absolute inset-0 bg-brandPurple/30 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                </span>

                <!-- Simple Dropdown trigger -->
                <div class="relative group">
                    <div class="flex items-center gap-2 cursor-pointer pr-2">
                        <div class="flex flex-col text-left">
                            <span class="font-bold text-gray-300 hidden md:block text-sm"><?= $_SESSION['user_name'] ?></span>
                            <?php
                            $xp = $_SESSION['user_xp'] ?? 0;
                            $rank = __('rank_trainee');
                            if ($xp >= 60) $rank = __('rank_novice');
                            if ($xp >= 150) $rank = __('rank_adept');
                            if ($xp >= 300) $rank = __('rank_warrior');
                            if ($xp >= 500) $rank = __('rank_commander');
                            if ($xp >= 750) $rank = __('rank_silver');
                            if ($xp >= 1000) $rank = __('rank_golden');
                            if ($xp >= 1150) $rank = __('rank_legend');
                            if ($xp >= 1200) $rank = __('rank_grandmaster');
                            ?>
                            <span class="text-xs text-brandPurple font-bold hidden md:block"><?= str_replace(':rank', $rank, __('rank')) ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gray-700 font-bold overflow-hidden ring-2 ring-[#30363d] group-hover:ring-brandPurple transition-colors flex justify-center items-center text-xl">
                            🐎
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="absolute <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] == 'en' ? 'right-0' : 'left-0'; ?> top-full pt-2 w-48 hidden group-hover:block z-50">
                        <div class="bg-[#161b22] border border-[#30363d] rounded-xl shadow-xl py-2">
                            <?php if ($_SESSION['user_role'] === 'instructor' || $_SESSION['user_role'] === 'admin'): ?>
                                <a href="<?= URLROOT ?>/instructor/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors"><?= __('instructor_dashboard') ?></a>
                                <a href="<?= URLROOT ?>/admin/stages" class="block px-4 py-2 text-sm text-brandYellow hover:bg-[#30363d] transition-colors font-bold">⚙️ إدارة المراحل</a>
                                <a href="<?= URLROOT ?>/admin/exercises" class="block px-4 py-2 text-sm text-brandGreen hover:bg-[#30363d] transition-colors font-bold">⚡ إدارة الأسئلة (التحديات)</a>
                            <?php else: ?>
                                <a href="<?= URLROOT ?>/student/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors"><?= __('dashboard') ?></a>
                                <a href="<?= URLROOT ?>/student/friends" class="block px-4 py-2 text-sm text-brandYellow hover:bg-[#30363d] transition-colors font-bold">⚔️ <?= __('friends_alliance') ?></a>
                            <?php endif; ?>

                            <div class="h-px bg-[#30363d] my-1"></div>
                            <a href="<?= URLROOT ?>/auth/logout" class="block px-4 py-2 text-sm text-brandRed font-bold hover:bg-[#30363d] transition-colors"><?= __('logout') ?></a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Guest View -->
                <a href="<?= URLROOT ?>/auth/login" class="text-textSecondary hover:text-white font-bold transition-colors"><?= __('login') ?></a>
                <a href="<?= URLROOT ?>/auth/register" class="bg-brandPurple hover:bg-brandPurpleHover text-white px-5 py-2 rounded-lg font-bold transition-colors shadow-lg shadow-brandPurple/20"><?= __('register') ?></a>
            <?php endif; ?>
        </div>
    </header>
    <main class="w-full flex-1 flex flex-col items-center">