CREATE DATABASE IF NOT EXISTS cpp_gamified_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cpp_gamified_db;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'instructor', 'admin') DEFAULT 'student',
    total_xp INT DEFAULT 0,
    current_level INT DEFAULT 1,
    current_title_id INT NULL,
    avatar_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- 2. Educational Hierarchy (Worlds, Stages, Concepts)
CREATE TABLE IF NOT EXISTS worlds (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    order_index INT NOT NULL,
    xp_reward INT DEFAULT 500,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS stages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    world_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    order_index INT NOT NULL,
    xp_reward INT DEFAULT 100,
    required_pass_percentage DECIMAL(5,2) DEFAULT 70.00,
    FOREIGN KEY (world_id) REFERENCES worlds(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS concepts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stage_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE CASCADE
);

-- 3. Exercises
CREATE TABLE IF NOT EXISTS exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stage_id INT NOT NULL,
    concept_id INT NOT NULL,
    type ENUM('training', 'challenge', 'final_test') DEFAULT 'training',
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    starter_code TEXT NULL,
    test_cases JSON NOT NULL,
    solution_code TEXT NOT NULL,
    xp_reward INT NOT NULL,
    FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE CASCADE,
    FOREIGN KEY (concept_id) REFERENCES concepts(id) ON DELETE CASCADE
);

-- 4. Evaluation and AI Logic
CREATE TABLE IF NOT EXISTS submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    submitted_code TEXT NOT NULL,
    status ENUM('passed', 'failed', 'pending') DEFAULT 'pending',
    score INT DEFAULT 0,
    execution_time_ms INT NULL,
    attempt_number INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ai_feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    submission_id INT NOT NULL,
    score_given INT NOT NULL,
    logical_errors JSON NULL,
    complexity_feedback TEXT NULL,
    general_feedback TEXT NOT NULL,
    hints_provided JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE
);

-- 5. Gamification (Titles, Badges, XP)
CREATE TABLE IF NOT EXISTS titles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    condition_type VARCHAR(50) NOT NULL,
    condition_value VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS badges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon_url VARCHAR(255) NOT NULL,
    stage_id INT NULL,
    FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS xp_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    xp_amount INT NOT NULL,
    source_type ENUM('exercise', 'stage_completion', 'world_completion', 'daily_challenge') NOT NULL,
    source_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_badges (
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, badge_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_titles (
    user_id INT NOT NULL,
    title_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, title_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (title_id) REFERENCES titles(id) ON DELETE CASCADE
);

-- 6. Spaced Reinforcement
CREATE TABLE IF NOT EXISTS spaced_reinforcement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    concept_id INT NOT NULL,
    failure_count INT DEFAULT 0,
    last_score DECIMAL(5,2) NULL,
    next_review_date DATE NULL,
    status ENUM('mastered', 'needs_review', 'struggling') DEFAULT 'needs_review',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (concept_id) REFERENCES concepts(id) ON DELETE CASCADE
);
