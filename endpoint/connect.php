<?php

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

define( 'WP_USE_THEMES', false );

/** Loads the WordPress Environment and Template */
require '../../../../wp-blog-header.php';
require '../../../../wp-admin/includes/admin.php';
require '../vendor/autoload.php';

if (!isset($_POST['token'])) {
    status_header(400);
    echo "Token Missing";
    die();
}

$signer = new Sha256();
$token = (new Parser())->parse($_POST['token']);

$validate = new ValidationData();
$validate->setIssuer('http://cs310-parent.uni:8080');
$validate->setAudience('http://cs310-child.uni');

if (!$token->validate($validate)) {
    status_header(403);
    echo "Invalid Token";
    die();
}

/**
 * @var wpdb
 */
global $wpdb;
global $wp_version;

if ($token->getClaim("action") == "connect") {
    $option_name = (child())->_token . '_plugin_parent_token';
    if (get_option($option_name)) {
        status_header(403);
        echo json_encode(['message' => 'Invalid Action']);
        die();
    }
    update_option($option_name, $token->getClaim("key"));
    echo "[]";
    die();
}

try {
    switch ($token->getClaim("action")) {
        case "status":
            $updates = [
                "framework" => [
                    "php"       => phpversion(),
                    "database"  => $wpdb->db_version(),
                    "wordpress" => $wp_version
                ],
                "updates" => [
                    "core"      => get_core_updates(),
                    "plugin"    => get_plugin_updates(),
                    "theme"     => get_theme_updates()
                ]
            ];
            echo json_encode($updates);
            break;
        default:
            status_header(403);
            echo json_encode(['message' => 'Invalid Action']);
            die();
    }
} catch (OutOfBoundsException $e) {
    status_header(403);
    echo json_encode(['message' => 'Invalid Token']);
    die();
}
