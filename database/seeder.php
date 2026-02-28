<?php
// Seeder Script for C++ Gamified Training Platform

// Load configuration
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/core/Database.php';

$db = new Database();

echo "Starting Database Seeding...\n";

// Disable foreign key checks for truncation
$db->query("SET FOREIGN_KEY_CHECKS = 0");
$db->execute();

echo "Truncating tables...\n";
$tables = ['exercises', 'concepts', 'stages', 'worlds', 'unlocked_worlds'];
foreach ($tables as $table) {
    // Only truncate if we want a fresh start
    $db->query("TRUNCATE TABLE $table");
    $db->execute();
}

$db->query("SET FOREIGN_KEY_CHECKS = 1");
$db->execute();

// ==========================================
// 1. Worlds
// ==========================================
$worlds = [
    ['title' => 'World 1: The Awakening', 'description' => 'إتقان أساسيات لغة C++ وفهم كيفية عمل البرامج والتفكير المنطقي البسيط.', 'order_index' => 1],
    ['title' => 'World 2: Formation Secrets', 'description' => 'إتقان تنظيم البيانات باستخدام المصفوفات، وتقسيم الكود إلى دوال.', 'order_index' => 2],
    ['title' => 'World 3: Mind Power', 'description' => 'تحسين الكود، فهم تعقيد الوقت لسرعة التنفيذ، والغوص في مكتبة C++ القياسية.', 'order_index' => 3],
    ['title' => 'World 4: Phantom Knight', 'description' => 'التفكير الاستراتيجي لحل المسائل المعقدة، والخوارزميات الجشعة (Greedy).', 'order_index' => 4],
];

echo "Seeding Worlds...\n";
foreach ($worlds as $world) {
    $db->query("INSERT INTO worlds (title, description, order_index, xp_reward, is_active) VALUES (:title, :description, :order_index, 500, 1)");
    $db->bind(':title', $world['title']);
    $db->bind(':description', $world['description']);
    $db->bind(':order_index', $world['order_index']);
    $db->execute();
}

// ==========================================
// 2 & 3. Stages AND Exercises 
// (Generating 30 stages per world with an exercise for each)
// ==========================================
echo "Seeding Stages and Exercises...\n";
$total_stages_per_world = 30;

// Base configuration for procedural generation
$topics = [
    1 => ["Basics", "Output", "Variables", "Math", "If/Else", "Loops", "Nested Loops", "Basic Funcs"],
    2 => ["Arrays", "Strings", "Sorting", "Searching", "Pointers", "References", "Structs", "Advanced Funcs"],
    3 => ["STL Vectors", "STL Sets", "STL Maps", "Time Complexity", "Queues", "Stacks", "Iterators", "Pair/Tuple"],
    4 => ["Greedy Algorithms", "Dynamic Programming Intro", "Graph Theory Intro", "Bitmasking", "Prefix Sums", "Two Pointers", "Binary Search Trees", "Backtracking"]
];

$stage_counter = 1;

for ($world_id = 1; $world_id <= 4; $world_id++) {
    for ($stage_order = 1; $stage_order <= $total_stages_per_world; $stage_order++) {

        // 1. Create Stage
        $topic_index = ($stage_order - 1) % count($topics[$world_id]);
        $topic_name = $topics[$world_id][$topic_index];

        // Embellish titles for gamification
        $stage_title = "تحدي الفرسان رقم $stage_order - " . $topic_name;
        $description = "في هذا التحدي الخاص بالعالم {$world_id}، سيقوم الفارس باختبار مهاراته في موضوع $topic_name.";

        $db->query("INSERT INTO stages (world_id, title, description, order_index, xp_reward) VALUES (:world_id, :title, :description, :order_index, 10)");
        $db->bind(':world_id', $world_id);
        $db->bind(':title', $stage_title);
        $db->bind(':description', $description);
        $db->bind(':order_index', $stage_order);
        $db->execute();

        $stage_id = $db->lastInsertId(); // Get the inserted stage ID

        // 2. Create Concept (Optional, but inserting dummy concept for mapping)
        $db->query("INSERT INTO concepts (stage_id, name, description) VALUES (:stage_id, :name, :description)");
        $db->bind(':stage_id', $stage_id);
        $db->bind(':name', "مفهوم $topic_name");
        $db->bind(':description', "شرح قاعدة $topic_name للفرسان المبتدئين.");
        $db->execute();
        $concept_id = $db->lastInsertId();

        // 3. Create Exercise
        // We generate a dynamic exercise based on the world and stage order

        // Basic template
        $ex_title = "مهمة الفارس: " . $topic_name;
        $ex_content = "استخدم مهاراتك في **$topic_name** لكتابة كود برمجي بلغة C++ يقرأ مدخلات مخصصة ويقوم بإرجاع النتيجة المطلوبة لحماية القلعة.";

        // Example dynamic logic: Just reading a number and returning something based on the stage
        $starter_code = "#include <iostream>\nusing namespace std;\n\nint main() {\n    // كود الفارس يبدأ هنا\n    int n;\n    if (cin >> n) {\n        // نفذ المطلوب\n    }\n    return 0;\n}";

        // To make test cases valid dynamically, let's say the task is simply to print N + stage_order
        $multiplier = $world_id * 10;
        $addition = $stage_order;
        $ex_content .= "\n\n**المهمة:** اقرأ رقماً صحيحاً N ثم قم بطباعة حاصل ضربه في $multiplier زائد $addition.";

        $test_cases = [
            ['input' => "5\n", 'output' => (string) (5 * $multiplier + $addition)],
            ['input' => "10\n", 'output' => (string) (10 * $multiplier + $addition)],
            ['input' => "1\n", 'output' => (string) (1 * $multiplier + $addition)]
        ];

        $solution_code = "#include <iostream>\nusing namespace std;\n\nint main() {\n    long long n;\n    if (cin >> n) {\n        cout << (n * $multiplier + $addition);\n    }\n    return 0;\n}";

        // Special manual override for Stage 1 World 1 (Hello Knight) just for flavor
        if ($world_id == 1 && $stage_order == 1) {
            $ex_title = "تحية الفارس (Hello Knight)";
            $ex_content = 'أهلاً بك في عالم البرمجة! التحدي الأول لك هو طباعة الجملة التالية تماماً: "Hello, Knight!"';
            $starter_code = "#include <iostream>\nusing namespace std;\n\nint main() {\n    // Code here\n    return 0;\n}";
            $test_cases = [['input' => '', 'output' => 'Hello, Knight!']];
            $solution_code = "#include <iostream>\nusing namespace std;\nint main() {\n    cout << \"Hello, Knight!\";\n    return 0;\n}";
        }

        $db->query("INSERT INTO exercises (stage_id, concept_id, type, difficulty, title, content, starter_code, test_cases, solution_code, xp_reward) VALUES (:stage_id, :concept_id, 'training', 'easy', :title, :content, :starter_code, :test_cases, :solution_code, 10)");
        $db->bind(':stage_id', $stage_id);
        $db->bind(':concept_id', $concept_id);
        $db->bind(':title', $ex_title);
        $db->bind(':content', $ex_content);
        $db->bind(':starter_code', $starter_code);
        $db->bind(':test_cases', json_encode($test_cases));
        $db->bind(':solution_code', $solution_code);
        $db->execute();

        $stage_counter++;
    }
}

echo "\n✅ Successfully seeded 4 Worlds, 120 Stages, 120 Concepts, and 120 Exercises!\n";
