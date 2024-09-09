<div class="modal modal-fullscreen-xl fade" id="modalCrudSales" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
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
                    <input type="text" hidden name="CrudSalesAction" id="CrudSalesAction">
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
                                    <input type="text" value="<?= date('Y-m-d') ?>" id="dateTransaksi" readonly required="required" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-3 col-sm-3" for="first-name">Membership<span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-6 ">
                                    <select name="member_id" class="form-control custom-select" id="member_id">
                                        @foreach($level as $s)
                                        <option value="{{ $s->id }}">{{ $s->name_level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12  form-group">
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="qty">Qty <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input onkeypress="return isNumberKey(event)" autocomplete="off" type="text" required autofocus id="qty" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="discount">Discount <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input autocomplete="off" value="0" type="text" autofocus id="discount" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-md-2 col-sm-3" for="first-name">Barcode <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input type="text" autocomplete="off" readonly name="barcode" placeholder="Scan Item Disini" rows="4" required class="form-control form-control-lg" id="barcode" />
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

                    <div class="col-md-9">
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="item form-group">
                                        <label class="col-md-5 col-sm-3" for="first-name">Sub Total <span class="required">*</span>
                                        </label>
                                        :
                                        <div class="col-md-7 col-sm-6 ">
                                            <input type="text" hidden name="sub_total" id="sub_total" readonly required="required" class="form-control form-control-sm">
                                            <input type="text" name="sub_total_pref" id="sub_total_pref" readonly required="required" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item form-group">
                                        <label class="col-md-5 col-sm-3" for="first-name">Uang Bayar <span class="required">*</span>
                                        </label>
                                        :
                                        <div class="col-md-7 col-sm-6 ">
                                            <input type="text" hidden name="uang_bayar" id="uang_bayar" required="required" class="form-control form-control-sm">
                                            <input type="text" name="uang_bayar_pref" id="uang_bayar_pref" required="required" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="item form-group">
                                        <label class="col-md-5 col-sm-3" for="first-name">Total Potongan <span class="required">*</span>
                                        </label>
                                        :
                                        <div class="col-md-7 col-sm-6 ">
                                            <input type="text" hidden name="total_potongan" id="total_potongan" readonly required="required" class="form-control form-control-sm">
                                            <input type="text" name="total_potongan_pref" id="total_potongan_pref" readonly required="required" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item form-group">
                                        <label class="col-md-5 col-sm-3" for="first-name">Kembalian <span class="required">*</span>
                                        </label>
                                        :
                                        <div class="col-md-7 col-sm-6 ">
                                            <input type="text" hidden name="kembalian" id="kembalian" readonly required="required" class="form-control form-control-sm">
                                            <input type="text" readonly name="kembalian_pref" id="kembalian_pref" required="required" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="item form-group">
                                        <label class="col-md-5 col-sm-3" for="first-name">Total Bayar <span class="required">*</span>
                                        </label>
                                        :
                                        <div class="col-md-7 col-sm-6 ">
                                            <input type="text" hidden name="total_bayar" id="total_bayar" readonly required="required" class="form-control form-control-sm">
                                            <input type="text" name="total_bayar_pref" id="total_bayar_pref" readonly required="required" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button id="btnBayarTrans" type="button" class="btn btn-sm btn-success"><i class="fa fa-dollar"></i> Bayar</button>
                                <button id="btnPrintStruk" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i> Print</button>
                                <button id="btnCancel" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-close"></i> Batalkan</button>
                                <button id="btnReset" type="button" class="btn btn-sm btn-danger"><i class="fa fa-refresh"></i> Akhiri</button>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12 mt-1">
                            <div class="alert alert-primary">
                                <h5>Shortcut</h5>
                                <span><b>[ Q ]</b> : Field Qty</span><br>
                                <span><b>[ S ]</b> : Field Barcode</span><br>
                                <span><b>[ U ]</b> : Field Uang Bayar</span><br>
                                <span><b>[ B ]</b> : Tekan Button Bayar</span><br>
                                <span><b>[ P ]</b> : Tekan Button Print</span><br>
                                <span><b>[ X ]</b> : Tekan Button Batalkan</span><br>
                                <span><b>[ E ]</b> : Tekan Button Akhiri</span>
                            </div>
                        </div>
                    </div>


                </div>


                <div id="CrudSalesError">
                </div>
                <div id="CrudSalesAlertDelete"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key == 'Tab') {
                var bcd = document.getElementById("barcode");
                bcd.focus();
            } else if (event.key === 'b') {
                // Trigger the button click
                document.getElementById('btnBayarTrans').click();
            } else if (event.key === 'q') {
                $("#barcode").attr("readonly", true)
                $("#barcode").val("");
                $("#qty").focus();
            } else if (event.key === 'p') {
                document.getElementById('btnPrintStruk').click();
            } else if (event.key === 'x') {
                document.getElementById('btnCancel').click();
            } else if (event.key === 'e') {
                document.getElementById('btnReset').click();
            } else if (event.key === 'u') {
                var ub = document.getElementById("uang_bayar_pref");
                ub.focus();
            } else if (event.key === 's') {
                $("#barcode").attr("readonly", false)
                var bc = document.getElementById("barcode");
                bc.focus();
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

        document.getElementById('uang_bayar_pref').addEventListener('input', function(e) {
            var value = this.value.replace(/[^,\d]/g, '').toString();
            var split = value.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah ? 'Rp ' + rupiah : '';
            // Update the raw input field with the unformatted value
            var rawValue = value.replace(/\./g, '');
            document.getElementById('uang_bayar').value = rawValue;

            if (rawValue == "" || rawValue == null) {
                $("#kembalian_pref").val('');
                $("#kembalian").val('');
            } else {
                // kembali 
                var Uangkembali = rawValue - parseInt($('#total_bayar').val());
                // TOTAL BAYAR 
                if (parseInt(rawValue) >= parseInt($('#total_bayar').val())) {
                    var kembalian_pref = document.getElementById('kembalian_pref');
                    var kembalian = document.getElementById('kembalian');
                    formatRupiah(Uangkembali.toString(), kembalian_pref, kembalian);
                }
            }


        });


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
        var qtys = document.getElementById("qty");

        qtys.addEventListener('keydown', function(event) {
            if (event.key === 'Tab') {
                $("#barcode").attr("readonly", false);
                var bcd = document.getElementById("barcode");
                bcd.focus();
                // Example action: Prevent default tab behavior and perform a custom action
                event.preventDefault();

                // Example action: Switch focus to the next input
                let activeElement = document.activeElement;
                if (activeElement && activeElement.nextElementSibling) {
                    activeElement.nextElementSibling.focus();
                }
            }
        })

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
                $("#barcode").attr("readonly", true);
                qty.focus();


                $.ajax({
                    url: '{{ url("administrator/getPrice") }}',
                    method: "GET",
                    type: 'GET',
                    data: {
                        'barcode': $("#barcode").val(),
                        'member_id': $("#member_id").val()
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
                                harga_jual: params.harga_jual,
                                discount: parseFloat($("#discount").val()),
                                subtotal: (parseInt($("#qty").val()) * params.harga_jual),
                                total: (parseInt($("#qty").val()) * params.harga_jual) - parseFloat($("#discount").val())
                            }
                            if (materialExists(params.material_id)) {
                                doSuccess('create', 'item sudah masuk list', 'error')
                            } else {
                                dataSales.push(datas);
                            }
                            reloadgridItem(dataSales);

                            countPrice();

                        } else {
                            doSuccess('create', resp.data, 'error')
                        }
                        $("#qty").val("");
                        $("#barcode").val("");
                        $("#uang_bayar").val("");
                        $("#uang_bayar_pref").val("");
                        $("#kembalian").val("");
                        $("#kembalian_pref").val("");
                        $("#discount").val(0)
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
            } else if (parseFloat($("#uang_bayar").val()) < parseFloat($("#total_bayar").val())) {
                doSuccess('create', "uang tidak cukup", 'warning');
            } else {
                var data = {
                    '_token': "{{ csrf_token() }}",
                    'listBelanja': JSON.stringify(dataSales),
                    '_sub_total': $("#sub_total").val(),
                    '_total_potongan': $("#total_potongan").val(),
                    '_total_bayar': $("#total_bayar").val(),
                    '_uang_bayar': $("#uang_bayar").val(),
                    '_kembalian': $("#kembalian").val(),
                    '_noTransaksi': $("#noTransaksi").val(),
                    '_member_id': $("#member_id").val(),
                    '_dateTransaksi': $("#dateTransaksi").val(),
                }
                $.ajax({
                    url: '{{ url("administrator/jsonSaveTransaksi") }}',
                    method: "POST",
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.msg == "success") {
                            $("#btnPrintStruk").attr("disabled", false);
                            $("#btnCancel").attr("disabled", false);
                            $("#btnReset").attr("disabled", false);
                            doSuccess('create', 'Data Save To Record', 'success')
                        } else {
                            $("#btnPrintStruk").attr("disabled", true);
                            $("#btnCancel").attr("disabled", true);
                            $("#btnReset").attr("disabled", true);
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
                        var errMsg = '<div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div>'
                        $('#CrudSalesError').html(errMsg);
                    },
                })
            }
        })

        $("#btnPrintStruk").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ url("administrator/jsonPrintInvoice") }}',
                type: 'GET',
                xhrFields: {
                    responseType: 'blob' // To handle the binary data
                },
                data: {
                    'no_trans': $("#noTransaksi").val(),
                    'header_id': $("#id").val()
                },
                beforeSend: function() {
                    document.getElementById("fullPageLoader").style.display = "block";
                },
                complete: function() {
                    document.getElementById("fullPageLoader").style.display = "none";
                },
                success: function(response) {
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    var url = URL.createObjectURL(blob);

                    var iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = url;
                    document.body.appendChild(iframe);

                    iframe.onload = function() {
                        iframe.contentWindow.print();
                    };
                }
            });
        })

        $("#btnCancel").click(function(e) {
            //       e.preventDefault();
            $.confirm({
                title: 'Perhatian!',
                keyboardEnabled: true,
                content: 'Batalkan Pesanan ?',
                buttons: {
                    yes: {
                        btnClass: 'btn-danger',
                        action: function() {
                            $.ajax({
                                url: '{{ url("administrator/jsonCancelTransaksi") }}',
                                type: 'POST',
                                data: {
                                    'no_transaksi': $("#noTransaksi").val(),
                                    '_token': "{{ csrf_token() }}",
                                },
                                beforeSend: function() {
                                    document.getElementById("fullPageLoader").style.display = "block";
                                },
                                complete: function() {
                                    document.getElementById("fullPageLoader").style.display = "none";
                                },
                                success: function(response) {
                                    if (response.msg == "success") {
                                        noTransaksi()
                                        dataSales = [];
                                        reloadgridItem(dataSales);
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
                                        $("#btnPrintStruk").attr("disabled", true);
                                        $("#btnCancel").attr("disabled", true);
                                        $("#btnReset").attr("disabled", true);
                                        doSuccess('create', response.txt, 'warning');
                                        ReloadBarang();
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

                                    var errMsg = '<div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div>'
                                    doSuccess('create', errMsg, 'warning')
                                },
                            });
                        }
                    },
                    no: {
                        btnClass: 'btn-blue',
                        action: function() {}
                    },
                }
            });
        })

        $("#btnReset").click(function(e) {
            if (confirm('Yakin Sudahi Pesanan ?')) {
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
                $("#btnPrintStruk").attr("disabled", true);
                $("#btnCancel").attr("disabled", true);
                $("#btnReset").attr("disabled", true);
                doSuccess('create', "Transaksi Selesai , Terimakasih", 'warning')
                dataSales = [];
                reloadgridItem(dataSales);
                ReloadBarang();
                if ($("#CrudSalesAction").val() == "update") {
                    $('#modalCrudSales').modal('hide');
                } else if ($("#CrudSalesAction").val() == "create") {
                    noTransaksi()
                }
            }
        })
    </script>