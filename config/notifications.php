<?php

return [
    'near_expiration_days' => (int) env('NOTIFICATION_NEAR_EXPIRATION_DAYS', 30),
    'near_capacity_percent' => (int) env('NOTIFICATION_NEAR_CAPACITY_PERCENT', 80),
    'duplicate_window_hours' => (int) env('NOTIFICATION_DUPLICATE_WINDOW_HOURS', 24),
];
