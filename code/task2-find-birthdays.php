<?php

$address = '/code/birthdays.txt';

// Текущая дата в формате ДД-ММ
$currentDate = date('d-m');

$fileHandler = fopen($address, 'r');

if ($fileHandler === false) {
    echo "Не удалось открыть файл.";
    exit; 
}

echo "Сегодня день рождения у:\n";

// Читаем файл построчно
while (($line = fgets($fileHandler)) !== false) {
    $line = trim($line);
    if ($line === '') continue; // Пропускаем пустые строки

    $parts = explode(", ", $line);

    if (count($parts) < 2) {
        continue; // Пропускаем строки, которые не соответствуют формату
    }

    $name = $parts[0];
    $birthday = $parts[1];

    $dateParts = explode("-", $birthday);

    if (count($dateParts) < 3) {
        continue; // Пропускаем строки с некорректной датой
    }

    $birthdayDayMonth = $dateParts[0] . '-' . $dateParts[1];

    if ($birthdayDayMonth === $currentDate) {
        echo "$name\n";
    }
}

fclose($fileHandler);
?>
