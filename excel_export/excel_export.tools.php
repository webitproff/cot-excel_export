<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('excel_export', 'plug');
require_once cot_langfile('excel_export', 'plug');

if (!cot_auth('plug', 'excel_export', 'W')) {
    cot_die_message(403);
}
$adminTitle = $L['excel_export_title'];

$t = new XTemplate(cot_tplfile('excel_export.tools', 'plug', true));

$a = cot_import('a', 'G', 'TXT');

// Get all fields from cot_pages dynamically
$exportFields = [];
$table = $db->pages ?? 'cot_pages';
if (!empty($table)) {
    $columns = $db->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        $fieldName = $column['Field'];
        $exportFields[$fieldName] = strtoupper($fieldName);
    }
}

if ($a === 'export' && !empty($_POST['fields'])) {
    $selectedFields = [];
    foreach ((array) $_POST['fields'] as $field => $enabled) {
        if ($enabled) {
            $customName = cot_import("field_names[$field]", 'P', 'TXT', 100);
            $selectedFields[$field] = $customName ?: strtoupper($field);
        }
    }

    $filePath = cot_excel_export_process($selectedFields);

    if (strpos($filePath, 'Error') === false && file_exists($filePath)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        // Не удаляем файл: unlink($filePath); убрано
        exit;
    } else {
        cot_message($filePath);
    }
}

// Render field selection form
foreach ($exportFields as $field => $label) {
    $t->assign([
        'FIELD_NAME' => $field,
        'FIELD_LABEL' => $label,
        'FIELD_CHECKBOX' => cot_checkbox(false, "fields[$field]", '', ['value' => 1]),
        'FIELD_NAME_INPUT' => cot_inputbox('text', "field_names[$field]", '', ['size' => 30, 'placeholder' => strtoupper($field)])
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
    'EXPORT_FORM_ACTION' => cot_url('admin', ['m' => 'other', 'p' => 'excel_export', 'a' => 'export'], '', true),
    'EXPORT_MAX_ROWS' => $cfg['plugin']['excel_export']['max_rows']
]);

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');