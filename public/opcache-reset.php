<?php
// public/opcache-reset.php — TEMPORARY: delete after use
if (php_sapi_name() === 'cli') {
    echo "Run this via HTTP only.\n";
    exit;
}
if (!function_exists('opcache_reset')) {
    echo "OPcache not available on this PHP build";
    exit;
}
$ok = opcache_reset();
if ($ok) {
    echo "OPcache reset OK\n";
} else {
    echo "OPcache reset FAILED\n";
}