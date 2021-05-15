<?php

if (! function_exists('generate_color')) {
    function generate_color() {
        return '#' . dechex(rand(0x000000, 0xFFFFFF));
    }
}
