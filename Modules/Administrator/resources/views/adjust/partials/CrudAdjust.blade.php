<div class="modal modal-fullscreen-xl fade" id="modalCrudAdjust" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-xl modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-headerr">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            @csrf()
            <div class="modal-body">
                <form method="post" data-parsley-validate id="formGetList">
                    <div class="row">
                        <div class="col-md-5 col-sm-12  form-group">
                            <div class="item form-group">
                                <label class="col-md-3 col-sm-3" for="noTransaksi">No Transaksi <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-6 ">
                                    <input readonly type="text" id="noTransaksi" name="noTransaksi" required="required" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-3 col-sm-3" for="dateTransaksi">Tanggal <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-6 ">
                                    <input type="date" id="dateTransaksi" value="<?= date('Y-m-d') ?>" required="required" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-md-3 col-sm-3" for="type_adjust">Type Adjust <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-6 ">
                                    <select name="type_adjust" class="form-control custom-select" id="type_adjust">
                                        <option value="in">Adjust Plus(+)</option>
                                        <option value="out">Adjust Minus(-)</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-7 col-sm-12  form-group">
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="first-name">Qty <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input onkeypress="return isNumberKey(event)" autocomplete="off" type="text" required id="qty" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="first-name">Barcode <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <select style="width: 100%;" name="barcode" placeholder="Scan Barcode Here" class="select2 js-example-matcher-start" name="barcode" id="barcode">
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="first-name"></span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <button class="btn btn-secondary btn-sm mt-2" type="submit"><i class="fa fa-shopping-cart"></i> Tambah</button>
                                </div>
                            </div>


                        </div>
                    </div>
                </form>

                <div class="row">

                    <div class="col-md-12">
                        <table id="jqGridSalesList"></table>
                        <div id="pagerGridInboundSales"></div>
                    </div>

                    <div class="col-md-12 mt-4 ">
                        <input type="text" hidden name="CrudActionAdjust" id="CrudActionAdjust">
                        <input type="text" hidden id="total_bayar" name="total_bayar">
                        <input type="text" hidden id="total_bayar_pref" name="total_bayar_pref">
                        <button id="btnBayarTrans" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Submit</button>
                        <button id="btnCancel" data-dismiss="modal" type="button" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Batal</button>
                    </div>
                    <!-- shortcut  -->
                </div>


                <div id="CrudAdjustError">
                </div>
                <div id="CrudAdjustAlertDelete"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(event) {
        if (event.key == 'q') {
            var qty = document.getElementById("qty");
            qty.value = '';
            qty.focus();
        } else if (event.key == 'h') {
            var hpp = document.getElementById("hpp");
            hpp.value = '';
            hpp.focus();
        } else if (event.key == 'b') {
            var bcd = document.getElementById("barcode");
            bcd.value = '';
            bcd.focus();
        }
    });

    $("#barcode").select2({
        // matcher: matchStart,
        placeholder: "Select a state",
        allowClear: true,
        dropdownParent: $('#modalCrudAdjust'),
        ajax: {
            url: "{{ url('administrator/searchMaterial') }}", // Your server endpoint that returns the data
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

    function formatResult(repo) {
        if (repo.loading) {
            return repo.text;
        }
        return '' + repo.text + '';
    }

    function formatSelection(repo) {
        return repo.text || repo.id;
    }

    // Capture Enter keypress within the Select2 dropdown
    $(document).on('keydown', '.select2-search__field', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent the default behavior
            var selectedValue = $('#barcode').val();
            if (selectedValue) {
                getPrice()
                $('#barcode').val(null).trigger('change');
            }
        }
    });

    function noTransaksi() {
        $.ajax({
            url: '{{ url("administrator/jsonNoTransaksiAdjust") }}',
            method: "GET",
            type: 'GET',
            data: {

            },
            success: function(data) {
                var resp = data;
                $("#noTransaksi").val(data)
            }
        })
    }
    noTransaksi()

    function doSuccess(act, msg, theme) {
        const myNotification = window.createNotification({
            // options here
            displayCloseButton: true,
            theme: theme //success error , information , success
        });

        myNotification({
            title: 'Information',
            message: msg
        });
    }

    $("#formGetList").parsley();
    $('#formGetList').submit(function(e) {
        e.preventDefault();
        if ($("#barcode").val() == "") {
            qty.focus();
        }
        var f = $(this);
        f.parsley().validate();


        if (f.parsley().isValid()) {
            var qty = document.getElementById("qty");
            qty.focus();
            getPrice()
            $('#barcode').val(null).trigger('change');
        }
    })

    function getPrice() {
        $.ajax({
            url: '{{ url("administrator/getJsonBarangAdjust") }}',
            method: "GET",
            type: 'GET',
            data: {
                'barcode': $("#barcode").val(),
            },
            success: function(data) {
                var resp = data;
                console.log(resp);
                console.log($("#barcode").val());
                if (data.msg == "ok") {
                    var params = resp.data[0];
                    var datas = {
                        id: params.id,
                        item_id: params.id,
                        item_name: params.name_item,
                        satuan_id: params.unit_id,
                        satuan: params.unit_code,
                        kode_item: params.kode_item,
                        merek: params.merek,
                        qty: $("#qty").val(),
                        supplier: $("#supplier").val(),
                        hpp: parseFloat($("#hpp").val()),
                        total: parseFloat($("#qty").val()) * parseFloat($("#hpp").val())
                    }
                    if (materialExists(params.id)) {
                        doSuccess('create', 'item sudah masuk list', 'error')
                    } else {
                        dataSales.push(datas);
                    }
                    let totalBayar = dataSales.reduce((accumulator, currentItem) => accumulator + currentItem.total, 0);
                    $("#total_bayar").val(totalBayar);
                    reloadgridItem(dataSales);
                } else {
                    doSuccess('create', resp.data, 'error')
                }
                $("#qty").val("");
                $("#hpp").val("");
                // $("#barcode").val("");
            },
            error: function(xhr, desc, err) {
                var respText = "";
                try {
                    respText = eval(xhr.responseText);
                } catch {
                    respText = xhr.responseText;
                }

                respText = unescape(respText).replaceAll("_n_", "<br/>")

                var errMsg = '<div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div>'
                // $('#crudCustomersError').html(errMsg);
            },
        });
    }



    $("#btnBayarTrans").click(function(e) {
        e.preventDefault();
        if (dataSales.length <= 0) {
            doSuccess('create', "data transaksi masih kosong", 'warning');
        } else {
            var data = {
                '_token': "{{ csrf_token() }}",
                'listBelanja': JSON.stringify(dataSales),
                '_total_bayar': $("#total_bayar").val(),
                '_noTransaksi': $("#noTransaksi").val(),
                '_dateTransaksi': $("#dateTransaksi").val(),
                '_type_adjust': $("#type_adjust").val(),
            }

            $.ajax({
                url: '{{ url("administrator/jsonSaveTransaksiAdjust") }}',
                method: "POST",
                type: 'POST',
                data: data,
                success: function(data) {
                    if ($("#CrudActionAdjust").val() == "create") {
                        noTransaksi();
                    }
                    ReloadBarang();
                    doSuccess('create', 'Data Save To Record', 'success')
                    $('#modalCrudAdjust').modal('hide');
                },
                error: function(xhr, desc, err) {
                    var respText = "";
                    try {
                        respText = eval(xhr.responseText);
                    } catch {
                        respText = xhr.responseText;
                    }

                    respText = unescape(respText).replaceAll("_n_", "<br/>")

                    var errMsg = '<div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div>'
                    $('#CrudAdjustError').html(errMsg);
                },
            })
        }



    })
</script>