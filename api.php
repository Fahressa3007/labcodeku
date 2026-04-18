<?php
// =======================================================
// API ENDPOINT - Domain Vault
// Menangani: login, logout, check_session, CRUD vault
// =======================================================
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

function jsonOut($data) {
    echo json_encode($data);
    exit;
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonOut(['success' => false, 'message' => 'Belum login']);
    }
}

switch ($action) {

    case 'login': {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            jsonOut(['success' => false, 'message' => 'Username & password wajib diisi']);
        }

        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            jsonOut(['success' => true, 'username' => $user['username']]);
        }
        jsonOut(['success' => false, 'message' => 'Username atau password salah!']);
    }

    case 'logout': {
        $_SESSION = [];
        session_destroy();
        jsonOut(['success' => true]);
    }

    case 'check': {
        jsonOut([
            'logged_in' => isset($_SESSION['user_id']),
            'username'  => $_SESSION['username'] ?? null,
        ]);
    }

    case 'get_data': {
        requireAuth();
        $stmt = $pdo->query("SELECT id, domain, email, password, created_at 
                             FROM vault_data ORDER BY created_at DESC");
        jsonOut(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    case 'add_data': {
        requireAuth();
        $domain = trim($_POST['domain']   ?? '');
        $email  = trim($_POST['email']    ?? '');
        $pass   = $_POST['password']      ?? '';

        if ($domain === '' || $email === '' || $pass === '') {
            jsonOut(['success' => false, 'message' => 'Semua field wajib diisi']);
        }

        $stmt = $pdo->prepare("INSERT INTO vault_data (domain, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$domain, $email, $pass]);
        jsonOut(['success' => true, 'id' => $pdo->lastInsertId()]);
    }

    case 'delete_data': {
        requireAuth();
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) jsonOut(['success' => false, 'message' => 'ID tidak valid']);

        $stmt = $pdo->prepare("DELETE FROM vault_data WHERE id = ?");
        $stmt->execute([$id]);
        jsonOut(['success' => true]);
    }

    case 'update_data': {
        requireAuth();
        $id     = (int)($_POST['id'] ?? 0);
        $domain = trim($_POST['domain']   ?? '');
        $email  = trim($_POST['email']    ?? '');
        $pass   = $_POST['password']      ?? '';

        if ($id <= 0 || $domain === '' || $email === '' || $pass === '') {
            jsonOut(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $stmt = $pdo->prepare("UPDATE vault_data SET domain = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$domain, $email, $pass, $id]);
        jsonOut(['success' => true]);
    }

    default:
        http_response_code(400);
        jsonOut(['success' => false, 'message' => 'Action tidak dikenal']);
}
