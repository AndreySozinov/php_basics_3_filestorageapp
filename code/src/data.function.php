<?php

function validateDate(string $date): bool {
    $dateBlocks = explode("-", $date);

    if (count($dateBlocks) < 3) {
        return false;
    }

    if (isset($dateBlocks[0]) && ($dateBlocks[0] > 31 || $dateBlocks[0] <= 0)) {
        return false;
    }

    if (isset($dateBlocks[1]) && ($dateBlocks[1] > 12 || $dateBlocks[1] <= 0)) {
        return false;
    }

    if (isset($dateBlocks[2]) && ($dateBlocks[2] > date('Y') || $dateBlocks[2] < 1900)) {
        return false;
    }

    return true;
}

function validateName(string $name): bool {
    if (trim($name) === '') {
        return false;
    }

    if (strlen($name) <= 3) {
        return false;
    }

    return true;
}

function findBirhdays(array $config) : string {
    $address = $config['storage']['address'];

    // Текущая дата в формате ДД-ММ
    $currentDate = date('d-m');

    $fileHandler = fopen($address, 'r');

    if ($fileHandler === false) {
        return "Не удалось открыть файл.";
        exit; 
    }

    $birthdays "Сегодня день рождения у:\n";

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
            $birthdays .= "$name\n";
        }
    }
    return $birthdays;

    fclose($fileHandler);
}

function deleteEntry(array $config) : string {
    $address = $config['storage']['address'];

    $searchTerm = readline("Введите имя или дату (в формате ДД-ММ-ГГГГ) для удаления строки: ");

    $fileHandler = fopen($address, 'r');

    if ($fileHandler === false) {
        return "Не удалось открыть файл.";
    }

    $lines = []; // Массив для хранения всех строк из файла
    $found = false; // Флаг для отслеживания, найдена ли строка

    while (($line = fgets($fileHandler)) !== false) {
        $line = trim($line);
        if ($line === '') continue;

        // Проверяем, содержит ли строка имя или дату и не добавляем ее в массив
        if (strpos($line, $searchTerm) !== false) {
            $found = true;
            continue;
        }

        $lines[] = $line;
    }

    fclose($fileHandler);

    // Если строка была найдена и удалена, переписываем файл
    if ($found) {
        $fileHandler = fopen($address, 'w');

        if ($fileHandler === false) {
            return "Не удалось открыть файл для записи.";
        }

        foreach ($lines as $line) {
            fwrite($fileHandler, $line . "\r\n");
        }

        fclose($fileHandler);

        return "Строка с \"$searchTerm\" удалена из файла $address.\n";
    } else {
        return "Строка с \"$searchTerm\" не найдена.\n";
    }
}