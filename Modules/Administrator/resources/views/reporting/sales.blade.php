@extends('administrator::layouts.master')

@section('content')

<?php

use Illuminate\Support\Facades\DB;


?>
<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Reporting Penjualan</h2>
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

                            <div class="input-group input-group-sm">
                                <select id="material_id" name="material_id" style="font-size: 0.75rem !important;" class="form-control form-control-sm custom-select select2">
                                    <option value="*">*All Product</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div class="input-group input-group-sm">
                                <input id="startdateFilter" type="text" class="form-control input-daterange" placeholder="Start Date">
                                <div class="input-group-append">
                                    <span class="input-group-text">To</span>
                                </div>
                                <input id="enddateFilter" type="text" class="form-control date" placeholder="End Date">
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
        // function GetlistMaterial(cust_id) {
        //     $.ajax({
        //         url: '{{ url("administrator/jsonListItemReporting") }}',
        //         data: {
        //             customer_id: cust_id
        //         },
        //         success: function(data) {
        //             var $select = $('#material_id');
        //             $select.empty();
        //             var sessCustomers = "{{ session()->get('customers_id') }}";
        //             if (sessCustomers == "*") {
        //                 $select.append('<option value="*">*All Material</option>');
        //             } else {
        //                 $select.append(`<option value="*">*All Material</option>`);
        //             }
        //             $.each(data, function(index, option) {
        //                 if (option.id == sessCustomers) {
        //                     // Stop the loop when the value is the same as targetValue
        //                     $select.append('<option  value="' + option.id + '">' + option.name_item + '</option>');
        //                     return false;
        //                 } else {
        //                     $select.append('<option  value="' + option.id + '">' + option.name_item + '</option>');
        //                 }

        //             });
        //         }
        //     });
        // }

        // GetlistMaterial("");

        function formatResult(repo) {
            if (repo.loading) {
                return repo.text;
            }
            return '' + repo.text + '';
        }

        function formatSelection(repo) {
            return repo.text || repo.id;
        }
        $("#material_id").select2({
            // matcher: matchStart,
            placeholder: "Select a state",
            allowClear: true,
            ajax: {
                url: "{{ url('administrator/getJsonMaterialReporting') }}", // Your server endpoint that returns the data
                dataType: 'json', // The data type expected from the server
                delay: 250, // Delay in ms before the request is sent
                data: function(params) {
                    return {
                        search: params.term, // Search term (what the user types)
                        page: params.page || 1 // Pagination (optional)
                    };
                },
                processResults: function(data, params) {
                    // Parse the results into the format expected by Select2
                    params.page = params.page || 1;

                    return {
                        results: data.items, // The array of results from the server
                        pagination: {
                            more: data.pagination.more // Indicates if there are more pages to load
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search Barcode', // Placeholder text
            minimumInputLength: 1, // Minimum number of characters before search begins
            templateResult: formatResult, // Optional function to customize how results are displayed
            templateSelection: formatSelection
        });

        // Set default value (replace 'DEFAULT_VALUE' with the actual value)
        var defaultId = '*'; // Default ID you want to select
        var defaultText = 'All Product'; // Default text you want to display

        // Create a new option and set it as selected
        var defaultOption = new Option(defaultText, defaultId, true, true);
        $("#material_id").append(defaultOption).trigger('change');



        function Exports() {
            var url = "";
            url = "{{ url('administrator/exportReportSales') }}"
            $.ajax({
                url: url,
                method: "GET",
                data: {
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
                        link.download = "Report_" + $("#startdateFilter").val() + '_' + $("#enddateFilter").val() + '.xlsx';
                        link.click();
                    } else if ($("#ExportOption").val() == "pdf") {
                        var blob = new Blob([data], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Report_" + $("#startdateFilter").val() + '_' + $("#enddateFilter").val() + '.pdf';
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

        $("#startdateFilter").daterangepicker({
            singleDatePicker: !0,
            singleClasses: "picker_2"
        }, function(e, a, t) {
            console.log(e.toISOString(), a.toISOString(), t)
        })
        $("#enddateFilter").daterangepicker({
            singleDatePicker: !0,
            singleClasses: "picker_2"
        }, function(e, a, t) {
            console.log(e.toISOString(), a.toISOString(), t)
        })

    })
</script>
@endsection