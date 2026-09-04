<?php
// ============================================
// DATABASE CONFIGURATION
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_information_system');

// ============================================
// DATABASE CONNECTION FUNCTION
// ============================================
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// ============================================
// SESSION MANAGEMENT
// ============================================
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function checkLogin() {
    startSession();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.html');
        exit();
    }
}

// ============================================
// SECURITY FUNCTIONS
// ============================================
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// ============================================
// HELPER FUNCTIONS
// ============================================
function getStudentCount() {
    $conn = getConnection();
    $result = $conn->query("SELECT COUNT(*) as count FROM students");
    $count = $result->fetch_assoc()['count'];
    $conn->close();
    return $count;
}

function getCourseCount() {
    $conn = getConnection();
    $result = $conn->query("SELECT COUNT(*) as count FROM courses");
    $count = $result->fetch_assoc()['count'];
    $conn->close();
    return $count;
}

function getPaymentCount() {
    $conn = getConnection();
    $result = $conn->query("SELECT COUNT(*) as count FROM payments");
    $count = $result->fetch_assoc()['count'];
    $conn->close();
    return $count;
}

function getStudentName($id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT full_name FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $name = $result->fetch_assoc()['full_name'] ?? 'Unknown';
    $conn->close();
    return $name;
}
?>
