# Export to Excel via PhpSpreadsheet for Cotonti CMF

## Описание

### Экспорт статей в Excel из БД в Cotonti через PhpSpreadsheet 

**Название**: Export to Excel via PhpSpreadsheet  
**Версия**: 1.0.1  
**Дата создания**: 16 марта 2025
**Дата обновления**: 17 марта 2025 
**Автор**: cot_webitproff  

## Основные возможности
- Динамический выбор полей таблицы `cot_pages` для экспорта.
- Настройка пользовательских названий колонок в итоговом файле.
- Ограничение количества экспортируемых строк (настраивается в конфигурации).
- Сохранение экспортированных файлов в папке `uploads/` с возможностью последующего скачивания.
- Интерфейс в админке Cotonti с формой выбора полей и списком предыдущих экспортов.

Плагин для Cotonti CMF, который позволяет экспортировать данные из таблицы `cot_pages` в файл формата Excel (.xlsx). После экспорта файл автоматически скачивается на компьютер пользователя и сохраняется в папке `plugins/excel_export/uploads/`. На странице плагина отображается список ранее созданных файлов экспорта с возможностью их скачивания. Плагин использует библиотеку PhpSpreadsheet для генерации файлов Excel и работает без Composer.

### Зависимости и требования

    Cotonti: Версия 0.9.26 или выше.
    PHP: 8.2 или выше.
    Права записи в папки logs/ и uploads/.
    Доступ к uploads/ через веб-сервер.
	


### Используемые библиотеки
- **PhpSpreadsheet 1.23.0** — генерация файлов Excel (.xlsx).
- **ZipStream 2.4.0** — создание ZIP-архивов для .xlsx файлов.
- **myclabs/php-enum 1.8.4** — зависимость ZipStream для работы с перечислениями.
- **psr/simple-cache** — кэширование (зависимость PhpSpreadsheet).

---

