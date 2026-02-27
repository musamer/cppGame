<?php
// Seeder Script for C++ Ninjas Gamified Training Platform

// Load configuration
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/core/Database.php';

$db = new Database();

echo "Starting Database Seeding...\n";

// Disable foreign key checks for truncation
$db->query("SET FOREIGN_KEY_CHECKS = 0");
$db->execute();

echo "Truncating tables...\n";
$tables = ['exercises', 'concepts', 'stages', 'worlds'];
foreach ($tables as $table) {
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
    ['title' => 'World 4: Phantom Ninja', 'description' => 'التفكير الاستراتيجي لحل المسائل المعقدة، والخوارزميات الجشعة (Greedy).', 'order_index' => 4],
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
// 2. Stages (Weeks -> Days Mapping)
// ==========================================
$stages_data = [
    1 => [ // World 1
        ['title' => 'Day 1: The First Step', 'order' => 1],
        ['title' => 'Day 2: Memory Chests (Variables)', 'order' => 2],
        ['title' => 'Day 3: The Data Gate (I/O)', 'order' => 3],
        ['title' => 'Day 4: Ninja Weapons (Operators)', 'order' => 4],
        ['title' => 'Day 5: Path of Choices (If/Else)', 'order' => 5],
        ['title' => 'Day 6: Time Vortex (Loops)', 'order' => 6],
        ['title' => 'Day 7: Classic Challenges', 'order' => 7]
    ],
    2 => [ // World 2
        ['title' => 'Day 8: Arts of Focus (Functions)', 'order' => 1],
        ['title' => 'Day 9: Troop Formations (Arrays)', 'order' => 2],
        ['title' => 'Day 10: The Magic Pouch (Vectors)', 'order' => 3],
        ['title' => 'Day 11: Ninja Detective (Debugging)', 'order' => 4],
        ['title' => 'Day 12-13: Competitive Bootcamp', 'order' => 5],
        ['title' => 'Day 14: World 2 Boss', 'order' => 6],
    ],
    3 => [ // World 3
        ['title' => 'Day 15: Time Race (Complexity)', 'order' => 1],
        ['title' => 'Day 16: Order from Chaos (Sorting)', 'order' => 2],
        ['title' => 'Day 17: Eagle Eye (Binary Search)', 'order' => 3],
        ['title' => 'Day 18: Matching Chests (Sets/Maps)', 'order' => 4],
        ['title' => 'Day 19: Smart Computor (Math)', 'order' => 5],
        ['title' => 'Day 20-21: Mind Challenges', 'order' => 6],
    ],
    4 => [ // World 4
        ['title' => 'Day 22: Greedy Strategies', 'order' => 1],
        ['title' => 'Day 23: Simulation Mechanics', 'order' => 2],
        ['title' => 'Day 24: Edge Cases', 'order' => 3],
        ['title' => 'Day 25-26: Calling the Bosses', 'order' => 4],
        ['title' => 'Day 27-28: The Grand Arena', 'order' => 5],
        ['title' => 'Day 29-30: Final Tournament', 'order' => 6]
    ]
];

echo "Seeding Stages...\n";
foreach ($stages_data as $world_id => $stages) {
    foreach ($stages as $stage) {
        $db->query("INSERT INTO stages (world_id, title, description, order_index, xp_reward) VALUES (:world_id, :title, :description, :order_index, 100)");
        $db->bind(':world_id', $world_id);
        $db->bind(':title', $stage['title']);
        $db->bind(':description', "Master the concepts of " . $stage['title']);
        $db->bind(':order_index', $stage['order']);
        $db->execute();
    }
}

// ==========================================
// 3. Concepts
// ==========================================
// For simplicity, let's just add one concept to Stage 1 (Day 1) to test
// and one concept for Variables.
echo "Seeding Concepts...\n";
$concepts = [
    ['stage_id' => 1, 'name' => 'Print Output', 'description' => 'Using cout to print text.'],
    ['stage_id' => 1, 'name' => 'Program Structure', 'description' => 'Understanding main() and headers.'],
    ['stage_id' => 2, 'name' => 'Integers', 'description' => 'Declaring and using int variables.'],
    ['stage_id' => 6, 'name' => 'For Loops', 'description' => 'Using for loops to repeat code.'],
    ['stage_id' => 7, 'name' => 'Bit++', 'description' => 'Codeforces problem 282A.'] // Stage 7 is Day 7
];

foreach ($concepts as $concept) {
    $db->query("INSERT INTO concepts (stage_id, name, description) VALUES (:stage_id, :name, :description)");
    $db->bind(':stage_id', $concept['stage_id']);
    $db->bind(':name', $concept['name']);
    $db->bind(':description', $concept['description']);
    $db->execute();
}

// ==========================================
// 4. Exercises
// ==========================================
echo "Seeding Exercises...\n";

$exercises = [
    [
        'stage_id' => 1,
        'concept_id' => 1,
        'title' => 'تحية النينجا (Hello Ninja)',
        'content' => 'أهلاً بك في عالم البرمجة! التحدي الأول لك هو طباعة الجملة التالية تماماً: "Hello, Ninja!"',
        'starter_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    // Write your code here\n    \n    return 0;\n}",
        'test_cases' => json_encode([['input' => '', 'output' => 'Hello, Ninja!']]),
        'solution_code' => "#include <iostream>\nusing namespace std;\nint main() {\n    cout << \"Hello, Ninja!\";\n    return 0;\n}"
    ],
    [
        'stage_id' => 2,
        'concept_id' => 3,
        'title' => 'عداد النقاط (XP Counter)',
        'content' => 'قم بتعريف متغير من نوع `int` باسم `xp` وأعطه القيمة 1500، ثم قم بطباعة قيمة المتغير.',
        'starter_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    // Declare the variable xp and print it\n    \n    return 0;\n}",
        'test_cases' => json_encode([['input' => '', 'output' => '1500']]),
        'solution_code' => "#include <iostream>\nusing namespace std;\nint main() {\n    int xp = 1500;\n    cout << xp;\n    return 0;\n}"
    ],
    [
        'stage_id' => 7,
        'concept_id' => 5,
        'title' => 'العمليات الثنائية (Bit++)',
        'content' => 'اكتب برنامجاً يعطى عدد العمليات N ثم سلسلة العمليات (++X, --X). القيمة المبدئية هي 0. أطبع النتيجة النهائية.',
        'starter_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    int n;\n    cin >> n;\n    // Read statements and update x\n    return 0;\n}",
        'test_cases' => json_encode([['input' => "1\n++X\n", 'output' => '1'], ['input' => "2\n++X\n--X\n", 'output' => '0']]),
        'solution_code' => "/* Will be solved by student */"
    ],
];

foreach ($exercises as $ex) {
    $db->query("INSERT INTO exercises (stage_id, concept_id, type, difficulty, title, content, starter_code, test_cases, solution_code, xp_reward) VALUES (:stage_id, :concept_id, 'training', 'easy', :title, :content, :starter_code, :test_cases, :solution_code, 50)");
    $db->bind(':stage_id', $ex['stage_id']);
    $db->bind(':concept_id', $ex['concept_id']);
    $db->bind(':title', $ex['title']);
    $db->bind(':content', $ex['content']);
    $db->bind(':starter_code', $ex['starter_code']);
    $db->bind(':test_cases', $ex['test_cases']);
    $db->bind(':solution_code', $ex['solution_code']);
    $db->execute();
}

echo "\n✅ Successfully seeded Worlds, Stages, Concepts, and initial Exercises!\n";
