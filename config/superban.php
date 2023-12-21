<?php

// config for LaravelSuperBan/SuperBan
return [
    'cache_driver' => env('SUPERBAN_CACHE_DRIVER', 'database'),
    'ban_duration' => env('SUPERBAN_DURATION', 1440), //1440 is the amount of minutes for which the user is banned for.
    'banned_response' => env('SUPERBAN_RESPONSE'),
];
