# Export to Excel via PhpSpreadsheet for Cotonti CMF

## Description: Exporting articles, forums, users, products, orders, and payments from a Cotonti-based website database to Excel

The **"excel_export"** plugin for Cotonti CMF is a simple and universal tool that **allows you to export data from any database table** into an Excel (.xlsx) file.
After export, the file is automatically downloaded to the user's computer and also saved in the `plugins/excel_export/uploads/` directory.
The plugin page displays a list of previously created export files with the ability to download them again.
The plugin uses the PhpSpreadsheet library to generate Excel files and works **without Composer**.

[![Version](https://img.shields.io/badge/version-2.0.1-green.svg)](https://github.com/webitproff/cot-excel_export/releases)
[![Cotonti Compatibility](https://img.shields.io/badge/Cotonti_Siena-0.9.26-orange.svg)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4-purple.svg)](https://www.php.net/releases/8_4_0.php)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://www.mysql.com/)
[![Bootstrap v5.3.8](https://img.shields.io/badge/Bootstrap-v5.3.8-blueviolet.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-BSD-blue.svg)](https://github.com/webitproff/cot-excel_export/blob/main/LICENSE)

## Important to read

1. To obtain exact table names when you do not currently have direct access to the database, you can use a similar tool called **"DB Structure Viewer"** for Cotonti CMF.  
   It provides flexible tools for viewing and exporting database fields, but with two key differences:
   - it does not use external libraries;
   - it does not export to Excel.

   This tool **displays all tables and fields in the database** and can export data to SQL, CSV, JSON, and PHP (as small arrays), **with a preview of data in all fields of all database tables**.
   **["DB Structure Viewer"](https://github.com/webitproff/cot-dbviewstructure)** and **["Export to Excel"](https://abuyfile.com/en/market/cotonti/plugs/excel-export)** are similar but serve different purposes.

2. Separate plugins are available for data import. Visit the **[Cotonti CMF Extensions Marketplace](https://abuyfile.com/market)** or browse my projects on **[GitHub](https://github.com/webitproff)**.

## Key features

- Dynamic selection of required fields from the selected table for export.
- Custom column names for the first row in the resulting Excel file.
- Limiting the number of exported rows (configurable in plugin settings).
- Saving exported files in the `uploads/` directory with the ability to download them later or simultaneously.
- Administrative interface in Cotonti with a field selection form and a list of previous export operations with download links.

## Dependencies and requirements

- Cotonti CMF: version 0.9.26 or higher.
- PHP: version 8.4 or higher.
- Write permissions for the `logs/` and `uploads/` directories.
- Web server access to the `uploads/` directory.

## Used libraries

- **PhpSpreadsheet 1.23.0** — generation of Excel (.xlsx) files.
- **ZipStream 2.4.0** — creation of ZIP archives for .xlsx files.
- **myclabs/php-enum 1.8.4** — ZipStream dependency for enum handling.
- **psr/simple-cache** — caching interface (PhpSpreadsheet dependency).

## Source libraries

- **PhpSpreadsheet 1.23.0**
  - GitHub release: https://github.com/PHPOffice/PhpSpreadsheet/releases/tag/1.23.0
  - Location: `plugins/excel_export/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet/`

- **ZipStream 2.4.0**
  - GitHub release: https://github.com/maennchen/ZipStream-PHP/releases/tag/2.4.0
  - Location: `plugins/excel_export/lib/zipstream/src/`

- **myclabs/php-enum 1.8.4**
  - GitHub release: https://github.com/myclabs/php-enum/releases/tag/1.8.4
  - Location: `plugins/excel_export/lib/php-enum/src/`

- **psr/simple-cache**
  - GitHub repository: https://github.com/php-fig/simple-cache
  - Location: `plugins/excel_export/lib/psr/simple-cache/src/Psr/SimpleCache/`

## Plugin structure

```
/plugins/excel_export/
├── excel_export.setup.php        # Plugin setup and configuration file
├── excel_export.tools.php        # Main admin tool file
├── excel_export.global.php       # Global hook connection (required)
├── inc/
│   └── excel_export.functions.php # Export logic and library autoloaders
├── lib/
│   ├── phpspreadsheet/
│   ├── psr/simple-cache/
│   ├── zipstream/
│   └── php-enum/
├── logs/
│   └── export.log                # Export debug log
├── tpl/
│   └── excel_export.tools.tpl    # Admin interface template
└── uploads/                      # Exported Excel files
```

## Installation procedure

### 1. Download the plugin
- Download the plugin source code and extract the archive.
- Copy the `excel_export` directory into the site's `plugins/` directory (e.g. `/home/var/public_html/plugins/`).
- It is recommended to upload files using FileZilla or another FTP client that allows you to verify file integrity.

### 2. Set directory permissions
- Set permissions to `755` for `plugins/excel_export/logs`.
- Set permissions to `755` for `plugins/excel_export/uploads`.

### 3. Install the plugin in Cotonti
- Go to the admin panel: **Site Management → Extensions**.
- Find **"Export to Excel via PhpSpreadsheet"**.
- Click **Install**.

Plugin configuration:
- **export_table**: database table name without prefix (default: `pages`, corresponds to `cot_pages`).
- **max_rows**: maximum number of exported rows (default: 100, set to `0` for unlimited).
- Clear cache: **Site Management → Cache → Clear cache**.

### 4. Verify uploads directory access
- Ensure that `uploads/` is accessible via the web (e.g. https://example.com/plugins/excel_export/uploads/).
- If access is denied (403), adjust your web server or `.htaccess` rules.

## Export workflow and configuration

### Select a table for export
Specify the database table (without prefix) in plugin settings, such as `pages`, `forum_posts`, `users`, or `market`.

### Access the export tool
Admin panel → Extensions → Export to Excel via PhpSpreadsheet → Administration.  
Direct URL example: `https://example.com/admin.php?m=other&p=excel_export`

### Select fields
- The page displays all fields of the selected table.
- Check the fields you want to export.
- Optionally set custom column names (uppercase field names are used by default).

### Export data
- Click **Export**.
- The `.xlsx` file is downloaded automatically.
- The same file is saved in `plugins/excel_export/uploads/`.

### View previous exports
- The **Previous Exports** section lists all generated `.xlsx` files.
- Click a file name to download it again.

## Example export log

The log file is stored at `plugins/excel_export/logs/export.log` and is used for debugging.

```
[2026-02-02 12:04:18] Starting export process
[2026-02-02 12:04:18] Target table: 'pages', Expected table: 'cot_pages'
[2026-02-02 12:04:18] Selected fields: page_id, page_title
[2026-02-02 12:04:18] Executing query: SELECT page_id, page_title FROM cot_pages LIMIT 100
[2026-02-02 12:04:18] Data fetched: X rows
[2026-02-02 12:04:18] Creating new Spreadsheet
[2026-02-02 12:04:18] Setting headers
[2026-02-02 12:04:18] Filling data
[2026-02-02 12:04:18] Generating XLSX to file: /plugins/excel_export/uploads/export_2026-02-02_12-04-18.xlsx
[2026-02-02 12:04:18] File generated successfully
```

## Plugin information

- **Name**: Export to Excel via PhpSpreadsheet
- **Version**: 2.0.1
- **Initial release**: March 16, 2025
- **Last update**: February 2, 2026
- **Author**: webitproff
- **Package**: excel_export
- **License**: BSD

## Download

- **GitHub repository**: https://github.com/webitproff/cot-excel_export
- **Support forum**: https://abuyfile.com/forums/cotonti/custom/plugs/topic124
- **Hire the author**: https://abuyfile.com/users/webitproff

  
_____

# Русский: Плагин Excel Export для Cotonti CMF

## Описание: Экспорт в Excel статей, форумов, пользователей, товаров, заказов и платежей из БД сайта на Cotonti 

Плагин "excel_export" для Cotonti CMF - это простой и универсальный инструмент, который **позволяет экспортировать данные из любой таблицы базы данных** в файл формата Excel (.xlsx). 
После экспорта файл автоматически скачивается на компьютер пользователя и сохраняется в папке `plugins/excel_export/uploads/`. 
На странице плагина отображается список ранее созданных файлов экспорта с возможностью их скачивания. 
Плагин использует библиотеку PhpSpreadsheet для генерации файлов Excel и работает без Composer.

## Важно к прочтению
1. Для получения точных названий таблиц, если у вас просто нет к ним доступа здесь и сейчас, можно использовать похожий инструмент "DB Structure Viewer" для Cotonti CMF, который также предоставляет гибкие инструменты для просмотра и экспорта полей из базы данных, но с важными двумя отличиями: 
- не использует внешних библиотек; 
- не экспортирует в Excel.  
Он **отображает все таблицы и поля в БД** и может экспортировать в sql, csv, json, а также в php в виде небольших массивов, **с предварительным просмотром данных в полях всех таблиц вашей БД**. 
"DB Structure Viewer" и "Export to Excel" это похожие, но разные инструменты для разных задач. 
2. Для импорта данных разработанны другие плагины, посетите **[Маркетплейс расширений для Cotonti CMF](https://abuyfile.com/market)** или смотрите мои разработки в репозиториях на **[GitHub](https://github.com/webitproff)**. 


## Основные возможности
- Динамический выбор нужных полей из нужной таблицы для экспорта.
- Настройка пользовательских названий колонок первой строки в итоговом файле Excel.
- Ограничение количества экспортируемых строк (настраивается в конфигурации).
- Сохранение экспортированных файлов в папке `uploads/` с возможностью последующего и/или одновременного скачивания.
- Интерфейс в админке Cotonti с формой выбора полей и списком предыдущих операций экспорта со ссылками на сохраненные файлы.


### Зависимости и требования

Cotonti: Версия 0.9.26+.
PHP: 8.4+.
Права записи в папки logs/ и uploads/.
Доступ к uploads/ через веб-сервер.
	

### Используемые библиотеки
- **PhpSpreadsheet 1.23.0** — генерация файлов Excel (.xlsx).
- **ZipStream 2.4.0** — создание ZIP-архивов для .xlsx файлов.
- **myclabs/php-enum 1.8.4** — зависимость ZipStream для работы с перечислениями.
- **psr/simple-cache** — кэширование (зависимость PhpSpreadsheet).

### Исходные библотеки
   - **PhpSpreadsheet 1.23.0**:
     - Репозиторий на [GitHub](https://github.com/PHPOffice/PhpSpreadsheet/releases/tag/1.23.0) файл `PhpSpreadsheet-1.23.0.zip`.
     - Расположение в структуре плагина `plugins/excel_export/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet/`.
   - **ZipStream 2.4.0**:
     - Репозиторий на [GitHub](https://github.com/maennchen/ZipStream-PHP/releases/tag/2.4.0) файл `zipstream-php-2.4.0.zip`.
     - Расположение в структуре плагина `plugins/excel_export/lib/zipstream/src/`.
   - **myclabs/php-enum 1.8.4**:
     - Репозиторий на [GitHub](https://github.com/myclabs/php-enum/releases/tag/1.8.4) файл `php-enum-1.8.4.zip`.
     - Расположение в структуре плагина `plugins/excel_export/lib/php-enum/src/`.
   - **psr/simple-cache**:
     - Репозиторий на [GitHub](https://github.com/php-fig/simple-cache) или используйте версию из PhpSpreadsheet.
     - Расположение в структуре плагина `plugins/excel_export/lib/psr/simple-cache/src/Psr/SimpleCache/`.
---

## Структура плагина
```
└──/plugins/excel_export/
	├── excel_export.setup.php       # Файл конфигурации и установки плагина
	├── excel_export.tools.php       # Основной файл инструмента админки
	├── excel_export.global.php      # Connect to hook "global" in Cotonti Core. Here is Required!
	├── inc/
	│   └── excel_export.functions.php  # Функции экспорта и автозагрузка библиотек
	├── lib/
	│   ├── phpspreadsheet/
	│   │   └── src/PhpOffice/PhpSpreadsheet/  # PhpSpreadsheet 1.23.0
	│   ├── psr/
	│   │   └── simple-cache/src/Psr/SimpleCache/  # PSR Simple Cache
	│   ├── zipstream/
	│   │   └── src/  # ZipStream 2.4.0
	│   └── php-enum/
	│       └── src/  # myclabs/php-enum 1.8.4
	├── logs/
	│   └── export.log              # Лог экспорта для отладки
	├── tpl/
	│   └── excel_export.tools.tpl  # Шаблон формы и списка файлов
	└── uploads/                    # Папка для сохранения экспортированных файлов
```	

---



## Порядок установки

### 1. **Скачайте плагин**:
   - Скачать исходный код плагина и распаковать архив.
   - Скопировать папку `excel_export` в папку `plugins` в корне вашего сайта, например `/home/var/public_html/plugins/`.
   - Копировать рекомендуется через FileZilla или другой инструмент, который позволяет контролировать потерю и пропуск файлов. Пропущенные файлы обязательно перезакачать. 
   - При закачивание файлов


### 2. **Права на необходимые папки**:
   - chmod 755 на папку `plugins/excel_export/logs`.
   - chmod 755 на папку `plugins/excel_export/uploads`.	 

	
### 3. **Установите плагин в Cotonti**:
        Зайдите в админку: "Управление сайтом / Расширения".
        Найдите "Export to Excel via PhpSpreadsheet" в списке.
        Нажмите "Установить".
		**Настройки плагина** для изменения конфигурации:
        export_table: **Таблица, которую будете использовать для экспорта** (по умолчанию pages, соответствует cot_pages).
        max_rows: Максимальное количество строк для экспорта (по умолчанию 100, установите 0 для снятия ограничения).
        Очистите кэш: "Управление сайтом / Кэш" → "Очистить кэш".
### 4. **Проверьте доступность uploads/**:
        Убедитесь, что папка uploads/ доступна через веб (например, https://example.com/plugins/excel_export/uploads/).
        Если доступ запрещён (403), настройте веб-сервер (например, уберите запрет в .htaccess).
		
##  Порядок и настройки экспорта в Excel

### Выберите таблицу для экспорта:
Прежде всего, в настройках конфигурации плагина нужно указать таблицу базы данных вашего сайта на Cotonti, которую будете использовать для экспорта. 
Имя таблицы, указывается без префикса, например: `pages`, `forum_posts`, `users`, `market` или любая другая, одна на ваш выбор, которая вам нужна для дальнейше работы в Excel после экспорта.

### Доступ к инструменту: кнопка "Администрирование"
Управление сайтом / Расширения / Экспорт в Excel через PhpSpreadsheet (карточка расширения).
Ссылка "Администрирование" ведёт на страницу: https://example.com/admin.php?m=other&p=excel_export где расположен интерфейс и инструменты экспорта.

Выберите поля для экспорта:
	На странице отображается таблица со всеми полями, например, при экспорте из cot_pages (например, page_id, page_title, page_text и т.д.).
	Поставьте галочку напротив полей, которые хотите экспортировать.
	(Опционально) Введите пользовательские названия колонок в поле "Custom Name" (если пусто, используется имя поля в верхнем регистре, например, PAGE_ID).
	
Экспортируйте данные:
	Нажмите кнопку "Экспортировать".
	Файл .xlsx (например, export_2025-03-16_13-XX-XX.xlsx) скачается на ваш компьютер.
	Этот же файл сохранится в папке plugins/excel_export/uploads/.
	
Просмотрите предыдущие экспорты:
	Ниже формы выбора полей отображается блок "Previous Exports".
	В нём перечислены все .xlsx файлы из uploads/ с названием, размером и датой создания.
	Щёлкните по названию файла, чтобы скачать его повторно.

###  Пример лога экспорта

Лог записывается в plugins/excel_export/logs/export.log для отладки:
```
[2026-02-02 12:04:18] Starting export process
[2026-02-02 12:04:18] Target table: 'pages', Expected table: 'cot_pages'
[2026-02-02 12:04:18] Selected fields: page_id, page_title
[2026-02-02 12:04:18] Executing query: SELECT page_id, page_title FROM cot_pages LIMIT 100
[2026-02-02 12:04:18] Data fetched: X rows
[2026-02-02 12:04:18] Creating new Spreadsheet
[2026-02-02 12:04:18] Setting headers
[2026-02-02 12:04:18] Filling data
[2026-02-02 12:04:18] Generating XLSX to file: /home/var/public_html/plugins/excel_export/uploads/export_2026-02-02_12-04-18.xlsx
[2026-02-02 12:04:18] File generated successfully: /home/var/public_html/plugins/excel_export/uploads/export_2026-02-02_12-04-18.xlsx
```


**Название**: Export to Excel via PhpSpreadsheet  
**Версия**: 2.0.1  
**Дата создания**: 16 марта 2025
**Дата обновления**: 02 февраль 2026 
**Автор**: webitproff  
**package**: excel_export
**copyright**: Copyright (c) webitproff 2026 | https://github.com/webitproff
**license**: BSD

### Скачать бесплатно плагин экспорта данных в Excel (.xlsx)

 **[Публичный репозиторий на GitHub с открытым исходным кодом](https://github.com/webitproff/cot-excel_export)**

 **[Тема поддержки плагина на форуме](https://abuyfile.com/forums/cotonti/custom/plugs/topic124)**
 
 **[Предложить мне задание](https://abuyfile.com/users/webitproff)**


