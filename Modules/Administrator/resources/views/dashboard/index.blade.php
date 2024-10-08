@extends('administrator::layouts.master')

@section('content')

<style>
    .tile-stats h3 {
        color: #4F5E74 !important;
    }

    .tile-stats .icon {
        color: #4D616E !important;
    }
</style>

<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_content">
                <h2>Welcome {{ ucwords(strtolower(session()->get("fullname"))) }}</h2>
                <div class="nav navbar-right panel_toolbox">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="">
            <div class="animated flipInY col-lg-6 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-users"></i></div>
                    <div class="count countLevelMember">0</div>
                    <h3>Total Kategori</h3>
                    <p></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-6 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                    <div class="count countMaterial">0</div>
                    <h3>Total Produk</h3>
                    <p></p>
                </div>
            </div>
        </div>
        <div class="">
            <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-inbox"></i></div>
                    <div class="count countPenjualan" id="countPenjualan">0</div>
                    <h3>Total Penjualan</h3>
                    <p></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-dropbox"></i></div>
                    <div class="count countPembelian">0</div>
                    <h3>Total Pembelian</h3>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="height: 225px !important;">
            <div class="card-body">
                <h6>Top 5 Sales</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Total Qty</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salestop as $sl)
                        <tr>
                            <td>{{ $sl->item_name }}</td>
                            <td>{{ $sl->qty }}</td>
                            <td>{{ number_format($sl->total_out,0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">Graph Trend</div>
                    <div class="p-2 bd-highlight" style="width: 25% !important;">
                        <select id="item_id" style="font-size: 0.75rem !important;" class="form-control form-control-sm custom-select select2">
                            <option value="">*Choose Item</option>
                        </select>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div id="daterange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ddd">
                            <i style="color:#ddd" class="fa fa-calendar"></i>
                            <span id="datesLabel">December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="dashboard-widget-content">
                    <div id="container" class="col-md-12 col-sm-12 " style="height:230px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>






<script>
    $(document).ready(function() {
        function countLevelMember() {
            $.ajax({
                url: "{{ url('administrator/countMember') }}",
                method: "GET",
                success: function(res) {
                    $(".countLevelMember").html(res.data)
                }
            })
        }

        function countMaterial() {
            $.ajax({
                url: "{{ url('administrator/countMaterial') }}",
                method: "GET",
                success: function(res) {
                    $(".countMaterial").html(res.data)
                }
            })
        }

        function countPenjualan() {
            $.ajax({
                url: "{{ url('administrator/countPenjualan') }}",
                method: "GET",
                success: function(res) {
                    var datas = res.data;
                    if (datas != null) {
                        $(".countPenjualan").html(formatRpDashboard(datas.toString()))
                    } else {
                        $(".countPenjualan").html(0)
                    }
                }
            })
        }

        function countPembelian() {
            $.ajax({
                url: "{{ url('administrator/countPembelian') }}",
                method: "GET",
                success: function(res) {
                    var datas = res.data;
                    console.log(res)
                    if (datas != null) {
                        $(".countPembelian").html(formatRpDashboard(datas.toString()))
                    } else {
                        $(".countPembelian").html(0)
                    }
                }
            })
        }


        // Fetch Item
        function GetlistMaterial(query) {
            $.ajax({
                url: '{{ url("administrator/jsonDashboardItem") }}',
                data: {
                    q: query
                },
                success: function(data) {
                    var $select = $('#item_id');
                    $select.empty();
                    $select.append('<option value="">*Choose Item</option>');
                    $.each(data, function(index, option) {
                        // Stop the loop when the value is the same as targetValue
                        $select.append('<option  value="' + option.id + '">' + option.name_item + '</option>');
                    });
                }
            });
        }



        GetlistMaterial("");
        countLevelMember();
        countMaterial();
        countPenjualan();
        countPembelian();

        $('#daterange').daterangepicker({
            opens: 'left', // Specifies where the picker opens (left/right/center)
            locale: {
                format: 'YYYY-MM-DD' // The format of the date displayed in the input field
            },
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('MMMM DD, YYYY');
            var endDate = picker.endDate.format('MMMM DD, YYYY');
            $("#datesLabel").html(`${ startDate } - ${ endDate }`);
            updateGraph()
        });

        $("#item_id").change(function() {
            updateGraph()
        })
        // Get the start and end dates of the current month
        var startDateLabel = moment().startOf('month').format('MMMM DD, YYYY');
        var endDateLabel = moment().endOf('month').format('MMMM DD, YYYY');
        $("#datesLabel").html(`${ startDateLabel } - ${ endDateLabel }`);

        var graph = Highcharts.chart('container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [],
                accessibility: {
                    description: ''
                }
            },
            yAxis: {
                title: {
                    text: ''
                },
                labels: {
                    format: '{value:,.0f}'
                }
            },
            tooltip: {
                crosshairs: true,
                shared: true
            },
            series: [{
                name: 'Penjualan',
                data: [],
                color: 'green',
                dataLabels: {
                    enabled: false // Disable data labels
                }

            }, {
                name: 'Pembelian',
                data: [],
                dataLabels: {
                    enabled: false // Disable data labels
                }
            }],
            exporting: {
                enabled: false // Disables the exporting button
            },
            dataLabels: {
                enabled: false // Disable data labels
            }
        });

        // update data
        function updateGraph() {
            var startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
            $.ajax({
                url: "{{ url('administrator/jsonGraph') }}",
                method: "GET",
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    item_id: $("#item_id").val()
                },
                success: function(res) {
                    grap = $("#container").highcharts();
                    var seriesData = [{
                            "name": res[0].label_in,
                            "data": res[0].data_in,
                        },
                        {
                            "name": res[0].label_out,
                            "data": res[0].data_out,
                        }
                    ]
                    // Update chart series
                    grap.update({
                        series: seriesData
                    });
                }
            })
            UpdateCategories(startDate, endDate)
        }
        updateGraph()

        function UpdateCategories(startDate, endDate) {
            const dates = [];
            let currentDate = moment(startDate);

            while (currentDate <= moment(endDate)) {
                dates.push(currentDate.format('DD MMM'));
                currentDate.add(1, 'days'); // Move to the next day
            }
            grap = $("#container").highcharts();
            // Update chart
            grap.xAxis[0].update({
                categories: dates
            });
        }

    });
</script>
@endsection