<?php

$address = '/code/birthdays.txt';

$searchTerm = readline("Введите имя или дату (в формате ДД-ММ-ГГГГ) для удаления строки: ");

$fileHandler = fopen($address, 'r');

if ($fileHandler === false) {
    echo "Не удалось открыть файл.";
    exit;
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
        echo "Не удалось открыть файл для записи.";
        exit;
    }

    foreach ($lines as $line) {
        fwrite($fileHandler, $line . "\r\n");
    }

    fclose($fileHandler);

    echo "Строка с \"$searchTerm\" удалена из файла $address.\n";
} else {
    echo "Строка с \"$searchTerm\" не найдена.\n";
}

?>
