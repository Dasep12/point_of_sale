<div class="modal fade" id="modalCrudUsers" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-xl modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudUsers">
                @csrf()
                <input type="text" hidden name="action" id="CrudUsersAction" />
                <input type="text" hidden name="id" id="id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fullname">FullName * :</label>
                                <input type="text" id="fullname" class="form-control" name="fullname" required />
                            </div>
                            <div class="form-group">
                                <label for="username">Username * :</label>
                                <input type="text" id="username" class="form-control" name="username" required />
                            </div>
                            <div class="form-group">
                                <label for="email">Email * :</label>
                                <input type="email" id="email" class="form-control" name="email" required />
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone * :</label>
                                <input type="text" id="phone" class="form-control" name="phone" required />
                            </div>

                        </div>
                        <div class="col md-3">
                            <div class="form-group">
                                <label for="password">Password * :</label>
                                <input type="password" id="password" class="form-control" name="password" required />
                            </div>

                            <div class="form-group">
                                <label for="role_id">Type * :</label>
                                <select name="role_id" class="custom-select form-control" id="role_id">
                                </select>
                            </div>



                            <div class="form-group">
                                <input type="checkbox" value="1" id="status_user" name="status_user" class="checkeds" checked="checked" /> <label for="status_user"> Status *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">List Menu</label>
                            <table id="jqGridMainModal"></table>
                            <div id="pagerModal"></div>
                        </div>
                    </div>

                    <div id="CrudUsersError"></div>
                    <div id="CrudUsersAlertDelete"></div>
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
            url: "{{ url('administrator/jsonListMenuForUsers') }}",
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
                label: 'Menu',
                name: 'MenuName',
                align: 'left',
                width: 60,
                formatter: function(value, opt, row) {
                    if (row.LevelNumber == 2) {
                        return `<span style="margin-left:20px !important;"><i class="fa fa-dot-circle-o"></i> ${row.MenuName}</span>`;
                    }
                    return `<span style="font-size:12.5px !important"><i class="${ row.MenuIcon }"></i> ${row.MenuName}</span>`;
                },
            }, {
                label: 'Show Menu',
                name: 'statsMenu',
                align: 'center',
                formatter: function(value, opt, row) {
                    var isChecked = row.statsMenu == 1 ? 'checked' : '';
                    return `<input hidden type="text" name="idAccessMenu[]" value="${ row.id_accessMenu }" ><input data-target="${ row.id_accessMenu }" name="checkedMenu[]" value="${row.Menu_id }" type="checkbox" ${ isChecked }  role='showMenu' >`;
                },
                width: 20
            }, {
                label: 'Create',
                name: 'addMenu',
                align: 'center',
                formatter: function(value, opt, row) {
                    var isChecked, disabled;
                    if ($("#CrudUsersAction").val() == "create") {
                        isChecked = row.addMenu === 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : 'checked';
                    } else {
                        isChecked = row.addMenu == 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : '';
                    }
                    return `<input class="add-target-${ row.id_accessMenu }" name="addMenu[]" role='addMenu' ${isChecked} ${disabled} value="${row.id_accessMenu }" type="checkbox"  >`;
                },
                width: 13
            }, {
                label: 'Edit',
                name: 'editMenu',
                align: 'center',
                formatter: function(value, opt, row) {
                    var isChecked, disabled;
                    if ($("#CrudUsersAction").val() == "create") {
                        isChecked = row.editMenu === 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : 'checked';
                    } else {
                        isChecked = row.editMenu == 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : '';
                    }
                    return `<input ${ disabled } name="editMenu[]" value="${row.id_accessMenu }" type="checkbox" ${ isChecked }   >`;
                },
                width: 13
            }, {
                label: 'Delete',
                name: 'deleteMenu',
                align: 'center',
                formatter: function(value, opt, row) {
                    var isChecked, disabled;
                    if ($("#CrudUsersAction").val() == "create") {
                        isChecked = row.deleteMenu === 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : 'checked';
                    } else {
                        isChecked = row.deleteMenu == 1 ? 'checked' : '';
                        disabled = row.statsMenu != 1 ? 'disabled' : '';
                    }
                    return `<input ${ disabled } name="deleteMenu[]" value="${row.id_accessMenu }" type="checkbox" ${ isChecked }   >`;
                },
                width: 13
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
            rownumbers: true,
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
                $grid.find("input[type='checkbox'][role='showMenu']").each(function() {
                    var isChecked = this.checked;
                    this.checked = isChecked;
                    this.disabled = this.value != "1";
                });

            }
        });


        $("#role_id").change(function() {
            ReloadModalMenu($("#id").val(), $("#role_id").val())
        })
    })



    function GetlistRoles(query) {
        $.ajax({
            url: '{{ url("administrator/jsonForListRoles") }}',
            data: {
                q: query
            },
            success: function(data) {
                var $select = $('#role_id');
                $select.empty();
                $select.append('<option value="*">Please Select Types</option>');
                $.each(data, function(index, option) {
                    $select.append('<option value="' + option.id + '">' + option.roleName + '</option>');
                });
            }
        });
    }

    GetlistRoles("")


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
    $("#formCrudUsers").parsley();
    $('#formCrudUsers').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();

        if (f.parsley().isValid()) {
            var formData = new FormData($('#formCrudUsers')[0]);
            var actions = $("#CrudUsersAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreateUsers") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdateUsers") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeleteUsers") }}';
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
                        $('#modalCrudUsers').modal('hide');
                        var act = $("#CrudUsersAction").val();
                        act = act.toLowerCase();
                        Reload();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudUsersError').html(errMsg);
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
                    $('#CrudUsersError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>