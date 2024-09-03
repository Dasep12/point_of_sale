<div class="modal fade" id="modalCrudMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-xl modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudMaterial">
                @csrf()
                <div class="modal-body">

                    <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Harga</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="text" hidden name="action" id="CrudMaterialAction" />
                                    <input type="text" hidden name="id" id="id" />
                                    <div class="form-group">
                                        <label for="warehouse_id">Warehouse* :</label>
                                        <select style="width: 100%;" name="warehouse_id" class="form-control custom-select" required id="warehouse_id">
                                            <option value="">Choose Warehouse</option>
                                            @foreach($warehouse as $w)
                                            <option value="{{ $w->id }}">{{ $w->NameWarehouse }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="barcode">Barcode Item* :</label>
                                        <input type="text" required class="form-control" name="barcode" id="barcode">
                                    </div>

                                    <div class="form-group">
                                        <label for="satuan_dasar">Satuan Dasar* :</label>
                                        <input type="text" required class="form-control" name="satuan_dasar" id="satuan_dasar">
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks* :</label>
                                        <textarea type="text" required class="form-control" name="remarks" id="remarks"></textarea>
                                    </div>

                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="location_id">Location* :</label>
                                        <select style="width: 100%;" name="location_id" class="form-control custom-select" required id="location_id">
                                            <option value="">Choose Location</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="categori_id">Jenis* :</label>
                                        <select style="width: 100%;" name="categori_id" class="form-control custom-select" required id="categori_id">
                                            <option value="">Choose Item</option>
                                            @foreach($categ as $c)
                                            <option value="{{ $c->id }}">{{ $c->code_categories }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="konversi_satuan">Konversi Satuan* :</label>
                                        <input type="text" required class="form-control" name="konversi_satuan" id="konversi_satuan">
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_minimum">Stock Minimum* :</label>
                                        <input type="text" required class="form-control" name="stock_minimum" id="stock_minimum">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="name_item">Name Item* :</label>
                                        <input type="text" required class="form-control" name="name_item" id="name_item">
                                    </div>

                                    <div class="form-group">
                                        <label for="unit_id">Unit* :</label>
                                        <select name="unit_id" class="form-control custom-select" required id="unit_id">
                                            <option value="">Choose Unit</option>
                                            @foreach($units as $u)
                                            <option value="{{ $u->id }}">{{ $u->unit_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tipe_item">Tipe Item* :</label>
                                        <input type="text" required class="form-control" name="tipe_item" id="tipe_item">
                                    </div>

                                    <div class="form-group">
                                        <label for="hpp">Harga Pokok* :</label>
                                        <input type="text" required class="form-control" name="hpp" id="hpp">
                                        <input type="text" hidden required class="form-control" name="harga_pokok" id="harga_pokok">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="kode_item">Kode Item* :</label>
                                        <input type="text" required class="form-control" name="kode_item" id="kode_item">
                                    </div>

                                    <div class="form-group">
                                        <label for="merek">Merek* :</label>
                                        <input type="text" required class="form-control" name="merek" id="merek">
                                    </div>

                                    <div class="form-group">
                                        <label for="serial">Serial* :</label>
                                        <input type="text" required class="form-control" name="serial" id="serial">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="status_item" name="status_item" class="checkeds" checked="checked" /> <label for="status_item"> Status *</label>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">

                                <div class="ml-4 col-md-2 mb-2 d-flex justify-content-end">
                                    <button onclick="CrudPrice('create','*')" type="button" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"></i> Add New
                                    </button>
                                </div>
                                <div class="col-md-12 mb-2 d-flex justify-content-center">
                                    <table id="jqGridMainPrice"></table>
                                    <div id="pagerPrice"></div>
                                </div>
                            </div>

                        </div>
                    </div>



                    <hr />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-sm btn-title"></button>
                    </div>
                </div>
            </form>

            <div id="CrudMaterialError"></div>
            <div id="CrudMaterialAlertDelete"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#formCrudMaterial").parsley({
            errorPlacement: function(error, ParsleyField) {
                var fieldId = ParsleyField.$element.attr('id') + '-errors';
                $('#' + fieldId).append(error);
            },
            errorsContainer: function(ParsleyField) {
                var fieldId = ParsleyField.$element.attr('id') + '-errors';
                return $('#' + fieldId);
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
        document.getElementById('hpp').addEventListener('input', function(e) {
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
            document.getElementById('harga_pokok').value = rawValue;
        });

        // submit form data to server
        $('#formCrudMaterial').submit(function(e) {
            e.preventDefault();
            var f = $(this);
            f.parsley().validate();

            if (f.parsley().isValid()) {
                var formData = new FormData($('#formCrudMaterial')[0]);
                var actions = $("#CrudMaterialAction").val();
                var url = '';
                if (actions == "create") {
                    url = '{{ url("administrator/jsonCreateMaterial") }}';
                } else if (actions == "update") {
                    url = '{{ url("administrator/jsonUpdateMaterial") }}';
                } else if (actions == "delete") {
                    url = '{{ url("administrator/jsonDeleteMaterial") }}';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
                    async: false,
                    success: function(data) {
                        // console.log(data)
                        var act = $("#CrudMaterialAction").val();
                        act = act.toLowerCase();
                        if (data.msg == "success") {
                            if (act == "delete" || act == "update") {
                                $('#modalCrudMaterial').modal('hide');
                            }
                            $("#id").val(data.lastId);
                            ReloadBarang();
                            doSuccess('create', 'success ' + act + ' data', 'success')
                        } else {
                            var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                            $('#CrudMaterialError').html(errMsg);
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

                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div></div>'
                        $('#crudCustomersError').html(errMsg);
                    },
                });
            } else {
                alert("form invalid");
            }
        })
    })
</script>