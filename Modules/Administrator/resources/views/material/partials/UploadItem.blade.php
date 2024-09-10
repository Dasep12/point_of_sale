<div class="modal fade" id="modalUploadItem" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalCrudAddPrice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form enctype="multipart/form-data" id="formUploadItem" method="post" data-parsley-validate>
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title" id="titleModal">Form Upload Material</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">File</label>
                        <input type="text" hidden name="actionUpload" id="actionUpload">
                        <input type="file" required class="form-control" name="file_upload" id="file_upload">
                        <a href="" id="format_upload">download format</a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-primary btn-sm btn-title-price"><i class="fa fa-save"></i> Submit</button>
                </div>

                <div id="UploadItemError"></div>

            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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
        $('#formUploadItem').submit(function(e) {
            e.preventDefault();
            var f = $(this);
            f.parsley().validate();

            if (f.parsley().isValid()) {
                var formData = new FormData($('#formUploadItem')[0]);
                var actions = $("#actionUpload").val();
                var url = '';
                if (actions == "uploaditem") {
                    url = '{{ url("administrator/uploadItemExcel") }}';
                } else {
                    url = '{{ url("administrator/uploadHargaExcel") }}';
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        console.log(data)
                        if (data.success) {
                            $('#modalUploadItem').modal('hide');
                            ReloadBarang();
                            doSuccess('create', 'success upload data', 'success')
                        } else {
                            var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                            $('#UploadItemError').html(errMsg);
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
                        $('#UploadItemError').html(errMsg);
                    },
                });
            } else {
                alert("form invalid");
            }
        })
    })
</script>