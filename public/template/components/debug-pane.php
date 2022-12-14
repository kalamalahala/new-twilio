<?php

/**
 * Debug Pane
 */

// get current user
$current_user = wp_get_current_user();

// get user role
$user_role = $current_user->roles[0];

// debug array
$debug = array(
    'is_user_logged_in' => is_user_logged_in(),
    'is_admin' => is_admin(),
    'is_super_admin' => is_super_admin(),
    'user_role' => $user_role,
);

// all user meta
$user_meta = get_user_meta($current_user->ID);
foreach ($user_meta as $key => $value) {
    $debug['user_meta'][$key] = $value[0];
}

 ?>

<div class="container-fluid mx-3 p-3">
    <div class="row">
        <div class="col-12">
            <h3>Debug Pane</h3>
        </div>
        <div class="col-4" style="overflow:scroll">
            <h4>Debug</h4>
            <pre><?php print_r($debug); ?></pre>
        </div>
        <!-- current WP user -->
        <div class="col-4" style="overflow:scroll">
            <h4>Current User</h4>
            <pre><?php print_r($current_user); ?></pre>
        </div>
    </div>
</div>