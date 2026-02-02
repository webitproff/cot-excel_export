<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */
/**
 * Tool for exporting data from any table database Cotonti CMF to Excel
 * Plugin excel_export for Cotonti 0.9.26, PHP 8.4+
 * Filename: excel_export.tools.php
 * Purpose: Administration for the Plugin excel_export
 * Date: Feb 02Th, 2026
 * @package excel_export
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('excel_export', 'plug');
require_once cot_langfile('excel_export', 'plug');

if (!cot_auth('plug', 'excel_export', 'A')) {
    cot_die_message(403);
}
$adminTitle = $L['excel_export_title'];

$t = new XTemplate(cot_tplfile('excel_export.tools', 'plug', true));

$a = cot_import('a', 'G', 'TXT');

// Get all fields from table dynamically
$exportFields = [];
	$settedTable = $cfg['plugin']['excel_export']['export_table'] ?? '';
	$expectedTable = $db_x . $settedTable;
$table = $expectedTable;
if (!empty($table)) {
    $columns = $db->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        $fieldName = $column['Field'];
        $exportFields[$fieldName] = strtoupper($fieldName);
    }
}

// Сессии для сохранения данных формы
session_start();

// Обработка AJAX-запроса на экспорт
if ($a === 'export' && !empty($_POST['fields'])) {
    $selectedFields = [];
    foreach ((array) $_POST['fields'] as $field => $enabled) {
        if ($enabled) {
            $customName = isset($_POST['field_names'][$field]) ? $_POST['field_names'][$field] : '';
            $selectedFields[$field] = $customName !== '' ? $customName : strtoupper($field);
        }
    }

    cot_excel_export_log("Полученные имена полей из формы: " . json_encode($_POST['field_names']));
    cot_excel_export_log("Выбранные поля с кастомными названиями: " . json_encode($selectedFields));

    $filePath = cot_excel_export_process($selectedFields);

    if (strpos($filePath, 'Ошибка') === false && file_exists($filePath)) {
        $_SESSION['excel_export_field_names'] = $_POST['field_names'];
        $_SESSION['excel_export_fields'] = $_POST['fields'];
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'file_url' => $cfg['mainurl'] . '/plugins/excel_export/uploads/' . basename($filePath)]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $filePath]);
    }
    exit;
}

// Загрузка данных из сессии
$fieldNames = isset($_SESSION['excel_export_field_names']) ? $_SESSION['excel_export_field_names'] : [];
$selectedCheckboxes = isset($_SESSION['excel_export_fields']) ? $_SESSION['excel_export_fields'] : [];

// Render field selection form
foreach ($exportFields as $field => $label) {
    $defaultValue = isset($fieldNames[$field]) ? $fieldNames[$field] : '';
    $checked = isset($selectedCheckboxes[$field]) && $selectedCheckboxes[$field];
    $t->assign([
        'FIELD_NAME' => $field,
        'FIELD_LABEL' => $label,
        'FIELD_CHECKBOX' => cot_checkbox($checked, "fields[$field]", '', ['value' => 1]),
        'FIELD_NAME_INPUT' => cot_inputbox('text', "field_names[$field]", $defaultValue, ['size' => 30, 'placeholder' => strtoupper($field)])
    ]);
    $t->parse('MAIN.FIELDS');
}

// List of exported files
$uploadDir = $cfg['plugins_dir'] . '/excel_export/uploads/';
if (is_dir($uploadDir)) {
    $files = glob($uploadDir . '*.xlsx');
    foreach ($files as $file) {
        $fileName = basename($file);
        $fileUrl = $cfg['mainurl'] . '/plugins/excel_export/uploads/' . $fileName;
        $t->assign([
            'EXPORTED_FILE_NAME' => $fileName,
            'EXPORTED_FILE_URL' => $fileUrl,
            'EXPORTED_FILE_SIZE' => cot_build_filesize(filesize($file)),
            'EXPORTED_FILE_DATE' => date('Y-m-d H:i:s', filemtime($file))
        ]);
        $t->parse('MAIN.EXPORTED_FILES');
    }
}

$t->assign([
    'EXPORT_FORM_ACTION' => cot_url('admin', ['m' => 'other', 'p' => 'excel_export'], '', true), // Self URL
    'EXPORT_MAX_ROWS' => $cfg['plugin']['excel_export']['max_rows']
]);

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');
