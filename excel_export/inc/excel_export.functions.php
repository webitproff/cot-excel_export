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
            cot_excel_export_log("File not found: $file");
            die("Class $class not found. Expected file: $file");
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
            cot_excel_export_log("ZipStream file not found: $file");
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
            cot_excel_export_log("PhpEnum file not found: $file");
        }
    }
});

require_once "$libPathPhpSpreadsheet/Spreadsheet.php";
require_once "$libPathPhpSpreadsheet/IOFactory.php";

/**
 * Logging
 */
function cot_excel_export_log(string $message): void
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

/**
 * Export data to Excel (.xlsx) and return file path
 */
function cot_excel_export_process(array $selectedFields): string
{
    global $db, $cfg;

    cot_excel_export_log("Starting export process");

    $pluginDir = $cfg['plugins_dir'] . '/excel_export';
    $expectedTable = $db->pages ?: 'cot_pages';
    $targetTable = $cfg['plugin']['excel_export']['export_table'] ?? $expectedTable;
    $maxRows = (int) ($cfg['plugin']['excel_export']['max_rows'] ?? 100);

    cot_excel_export_log("Target table: '$targetTable', Expected table: '$expectedTable'");

    if ($targetTable !== 'pages' && $targetTable !== $expectedTable) {
        cot_excel_export_log("Error: Export supports only table '$expectedTable', specified: '$targetTable'");
        return "Error: Export supports only table '$expectedTable'.";
    }

    $table = $expectedTable;

    if (empty($table)) {
        cot_excel_export_log("Error: Table '$targetTable' is not defined.");
        return "Error: Table 'pages' is not defined.";
    }

    if (empty($selectedFields)) {
        cot_excel_export_log("Error: No fields selected for export.");
        return "Error: No fields selected.";
    }
    $dbFields = array_keys($selectedFields);
    $excelHeaders = array_values($selectedFields);
    cot_excel_export_log("Selected fields: " . implode(', ', $dbFields));

    $query = "SELECT " . implode(',', $dbFields) . " FROM $table";
    if ($maxRows > 0) {
        $query .= " LIMIT $maxRows";
    }
    cot_excel_export_log("Executing query: $query");
    try {
        $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        cot_excel_export_log("Database error: " . $e->getMessage());
        return "Error: Database query failed - " . $e->getMessage();
    }

    if (empty($result)) {
        cot_excel_export_log("No data found in table '$table' for selected fields.");
        return "No data to export.";
    }
    cot_excel_export_log("Data fetched: " . count($result) . " rows");

    cot_excel_export_log("Creating new Spreadsheet");
    try {
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    } catch (Exception $e) {
        cot_excel_export_log("Spreadsheet creation error: " . $e->getMessage());
        return "Error: Failed to create spreadsheet - " . $e->getMessage();
    }

    cot_excel_export_log("Setting headers");
    $col = 'A';
    foreach ($excelHeaders as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    cot_excel_export_log("Filling data");
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
    cot_excel_export_log("Generating XLSX to file: $filePath");
    try {
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);
    } catch (Exception $e) {
        cot_excel_export_log("Writer error: " . $e->getMessage());
        return "Error: Failed to generate XLSX - " . $e->getMessage();
    }

    cot_excel_export_log("File generated successfully: $filePath");
    return $filePath;
}