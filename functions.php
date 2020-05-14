<?php
require 'functions/Model/Submission.php';
require 'functions/Controllers/SubmissionController.php';

$submissionModel = new \OsomRecrutation\Models\Submission\Submission;
$submissionController = new \OsomRecrutation\Controllers\SubmissionController\SubmissionController;

function osom_recrutation_scripts() {
    wp_enqueue_style( 'bootstrap-css', get_template_directory_uri().'/css/bootstrap.min.css');
    wp_enqueue_style( 'style-css', get_template_directory_uri().'/css/style.css');
    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'osom_recrutation_scripts' );

function osom_recrutation_admin_scripts($hook) {
    // Only add to the edit.php admin page.
    // See WP docs.
    $current_screen = get_current_screen();

    if ($current_screen->id != "toplevel_page_form-submissions") {
        return;
    }
    wp_enqueue_script('admin-js', get_template_directory_uri() . '/js/admin.js');
}

add_action('admin_enqueue_scripts', 'osom_recrutation_admin_scripts');


function admin_menu_page() {
    add_menu_page(
        __( 'Zapisy formularza', 'my-textdomain' ),
        __( 'Zapisy formularza', 'my-textdomain' ),
        'manage_options',
        'form-submissions',
        'form_submissions_page',
        'dashicons-schedule',
        3
    );
};
add_action( 'admin_menu', 'admin_menu_page' );

function form_submissions_page() {
    ?>
    <h1>Zapisy formularza</h1>
    <table>
    <tr>
        <th>ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Login</th>
        <th>Email</th>
        <th>City</th>
    </tr>
    </table>
    <?php
}