## Структура плагина
```
└──/plugins/excel_export/
	├── excel_export.setup.php       # Файл конфигурации и установки плагина
	├── excel_export.tools.php       # Основной файл инструмента админки
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

## Порядок сборки и установки

1. **Скачайте плагин**:
   - Создайте папку `excel_export` в `/home/var/public_html/plugins/`.
   - Скопируйте все файлы плагина в эту папку (см. структуру выше).

2. **Установите библиотеки (уже включены в плагин, но на всякий)**:
   - **PhpSpreadsheet 1.23.0**:
     - Скачайте с [GitHub](https://github.com/PHPOffice/PhpSpreadsheet/releases/tag/1.23.0) файл `PhpSpreadsheet-1.23.0.zip`.
     - Распакуйте и скопируйте папку `src/PhpOffice/PhpSpreadsheet/` в `plugins/excel_export/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet/`.
   - **ZipStream 2.4.0**:
     - Скачайте с [GitHub](https://github.com/maennchen/ZipStream-PHP/releases/tag/2.4.0) файл `zipstream-php-2.4.0.zip`.
     - Распакуйте и скопируйте папку `src/` в `plugins/excel_export/lib/zipstream/src/`.
   - **myclabs/php-enum 1.8.4**:
     - Скачайте с [GitHub](https://github.com/myclabs/php-enum/releases/tag/1.8.4) файл `php-enum-1.8.4.zip`.
     - Распакуйте и скопируйте папку `src/` в `plugins/excel_export/lib/php-enum/src/`.
   - **psr/simple-cache**:
     - Скачайте с [GitHub](https://github.com/php-fig/simple-cache) или используйте версию из PhpSpreadsheet.
     - Скопируйте `src/Psr/SimpleCache/` в `plugins/excel_export/lib/psr/simple-cache/src/Psr/SimpleCache/`.

3. **Создайте необходимые папки**:
   - Создайте папку `logs`:
     ```
     mkdir -p /home/var/public_html/plugins/excel_export/logs
     chmod 755 /home/var/public_html/plugins/excel_export/logs ```
	 
	- Создайте папку uploads:
	```
	mkdir -p /home/var/public_html/plugins/excel_export/uploads
	chmod 755 /home/var/public_html/plugins/excel_export/uploads ```
	
4. **Установите плагин в Cotonti**:
        Зайдите в админку: "Управление сайтом / Расширения".
        Найдите "Export to Excel via PhpSpreadsheet" в списке.
        Нажмите "Установить".
		Настройки плагина для изменения конфигурации:
        export_table: Таблица для экспорта (по умолчанию pages, соответствует cot_pages).
        max_rows: Максимальное количество строк для экспорта (по умолчанию 100, установите 0 для снятия ограничения).
        Очистите кэш: "Управление сайтом / Кэш" → "Очистить кэш".
5. **Проверьте доступность uploads/**:
        Убедитесь, что папка uploads/ доступна через веб (например, https://example.com/plugins/excel_export/uploads/).
        Если доступ запрещён (403), настройте веб-сервер (например, уберите запрет в .htaccess).
		
##  Порядок и настройки экспорта в Excel

### Доступ к инструменту

    Управление сайтом / Расширения / Экспорт в Excel через PhpSpreadsheet (карточка расширения).
    Ссылка "Администрирование" ведёт на страницу: https://example.com/admin.php?m=other&p=excel_export где расположен интерфейс и инструменты экспорта.
	
	Выберите поля для экспорта:
        На странице отображается таблица со всеми полями из cot_pages (например, page_id, page_title, page_text и т.д.).
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
[2025-03-16 13:XX:XX] Starting export process
[2025-03-16 13:XX:XX] Target table: 'pages', Expected table: 'cot_pages'
[2025-03-16 13:XX:XX] Selected fields: page_id, page_title
[2025-03-16 13:XX:XX] Executing query: SELECT page_id, page_title FROM cot_pages LIMIT 100
[2025-03-16 13:XX:XX] Data fetched: X rows
[2025-03-16 13:XX:XX] Creating new Spreadsheet
[2025-03-16 13:XX:XX] Setting headers
[2025-03-16 13:XX:XX] Filling data
[2025-03-16 13:XX:XX] Generating XLSX to file: /home/var/public_html/plugins/excel_export/uploads/export_2025-03-16_13-XX-XX.xlsx
[2025-03-16 13:XX:XX] File generated successfully: /home/var/public_html/plugins/excel_export/uploads/export_2025-03-16_13-XX-XX.xlsx
```

Скачать бесплатно плагин экспорта данных в Excel (.xlsx) (использует библиотеку&nbsp;PhpSpreadsheet)

<h3><a href="https://github.com/webitproff/cot-excel_export"><strong>Публичный репозиторий на GitHub с открытым исходным кодом.</strong></a></h3>

<h3><a href="https://abuyfile.com/ru/forums/cotonti/custom/plugs/topic124"><strong>Тема поддержки, инструкции и скриншоты плагина на форуме</strong></a></h3>

<hr />
<p>Если вас интересует плагин импорта данных из Excel через PhpSpreadsheet - <a href="https://abuyfile.com/ru/forums/cotonti/custom/plugs/topic123"><strong>смотреть на странице плагина</strong></a></p>

*********
# EN
*********

## Description

### Exporting Articles to Excel from the Database in Cotonti via PhpSpreadsheet

**Name**: Export to Excel via PhpSpreadsheet  
**Version**: 1.0.1  
**Creation Date**: March 16, 2025  
**Update Date**: March 17, 2025  
**Author**: cot_webitproff  

## Key Features
- Dynamic selection of fields from the `cot_pages` table for export.
- Custom column name configuration in the resulting file.
- Limit on the number of exported rows (configurable in settings).
- Saving exported files to the `uploads/` folder with the option for subsequent downloading.
- Interface in the Cotonti admin panel with a field selection form and a list of previous exports.

A plugin for Cotonti CMF that enables exporting data from the `cot_pages` table into an Excel (.xlsx) file. After export, the file is automatically downloaded to the user’s computer and saved in the `plugins/excel_export/uploads/` folder. The plugin page displays a list of previously created export files with the ability to download them. The plugin uses the PhpSpreadsheet library to generate Excel files and operates without Composer.

