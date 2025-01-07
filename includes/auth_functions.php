<?php
function hasPermission($permission) {
    // Check if user is admin first
    if (isAdmin()) {
        return true;
    }
    
    // Then check specific permissions
    if (!isset($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
        return false;
    }
    return isset($_SESSION['permissions'][$permission]) && $_SESSION['permissions'][$permission] == 1;
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}