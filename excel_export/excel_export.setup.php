<?php
/* ====================
[BEGIN_COT_EXT]
Code=excel_export
Name=Export to Excel via PhpSpreadsheet
Description=Tool for exporting data from Cotonti tables to Excel
Version=1.0.1
Date=2025-03-17
Author=cot_webitproff
Copyright=(c) 2025 cot_webitproff
Notes=Uses PhpSpreadsheet 1.23.0 without Composer. Tested on Cotonti 0.9.26 with PHP 8.2
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=
Requires_modules=page
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
export_table=01:string::pages:Target table for import (e.g., 'pages' for cot_pages)
max_rows=02:string::100:Maximum rows to import per file (0 for unlimited)
[END_COT_EXT_CONFIG]
==================== */

defined('COT_CODE') or die('Wrong URL');

