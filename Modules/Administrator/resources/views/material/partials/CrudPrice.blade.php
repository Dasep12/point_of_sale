<div class="modal fade" id="modalCrudPrice" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalCrudAddPrice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formCrudPrice" method="post" data-parsley-validate>
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="titleModal">Form Add Price</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" hidden name="actionPrice" id="CrudPriceAction" />
                    <input type="text" hidden name="idPrice" id="idPrice" />
                    <input type="text" hidden name="material_id" id="material_id" />

                    <div class="form-group">
                        <label for="">Member</label>
                        <select name="member_id" class="form-control custom-select" id="member_id">
                            @foreach($member as $m)
                            <option value="{{ $m->id }}">{{ $m->name_level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Harga Jual</label>
                        <input type="text" hidden name="harga_jual" id="harga_jual">
                        <input type="text" class="form-control" name="harga_jual_ex" id="harga_jual_ex">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-primary btn-sm btn-title-price"><i class="fa fa-check"></i> Save</button>
                </div>

                <div id="CrudPriceError"></div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        document.getElementById('harga_jual_ex').addEventListener('input', function(e) {
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
            document.getElementById('harga_jual').value = rawValue;
        });

        $("#formCrudPrice").parsley({
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

        // submit form data to server
        $('#formCrudPrice').submit(function(e) {
            e.preventDefault();
            var f = $(this);
            f.parsley().validate();

            if (f.parsley().isValid()) {
                var formData = new FormData($('#formCrudPrice')[0]);
                var actions = $("#CrudPriceAction").val();
                var url = '';
                if (actions == "create") {
                    url = '{{ url("administrator/jsonCreatePrice") }}';
                } else if (actions == "update") {
                    url = '{{ url("administrator/jsonUpdatePrice") }}';
                } else if (actions == "delete") {
                    url = '{{ url("administrator/jsonDeletePrice") }}';
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
                        if (data.msg == "success") {
                            $('#modalCrudPrice').modal('hide');
                            var act = $("#CrudPriceAction").val();
                            act = act.toLowerCase();
                            ReloadHargaBarang();
                            doSuccess('create', 'success ' + act + ' data', 'success')
                        } else {
                            var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                            $('#CrudPriceError').html(errMsg);
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
                        $('#CrudPriceError').html(errMsg);
                    },
                });
            } else {
                alert("form invalid");
            }
        })
    })
</script>