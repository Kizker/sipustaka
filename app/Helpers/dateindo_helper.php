<?php

use CodeIgniter\I18n\Time;

function indo_day_name(string $english): string
{
    return [
        'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
    ][$english] ?? $english;
}

function format_tanggal_indo($dateTime): string
{
    $t = $dateTime instanceof Time ? $dateTime : Time::parse((string)$dateTime);
    return indo_day_name($t->format('l')) . ', ' . $t->format('d/m/Y');
}
