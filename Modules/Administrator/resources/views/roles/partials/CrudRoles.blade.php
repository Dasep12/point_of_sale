<div class="modal fade" id="modalCrudRoles" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudRoles">
                @csrf()
                <input type="text" hidden name="action" id="CrudRolesAction" />
                <input type="text" hidden name="id" id="id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="roleName">Name Role * :</label>
                                <input type="text" id="roleName" class="form-control" name="roleName" required />
                            </div>
                            <div class="form-group">
                                <label for="code_role">ID Role * :</label>
                                <input type="text" id="code_role" class="form-control" name="code_role" required />
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="status_role" name="status_role" class="checkeds" checked="checked" /> <label for="status_role"> Status *</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="">List Menu</label>
                            <table id="jqGridMainModal"></table>
                            <div id="pagerModal"></div>
                        </div>
                    </div>

                    <div id="CrudRolesError"></div>
                    <div id="CrudRolesAlertDelete"></div>
                    <hr />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-sm btn-title"></button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

<style>

</style>
<script>
    $(document).ready(function() {
        $("#jqGridMainModal").jqGrid({
            url: "{{ url('administrator/jsonDetailListMenu') }}",
            datatype: "json",
            mtype: "GET",
            postData: {
                id: "",
                "_token": "{{ csrf_token() }}",
            },
            colModel: [{
                label: 'ID',
                name: 'Menu_id',
                key: true,
                hidden: true,
            }, {
                label: '',
                name: 'statsMenu',
                align: 'center',
                formatter: function(value, opt, row) {
                    var isChecked = row.statsMenu == 1 ? 'checked' : ''
                    return `<input hidden type="text" name="allMenu[]" value ='${row.Menu_id}' /><input name="checkedMenu[]" value="${row.Menu_id }" type="checkbox" ${ isChecked }  role='checkbox' >`;
                },
                width: 20
            }, {
                label: 'Menu',
                name: 'MenuName',
                align: 'left',
                width: 100,
                formatter: function(value, opt, row) {
                    if (row.LevelNumber == 2) {
                        return `<span style="margin-left:20px !important;"><i class="fa fa-dot-circle-o"></i> ${row.MenuName}</span>`;
                    }
                    return `<span style="font-size:12.5px !important"><i class="${ row.MenuIcon }"></i> ${row.MenuName}</span>`;
                },
            }],
            jsonReader: {
                repeatitems: false,
                root: function(obj) {
                    return obj.rows;
                },
                page: function(obj) {
                    return obj.page;
                },
                total: function(obj) {
                    return obj.total;
                },
                records: function(obj) {
                    return obj.records;
                }
            },
            viewrecords: true,
            rownumbers: false,
            rownumWidth: 30,
            autoresizeOnLoad: true,
            gridview: true,
            height: 350,
            width: 500,
            rowNum: 50,
            // rowList: [10, 30, 50],
            pager: "#pagerModal",
            loadComplete: function() {
                var $grid = $("#jqGridMainModal");
                var colHeader = $grid.closest("div.ui-jqgrid-view")
                    .find("table.ui-jqgrid-htable th.ui-th-column")
                    .eq(1); // Adjust index if needed

                if (colHeader.find("input[type='checkbox']").length === 0) {
                    colHeader.html('<input type="checkbox" id="selectAll" />');
                }

                if ($("#CrudRolesAction").val() == "delete") {
                    $("input[type='checkbox']").prop("disabled", true);
                    $("input[type='checkbox'][role='checkbox']").prop("disabled", true);
                } else {
                    $("input[type='checkbox']").prop("disabled", false);
                    $("input[type='checkbox'][role='checkbox']").prop("disabled", false);
                }

                $grid.find("input[type='checkbox'][role='checkbox']").each(function() {
                    var isChecked = this.checked;
                    this.checked = isChecked;
                    $(this).closest("tr").toggleClass("ui-state-highlight", isChecked);
                });


                $("input[type='checkbox']").on("click", function() {
                    var isChecked = this.checked;
                    this.checked = isChecked;
                    $(this).closest("tr").toggleClass("ui-state-highlight", isChecked);
                });

                $("#selectAll").on("click", function() {
                    var isChecked = this.checked;
                    $grid.find("input[type='checkbox'][role='checkbox']").each(function() {
                        this.checked = isChecked;
                        $(this).closest("tr").toggleClass("ui-state-highlight", isChecked);
                    });
                });
            }
        });
    })





    function doSuccess(act, msg, theme) {
        const myNotification = window.createNotification({
            // options here
            displayCloseButton: true,
            theme: theme //success error , information , success
        });

        myNotification({
            title: 'Information',
            message: msg
        });
    }
    $("#formCrudRoles").parsley();
    $('#formCrudRoles').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();

        if (f.parsley().isValid()) {
            var formData = new FormData($('#formCrudRoles')[0]);
            var actions = $("#CrudRolesAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreateRoles") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdateRoles") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeleteRoles") }}';
            }

            $.ajax({
                url: url,
                type: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                async: false,
                success: function(data) {
                    if (data.msg == "success") {
                        $('#modalCrudRoles').modal('hide');
                        var act = $("#CrudRolesAction").val();
                        act = act.toLowerCase();
                        Reload();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudRolesError').html(errMsg);
                    }
                },
                error: function(xhr, desc, err) {
                    var respText = "";
                    try {
                        respText = eval(xhr.responseText);
                    } catch {
                        respText = xhr.responseText;
                    }

                    respText = unescape(respText).replaceAll("_n_", "<br/>")

                    var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div></div>'
                    $('#CrudRolesError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>