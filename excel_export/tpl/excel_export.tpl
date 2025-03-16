<!-- BEGIN: MAIN -->
<h2>{PHP.L.excel_export_title}</h2>
<!-- IF {PHP.cot_messages_count} > 0 -->
<div class="messages">{PHP.out.messages}</div>
<!-- ENDIF -->

<h3>{PHP.L.excel_export_previous_exports}</h3>
<!-- BEGIN: EXPORTED_FILES -->
<div>
    <a href="{EXPORTED_FILE_URL}" target="_blank">{EXPORTED_FILE_NAME}</a> 
    ({EXPORTED_FILE_SIZE}, {EXPORTED_FILE_DATE})
</div>
<!-- END: EXPORTED_FILES -->
<!-- IF !{PHP.files} --> 
<p>{PHP.L.excel_export_no_files}</p>
<!-- ENDIF -->
{FILE "{PHP.cfg.system_dir}/admin/tpl/warnings.tpl"}
<form action="{EXPORT_FORM_ACTION}" method="post">
    <table class="cells">
        <thead>
            <tr>
                <th>{PHP.L.Field}</th>
                <th>{PHP.L.Export}</th>
                <th>{PHP.L.CustomName}</th>
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
    <p>{PHP.L.excel_export_max_rows_label}: {EXPORT_MAX_ROWS}</p>
    <button type="submit">{PHP.L.excel_export_export}</button>
</form>

{MESSAGES}
<!-- END: MAIN -->


<div class="block">
    <h2>{PHP.L.excel_export_title}</h2>
    {FILE "{PHP.cfg.system_dir}/admin/tpl/warnings.tpl"}

    <form id="exportForm" action="{EXPORT_FORM_ACTION}" method="post">
        <table class="cells">
            <tr>
                <th>{PHP.L.excel_export_field_table}</th>
                <th>{PHP.L.excel_export_select}</th>
                <th>{PHP.L.excel_export_field_excel}</th>
            </tr>
            <!-- BEGIN: FIELDS -->
            <tr>
                <td>{FIELD_LABEL}</td>
                <td>{FIELD_CHECKBOX}</td>
                <td>{FIELD_NAME_INPUT}</td>
            </tr>
            <!-- END: FIELDS -->
            <tr>
                <td>{PHP.L.excel_export_max_rows_label}</td>
                <td colspan="2">{EXPORT_MAX_ROWS}</td>
            </tr>
            <tr>
                <td colspan="3">
                    <button type="submit">{PHP.L.excel_export_export}</button>
                </td>
            </tr>
        </table>
    </form>

    {MESSAGES}
</div>
