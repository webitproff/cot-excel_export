<?php
/**
 * Tool for exporting data from any table database Cotonti CMF to Excel
 * Plugin excel_export for Cotonti 0.9.26, PHP 8.4+
 * Filename: excel_export.ru.lang.php
 * Purpose: Russian localizations strings for the Plugin excel_export
 * Date: Feb 02Th, 2026
 * @package excel_export
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL');

$L['cfg_export_table'] = 'Целевая таблица';
$L['cfg_export_table_hint'] = 'Укажите имя таблицы <strong>без префикса</strong>, например <code>pages</code> для экспорта данных из таблицы <strong>cot_pages</strong>';
$L['cfg_max_rows'] = 'Максимум строк';
$L['cfg_max_rows_hint'] = 'Максимальное число строк для экспорта. <br>Установите число 0 для экспорта без лимита строк.';
$L['info_title'] = 'Экспорт в Excel через PhpSpreadsheet';
$L['info_desc'] = 'Инструмент для экспорта данных из любой таблицы базы данных Cotonti CMF в Excel';
$L['info_notes'] = 'Используется библиотека PhpSpreadsheet версии 1.23.0 без Composer. Тестировалось на Cotonti 0.9.26 под PHP 8.4 & MySQL-8.0';

$L['excel_export_title'] = $L['info_title'];
$L['excel_export_subtitle'] = $L['info_desc'];

$L['excel_export_field_table'] = 'Поле в базе данных';
$L['excel_export_select'] = 'Выбрать';
$L['excel_export_field_excel'] = 'Название в Excel';
$L['excel_export_max_rows_label'] = 'Максимальное количество строк';
$L['excel_export_export'] = 'Экспортировать';
$L['excel_export_export_info'] = 'После нажатия кнопки экспорта, какую-то секунду-две не будет ничего происходить, взависимости от объема данных. На самом деле все хорошо, просто дождитесь завершения операции экспорта.';
