<?php
// includes/functions.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

// ================= AUTH FUNCTIONS =================
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isPetugas() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'petugas';
}

// Fungsi bantu buat arahin user ke dashboard miliknya masing-masing
function redirectBasedOnRole() {
    $role = $_SESSION['role'] ?? 'user';
    if ($role === 'admin') header("Location: admin/dashboard.php");
    elseif ($role === 'petugas') header("Location: petugas/dashboard.php");
    else header("Location: dashboard.php");
    exit;
}

function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('warning', 'Silakan login terlebih dahulu.');
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        setFlash('danger', 'Akses ditolak. Halaman ini khusus Admin.');
        redirectBasedOnRole();
    }
}

function requirePetugas() {
    if (!isPetugas()) {
        setFlash('danger', 'Akses ditolak. Halaman ini khusus Petugas.');
        redirectBasedOnRole();
    }
}

// FUNGSI BARU: Khusus User, Admin & Petugas dilarang masuk
function requireUser() {
    if (isAdmin() || isPetugas()) {
        setFlash('danger', 'Akses ditolak. Admin/Petugas tidak bisa melakukan transaksi user.');
        redirectBasedOnRole();
    }
}

// ================= UTILITY FUNCTIONS =================
function clean($data) {
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function setFlash($type, $msg) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_msg'] = $msg;
}

function getFlash() {
    if (isset($_SESSION['flash_msg'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $msg = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg'], $_SESSION['flash_type']);
        
        $icon = match($type) {
            'success' => 'fa-check-circle',
            'danger' => 'fa-times-circle',
            'warning' => 'fa-exclamation-triangle',
            default => 'fa-info-circle'
        };

        return "
        <div class='alert alert-{$type} alert-dismissible fade show d-flex align-items-center' role='alert'>
            <i class='fas {$icon} me-2'></i>
            <div>{$msg}</div>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    return '';
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// ================= PAGINATION FUNCTION =================
function getPagination($total, $limit, $page, $base_url) {
    $total_pages = ceil($total / $limit);
    if ($total_pages <= 1) return '';

    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    $prev_disabled = ($page <= 1) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $prev_disabled . '">
                <a class="page-link" href="' . $base_url . '&page=' . ($page - 1) . '" tabindex="-1">Previous</a>
              </li>';

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($page == $i) ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '">
                    <a class="page-link" href="' . $base_url . '&page=' . $i . '">' . $i . '</a>
                  </li>';
    }

    $next_disabled = ($page >= $total_pages) ? 'disabled' : '';
    $html .= '<li class="page-item ' . $next_disabled . '">
                <a class="page-link" href="' . $base_url . '&page=' . ($page + 1) . '">Next</a>
              </li>';

    $html .= '</ul></nav>';
    return $html;
}
?>