<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Functions for exporting data to Excel in Cotonti using PhpSpreadsheet
 */

$pluginDir = $cfg['plugins_dir'] . '/excel_export';
$libPathPhpSpreadsheet = "$pluginDir/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet";
$libPathPsr = "$pluginDir/lib/psr/simple-cache/src/Psr/SimpleCache";
$libPathZipStream = "$pluginDir/lib/zipstream/src";
$libPathPhpEnum = "$pluginDir/lib/php-enum/src";
$logFile = "$pluginDir/logs/export.log";

// Autoloader for PhpSpreadsheet
spl_autoload_register(function (string $class) use ($libPathPhpSpreadsheet): void {
    if (str_starts_with($class, 'PhpOffice\\PhpSpreadsheet\\')) {
        $relativePath = substr($class, strlen('PhpOffice\\PhpSpreadsheet\\'));
        $file = $libPathPhpSpreadsheet . '/' . str_replace('\\', '/', $relativePath) . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            cot_excel_export_log("Файл не найден: $file");
            die("Класс $class не найден. Ожидаемый файл: $file");
        }
    }
});

// Autoloader for Psr\SimpleCache
spl_autoload_register(function (string $class) use ($libPathPsr): void {
    if (str_starts_with($class, 'Psr\\SimpleCache\\')) {
        $relativePath = substr($class, strlen('Psr\\SimpleCache\\'));
        $file = $libPathPsr . '/' . str_replace('\\', '/', $relativePath) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Autoloader for ZipStream
spl_autoload_register(function (string $class) use ($libPathZipStream): void {
    if (str_starts_with($class, 'ZipStream\\')) {
        $relativePath = substr($class, strlen('ZipStream\\'));
        $file = $libPathZipStream . '/' . str_replace('\\', '/', $relativePath) . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            cot_excel_export_log("Файл ZipStream не найден: $file");
        }
    }
});

// Autoloader for MyCLabs\Enum
spl_autoload_register(function (string $class) use ($libPathPhpEnum): void {
    if (str_starts_with($class, 'MyCLabs\\Enum\\')) {
        $relativePath = substr($class, strlen('MyCLabs\\Enum\\'));
        $file = $libPathPhpEnum . '/' . str_replace('\\', '/', $relativePath) . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            cot_excel_export_log("Файл PhpEnum не найден: $file");
        }
    }
});

require_once "$libPathPhpSpreadsheet/Spreadsheet.php";
require_once "$libPathPhpSpreadsheet/IOFactory.php";

/**
 * Логирование
 */
function cot_excel_export_log(string $message): void
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

/**
 * Экспорт данных в Excel (.xlsx) и возврат пути к файлу
 */
function cot_excel_export_process(array $selectedFields): string
{
    global $db, $cfg;

    cot_excel_export_log("Начало процесса экспорта");

    $pluginDir = $cfg['plugins_dir'] . '/excel_export';
    $expectedTable = $db->pages ?: 'cot_pages';
    $targetTable = $cfg['plugin']['excel_export']['export_table'] ?? $expectedTable;
    $maxRows = (int) ($cfg['plugin']['excel_export']['max_rows'] ?? 100);

    cot_excel_export_log("Целевая таблица: '$targetTable', Ожидаемая таблица: '$expectedTable'");

    if ($targetTable !== 'pages' && $targetTable !== $expectedTable) {
        cot_excel_export_log("Ошибка: Экспорт поддерживает только таблицу '$expectedTable', указана: '$targetTable'");
        return "Ошибка: Экспорт поддерживает только таблицу '$expectedTable'.";
    }

    $table = $expectedTable;

    if (empty($table)) {
        cot_excel_export_log("Ошибка: Таблица '$targetTable' не определена.");
        return "Ошибка: Таблица 'pages' не определена.";
    }

    if (empty($selectedFields)) {
        cot_excel_export_log("Ошибка: Не выбраны поля для экспорта.");
        return "Ошибка: Не выбраны поля.";
    }
    $dbFields = array_keys($selectedFields);
    $excelHeaders = array_values($selectedFields);
    cot_excel_export_log("Выбранные поля: " . implode(', ', $dbFields));

    $query = "SELECT " . implode(',', $dbFields) . " FROM $table";
    if ($maxRows > 0) {
        $query .= " LIMIT $maxRows";
    }
    cot_excel_export_log("Выполняется запрос: $query");
    try {
        $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        cot_excel_export_log("Ошибка базы данных: " . $e->getMessage());
        return "Ошибка: Запрос к базе данных не выполнен - " . $e->getMessage();
    }

    if (empty($result)) {
        cot_excel_export_log("Данные в таблице '$table' для выбранных полей не найдены.");
        return "Нет данных для экспорта.";
    }
    cot_excel_export_log("Получено строк: " . count($result));

    cot_excel_export_log("Создание новой таблицы");
    try {
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    } catch (Exception $e) {
        cot_excel_export_log("Ошибка создания таблицы: " . $e->getMessage());
        return "Ошибка: Не удалось создать таблицу - " . $e->getMessage();
    }

    cot_excel_export_log("Установка заголовков");
    $col = 'A';
    foreach ($excelHeaders as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    cot_excel_export_log("Заполнение данными");
    $rowNum = 2;
    foreach ($result as $row) {
        $col = 'A';
        foreach ($dbFields as $field) {
            $sheet->setCellValue($col . $rowNum, $row[$field]);
            $col++;
        }
        $rowNum++;
    }

    $fileName = "export_" . date('Y-m-d_H-i-s') . ".xlsx";
    $filePath = $pluginDir . '/uploads/' . $fileName;
    cot_excel_export_log("Генерация файла XLSX: $filePath");
    try {
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);
    } catch (Exception $e) {
        cot_excel_export_log("Ошибка записи файла: " . $e->getMessage());
        return "Ошибка: Не удалось сгенерировать XLSX - " . $e->getMessage();
    }

    cot_excel_export_log("Файл успешно сгенерирован: $filePath");
    return $filePath;
}
