<?php
$time_list = [$hour_start = $_GET("hour_start"), $minute_start = $_GET("minute_start"), $hour_end = $_GET("hour_end"), $minute_end = $_GET("minute_end")];

foreach ($time_unit as $time_list) {
    if (strlen($time_unit) == 1):
        $time_unit = "0" . $time_unit;
}