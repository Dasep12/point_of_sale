<div class="modal modal-fullscreen-xl fade" id="modalCrudPembelian" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
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
                                    <input type="date" value="<?= date('m/d/y') ?>" id="dateTransaksi" required="required" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-md-3 col-sm-3" for="supplier">Supplier <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-6 ">
                                    <input type="text" id="supplier" required="required" class="form-control form-control-sm">
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
                                <label class="col-md-2 col-sm-3" for="hpp">HPP / Qty <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input type="text" autocomplete="off" name="hpp" placeholder="" required class="form-control form-control-sm" id="hpp" />
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="first-name">Barcode <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input type="text" autocomplete="off" name="barcode" placeholder="Scan Item Disini" required class="form-control form-control-sm" id="barcode" />
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
                        <input type="text" hidden id="total_bayar" name="total_bayar">
                        <input type="text" hidden id="total_bayar_pref" name="total_bayar_pref">
                        <button id="btnBayarTrans" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Submit</button>
                        <button id="btnCancel" type="button" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Batal</button>
                    </div>
                    <!-- shortcut  -->
                </div>


                <div id="CrudPembelianError">
                </div>
                <div id="CrudPembelianAlertDelete"></div>
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

        function noTransaksi() {
            $.ajax({
                url: '{{ url("administrator/jsonNoTransaksi") }}',
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
                $.ajax({
                    url: '{{ url("administrator/getJsonPriceBeli") }}',
                    method: "GET",
                    type: 'GET',
                    data: {
                        'barcode': $("#barcode").val(),
                    },
                    success: function(data) {
                        var resp = data;
                        if (data.msg == "ok") {
                            var params = resp.data[0];
                            var datas = {
                                id: params.material_id,
                                item_id: params.material_id,
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
                            console.log(datas)
                            if (materialExists(params.material_id)) {
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
                        $("#barcode").val("");
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

        })



        $("#btnBayarTrans").click(function(e) {
            e.preventDefault();
            if (dataSales.length <= 0 || $("#uang_bayar").val() == "" || $("#sub_total").val() == "") {
                doSuccess('create', "data transaksi masih kosong", 'warning');
            } else {
                var data = {
                    '_token': "{{ csrf_token() }}",
                    'listBelanja': JSON.stringify(dataSales),
                    '_total_bayar': $("#total_bayar").val(),
                    '_noTransaksi': $("#noTransaksi").val(),
                    '_dateTransaksi': $("#dateTransaksi").val(),
                }

                $.ajax({
                    url: '{{ url("administrator/jsonSaveTransaksiBeli") }}',
                    method: "POST",
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        // console.log(data);
                        noTransaksi();
                        ReloadBarang();
                        doSuccess('create', 'Data Save To Record', 'success')
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
                        $('#CrudPembelianError').html(errMsg);
                    },
                })
            }



        })
    </script>