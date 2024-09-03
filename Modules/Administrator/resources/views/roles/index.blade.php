@extends('administrator::layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Roles</h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Roles..">
                        <span class="input-group-btn">
                            <button onclick="search()" id="searchBtn" class="btn-filter btn btn-secondary btn-sm" type="button"><i class="fa fa-search"></i> Search</button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="jqGridMain"></table>
                <div id="pager"></div>

                <hr>

                <div class="form-group">
                    @if(CrudMenuPermission($MenuUrl, $user_id, "add"))
                    <button type="button" name="tloEnable" onclick="CrudRoles('create','*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-plus"></i> Create</button>
                    @endif
                    <button type="button" name="tloEnable" onclick="Reload()" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<script>
    function Reload() {
        $("#jqGridMain").jqGrid('setGridParam', {
            datatype: 'json',
            mtype: 'GET',
            postData: {
                id: "",
                search: $("#searching").val()
            }
        }).trigger('reloadGrid');
    }

    function ReloadModalMenu(idRole) {
        $("#jqGridMainModal").jqGrid('setGridParam', {
            datatype: 'json',
            mtype: 'GET',
            postData: {
                id: idRole,
                search: $("#searching").val()
            }
        }).trigger('reloadGrid');
    }

    function search() {
        Reload()
        $("#searching").val("")
    }

    var input = document.getElementById("searching");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("searchBtn").click();
        }
    });
</script>
@include('administrator::roles.partials.CrudRoles')
<script>
    $(document).ready(function() {

        $("#jqGridMain").jqGrid({
            url: "{{ url('administrator/jsonRole') }}",
            datatype: "json",
            mtype: "GET",
            postData: {
                id: "",
                "_token": "{{ csrf_token() }}",
            },
            colModel: [{
                label: 'ID',
                name: 'id',
                key: true,
                hidden: true,
            }, {
                label: 'ID Role',
                name: 'code_role',
                align: 'center',
                width: 40,
            }, {
                label: 'Role Name',
                name: 'roleName',
                align: 'left',
                width: 60
            }, {
                label: 'Accessed',
                name: 'Accessed',
                align: 'left',
                width: 200
            }, {
                label: 'Date',
                name: 'created_at',
                align: 'center',
                formatter: "date",
                formatoptions: {
                    srcformat: "ISO8601Long",
                    newformat: "d M Y H:i:s"
                },
                width: 60
            }, {
                label: 'Action',
                name: 'id',
                align: 'center',
                width: 70,
                formatter: actionBarangFormatter
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
            width: 780,
            height: 350,
            rowNum: 10,
            rowList: [10, 30, 50],
            pager: "#pager",
            loadComplete: function(data) {
                $("#jqGridMain").parent().find(".no-data").remove(); // Remove the message if there is data
                if (data.records == 0) {
                    $("#jqGridMain").parent().append("<div class='d-flex justify-content-center no-data'><h3 class='text-secondary'>data not found</h3></div>");
                }
            },
        });



        function actionBarangFormatter(cellvalue, options, rowObject) {
            var btnid = options.rowId;
            var btn = "";
            <?php
            if (CrudMenuPermission($MenuUrl, $user_id, 'edit')) { ?>
                btn += `<button data-id="${btnid}" onclick="CrudRoles('update','${btnid}')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
            <?php } else { ?>
                btn += `<button disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
            <?php } ?>
            <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
                btn += `<button  data-id="${btnid}" onclick="CrudRoles('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
            <?php } else { ?>
                btn += `<button disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
            <?php } ?>
            return btn;
        }

        $(window).on('resize', function() {
            var gridWidth = $('#jqGridMain').closest('.ui-jqgrid').parent().width();
            $('#jqGridMain').jqGrid('setGridWidth', gridWidth);
        }).trigger('resize');
    })

    function loadFieldRoles(idx) {
        var Grid = $('#jqGridMain'),
            selRowId = Grid.jqGrid('getGridParam', 'selrow'),
            idRoles = Grid.jqGrid('getCell', idx, 'id'),
            roleName = Grid.jqGrid('getCell', idRoles, 'roleName'),
            code_role = Grid.jqGrid('getCell', idRoles, 'code_role');
        $("#id").val(idx)
        $("#roleName").val(roleName)
        $("#code_role").val(code_role)
    }

    function CrudRoles(action, idx) {

        if (action == "create") {
            document.getElementById("formCrudRoles").reset();
            $("#formCrudRoles .form-control").prop("disabled", false);
            $(".btn-title").html('<i class="fa fa-save"></i> Create')
            $("#titleModal").html('Create Units')
            $('#modalCrudRoles').modal('show');
            $('#CrudRolesError').html("");
            $("#CrudRolesAction").val('create');
            $("#CrudRolesAlertDelete").html('');
            ReloadModalMenu("")
            $("#formCrudRoles .form-control").attr("readonly", false)
        } else if (action == "update") {
            document.getElementById("formCrudRoles").reset();
            $(".btn-title").html('<i class="fa fa-save"></i> Update')
            $("#titleModal").html('Update Units')
            $('#modalCrudRoles').modal('show');
            $('#CrudRolesError').html("");
            $("#CrudRolesAction").val('update')
            ReloadModalMenu(idx)
            loadFieldRoles(idx)
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            $("#CrudRolesAlertDelete").html('');
            $("#formCrudRoles .form-control").attr("readonly", false)
        } else if (action == "delete") {
            document.getElementById("formCrudRoles").reset();
            $("#formCrudRoles .form-control").attr("readonly", true)
            loadFieldRoles(idx)
            ReloadModalMenu(idx)
            $(".btn-title").html('<i class="fa fa-trash"></i> Delete')
            $("#titleModal").html('Delete Units')
            $('#modalCrudRoles').modal('show');
            $('#CrudRolesError').html("");
            $("#CrudRolesAction").val('delete')
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            var errMsg = '<div class="col-md-12"><div class="alert alert-danger mt-2" role="alert"><span><b>Data Will Be Delete Permanently ,sure want delete ?</span></div></div>'
            $("#CrudRolesAlertDelete").html(errMsg)
        }
    }
</script>
@endsection