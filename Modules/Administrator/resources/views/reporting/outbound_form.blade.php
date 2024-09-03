@extends('administrator::layouts.master')

@section('content')

<?php

use Illuminate\Support\Facades\DB;


?>
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Reporting Outbound</h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">
                        <!-- <input type="text" id="searching" class="form-control form-control-sm" placeholder="Search Name Material..">
                        <span class="input-group-btn">
                            <button onclick="search()" class="btn-filter btn btn-secondary btn-sm" type="button"><i class="fa fa-search"></i> Search</button>
                        </span> -->
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="d-flex justify-content-center">
                    <form id="form-filter" class="p-4 bg-light" style="width:350px">
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
                            <!-- <label for="startdateFilter" class="col-form-label col-form-label-sm">Types</label> -->
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
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
    $(document).ready(function() {



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
                        $select.append(`<option value="*">*All Material</option>`);
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
            url = "{{ url('administrator/exportReportOutbound') }}"
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