### Dependencies and Requirements

    Cotonti: Version 0.9.26 or higher.
    PHP: 8.2 or higher.
    Write permissions for the `logs/` and `uploads/` folders.
    Web server access to the `uploads/` folder.

### Used Libraries
- **PhpSpreadsheet 1.23.0** — for generating Excel (.xlsx) files.
- **ZipStream 2.4.0** — for creating ZIP archives for .xlsx files.
- **myclabs/php-enum 1.8.4** — a dependency of ZipStream for working with enumerations.
- **psr/simple-cache** — caching (a dependency of PhpSpreadsheet).

---

## Plugin Structure
```
└──/plugins/excel_export/
├── excel_export.setup.php       # Plugin configuration and setup file
├── excel_export.tools.php       # Main admin tool file
├── inc/
│   └── excel_export.functions.php  # Export functions and library autoloader
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
│   └── export.log              # Export log for debugging
├── tpl/
│   └── excel_export.tools.tpl  # Template for the form and file list
└── uploads/                    # Folder for saving exported files
```
---

## Installation and Setup Instructions

1. **Download the Plugin**:
   - Create a folder `excel_export` in `/home/var/public_html/plugins/`.
   - Copy all plugin files into this folder (see structure above).

2. **Install Libraries (already included in the plugin, no additional download needed, but just in case)**:
   - **PhpSpreadsheet 1.23.0**:
     - Download from [GitHub](https://github.com/PHPOffice/PhpSpreadsheet/releases/tag/1.23.0) the file `PhpSpreadsheet-1.23.0.zip`.
     - Extract and copy the `src/PhpOffice/PhpSpreadsheet/` folder to `plugins/excel_export/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet/`.
   - **ZipStream 2.4.0**:
     - Download from [GitHub](https://github.com/maennchen/ZipStream-PHP/releases/tag/2.4.0) the file `zipstream-php-2.4.0.zip`.
     - Extract and copy the `src/` folder to `plugins/excel_export/lib/zipstream/src/`.
   - **myclabs/php-enum 1.8.4**:
     - Download from [GitHub](https://github.com/myclabs/php-enum/releases/tag/1.8.4) the file `php-enum-1.8.4.zip`.
     - Extract and copy the `src/` folder to `plugins/excel_export/lib/php-enum/src/`.
   - **psr/simple-cache**:
     - Download from [GitHub](https://github.com/php-fig/simple-cache) or use the version bundled with PhpSpreadsheet.
     - Copy `src/Psr/SimpleCache/` to `plugins/excel_export/lib/psr/simple-cache/src/Psr/SimpleCache/`.

3. **Create Necessary Folders**:
   - Create the `logs` folder:

mkdir -p /home/var/public_html/plugins/excel_export/logs
chmod 755 /home/var/public_html/plugins/excel_export/logs
text
- Create the `uploads` folder:

mkdir -p /home/var/public_html/plugins/excel_export/uploads
chmod 755 /home/var/public_html/plugins/excel_export/uploads
text
Or simply use a file manager like "FileZilla Client" or a similar remote access tool to create the `logs` and `uploads` folders if they don’t exist.

4. **Install the Plugin in Cotonti**:
- Go to the admin panel: "Site Management / Extensions".
- Find "Export to Excel via PhpSpreadsheet" in the list.
- Click "Install".
- Plugin settings for configuration adjustments:
- `export_table`: Table to export (default is `pages`, corresponds to `cot_pages`).
- `max_rows`: Maximum number of rows to export (default is 100, set to 0 to remove the limit).
- Clear the cache: "Site Management / Cache" → "Clear Cache".

5. **Verify `uploads/` Accessibility**:
- Ensure the `uploads/` folder is accessible via the web (e.g., https://example.com/plugins/excel_export/uploads/).
- If access is denied (403), configure the web server (e.g., remove restrictions in `.htaccess`).

## Export to Excel Instructions and Settings

### Accessing the Tool

Site Management / Extensions / Export to Excel via PhpSpreadsheet (extension card).
The "Administration" link leads to the page: https://example.com/admin.php?m=other&p=excel_export, where the export interface and tools are located.

- **Select Fields for Export**:
- The page displays a table with all fields from `cot_pages` (e.g., `page_id`, `page_title`, `page_text`, etc.).
- Check the boxes next to the fields you want to export.
- (Optional) Enter custom column names in the "Custom Name" field (if left blank, the field name in uppercase is used, e.g., `PAGE_ID`).

- **Export Data**:
- Click the "Export" button.
- The .xlsx file (e.g., `export_2025-03-16_13-XX-XX.xlsx`) will download to your computer.
- The same file will be saved in the `plugins/excel_export/uploads/` folder.

- **View Previous Exports**:
- Below the field selection form, the "Previous Exports" section lists all .xlsx files from `uploads/` with their names, sizes, and creation dates.
- Click a file name to download it again.

### Export Log Example

The log is recorded in `plugins/excel_export/logs/export.log` for debugging:
```
[2025-03-16 13:XX:XX] Starting export process
[2025-03-16 13:XX:XX] Target table: 'pages', Expected table: 'cot_pages'
[2025-03-16 13:XX:XX] Selected fields: page_id, page_title
[2025-03-16 13:XX:XX] Executing query: SELECT page_id, page_title FROM cot_pages LIMIT 100
[2025-03-16 13:XX:XX] Data fetched: X rows
[2025-03-16 13:XX:XX] Creating new Spreadsheet
[2025-03-16 13:XX:XX] Setting headers
[2025-03-16 13:XX:XX] Filling data
[2025-03-16 13:XX:XX] Generating XLSX to file: /home/var/public_html/plugins/excel_export/uploads/export_2025-03-16_13-XX-XX.xlsx
[2025-03-16 13:XX:XX] File generated successfully: /home/var/public_html/plugins/excel_export/uploads/export_2025-03-16_13-XX-XX.xlsx
```
Download the free Excel (.xlsx) data export plugin (uses the PhpSpreadsheet library) for free:

<h3><a href="https://github.com/webitproff/cot-excel_export"><strong>Public GitHub repository with open-source code.</strong></a></h3>

<h3><a href="https://abuyfile.com/ru/forums/cotonti/custom/plugs/topic124"><strong>Support thread, instructions, and plugin screenshots on the forum</strong></a></h3>

<hr />
<p>If you're interested in a plugin for importing data from Excel via PhpSpreadsheet - <a href="https://abuyfile.com/ru/forums/cotonti/custom/plugs/topic123"><strong>view it on the plugin page</strong></a></p>

<hr />

# RU
История изменений 17.03.2025. версия 1.0.1

1. Пофиксил сохранение пользователських названий колонок таблицы для создаваемого при экспорте файла Excel.

2. Теперь название полей можно писать на кириллице, на латинском+кириллица.
Например:
ID | ALIAS | Статус | Категория | PAGE_TITLE | TEXT-DESC | PAGE_PARSER | PAGE_OWNERID | PAGE_DATE | Дата обновления | Просмотры

Это позволяет легко и гибко подходить к экспорту, если файл Excel используется для импорта товаров в торговые площадки или интернет магазины, для людей, которые не знакомы с полями таблицы БД в Cotonti.

3. Расширено логирование на русском языке.

# English

Changelog 1.0.1 (March 17, 2025)
Revision history 03/17/2025. version 1.0.1

1. Fixed saving of custom column names of the table for the Excel file created during export.

2. Now the names of the fields can be written in Cyrillic, Latin + Cyrillic.
For example:
ID | ALIAS | Status | Category | PAGE_TITLE | TEXT-DESC | PAGE_PARSER | PAGE_OWNER ID | PAGE_DATE | Update date | Views

This allows for an easy and flexible approach to exporting if an Excel file is used to import products to marketplaces or online stores, for people who are not familiar with the fields of the Cotonti database table.

3. Advanced logging in Russian.
