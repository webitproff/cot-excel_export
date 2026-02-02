<?php
/* ====================
[BEGIN_COT_EXT]
Code=excel_export
Name=Export to Excel via PhpSpreadsheet
Description=Tool for exporting data from any table database Cotonti CMF to Excel
Version=2.0.1
Date=Feb 02Th, 2026
Author=webitproff
Copyright=(c) webitproff 2026 | https://github.com/webitproff
Notes=Uses PhpSpreadsheet 1.23.0 without Composer. Tested on Cotonti 0.9.26 with PHP 8.4 & MySQL-8.0
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=
Requires_modules=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
export_table=01:string::pages:Target table for import (e.g., 'pages' for cot_pages)
max_rows=02:string::100:Maximum rows to import per file (0 for unlimited)
[END_COT_EXT_CONFIG]
==================== */

/**
 * Tool for exporting data from any table database Cotonti CMF to Excel
 * Plugin excel_export for Cotonti 0.9.26, PHP 8.4+
 * Filename: excel_export.setup.php
 * Purpose: Setup & Config File. Register data in $db_core, $db_auth and $db_config for the Plugin excel_export
 * Date: Feb 02Th, 2026
 * @package excel_export
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

