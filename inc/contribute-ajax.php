<?php
/**
 * 弃用，已经移至ajax.php
 * 此文件已弃用，禁止直接访问
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 禁止直接访问
header('HTTP/1.1 403 Forbidden');
echo 'Access Denied';
exit;
