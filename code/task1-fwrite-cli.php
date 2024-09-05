<?php

$address = '/code/birthdays.txt';

$name = readline("Введите имя: ");
$date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");

if (validateName($name) && validateDate($date)) {
    $data = $name . ", " . $date . "\r\n";

    $fileHandler = fopen($address, 'a');

    if ($fileHandler === false) {
        echo "Не удалось открыть файл для записи.";
        exit; // Завершаем выполнение скрипта в случае ошибки открытия файла
    }

    if (fwrite($fileHandler, $data)) {
        echo "Запись $data добавлена в файл $address";
    } else {
        echo "Произошла ошибка записи. Данные не сохранены";
    }

    fclose($fileHandler);
} else {
    echo "Введена некорректная информация";
}

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

