<?php
// supporter/permissions.php

if (!function_exists('is_super_admin')) {
    function is_super_admin(): bool {
        return isset($_SESSION['auth_user']['role_as']) && $_SESSION['auth_user']['role_as'] === 2;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool {
        return isset($_SESSION['auth_user']['role_as']) && $_SESSION['auth_user']['role_as'] === 1;
    }
}

if (!function_exists('is_officer')) {
    function is_officer(): bool {
        return isset($_SESSION['auth_user']['role_as']) && $_SESSION['auth_user']['role_as'] === 0;
    }
}

if (!function_exists('is_report_user')) {
    function is_report_user(): bool {
        global $con;
        $user_id = $_SESSION['auth_user']['user_id'] ?? 0;
        if (!$user_id) return false;
        
        $query = "SELECT is_report FROM users WHERE id = '$user_id' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            return (bool)$user['is_report'];
        }
        return false;
    }
}

if (!function_exists('user_unit_id')) {
    function user_unit_id(): int {
        return $_SESSION['auth_user']['unit_id'] ?? 0;
    }
}

if (!function_exists('can_create_incident')) {
    function can_create_incident(): bool {
        return is_officer();
    }
}

if (!function_exists('can_view_incident')) {
    function can_view_incident(): bool {
        return is_officer() || is_admin() || is_super_admin();
    }
}

if (!function_exists('can_edit_incident')) {
    function can_edit_incident(int $incident_unit_id): bool {
        if (is_super_admin()) {
            return true; // Super Admin can edit any incident
        }
        if (is_admin()) {
            return user_unit_id() === $incident_unit_id; // Admin can edit only their unit
        }
        return false; // Officer cannot edit others' incidents
    }
}

if (!function_exists('can_delete_incident')) {
    function can_delete_incident(int $incident_unit_id): bool {
        return is_super_admin();
    }
}

if (!function_exists('can_manage_users')) {
    function can_manage_users(): bool {
        return is_super_admin();
    }
}

if (!function_exists('can_view_reports')) {
    function can_view_reports(): bool {
        return is_admin() || is_super_admin() || is_report_user();
    }
}

if (!function_exists('get_visible_actions')) {
    function get_visible_actions(): array {
        $actions = [];
        
        if (is_super_admin()) {
            $actions = ['discuss', 'edit', 'delete', 'report'];
        } elseif (is_admin()) {
            $actions = ['discuss', 'report'];
        } else {
            $actions = ['edit'];
        }
        
        // Filter out report action if user doesn't have report permission
        if (in_array('report', $actions) && !is_report_user() && !is_super_admin()) {
            $actions = array_filter($actions, function($action) {
                return $action !== 'report';
            });
        }
        
        return $actions;
    }
}

if (!function_exists('render_action_button')) {
    function render_action_button($action, $incident_id): string {
        $id = (int)$incident_id;
        return match($action) {
            'discuss' => '<a href="incident_chat.php?id=' . $id . '" class="btn btn-success btn-sm">Discuss</a>',
            'edit'    => '<a href="incident_edit.php?id=' . $id . '" class="btn btn-info btn-sm">Assign</a>',
            'delete'  => '<button type="button" class="btn btn-danger btn-sm delete_btn" value="' . $id . '">Delete</button>',
            'report'  => (is_report_user() || is_super_admin()) 
                         ? '<a href="report.php?id=' . $id . '" class="btn btn-warning btn-sm">प्रतिवेदन</a>'
                         : '',
            default   => ''
        };
    }
}

if (!function_exists('get_action_label')) {
    function get_action_label($action): string {
        return match($action) {
            'discuss' => 'Inquiry',
            'edit'    => 'Edit',
            'delete'  => 'Delete',
            'report'  => (is_report_user() || is_super_admin()) ? 'Report' : '',
            default   => ''
        };
    }
}
?>