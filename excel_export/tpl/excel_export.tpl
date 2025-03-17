<!-- BEGIN: MAIN -->
<div class="container mt-4">
    <h2 class="mb-3">{PHP.L.excel_export_title}</h2>

    <!-- IF {PHP.cot_messages_count} > 0 -->
    <div class="alert alert-info messages">{PHP.out.messages}</div>
    <!-- ENDIF -->

    {FILE "{PHP.cfg.system_dir}/admin/tpl/warnings.tpl"}

    <h3 class="mt-4 mb-3">{PHP.L.excel_export_previous_exports}</h3>
    <!-- BEGIN: EXPORTED_FILES -->
    <div class="card mb-2 p-2">
        <a href="{EXPORTED_FILE_URL}" target="_blank" class="text-primary">{EXPORTED_FILE_NAME}</a> 
        <small class="text-muted">({EXPORTED_FILE_SIZE}, {EXPORTED_FILE_DATE})</small>
    </div>
    <!-- END: EXPORTED_FILES -->
    <!-- IF !{PHP.files} --> 
    <p class="text-muted">{PHP.L.excel_export_no_files}</p>
    <!-- ENDIF -->

    <form id="exportForm" action="{EXPORT_FORM_ACTION}" method="post" class="mt-4">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">{PHP.L.Field}</th>
                        <th scope="col">{PHP.L.Export}</th>
                        <th scope="col">{PHP.L.CustomName}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: FIELDS -->
                    <tr>
                        <td>{FIELD_LABEL}</td>
                        <td>{FIELD_CHECKBOX}</td>
                        <td>{FIELD_NAME_INPUT}</td>
                    </tr>
                    <!-- END: FIELDS -->
                </tbody>
            </table>
        </div>
        <p class="mt-3">{PHP.L.excel_export_max_rows_label}: <strong>{EXPORT_MAX_ROWS}</strong></p>
        <button type="submit" class="btn btn-primary">{PHP.L.excel_export_export}</button>
    </form>
</div>

<script>
document.getElementById('exportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    // Добавляем a=export к текущему URL
    var actionUrl = this.action + (this.action.includes('?') ? '&' : '?') + 'a=export';

    fetch(actionUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            var link = document.createElement('a');
            link.href = data.file_url;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            location.reload();
        } else {
            alert('Ошибка: ' + data.error);
        }
    })
    .catch(error => {
        alert('Ошибка экспорта: ' + error);
    });
});
</script>
<!-- END: MAIN -->
