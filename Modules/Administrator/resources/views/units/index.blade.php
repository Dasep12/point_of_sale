@extends('administrator::layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Units</h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Unit..">
                        <span class="input-group-btn">
                            <button id="searchBtn" onclick="search()" class="btn-filter btn btn-secondary btn-sm" type="button"><i class="fa fa-search"></i> Search</button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- Button to Get Selected Row IDs -->
                <button style="display: none;" class="btn btn-sm btn-outline-danger" id="getSelectedIdxDelete"><i class="fa fa-trash"></i> Delete</button>
                <table id="jqGridMain"></table>
                <div id="pager"></div>

                <hr>

                <div class="form-group">
                    @if(CrudMenuPermission($MenuUrl, $user_id, "add"))
                    <button type="button" name="tloEnable" onclick="CrudUnits('create','*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-plus"></i> Create</button>
                    @endif
                    <button type="button" name="tloEnable" onclick="ReloadBarang()" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<script>
    function ReloadBarang() {
        $("#jqGridMain").jqGrid('setGridParam', {
            datatype: 'json',
            mtype: 'GET',
            postData: {
                id: "",
                search: $("#searching").val()
            }
        }).trigger('reloadGrid');
    }

    function search() {
        ReloadBarang()
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
@include('administrator::units.partials.CrudUnits')
<script>
    $(document).ready(function() {

        $("#jqGridMain").jqGrid({
            url: "{{ url('administrator/jsonUnits') }}",
            datatype: "json",
            mtype: "GET",
            postData: {
                id: "1",
                "_token": "{{ csrf_token() }}",
                parent: "*"
            },
            colModel: [{
                label: 'ID',
                name: 'id',
                key: true,
                hidden: true,
            }, {
                label: 'Name Unit',
                name: 'unit_name',
                align: 'left',
            }, {
                label: 'Code Unit',
                name: 'unit_code',
                align: 'left',
            }, {
                label: 'Remarks',
                name: 'remarks',
            }, {
                label: 'User Id',
                name: 'CreatedBy',
                align: 'CreatedBy',
                align: 'center',

            }, {
                label: 'Created',
                name: 'CreatedAt',
                align: 'center',
                formatter: "date",
                formatoptions: {
                    srcformat: "ISO8601Long",
                    newformat: "d M Y H:i:s"
                },
            }, {
                label: 'Status',
                name: 'status_unit',
                align: 'center',
                width: 40,
                formatter: function(cellvalue, options, rowObject) {
                    var status = rowObject.status_unit == 1 ? 'Active' : 'Inactive';
                    var badge = rowObject.status_unit == 1 ? 'badge-success' : 'badge-danger';
                    return `<span class="badge ${badge}">${status}</span>`;
                },
            }, {
                label: 'Action',
                name: 'id',
                width: 80,
                align: 'center',
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
            width: '100%',
            height: 350,
            rowNum: 10,
            rowList: [10, 30, 50],
            multiselect: true,
            pager: "#pager",
            shrinkToFit: true,
            loadComplete: function(data) {
                $("#jqGridMain").parent().find(".no-data").remove(); // Remove the message if there is data
                if (data.records === 0) {
                    $("#jqGridMain").parent().append("<div class='d-flex justify-content-center no-data'><h3 class='text-secondary'>data not found</h3></div>");
                }
                $(window).on('resize', function() {
                    var gridWidth = $('#jqGridMain').closest('.ui-jqgrid').parent().width();
                    $('#jqGridMain').jqGrid('setGridWidth', gridWidth);
                }).trigger('resize');
            },
        });


        function actionBarangFormatter(cellvalue, options, rowObject) {
            var btnid = options.rowId;
            var btn = "";
            <?php
            if (CrudMenuPermission($MenuUrl, $user_id, 'edit')) { ?>
                btn += `<button data-id="${btnid}" onclick="CrudUnits('update','${btnid}')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
            <?php } else { ?>
                btn += `<button disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
            <?php } ?>
            <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
                btn += `<button  data-id="${btnid}" onclick="CrudUnits('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
            <?php } else { ?>
                btn += `<button disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
            <?php } ?>
            return btn;
        }

        // Delete Multiple
        $('#jqGridMain').on('jqGridSelectRow jqGridSelectAll', function() {
            var selectedRows = $("#jqGridMain").jqGrid('getGridParam', 'selarrrow');
            if (selectedRows.length > 0) {
                document.getElementById("getSelectedIdxDelete").style.display = "block";

                $("#getSelectedIdxDelete").off('click').on('click', function() {
                    $.confirm({
                        title: 'Perhatian!',
                        content: 'Delete Warehouse ?',
                        buttons: {
                            yes: {
                                btnClass: 'btn-danger',
                                action: function() {
                                    $.ajax({
                                        url: "{{ url('administrator/jsonMultiDeleteUnits') }}",
                                        method: "GET",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "id": selectedRows
                                        },
                                        success: function(res) {
                                            if (res.success) {
                                                ReloadBarang();
                                                doSuccess('delete', 'success delete data', 'success')
                                            } else {
                                                doSuccess('delete', res.msg, 'error')
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

                                            var errMsg = ' Error ' + xhr.status + '!</b><br/>' + respText + '</small>'
                                            doSuccess('delete', errMsg, 'success')
                                        },
                                    })
                                }
                            },
                            no: {
                                btnClass: 'btn-blue',
                                action: function() {}
                            },
                        }
                    });
                })

            } else {
                document.getElementById("getSelectedIdxDelete").style.display = "none";
            }
        });

    })

    function CrudUnits(action, idx) {

        if (action == "create") {
            document.getElementById("formCrudUnits").reset();
            $("#formCrudUnits .form-control").prop("disabled", false);
            $(".btn-title").html('<i class="fa fa-save"></i> Create')
            $("#titleModal").html('Create Units')
            $('#modalCrudUnits').modal('show');
            $('#CrudUnitsError').html("");
            $("#CrudUnitsAction").val('create');
            $("#CrudUnitsAlertDelete").html('');
        } else if (action == "update") {
            document.getElementById("formCrudUnits").reset();
            $(".btn-title").html('<i class="fa fa-save"></i> Update')
            $("#titleModal").html('Update Units')
            $('#modalCrudUnits').modal('show');
            $('#CrudUnitsError').html("");
            $("#CrudUnitsAction").val('update')
            details(idx)
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            $("#CrudUnitsAlertDelete").html('');
        } else if (action == "delete") {
            document.getElementById("formCrudUnits").reset();
            $(".btn-title").html('<i class="fa fa-trash"></i> Delete')
            $("#titleModal").html('Delete Units')
            $('#modalCrudUnits').modal('show');
            $('#CrudUnitsError').html("");
            $("#CrudUnitsAction").val('delete')
            details(idx)
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            var errMsg = '<div class="col-md-12"><div class="alert alert-danger mt-2" role="alert"><span><b>Data Will Be Delete Permanently ,sure want delete ?</span></div></div>'
            $("#CrudUnitsAlertDelete").html(errMsg)
        }
    }

    function details(idx) {
        $.ajax({
            url: '{{ url("administrator/jsonDetailUnits") }}',
            type: 'POST',
            method: 'post',
            data: {
                id: idx,
                "_token": "{{ csrf_token() }}",
            },
            async: false,
            success: function(res) {
                var act = $("#CrudUnitsAction").val()
                $("#unit_name").val(res.unit_name)
                $("#unit_code").val(res.unit_code)
                $("#remarks").val(res.remarks)
                res.status_unit == 1 ? $('#status_unit').prop('checked', true) : $('#status_unit').prop('checked', false);
                $("#id").val(res.id)
                if (act == "delete") {
                    $("#formCrudUnits .form-control").prop("disabled", true);
                } else {
                    $("#formCrudUnits .form-control").prop("disabled", false);
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
                $('#CrudUnitsError').html(errMsg);
            },

        })
    }
</script>
@endsection