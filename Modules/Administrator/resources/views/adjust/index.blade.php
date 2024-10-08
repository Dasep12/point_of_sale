@extends('administrator::layouts.master')

@section('content')
<style>
    .modal-dialog {
        max-width: 100% !important;
        margin: 0;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        display: flex;
    }

    .table-sm td,
    .table-sm th {
        padding: 1px !important;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search No Transaksi..">
                        <span class="input-group-btn">
                            <button id="searchBtn" onclick="search()" class="btn-filter btn btn-secondary btn-sm" type="button"><i class="fa fa-search"></i> Search</button>
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
                    <button type="button" name="tloEnable" id="openModalBtn" onclick="CrudAdjust('create', '*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-plus"></i> Create</button>

                    <button type="button" name="tloEnable" id="openModalBtn" onclick="CrudAdjust('upload', '*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-file-excel-o"></i> Upload</button>
                    @endif
                    <button type="button" name="tloEnable" onclick="ReloadBarang()" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<style>

</style>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



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
    // Prepare Data Sales
    var dataSales = [];
    var dataTemp = [];
</script>

@include('administrator::adjust.partials.CrudAdjust')

<script>
    $("#jqGridMain").jqGrid({
        url: "{{ url('administrator/jsonAdjust') }}",
        datatype: "json",
        mtype: "GET",
        postData: {
            "_token": "{{ csrf_token() }}",
        },
        colModel: [{
            label: 'ID',
            name: 'id',
            key: true,
            hidden: true,
        }, {
            label: 'No Transaksi',
            name: 'no_transaksi',
            align: 'left',
        }, {
            label: 'Tanggal',
            name: 'date_trans',
            align: 'left',
            formatter: "date",
            formatoptions: {
                srcformat: "ISO8601Long",
                newformat: "d M Y H:i:s"
            },
        }, {
            label: 'Total Item',
            name: 'total_item',
            align: 'center',
            width: 60
        }, {
            label: 'type',
            name: 'type',
            align: 'center',
            width: 60,
            hidden: true
        }, {
            label: 'User Id',
            name: 'created_by',
            align: 'center',
            width: 60,
        }, {
            label: 'Type Adjust',
            name: 'idx',
            align: 'center',
            width: 60,
            hidden: false,
            formatter: function(val, row, opt) {
                var res = opt.type == "in" ? 'Plus (+)' : "Minus (-)";
                return `${ res }`
            }
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
        pager: "#pager",
        subGrid: true,
        subGridRowExpanded: loadDetailMaterial,
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

    function loadDetailMaterial(subgrid_id, row_id) {
        // Function to load subgrid data
        var Grid = $('#jqGridMain'),
            type = Grid.jqGrid('getCell', row_id, 'type');

        var subgrid_table_id = subgrid_id + "_t";
        $("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table>");
        $("#" + subgrid_table_id).jqGrid({
            url: "{{ url('administrator/jsonListDetailAdjust') }}",
            mtype: "GET",
            datatype: "json",
            postData: {
                id: row_id,
                adjust_type: type,
                "_token": "{{ csrf_token() }}",
            },
            page: 1,
            colModel: [{
                label: 'ID',
                name: 'id',
                key: true,
                hidden: true,
            }, {
                label: 'Name Item',
                name: 'item_name',
                align: 'left',
            }, {
                label: 'Satuan',
                name: 'unit_name',
                align: 'center',
                width: 60
            }, {
                label: 'Qty',
                name: 'qty',
                align: 'center',
                width: 60
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
            height: '100%',
            rowNum: 20,
            pager: "#" + subgrid_id + "_p"
        });
    }


    function actionBarangFormatter(cellvalue, options, rowObject) {
        var btnid = options.rowId;
        var btn = "";
        <?php
        if (CrudMenuPermission($MenuUrl, $user_id, 'edit')) { ?>
            btn += `<button data-id="${btnid}" onclick="CrudAdjust('update','${btnid}','')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } ?>
        <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
            btn += `<button  data-id="${btnid}" onclick="CrudAdjust('delete','${btnid}','${rowObject.type}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } ?>
        return btn;
    }
    var modal = document.getElementById("modalCrudAdjust");
    var elem = document.documentElement;


    $('#modalCrudAdjust').on('shown.bs.modal', function() {
        $('#qty').trigger('focus');
        $("#jqGridSalesList").jqGrid('setGridWidth', $(".modal-dialog").width() * 0.98); //
    });




    // Function to open fullscreen mode
    function openFullscreen() {
        // if (elem.requestFullscreen) {
        //     elem.requestFullscreen();
        // } else if (elem.webkitRequestFullscreen) {
        //     /* Safari */
        //     elem.webkitRequestFullscreen();
        // } else if (elem.msRequestFullscreen) {
        //     /* IE11 */
        //     elem.msRequestFullscreen();
        // }
    }


    // List Adjust
    $("#jqGridSalesList").jqGrid({
        datatype: "local",
        data: [],
        colModel: [{
            name: 'id',
            label: 'Id',
            hidden: true,
            key: true,
        }, {
            label: 'Item',
            name: 'item_name',
        }, {
            label: 'Kode Item',
            name: 'kode_item',
        }, {
            label: 'Merek',
            name: 'merek',
        }, {
            label: 'Satuan',
            name: 'satuan',
            align: 'center',
        }, {
            label: 'Qty',
            name: 'qty',
            align: 'center',
        }, {
            label: 'Aksi',
            name: 'action',
            align: 'center',
            formatter: actionListMaterial
        }],
        pager: "#pagerGridInboundSales",
        viewrecords: true,
        width: '100%',
        rownumbers: true,
        rownumWidth: 30,
        rowNum: 3,
        height: 'auto',
        shrinkToFit: true,
        autowidth: true,
        loadComplete: function(data) {
            $(window).on('resize', function() {
                var gridWidth = $('#jqGridSalesList').closest('.ui-jqgrid').parent().width();
                $('#jqGridSalesList').jqGrid('setGridWidth', gridWidth);
            }).trigger('resize');
        },
    });

    function actionListMaterial(values, options, rowObject) {
        var btnid = rowObject.id;
        var btn = '';
        btn += `<button type="button" data-id="${btnid}" onclick="CrudListItem('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger btnActionMaterial"><i class="fa fa-remove"></i></button>`;
        return btn;
    }

    function actionListUpload(values, options, rowObject) {
        var btnid = rowObject.id;
        var btn = '';
        btn += `<button type="button" data-id="${btnid}" onclick="CrudListItem('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger btnActionMaterial"><i class="fa fa-remove"></i></button>`;
        return btn;
    }


    function reloadgridItem(data) {
        // Clear existing data
        // Clear existing data
        $("#jqGridSalesList").jqGrid('clearGridData', true);
        $("#jqGridSalesList").jqGrid('setGridParam', {
            data: data
        });
        // Refresh the grid
        $("#jqGridSalesList").trigger('reloadGrid');
    }

    function reloadgridItemAdjustUpload(data) {
        // Clear existing data
        // Clear existing data
        $("#JqGridTempUpload").jqGrid('clearGridData', true);
        $("#JqGridTempUpload").jqGrid('setGridParam', {
            data: data
        });
        // Refresh the grid
        $("#JqGridTempUpload").trigger('reloadGrid');
    }



    function CrudAdjust(act, id) {
        if (act == "create") {
            dataSales = [];
            reloadgridItem(dataSales);
            noTransaksi();
            $("#CrudActionAdjust").val(act)
            $('#modalCrudAdjust').modal('show');
            var qty = document.getElementById("qty");
            qty.focus();
            $('#qty').val('');
            $('#barcode').val('');
            openFullscreen(); // Trigger fullscreen mode
        } else if (act == "update") {
            $('#modalCrudAdjust').modal('show');
            $("#CrudActionAdjust").val(act)
            var qty = document.getElementById("qty");
            qty.focus();
            $('#qty').val('');
            $('#barcode').val('');
            var Grid = $('#jqGridMain'),
                type = Grid.jqGrid('getCell', id, 'type'),
                no_transaksi = Grid.jqGrid('getCell', id, 'no_transaksi'),
                tanggal = Grid.jqGrid('getCell', id, 'date_trans'),
                idMaterialField = Grid.jqGrid('getCell', id, 'id');
            const date = new Date(tanggal);
            var month = date.getMonth() <= 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
            var years = date.getFullYear();
            var dates = date.getDate() <= 9 ? '0' + (date.getDate()) : date.getDate();
            const formattedDate = `${years }-${month}-${dates}`;
            $("#type_adjust").val(type);
            $("#noTransaksi").val(no_transaksi);
            $("#dateTransaksi").val(formattedDate);
            detailList(idMaterialField, type)
        } else if (act == "delete") {
            var Grid = $('#jqGridMain'),
                type = Grid.jqGrid('getCell', id, 'type');
            $.confirm({
                title: 'Perhatian!',
                content: 'Hapus Transaksi ?',
                buttons: {
                    yes: {
                        btnClass: 'btn-danger',
                        action: function() {
                            $.ajax({
                                url: '{{ url("administrator/jsonDeleteAdjust") }}',
                                type: 'GET',
                                data: {
                                    'id': id,
                                    'type': type,
                                    '_token': "{{ csrf_token() }}",
                                },
                                beforeSend: function() {
                                    document.getElementById("fullPageLoader").style.display = "block";
                                },
                                complete: function() {
                                    document.getElementById("fullPageLoader").style.display = "none";
                                },
                                success: function(response) {
                                    ReloadBarang()
                                }
                            })
                        }
                    },
                    no: {
                        btnClass: 'btn-blue',
                        action: function() {}
                    },
                }
            });
        } else if (act == "upload") {
            $('#CrudAdjustUploadModalUpload').modal('show');
            noTransaksiUpload()
        }
    }

    function CrudListItem(act, id) {
        if (act == "delete") {
            dataSales = dataSales.filter(item => item.id != id);
            dataTemp = dataTemp.filter(item => item.id != id);
            reloadgridItem(dataSales);
            reloadgridItemAdjustUpload(dataTemp);
        }
    }

    function materialExists(idx) {
        return dataSales.some(function(el) {
            return el.id == idx;
        });
    }


    function detailList(idHeader, type) {
        $.ajax({
            url: '{{ url("administrator/jsonDetailAdjust") }}',
            method: "GET",
            type: 'GET',
            data: {
                'id': idHeader,
                'type': type
            },
            success: function(res) {
                dataSales = [];


                for (let i = 0; i < res.length; i++) {
                    var params = res[i];
                    var data = {
                        id: params.item_id,
                        item_id: params.item_id,
                        item_name: params.item_name,
                        satuan_id: params.unit_id,
                        satuan: params.unit_name,
                        kode_item: params.kode_item,
                        merek: params.merek,
                        qty: params.qty,
                        hpp: 0,
                        total: 0
                    }
                    if (materialExists(params.item_id)) {
                        doSuccess('create', 'item sudah masuk list', 'error')
                    } else {
                        dataSales.push(data);
                    }
                }
                reloadgridItem(dataSales);
            }
        })

    }


    function removeMaterialWithId(arr, id) {
        const objWithIdIndex = arr.findIndex((obj) => obj.id == id);
        if (objWithIdIndex > -1) {
            arr.splice(objWithIdIndex, 1);
        }
        return arr;
    }

    function noTransaksiUpload() {
        $.ajax({
            url: '{{ url("administrator/jsonNoTransaksiAdjust") }}',
            method: "GET",
            type: 'GET',
            data: {

            },
            success: function(data) {
                var resp = data;
                $("#noTransaksiUpload").val(data)
            }
        })
    }
    noTransaksiUpload()
</script>
@include('administrator::adjust.partials.CrudAdjustUpload')
@endsection