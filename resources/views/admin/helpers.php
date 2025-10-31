<?php
// helpers.php
function sort_recursive(&$item) {
    if (is_array($item)) {
        ksort($item);
        foreach ($item as &$v) {
            sort_recursive($v);
        }
    }
}

function canonical_json_from_string($raw) {
    // decode, sort keys recursively, encode without spaces
    $data = json_decode($raw, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }
    sort_recursive($data);
    // json_encode by default does not add spaces. Ensure unescaped slashes/unicode for parity.
    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function hash_equals_safe($a, $b) {
    if (function_exists('hash_equals')) return hash_equals($a, $b);
    // fallback
    if (strlen($a) !== strlen($b)) return false;
    $res = 0;
    for ($i = 0; $i < strlen($a); $i++) {
        $res |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $res === 0;
}
