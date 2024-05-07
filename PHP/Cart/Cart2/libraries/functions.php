<?php
if (!function_exists('mb_str_replace')) {
    function mb_str_replace($needle, $text_replace, $haystack): string
    {
        return implode($text_replace, explode($needle, $haystack));
    }
}
if (!function_exists('mb_escape')) {
    function mb_escape(string $string): string
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $string);
    }
}