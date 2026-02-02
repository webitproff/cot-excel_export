<?php
/**
 * Tool for exporting data from any table of the Cotonti CMF database to Excel
 * Plugin excel_export for Cotonti 0.9.26, PHP 8.4+
 * Filename: excel_export.en.lang.php
 * Purpose: English localization strings for the Plugin excel_export
 * Date: Feb 02nd, 2026
 * @package excel_export
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

$L['cfg_export_table'] = 'Target table';
$L['cfg_export_table_hint'] = 'Specify the table name <strong>without prefix</strong>, for example <code>pages</code> to export data from the <strong>cot_pages</strong> table';
$L['cfg_max_rows'] = 'Maximum rows';
$L['cfg_max_rows_hint'] = 'Maximum number of rows to export. <br>Set to 0 to export without row limits.';

$L['info_title'] = 'Export to Excel via PhpSpreadsheet';
$L['info_desc'] = 'Tool for exporting data from any Cotonti CMF database table to Excel';
$L['info_notes'] = 'Uses PhpSpreadsheet library version 1.23.0 without Composer. Tested on Cotonti 0.9.26 with PHP 8.4 & MySQL 8.0';

$L['excel_export_title'] = $L['info_title'];
$L['excel_export_subtitle'] = $L['info_desc'];

$L['excel_export_field_table'] = 'Database field';
$L['excel_export_select'] = 'Select';
$L['excel_export_field_excel'] = 'Excel column name';
$L['excel_export_max_rows_label'] = 'Maximum number of rows';
$L['excel_export_export'] = 'Export';
$L['excel_export_export_info'] = 'After clicking the export button, nothing may appear to happen for a second or two, depending on the data volume. This is normal — please wait until the export process completes.';
