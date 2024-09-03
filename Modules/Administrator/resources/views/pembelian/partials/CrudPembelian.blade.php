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
                                <label class="col-md-2 col-sm-3" for="first-name">HPP / Qty <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-6 ">
                                    <input type="text" autocomplete="off" name="barcode" placeholder="" required class="form-control form-control-sm" id="barcode" />
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

                    <div class="col-md-9">
                        <div class="col-md-12 mt-3">


                            <div class="col-md-12 mt-3">
                                <button id="btnBayarTrans" type="button" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                                <button id="btnCancel" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-close"></i> Batalkan</button>


                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-3">
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
                    </div> -->
                </div>


                <div id="CrudPembelianError">
                </div>
                <div id="CrudPembelianAlertDelete"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key == 'Tab') {
                var bcd = document.getElementById("barcode");
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
                                discount: 0,
                                total: parseInt($("#qty").val()) * params.harga_jual
                            }
                            if (materialExists(params.material_id)) {
                                doSuccess('create', 'item sudah masuk list', 'error')
                            } else {
                                dataSales.push(datas);
                            }
                            reloadgridItem(dataSales);
                            let totalSum = dataSales.reduce((accumulator, currentItem) => accumulator + currentItem.total, 0);
                            let totalPot = dataSales.reduce((accumulator, currentItem) => accumulator + currentItem.discount, 0);


                            // SUB TOTAL
                            var sub_total_pref = document.getElementById('sub_total_pref');
                            var sub_total = document.getElementById('sub_total');
                            formatRupiah(totalSum.toString(), sub_total_pref, sub_total);

                            // TOTAL POTONGAN
                            var sub_total_pref = document.getElementById('total_potongan_pref');
                            var sub_total = document.getElementById('total_potongan');
                            formatRupiah(totalPot.toString(), sub_total_pref, sub_total);

                            // TOTAL BAYAR 
                            var total_bayar_pref = document.getElementById('total_bayar_pref');
                            var total_bayar = document.getElementById('total_bayar');
                            formatRupiah(totalSum.toString(), total_bayar_pref, total_bayar);

                        } else {
                            doSuccess('create', resp.data, 'error')
                        }
                        $("#qty").val("");
                        $("#barcode").val("");
                        $("#uang_bayar").val("");
                        $("#uang_bayar_pref").val("");
                        $("#kembalian").val("");
                        $("#kembalian_pref").val("");



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
                        console.log(data);
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
                        $('#CrudPembelianError').html(errMsg);
                    },
                })
            }



        })
    </script>