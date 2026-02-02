<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=global
 * [END_COT_EXT]
 */
/**
 * Tool for exporting data from any table database Cotonti CMF to Excel
 * Plugin excel_export for Cotonti 0.9.26, PHP 8.4+
 * Filename: excel_export.global.php
 * Purpose: Connect to hook "global" in Cotonti Core. Here is Required for Administration button after update plugin, else Administration button may be lost.
 * Date: Feb 02Th, 2026
 * @package excel_export
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('excel_export', 'plug');
