<?php

return [
    'target_menit' => [
        'Tinggi' => env('SLA_TARGET_TINGGI_MINUTES', 1440),
        'Sedang' => env('SLA_TARGET_SEDANG_MINUTES', 4320),
        'Rendah' => env('SLA_TARGET_RENDAH_MINUTES', 10080),
    ],
];