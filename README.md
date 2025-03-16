# Export to Excel via PhpSpreadsheet for Cotonti CMF

## Описание

**Название**: Export to Excel via PhpSpreadsheet  
**Версия**: 1.0.0  
**Дата**: 16 марта 2025  
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

	Доступ к инструменту

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

Тема поддержки




