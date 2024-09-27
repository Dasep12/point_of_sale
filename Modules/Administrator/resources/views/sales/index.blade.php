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

    #suggestions {
        padding: 4px;
        background: #726666;
        color: #FFF;
        font-size: 15px !important;
        cursor: pointer;
        display: none;
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
                    <button type="button" name="tloEnable" id="openModalBtn" onclick="CrudSales('create', '*')" class="btn btn-sm btn-outline-secondary"><i class="fa fa-plus"></i> Create</button>
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
</script>

@include('administrator::sales.partials.CrudSales')
<script>
    $("#jqGridMain").jqGrid({
        url: "{{ url('administrator/jsonSales') }}",
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
            label: 'Member',
            name: 'name_level',
            align: 'left',
        }, {
            label: 'Member',
            name: 'member_id',
            align: 'left',
            hidden: true
        }, {
            label: 'Sub Total',
            name: 'sub_total',
            formatter: 'currency',
            formatoptions: {
                prefix: 'Rp ',
                suffix: '',
                thousandsSeparator: ','
            }
        }, {
            label: 'Discount',
            name: 'total_potongan',
            align: 'center',
            formatter: 'currency',
            formatoptions: {
                prefix: 'Rp ',
                suffix: '',
                thousandsSeparator: ','
            }
        }, {
            label: 'Total',
            name: 'total_bayar',
            align: 'center',
            formatter: 'currency',
            formatoptions: {
                prefix: 'Rp ',
                suffix: '',
                thousandsSeparator: ','
            }
        }, {
            label: 'Struk',
            name: 'total_bayar',
            align: 'center',
            width: 70,
            formatter: function(cellvalue, options, rowObject) {
                return `<a target="_blank" href='{{ url('administrator/jsonPrintStruck?no_trans=${rowObject.no_transaksi}') }}' class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-file-pdf-o"></i></a>`;
            },
        }, {
            label: 'Invoice',
            name: 'total_bayar',
            align: 'center',
            width: 70,
            formatter: function(cellvalue, options, rowObject) {
                return `<a target="_blank" href='{{ url('administrator/jsonPrintInvoice?no_trans=${rowObject.no_transaksi}') }}' class="btn btn-sm text-white btn-option badge-primary"><i class="fa fa-file-pdf-o"></i></a>`;
            },
        }, {
            label: 'Status',
            name: 'status_bayar',
            align: 'center',
            width: 70,
            formatter: function(cellvalue, options, rowObject) {
                var status = rowObject.status_bayar;
                var badge = rowObject.status_bayar == "lunas" ? 'badge-success' : 'badge-danger';
                return `<span class="badge ${badge}">${status}</span>`;
            },
        }, {
            label: 'Action',
            name: 'id',
            // width: 80,
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
        loadonce: false,
        viewrecords: true,
        rownumbers: true,
        rownumWidth: 30,
        autoresizeOnLoad: true,
        gridview: true,
        width: 780,
        height: 350,
        multiselect: false,
        rowNum: 20,
        rowList: [20, 50, 100],
        shrinkToFit: true,
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
        var subgrid_table_id = subgrid_id + "_t";
        $("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table>");
        $("#" + subgrid_table_id).jqGrid({
            url: "{{ url('administrator/jsonDetailSales') }}",
            mtype: "GET",
            datatype: "json",
            postData: {
                id: row_id,
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
                name: 'out_stock',
                align: 'center',
                width: 60
            }, {
                label: 'Harga jual',
                name: 'harga_jual',
                align: 'center',
                width: 100,
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                },
            }, {
                label: 'Discount',
                name: 'discount',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                },
                width: 100
            }, {
                label: 'Total',
                name: 'total',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                },
                width: 100
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
            btn += `<button data-id="${btnid}" onclick="CrudSales('update','${btnid}')"  class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-success"><i class="fa fa-pencil"></i></button>`;
        <?php } ?>

        <?php if (CrudMenuPermission($MenuUrl, $user_id, 'delete')) { ?>
            btn += `<button  data-id="${btnid}" onclick="CrudSales('delete','${btnid}')" class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } else { ?>
            btn += `<button disabled class="btn btn-sm text-white btn-option badge-danger"><i class="fa fa-remove"></i></button>`;
        <?php } ?>
        return btn;
    }
    var modal = document.getElementById("modalCrudSales");
    var elem = document.documentElement;


    $('#modalCrudSales').on('shown.bs.modal', function() {
        $('#qty').trigger('focus');
        $("#jqGridSalesList").jqGrid('setGridWidth', $(".modal-dialog").width() * 0.98); //
    });


    // var elem = document.getElementById("modalCrudSales");

    function openFullscreen() {
        // $("#modalCrudSales").css("background", "#f9fbfd");
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            /* Firefox */
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Chrome, Safari & Opera */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE/Edge */
            elem.msRequestFullscreen();
        }
    }


    // List sales
    $("#jqGridSalesList").jqGrid({
        datatype: "local",
        data: [],
        colModel: [{
                name: 'id',
                label: 'Id',
                hidden: true,
                key: true,
            }, {
                label: 'Name Item',
                name: 'item_name',
            },
            {
                label: 'Kode Item',
                name: 'kode_item',
            },
            {
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
                label: 'Harga',
                name: 'harga_jual',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                }
            }, {
                label: 'Sub Total',
                name: 'subtotal',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                }
            }, {
                label: 'Discount',
                name: 'discount',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                }
            }, {
                label: 'Total',
                name: 'total',
                align: 'center',
                formatter: 'currency',
                formatoptions: {
                    prefix: 'Rp ',
                    suffix: '',
                    thousandsSeparator: ','
                }
            }, {
                label: 'Aksi',
                name: 'action',
                align: 'center',
                formatter: actionListMaterial
            }
        ],
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



    function CrudSales(act, id) {
        $("#CrudSalesAction").val(act);
        if (act == "create") {
            dataSales = [];
            reloadgridItem(dataSales);
            noTransaksi();
            $("#btnPrintStruk").attr("disabled", true);
            $("#btnCancel").attr("disabled", true);
            $("#btnReset").attr("disabled", true);
            $('#modalCrudSales').modal('show');
            var qty = document.getElementById("qty");
            qty.focus();
            $('#sub_total_pref').val('');
            $('#sub_total').val('');
            $('#total_potongan_pref').val('');
            $('#total_potongan').val('');
            $('#total_bayar_pref').val('');
            $('#total_bayar').val('');
            $('#uang_bayar_pref').val('');
            $('#uang_bayar').val('');
            $('#kembalian_pref').val('');
            $('#kembalian').val('');
            openFullscreen(); // Trigger fullscreen mode
            countPrice()
        } else if (act == "update") {
            detailList(id)
            $("#btnPrintStruk").attr("disabled", true);
            $("#btnCancel").attr("disabled", true);
            $("#btnReset").attr("disabled", true);
            $('#modalCrudSales').modal('show');
            var qty = document.getElementById("qty");
            qty.focus();
            var Grid = $('#jqGridMain'),
                type = Grid.jqGrid('getCell', id, 'type'),
                no_transaksi = Grid.jqGrid('getCell', id, 'no_transaksi'),
                tanggal = Grid.jqGrid('getCell', id, 'date_trans'),
                member_id = Grid.jqGrid('getCell', id, 'member_id'),
                idMaterialField = Grid.jqGrid('getCell', id, 'id');
            const date = new Date(tanggal);
            var month = date.getMonth() <= 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
            var years = date.getFullYear();
            var dates = date.getDate() <= 9 ? '0' + (date.getDate()) : date.getDate();
            const formattedDate = `${years }-${month}-${dates}`;
            $("#noTransaksi").val(no_transaksi);
            $("#dateTransaksi").val(formattedDate);
            $("#member_id").val(member_id)
        } else if (act == "delete") {
            $.confirm({
                title: 'Perhatian!',
                content: 'Hapus Transaksi ?',
                buttons: {
                    yes: {
                        btnClass: 'btn-danger',
                        action: function() {
                            $.ajax({
                                url: '{{ url("administrator/jsonDeleteSales") }}',
                                type: 'GET',
                                data: {
                                    'id': id,
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
        }
    }

    function CrudListItem(act, id) {
        if (act == "delete") {
            dataSales = dataSales.filter(item => item.id != id);
            reloadgridItem(dataSales);
            countPrice();
        }
    }

    function materialExists(idx) {
        return dataSales.some(function(el) {
            return el.id == idx;
        });
    }


    function removeMaterialWithId(arr, id) {
        const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
        if (objWithIdIndex > -1) {
            arr.splice(objWithIdIndex, 1);
        }
        return arr;
    }

    function countPrice() {
        // sub total
        let subtotalSum = dataSales.reduce((accumulator, currentItem) => accumulator + currentItem.subtotal, 0);

        // sub total potongan
        let totalPot = dataSales.reduce((accumulator, currentItem) => accumulator + currentItem.discount, 0);


        // SUB TOTAL
        var sub_total_pref = document.getElementById('sub_total_pref');
        var sub_total = document.getElementById('sub_total');
        formatRupiah(subtotalSum.toString(), sub_total_pref, sub_total);

        // TOTAL POTONGAN
        var total_potongan_pref = document.getElementById('total_potongan_pref');
        var total_potongan = document.getElementById('total_potongan');
        formatRupiah(totalPot.toString(), total_potongan_pref, total_potongan);

        // TOTAL BAYAR
        var totales = parseFloat(sub_total.value) - parseFloat(total_potongan.value);

        var total_bayar_pref = document.getElementById('total_bayar_pref');
        var total_bayar = document.getElementById('total_bayar');
        formatRupiah(totales.toString(), total_bayar_pref, total_bayar);
    }

    function detailList(idHeader) {
        $.ajax({
            url: '{{ url("administrator/jsonDetailSalesEdit") }}',
            method: "GET",
            type: 'GET',
            data: {
                'id': idHeader,
            },
            success: function(res) {
                var params = res;
                dataSales = [];
                for (let i = 0; i < params.length; i++) {
                    var data = {
                        id: params[i].item_id,
                        item_id: params[i].item_id,
                        item_name: params[i].item_name,
                        satuan_id: params[i].unit_id,
                        satuan: params[i].unit_name,
                        kode_item: params[i].kode_item,
                        merek: params[i].merek,
                        qty: params[i].out_stock,
                        harga_jual: params[i].harga_jual,
                        subtotal: (parseFloat(params[i].harga_jual) * parseFloat(params[i].out_stock)),
                        discount: params[i].discount,
                        total: (parseFloat(params[i].harga_jual) * parseFloat(params[i].out_stock)) - parseFloat(params[i].discount)
                    }
                    if (materialExists(params.item_id)) {
                        doSuccess('create', 'item sudah masuk list', 'error')
                    } else {
                        dataSales.push(data);
                    }
                }
                reloadgridItem(dataSales);
                countPrice();

            }
        })

    }
</script>

@endsection