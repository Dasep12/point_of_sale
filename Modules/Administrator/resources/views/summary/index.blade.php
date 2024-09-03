@extends('administrator::layouts.master')

@section('content')

<?php

use Illuminate\Support\Facades\DB;


?>
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Summary Stock</h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Material..">
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
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fa fa-external-link"></span> Export
                            </button>
                            <form id="form-filter" class="dropdown-menu  p-4 bg-light" style="width:320px">
                                <h6>Export Summary Stock</h6>
                                <div class="form-group form-group-sm">
                                    <!-- <label for="startdateFilter" class="col-form-label col-form-label-sm">Customers</label> -->
                                    <div class="input-group input-group-sm">
                                        <select id="customer_id" name="customer_id" style="font-size: 0.75rem !important;" class="form-control form-control-sm custom-select select2">
                                            <option value="*">*All Customers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <!-- <label for="startdateFilter" class="col-form-label col-form-label-sm">Material</label> -->
                                    <div class="input-group input-group-sm">
                                        <select id="material_id" name="material_id" style="font-size: 0.75rem !important;" class="form-control form-control-sm custom-select select2">
                                            <option value="*">*All Material</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-group-sm">
                                    <!-- <label for="startdateFilter" class="col-form-label col-form-label-sm">Date</label> -->
                                    <div class="input-group input-group-sm">
                                        <input id="startdateFilter" type="date" class="form-control input-daterange" placeholder="Start Date">
                                        <div class="input-group-append">
                                            <span class="input-group-text">To</span>
                                        </div>
                                        <input id="enddateFilter" type="date" class="form-control date" placeholder="End Date">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" id="ExportOption" name="ExportOption">
                                            <option value="pdf">PDF File</option>
                                            <option value="xls">Excel File</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" id="exportBtn" class="btn btn-sm btn-dark"><span class="fa fa-file-text-o"></span> Download</button>
                            </form>
                        </div>
                        <button type="button" name="tloEnable" onclick="ReloadBarang()" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i> Refresh</button>
                    </div>
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

    $(document).ready(function() {

        $("#jqGridMain").jqGrid({
            url: "{{ url('administrator/jsonSummary') }}",
            datatype: "json",
            mtype: "GET",
            postData: {
                "_token": "{{ csrf_token() }}",
                parent: "*"
            },
            colModel: [{
                label: 'ID',
                name: 'id',
                key: true,
                hidden: true,
            }, {
                label: 'Name Material',
                name: 'name_material',
                align: 'left',
                width: 140
            }, {
                label: 'Material Number',
                name: 'no_material',
                align: 'left',
                width: 150
            }, {
                label: 'Unique Id',
                name: 'uniqueId',
                align: 'center',
                width: 80
            }, {
                label: 'Unit',
                name: 'unit',
                align: 'center',
                width: 80,
                hidden: true
            }, {
                label: 'Units',
                name: 'units',
                align: 'center',
                width: 100
            }, {
                label: 'Packaging',
                name: 'packaging',
                align: 'center',
                width: 110
            }, {
                label: 'Qty Unit',
                name: 'QtyUnit',
                align: 'center',
                width: 90,
                hidden: true
            }, {
                label: 'Qty Units',
                name: 'QtyUnits',
                align: 'center',
                width: 100
            }, {
                label: 'Qty Packaging',
                name: 'QtyPackaging',
                align: 'center',
                width: 90
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
            },
            subGrid: true,
            subGridRowExpanded: loadDetailTransaksi

        });

        function loadDetailTransaksi(subgrid_id, row_id) {
            // Function to load subgrid data
            var subgrid_table_id = subgrid_id + "_t";
            var pager_id = "p_" + subgrid_table_id;
            $("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table><div id='" + pager_id + "' class='scroll'></div>");
            $("#" + subgrid_table_id).jqGrid({
                url: "{{ url('administrator/jsonDetailSummary') }}",
                mtype: "GET",
                datatype: "json",
                postData: {
                    id: row_id,
                    "_token": "{{ csrf_token() }}",
                },
                page: 1,
                colModel: [{
                        label: "Date",
                        name: "dates",
                        width: 150,
                        formatter: "date",
                        formatoptions: {
                            srcformat: "ISO8601Long",
                            newformat: "d F Y H:i:s"
                        }
                    }, {
                        label: "Trans",
                        name: "types_trans",
                        width: 50,
                        align: "center"
                    }, {
                        label: "Type",
                        name: "types",
                        width: 50,
                        align: "center"
                    },
                    {
                        label: "Units",
                        name: "units",
                        width: 68,
                        align: 'center',
                    }, {
                        label: "Packaging",
                        name: "packaging",
                        width: 95,
                        align: 'center',
                    },
                    {
                        label: "Units",
                        name: "begin_stock_units",
                        width: 60,
                        align: 'center',
                    }, {
                        label: "Packaging",
                        name: "begin_stock_packaging",
                        width: 65,
                        align: 'center',
                    },
                    {
                        label: "Units",
                        name: "QtyUnits",
                        width: 60,
                        align: 'center',
                    }, {
                        label: "Packaging",
                        name: "QtyPackaging",
                        width: 65,
                        align: 'center',
                    },
                    {
                        label: "Units",
                        name: "EndStockUnits",
                        width: 60,
                        align: 'center',
                    }, {
                        label: "Packaging",
                        name: "EndStockPackaging",
                        width: 65,
                        align: 'center',
                    }
                ],
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
                width: '100%',
                rowNum: 15,
                caption: "Log Book",
                pager: "#" + pager_id,
            });

            jQuery("#" + subgrid_table_id).jqGrid('setGroupHeaders', {
                useColSpanStyle: true,
                groupHeaders: [{
                    startColumnName: 'units',
                    numberOfColumns: 2,
                    titleText: 'Detail'
                }, {
                    startColumnName: 'begin_stock_units',
                    numberOfColumns: 2,
                    titleText: 'Begin Stock'
                }, {
                    startColumnName: 'QtyUnits',
                    numberOfColumns: 2,
                    titleText: 'IN/OUT Stock'
                }, {
                    startColumnName: 'EndStockUnits',
                    numberOfColumns: 2,
                    titleText: 'Final Stock'
                }, {
                    startColumnName: 'types_trans',
                    numberOfColumns: 2,
                    titleText: 'Types'
                }]
            });
        }


        jQuery("#jqGridMain").jqGrid('setGroupHeaders', {
            useColSpanStyle: true,
            groupHeaders: [{
                startColumnName: 'units',
                numberOfColumns: 2,
                titleText: 'Detail'
            }, {
                startColumnName: 'QtyUnit',
                numberOfColumns: 3,
                titleText: 'Final Stock'
            }]
        });

        $(window).on('resize', function() {
            var gridWidth = $('#jqGridMain').closest('.ui-jqgrid').parent().width();
            $('#jqGridMain').jqGrid('setGridWidth', gridWidth);
        }).trigger('resize');

        // Fetch Customers
        function GetlistCustomers(query) {
            $.ajax({
                url: '{{ url("administrator/jsonListUnitsByCustomers") }}',
                data: {
                    q: query
                },
                success: function(data) {
                    var $select = $('#customer_id');
                    $select.empty();
                    var sessCustomers = "{{ session()->get('customers_id') }}";
                    if (sessCustomers == "*") {
                        $select.append('<option value="*">*All Customers</option>');
                    }
                    $.each(data, function(index, option) {
                        if (option.id == sessCustomers) {
                            // Stop the loop when the value is the same as targetValue
                            $select.append('<option  value="' + option.id + '">' + option.name_customers + '</option>');
                            GetlistMaterial(option.id)
                            return false;
                        } else {
                            $select.append('<option  value="' + option.id + '">' + option.name_customers + '</option>');
                        }

                    });
                }
            });
        }

        // Fetch Customers
        function GetlistMaterial(cust_id) {
            $.ajax({
                url: '{{ url("administrator/jsonListMaterialSummary") }}',
                data: {
                    customer_id: cust_id
                },
                success: function(data) {
                    var $select = $('#material_id');
                    $select.empty();
                    var sessCustomers = "{{ session()->get('customers_id') }}";
                    if (sessCustomers == "*") {
                        $select.append('<option value="*">*All Material</option>');
                    } else {
                        $select.append('<option value="*">*All Material</option>');
                    }
                    $.each(data, function(index, option) {
                        if (option.id == sessCustomers) {
                            // Stop the loop when the value is the same as targetValue
                            $select.append('<option  value="' + option.id + '">' + option.name_material + '</option>');
                            return false;
                        } else {
                            $select.append('<option  value="' + option.id + '">' + option.name_material + '</option>');
                        }

                    });
                }
            });
        }

        $("#customer_id").change(function() {
            GetlistMaterial($("#customer_id").val())
        })

        GetlistCustomers("");

        function Exports() {
            var url = "";
            url = "{{ url('administrator/jsonExportExcel') }}"
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    customer_id: $("#customer_id").val(),
                    material_id: $("#material_id").val(),
                    startDate: $("#startdateFilter").val(),
                    endDate: $("#enddateFilter").val(),
                    act: $("#ExportOption").val()
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data, status, xhr) {

                    if ($("#ExportOption").val() == "xls") {
                        // Create a URL for the Blob object and initiate download
                        var blob = new Blob([data], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Summary_" + $("#startdateFilter").val() + '_' + $("#enddateFilter").val() + '.xlsx';
                        link.click();
                    } else if ($("#ExportOption").val() == "pdf") {
                        var blob = new Blob([data], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Summary_" + $("#startdateFilter").val() + '_' + $("#enddateFilter").val() + '.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error exporting file:', error);
                }
            })
        }

        $("#exportBtn").click(function() {
            Exports()
        })


    })
</script>
@endsection