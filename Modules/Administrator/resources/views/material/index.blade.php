@extends('administrator::layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Material </h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Item or Kode Item..">
                        <span class="input-group-btn">
                            <button onclick="search()" id="searchBtn" class="btn-filter btn btn-secondary btn-sm" type="button"><i class="fa fa-search"></i> Search</button>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <!-- Button to Get Selected Row IDs -->
                <button class="btn btn-sm btn-outline-secondary" id="getSelectedIds"><i class="fa fa-barcode"></i> Export Barcode</button>

                <table id="jqGridMain"></table>
                <div id="pager"></div>

                <hr>

                <div class="form-group">
                    @if(CrudMenuPermission($MenuUrl, $user_id, "add"))
                    <button type="button" name="tloEnable" onclick="CrudMaterial('create','*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-plus"></i> Create</button>

                    <button type="button" name="tloEnable" onclick="CrudMaterial('upload','uploaditem')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-file-excel-o"></i> Upload Item</button>

                    <button type="button" name="tloEnable" onclick="CrudMaterial('upload','uploadharga')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-file-excel-o"></i> Upload Price</button>
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
    $(document).on('show.bs.modal', '.modal', function() {
        const zIndex = 1040 + 10 * $('.modal:visible').length;
        $(this).css('z-index', zIndex);
        setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    });

    function ReloadBarang() {
        $("#jqGridMain").jqGrid('setGridParam', {
            datatype: 'json',
            mtype: 'GET',
            postData: {
                search: $("#searching").val()
            }
        }).trigger('reloadGrid');
    }

    function ReloadHargaBarang() {
        $("#jqGridMainPrice").jqGrid('setGridParam', {
            datatype: 'json',
            mtype: 'GET',
            postData: {
                material_id: $("#id").val(),
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
@include('administrator::material.partials.CrudMaterial')
@include('administrator::material.partials.CrudPrice')
@include('administrator::material.partials.UploadItem')
<script>
    var $grid = $("#jqGridMain").jqGrid({
        url: "{{ url('administrator/jsonMaterial') }}",
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
            label: 'Name Item',
            name: 'name_item',
            align: 'left',
            width: 100
        }, {
            label: 'Kode Item',
            name: 'kode_item',
            align: 'center',
            width: 70
        }, {
            label: 'Barcode',
            name: 'barcode',
            align: 'left',
            width: 80
        }, {
            label: 'Jenis',
            name: 'code_categories',
            align: 'center',
            width: 60
        }, {
            label: 'Merek',
            name: 'merek',
            align: 'center',
            width: 70
        }, {
            label: 'Satuan',
            name: 'satuan_dasar',
            align: 'center',
            width: 60
        }, {
            label: 'Konversi',
            name: 'konversi_satuan',
            align: 'center',
            width: 60
        }, {
            label: 'Item',
            name: 'tipe_item',
            align: 'center',
            width: 60
        }, {
            label: 'Serial',
            name: 'serial',
            align: 'center',
            width: 60
        }, {
            label: 'Location',
            name: 'location',
            align: 'center',
            width: 60
        }, {
            label: 'HPP',
            name: 'harga_pokok',
            align: 'center',
            width: 80,
            formatter: 'currency',
            formatoptions: {
                prefix: 'Rp ',
                suffix: '',
                thousandsSeparator: ','
            }
        }, {
            label: 'Stok Min',
            name: 'stock_minimum',
            align: 'center',
            width: 60
        }, {
            label: 'Status',
            name: 'status_item',
            align: 'center',
            width: 60,
            formatter: function(cellvalue, options, rowObject) {
                var status = rowObject.status_item == 1 ? 'Active' : 'Inactive';
                var badge = rowObject.status_item == 1 ? 'badge-success' : 'badge-danger';
                return `<span class="badge ${badge}">${status}</span>`;
            },
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
        loadonce: false,
        viewrecords: true,
        rownumbers: true,
        rownumWidth: 30,
        autoresizeOnLoad: true,
        gridview: true,
        width: 780,
        height: 350,
        multiselect: true,
        rowNum: 20,
        rowList: [20, 50, 100],
        shrinkToFit: false,
        pager: "#pager",
        loadComplete: function(data) {
            // $(this).jqGrid('setGridWidth', $("#jqGridMain").closest(".ui-jqgrid").parent().width());
            $("#jqGridMain").parent().find(".no-data").remove(); // Remove the message if there is data
            if (data.records == 0) {
                $("#jqGridMain").parent().append("<div class='d-flex justify-content-center no-data'><h3 class='text-secondary'>data not found</h3></div>");
            }

            $(window).on('resize', function() {
                var gridWidth = $('#jqGridMain').closest('.ui-jqgrid').parent().width();
                $('#jqGridMain').jqGrid('setGridWidth', gridWidth);
            }).trigger('resize');
        }
    });

    // Export Barcode
    $("#getSelectedIds").click(function() {
        var selectedRows = $("#jqGridMain").jqGrid('getGridParam', 'selarrrow');
        if (selectedRows.length > 0) {
            console.log(selectedRows);
            $.ajax({
                url: "{{ url('administrator/barcodeGenerate') }}",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": selectedRows
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(res) {
                    console.log(res)
                    var blob = new Blob([res], {
                        type: 'application/pdf'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Barcode" + '.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            })
        }
    });

    $("#jqGridMainPrice").jqGrid({
        url: "{{ url('administrator/jsonMaterialPrice') }}",
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
            label: 'LEVEL ID',
            name: 'member_id',
            align: 'left',
            hidden: true
        }, {
            label: 'Member',
            name: 'name_level',
            align: 'left',
        }, {
            label: 'Member',
            name: 'hrg_jual',
            align: 'left',
            formatter: function(value, opt, row) {
                return row.harga_jual
            },
            hidden: true
        }, {
            label: 'Harga Jual',
            name: 'harga_jual',
            align: 'center',
            formatter: 'currency',
            formatoptions: {
                prefix: 'Rp ',
                suffix: '',
                thousandsSeparator: ','
            }
        }, {
            label: 'Date',
            name: 'created_at',
            align: 'left',
            width: 90,
            formatter: "date",
            formatoptions: {
                srcformat: "ISO8601Long",
                newformat: "d M Y H:i"
            }
        }, {
            label: 'Action',
            name: 'id',
            align: 'center',
            width: 70,
            formatter: actionHargaFormatter
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
        height: 250,
        rowNum: 10,
        rowList: [10, 30, 50],
        gridComplete: function() {
            resizeGrid()
        },
        loadComplete: function(data) {
            // $(this).jqGrid('setGridWidth', $("#jqGridMainPrice").closest(".ui-jqgrid").parent().width());
            $("#jqGridMainPrice").parent().find(".no-data").remove(); // Remove the message if there is data
            if (data.records === 0) {
                $("#jqGridMainPrice").parent().append("<div class='d-flex justify-content-center no-data'><h3 class='text-secondary'>data not found</h3></div>");
            }

            // Trigger grid resize when the window is resized
            $(window).on('resize', function() {
                resizeGrid();
            });
        }
    });

    jQuery("#jqGridMain").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [{
            startColumnName: 'name_item',
            numberOfColumns: 4,
            titleText: 'Item'
        }, {
            startColumnName: 'satuan_dasar',
            numberOfColumns: 2,
            titleText: 'Satuan Dasar'
        }, {
            startColumnName: 'tipe_item',
            numberOfColumns: 2,
            titleText: 'Tipe'
        }]
    });

    function actionBarangFormatter(cellvalue, options, rowObject) {
        var btnid = options.rowId;
        var btn = "";
        <?php
        if (CrudMenuPermission($MenuUrl, $user_id, 'edit')) { ?>
            btn += `<button data-id="${btnid}" onclick="CrudMaterial('update','${btnid}')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } ?>
        <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
            btn += `<button  data-id="${btnid}" onclick="CrudMaterial('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } ?>
        return btn;
    }

    function actionHargaFormatter(cellvalue, options, rowObject) {
        var btnid = rowObject.id;
        var btn = "";
        <?php
        if (CrudMenuPermission($MenuUrl, $user_id, 'edit')) { ?>
            btn += `<button type="button" onclick="CrudPrice('update','${btnid}')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } else { ?>
            btn += `<button type="button" disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } ?>
        <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
            btn += `<button type="button" onclick="CrudPrice('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } else { ?>
            btn += `<button type="button" disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } ?>
        return btn;
    }

    // Function to resize grid
    function resizeGrid() {
        $("#jqGridMainPrice").jqGrid('setGridWidth', $(".modal-dialog").width() * 0.8);
    }

    // Resize the grid when the modal is shown
    $('#modalCrudMaterial').on('shown.bs.modal', function() {
        resizeGrid()
    });



    function CrudMaterial(action, idx) {

        if (action == "create") {
            document.getElementById("formCrudMaterial").reset();
            $("#formCrudMaterial .form-control,#formCrudMaterial .checkeds").prop("disabled", false);
            $(".btn-title").html('<i class="fa fa-save"></i> Create')
            $("#titleModal").html('Create Material')
            $('#modalCrudMaterial').modal('show');
            $('#CrudMaterialError').html("");
            $("#CrudMaterialAction").val('create');
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            $("#CrudMaterialAlertDelete").html('');
            $("#material_id").val('');
            ReloadHargaBarang()
        } else if (action == "update") {
            document.getElementById("formCrudMaterial").reset();
            $(".btn-title").html('<i class="fa fa-save"></i> Update')
            $("#titleModal").html('Update Material')
            $('#modalCrudMaterial').modal('show');
            $('#CrudMaterialError').html("");
            $("#CrudMaterialAction").val('update')
            details(idx)
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            $("#CrudMaterialAlertDelete").html('');
        } else if (action == "delete") {
            document.getElementById("formCrudMaterial").reset();
            $(".btn-title").html('<i class="fa fa-trash"></i> Delete')
            $("#titleModal").html('Delete Material')
            $('#modalCrudMaterial').modal('show');
            $('#CrudMaterialError').html("");
            $("#CrudMaterialAction").val('delete')
            details(idx)
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            var errMsg = '<div class="col-md-12"><div class="alert alert-danger mt-2" role="alert"><span><b>Data Will Be Delete Permanently ,sure want delete ?</span></div></div>'
            $("#CrudMaterialAlertDelete").html(errMsg)
        } else if (action == "upload") {
            document.getElementById("formUploadItem").reset();
            $('#modalUploadItem').modal('show');
            $('#UploadItemError').html("");
            $(".form-control").removeClass("parsley-error");
            $(".parsley-required").html("");
            $("#actionUpload").val(idx);

            if (idx == "uploaditem") {
                $('#format_upload').attr('href', '{{ asset("document/format_upload_material.xlsx") }}');
            } else if (idx == "uploadharga") {
                $('#format_upload').attr('href', '{{ asset("document/format_upload_harga.xlsx") }}');
            }
        }
    }



    function CrudPrice(action, idx) {
        $('#CrudPriceError').html("");

        switch (action) {
            case "create":
                document.getElementById("formCrudPrice").reset();
                $("#material_id").val($("#id").val());
                $('#modalCrudPrice').modal('show');
                $("#CrudPriceAction").val(action);
                $(".btn-title-price").html('<i class="fa fa-save"></i> Create')
                break;
            case "update":
                document.getElementById("formCrudPrice").reset();
                var Grid = $('#jqGridMainPrice'),
                    selRowId = idx,
                    idPrice = Grid.jqGrid('getCell', selRowId, 'id'),
                    member_id = Grid.jqGrid('getCell', selRowId, 'member_id'),
                    hrg_jual = Grid.jqGrid('getCell', selRowId, 'hrg_jual'),
                    harga_jual = Grid.jqGrid('getCell', selRowId, 'harga_jual');
                $('#modalCrudPrice').modal('show');
                $("#CrudPriceAction").val(action);
                $("#idPrice").val(idPrice);
                $("#hrg_jual").val(hrg_jual);
                $("#member_id").val(member_id);
                $("#material_id").val($("#id").val());
                var formattedInput = document.getElementById('harga_jual_ex');
                var rawInput = document.getElementById('harga_jual');
                formatRupiah(hrg_jual, formattedInput, rawInput);
                $(".btn-title-price").html('<i class="fa fa-save"></i> Update')
                break;
            case "delete":
                document.getElementById("formCrudPrice").reset();
                $("#material_id").val($("#id").val());
                var Grid = $('#jqGridMainPrice'),
                    selRowId = idx,
                    idPrice = Grid.jqGrid('getCell', selRowId, 'id'),
                    member_id = Grid.jqGrid('getCell', selRowId, 'member_id'),
                    hrg_jual = Grid.jqGrid('getCell', selRowId, 'hrg_jual'),
                    harga_jual = Grid.jqGrid('getCell', selRowId, 'harga_jual');
                $('#modalCrudPrice').modal('show');
                $("#CrudPriceAction").val(action);
                $("#idPrice").val(idPrice);
                $("#hrg_jual").val(hrg_jual);
                $("#member_id").val(member_id);
                var formattedInput = document.getElementById('harga_jual_ex');
                var rawInput = document.getElementById('harga_jual');
                formatRupiah(hrg_jual, formattedInput, rawInput);
                $('#modalCrudPrice').modal('show');
                $("#CrudPriceAction").val(action);
                $(".btn-title-price").html('<i class="fa fa-trash"></i> Delete')
                break
        }
    }



    $("#parentUnitId").change(function() {
        loadChildUnits('*')
    })

    function loadChildUnits(idxChild) {
        $.ajax({
            url: "{{ url('administrator/jsonForListUnit') }}",
            mtype: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
                parent: $("#parentUnitId").val(),
            },
            success: function(e) {
                var data = e;
                $("select[name=unit_id]").empty();
                for (var i = 0; i < data.length; i++) {
                    $('select[name=unit_id]').append(
                        $('<option>', {
                            value: data[i].id,
                            text: data[i].name_unit
                        })
                    )
                }
                if (idxChild != "*") {
                    $("#unit_id").val(idxChild);
                }
            }
        })
    }

    function loadRakLocation(selectedLocationId) {
        $.ajax({
            url: '{{ url("administrator/jsonLocationMaterialByWarehouse") }}',
            data: {
                warehouse_id: $("#warehouse_id").val(),
            },
            success: function(data) {
                var $select = $('#location_id');
                $select.empty();
                $select.append('<option value="">Choose Location</option>');
                $.each(data, function(index, option) {
                    $select.append('<option value="' + option.id + '">' + option.location + '</option>');
                });

                if (selectedLocationId) {
                    $select.val(selectedLocationId);
                }
            }
        });
    }

    $("#warehouse_id").change(function() {
        var selectedLocationId = $("#location_id").val();
        loadRakLocation(selectedLocationId);
    })


    function details(idx) {
        $.ajax({
            url: '{{ url("administrator/jsonDetailMaterial") }}',
            type: 'POST',
            method: 'post',
            data: {
                id: idx,
                "_token": "{{ csrf_token() }}",
            },
            async: false,
            success: function(res) {
                var act = $("#CrudMaterialAction").val()
                $("#warehouse_id").val(res.warehouse_id).trigger('change');
                // Menunggu opsi lokasi dimuat sebelum mengatur nilai yang dipilih
                setTimeout(function() {
                    $("#location_id").val(res.location_id);
                }, 300);
                $("#categori_id").val(res.categori_id);
                $("#unit_id").val(res.unit_id);
                $("#name_item").val(res.name_item)
                $("#kode_item").val(res.kode_item)
                $("#barcode").val(res.barcode)
                $("#merek").val(res.merek)
                $("#satuan_dasar").val(res.satuan_dasar)
                $("#konversi_satuan").val(res.konversi_satuan)
                $("#tipe_item").val(res.tipe_item)
                $("#serial").val(res.serial)
                $("#stock_minimum").val(res.stock_minimum)
                var formattedInput = document.getElementById('hpp');
                var rawInput = document.getElementById('harga_pokok');
                formatRupiah(res.harga_pokok.toString(), formattedInput, rawInput);
                $("#remarks").val(res.remarks)
                $("#id").val(res.id)
                ReloadHargaBarang()
                res.status_material == 1 ? $('#status_material').prop('checked', true) : $('#status_material').prop('checked', false);
                if (act == "delete") {
                    $("#formCrudMaterial .form-control,#formCrudMaterial .checkeds").prop("disabled", true);
                } else {
                    $("#formCrudMaterial .form-control,#formCrudMaterial .checkeds").prop("disabled", false);
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
                $('#crudMaterialError').html(errMsg);
            },

        })
    }
</script>
@endsection