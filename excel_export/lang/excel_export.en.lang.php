<?php
defined('COT_CODE') or die('Wrong URL');

$L['cfg_export_table'] = 'Target Table';
$L['cfg_export_table_hint'] = 'Specify <code>pages</code> to export data, e.g., from cot_pages';
$L['cfg_max_rows'] = 'Maximum Rows';
$L['cfg_max_rows_hint'] = 'Maximum number of rows to export (0 — no limit)';
$L['info_title'] = 'Export to Excel via PhpSpreadsheet';
$L['info_desc'] = 'Tool for exporting data from Cotonti tables to Excel';
$L['info_notes'] = 'Uses PhpSpreadsheet library version 1.23.0 without Composer. Tested on Cotonti 0.9.26 with PHP 8.2';

$L['excel_export_title'] = 'Export to Excel via PhpSpreadsheet';
$L['excel_export_subtitle'] = 'Tool for exporting data from Cotonti tables to Excel';

$L['excel_export_field_table'] = 'Database Field';
$L['excel_export_select'] = 'Select';
$L['excel_export_field_excel'] = 'Excel Column Name';
$L['excel_export_max_rows_label'] = 'Maximum Number of Rows';
$L['excel_export_export'] = 'Export';