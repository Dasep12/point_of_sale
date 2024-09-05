@extends('administrator::layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Stock</h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Item..">
                        <span class="input-group-btn">
                            <button onclick="search()" class="btn-filter btn btn-secondary btn-sm" id="searchBtn" type="button"><i class="fa fa-search"></i> Search</button>
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
                    <div class="row col-md-12">
                        <button type="button" name="tloEnable" onclick="ReloadBarang()" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i> Refresh</button>
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

    $(document).ready(function() {

        $("#jqGridMain").jqGrid({
            url: "{{ url('administrator/jsonStock') }}",
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
                name: 'item_name',
                align: 'left',
            }, {
                label: 'Kode Item',
                name: 'kode_item',
                align: 'center',
                width: 70
            }, {
                label: 'Merek',
                name: 'merek',
                align: 'center',
            }, {
                label: 'Satuan',
                name: 'unit_code',
                align: 'center',
                width: 60,
            }, {
                label: 'Stock Minimum',
                name: 'stock_minimum',
                align: 'center',
                width: 90,
            }, {
                label: 'Sales',
                name: 'outStock',
                align: 'center',
                width: 50,
            }, {
                label: 'Buy',
                name: 'inStock',
                align: 'center',
                width: 50,
            }, {
                label: 'Existing Stock',
                name: 'Stock',
                align: 'center',
                width: 90,
            }, {
                label: 'Status',
                name: 'Stock',
                align: 'center',
                width: 80,
                formatter: function(cell, row, opt) {
                    var alert = opt.Stock < opt.stock_minimum ? 'danger' : '';
                    var status = opt.Stock < opt.stock_minimum ? 'Stock Minuns' : 'NORMAL';
                    return `<span class="badge badge-${alert}">${status}</span>`
                }
            }, {
                label: 'Updated At',
                name: 'updated_at',
                align: 'center',
                width: 140,
                formatter: "date",
                formatoptions: {
                    srcformat: "ISO8601Long",
                    newformat: "d F Y H:i:s"
                }
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
            width: '100%',
            rownumbers: true,
            rownumWidth: 30,
            rowNum: 15,
            height: 'auto',
            shrinkToFit: false,
            autowidth: true,
            pager: "#pager",
            loadComplete: function() {
                $(this).jqGrid('setGridWidth', $("#jqGridMain").closest(".ui-jqgrid").parent().width());
                $(window).on('resize', function() {
                    var gridWidth = $('#jqGridMain').closest('.ui-jqgrid').parent().width();
                    $('#jqGridMain').jqGrid('setGridWidth', gridWidth);
                }).trigger('resize');
            },

        });


        jQuery("#jqGridMain").jqGrid('setGroupHeaders', {
            useColSpanStyle: true,
            groupHeaders: [{
                startColumnName: 'outStock',
                numberOfColumns: 2,
                titleText: 'Trans'
            }, {
                startColumnName: 'item_name',
                numberOfColumns: 4,
                titleText: 'Item'
            }]
        });

    })
</script>
@endsection