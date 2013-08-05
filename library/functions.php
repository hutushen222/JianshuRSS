<?php

function str_start_with($needle, $haystack) {
    return !strncmp($haystack, $needle, strlen($needle));
}

function str_end_with($needle, $haystack) {
    $length = strlen($needle);
    if ($length == 0) { return true; }

    return (substr($haystack, -$length) === $needle);
}
