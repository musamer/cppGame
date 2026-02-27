<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITENAME; ?></title>
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

    <header class="bg-cardDark border-b border-[#30363d] px-8 py-4 flex justify-between items-center relative gap-4">
        <a href="<?= URLROOT ?>" class="text-2xl font-bold tracking-tight text-brandPurple decoration-none">🚀 C++ Ninjas</a>

        <div class="flex items-center gap-4">
            <?php if (Session::isLoggedIn()): ?>
                <span class="hidden sm:inline-block bg-brandPurple/20 text-[#a78bfa] px-4 py-1.5 rounded-full font-semibold text-sm border border-brandPurple/30 relative overflow-hidden group hover:cursor-default">
                    <span class="relative z-10">⭐ <?= $_SESSION['user_xp'] ?? '0' ?> XP</span>
                    <span class="absolute inset-0 bg-brandPurple/30 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                </span>

                <!-- Simple Dropdown trigger -->
                <div class="relative group">
                    <div class="flex items-center gap-2 cursor-pointer pr-2">
                        <span class="font-bold text-gray-300 hidden md:block"><?= $_SESSION['user_name'] ?></span>
                        <div class="w-10 h-10 rounded-full bg-gray-700 font-bold overflow-hidden ring-2 ring-[#30363d] group-hover:ring-brandPurple transition-colors flex justify-center items-center text-xl">
                            🥷
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-48 bg-[#161b22] border border-[#30363d] rounded-xl shadow-xl py-2 hidden group-hover:block z-50">
                        <?php if ($_SESSION['user_role'] === 'instructor'): ?>
                            <a href="<?= URLROOT ?>/instructor/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">لوحة المدرب</a>
                        <?php else: ?>
                            <a href="<?= URLROOT ?>/student/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">لوحة النينجا (Dashboard)</a>
                        <?php endif; ?>

                        <div class="h-px bg-[#30363d] my-1"></div>
                        <a href="<?= URLROOT ?>/auth/logout" class="block px-4 py-2 text-sm text-brandRed hover:bg-[#30363d] transition-colors">تسجيل الخروج</a>
                    </div>
                </div>

            <?php else: ?>
                <!-- Guest View -->
                <a href="<?= URLROOT ?>/auth/login" class="text-textSecondary hover:text-white font-bold transition-colors">دخول المنصة</a>
                <a href="<?= URLROOT ?>/auth/register" class="bg-brandPurple hover:bg-brandPurpleHover text-white px-5 py-2 rounded-lg font-bold transition-colors shadow-lg shadow-brandPurple/20">انضم للنينجا</a>
            <?php endif; ?>
        </div>
    </header>
    <main class="w-full flex-1 flex flex-col items-center">