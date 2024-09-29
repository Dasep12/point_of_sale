<style>
  .excel_file {
    position: absolute;
    font-size: 50px;
    opacity: 0;
    right: 0;
    top: 0;
  }
</style>
<!-- Modal -->
<div class="modal fade" id="CrudAdjustUploadModalUpload" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="CrudAdjustUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CrudAdjustUploadModalLabel"><i class="fa fa-plus-square"></i> Upload Adjust</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row mb-0">
          <div class="col-md-7">
            <form action="{{ url('administrator/uploadFilesAdjust') }}" enctype="multipart/form-data" method="post" id="CrudAdjustUploadFormUpload">
              @csrf
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group form-group-sm">
                    <label for="">Type Adjust</label>
                    <select name="type_adjust_upload" class="form-control custom-select" id="type_adjust_upload">
                      <option value="in">Adjust Plus(+)</option>
                      <option value="out">Adjust Minus(-)</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">

                  <div class="form-group form-group-sm">
                    <label for="">File Upload</label>
                    <div id="btn-upload" style="position: relative;overflow: hidden;cursor:pointer" class="btn btn-dark btn-sm btn-block">
                      <i class="fa fa-upload"></i> Select File
                      <input type="file" style="cursor:pointer" id="files_upload" name="files_upload" class="form-control-file excel_file" required>
                    </div>
                  </div>
                </div>
              </div>

            </form>
          </div>
        </div>

        <div class="row mb-2 mt-1">
          <div class="col-lg-12 mb-1">
            <div class="progress" style="display: none;height:35px">
              <div id="errorText" class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 0%;"></div>
            </div>
          </div>
        </div>
        <form action="#" enctype="multipart/form-data" method="post" id="CrudAdjustUploadForm2">
          @csrf
          <div class="row">
            <div class="col md-12">
              <table id="JqGridTempUpload"></table>
              <div id="jqGridPager2"></div>
              <a href="" class="mt-2 btn btn-sm btn-outline-secondary"><i class="fa fa-file-excel"></i> Download Template</a>
            </div>
          </div>
          <div class="row mt-1" id="ErrorInfoUpload"></div>
          <input type="text" hidden name="CrudActionAdjustUpload" id="CrudActionAdjustUpload">
      </div>

      <div class="modal-footer">
        <button id="btnUploadTrans" type="button" class="btn btn-sm btn-primary btn-upload-file"><i class="fa fa-save"></i> Submit</button>
        <button id="btnUploadCancel" data-dismiss="modal" type="button" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Batal</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
  $('#CrudAdjustUploadModalUpload').on('shown.bs.modal', function() {
    $(window).on('resize', function() {
      var gridWidth = $('#JqGridTempUpload').closest('.ui-jqgrid').parent().width();
      $('#JqGridTempUpload').jqGrid('setGridWidth', gridWidth);
    }).trigger('resize');
  });

  // Initialize jqGrid
  $("#JqGridTempUpload").jqGrid({
    datatype: "local",
    data: [],
    colModel: [{
      name: 'id',
      label: 'Id',
      hidden: true,
      key: true,
    }, {
      label: 'Item',
      name: 'item_name',
    }, {
      label: 'Kode Item',
      name: 'kode_item',
    }, {
      label: 'Merek',
      name: 'merek',
    }, {
      label: 'Satuan',
      name: 'satuan',
      align: 'center',
    }, {
      label: 'Qty',
      name: 'qty',
      align: 'center',
    }, {
      label: 'Aksi',
      name: 'action',
      align: 'center',
      formatter: actionListUpload
    }],
    loadonce: false,
    viewrecords: true,
    rownumbers: true,
    rownumWidth: 30,
    autoresizeOnLoad: true,
    gridview: true,
    width: '100%',
    rowNum: 20,
    shrinkToFit: true,
    rowList: [10, 20],
    pager: "#jqGridPager2",
    loadComplete: function(data) {
      $(window).on('resize', function() {
        var gridWidth = $('#JqGridTempUpload').closest('.ui-jqgrid').parent().width();
        $('#JqGridTempUpload').jqGrid('setGridWidth', gridWidth);
      }).trigger('resize');
    },
  });

  function partExists(idx) {
    return dataTemp.some(function(el) {
      return el.id == idx;
    });
  }

  // Trigger form submission when a file is selected
  $('#files_upload').on('change', function() {
    if ($(this).val()) {
      $('.progress').hide();
      // If file is selected, submit the form
      $('#CrudAdjustUploadFormUpload').submit();
    }
  });

  $('#CrudAdjustUploadFormUpload').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      beforeSend: function() {
        dataTemp = [];
        // Show the progress bar and reset its state
        $('.progress').show();
        $('.progress-bar').css('width', '0%');
      },
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(e) {
          if (e.lengthComputable) {
            var percentComplete = Math.round((e.loaded / e.total) * 100);
            // Update progress bar
            $('.progress-bar').css('width', percentComplete + '%');
            $('.progress-bar').html('Uploading');
            $('.progress-bar').attr('aria-valuenow', percentComplete);
          }
        }, false);

        return xhr;
      },
      success: function(response) {
        $('.progress-bar').css('width', '100%');
        $("#file-upload").val('');
        $("#errorText").removeClass('bg-danger');
        $("#errorText").addClass('bg-success');
        $('.progress-bar').html('<h5 class="mt-2"><i class="fa fa-check"></i> Upload Success</h5>');

        if (response.success) {
          var resp = response.data;
          for (let r = 0; r < resp.length; r++) {
            var datas = {
              id: resp[r].kode_item,
              item_id: resp[r].kode_item,
              item_name: resp[r].item_name,
              satuan_id: resp[r].satuan_id,
              satuan: resp[r].satuan,
              kode_item: resp[r].kode_item,
              merek: resp[r].merek,
              qty: resp[r].qty
            }
            if (partExists(443)) {
              // console.log("data has been exist " + resp[r].uniq)
            } else {
              dataTemp.push(datas);
            }
          }
          reloadgridItemAdjustUpload(dataTemp)
          $(".btn-upload-file").attr("disabled", false);
          $('#ErrorInfoUpload').html('');
        } else {
          var error = response.errors;
          var errMsg = '<div class="col-md-12"><div class="alert alert-custom-warning alert-warning alert-dismissible fade show" role="alert"><small><b> Error !</b><br/><ul>';
          for (er = 0; er < error.length; er++) {
            errMsg += '<li>'
            errMsg += '<b>' + error[er] + '</b>'
            errMsg += '</li>'
          }
          errMsg += '</ul></small><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div></div>'
          $('#ErrorInfoUpload').html(errMsg);
          $(".btn-upload-file").attr("disabled", true);
          setTimeout(() => {
            $('.progress').hide();
          }, 1500);
        }
      },
      error: function(xhr, desc, err) {
        var respText = "";
        try {
          respText = eval(xhr.responseText);
        } catch {
          respText = xhr.responseJSON.message;
        }
        $("#errorText").removeClass('bg-success');
        $("#errorText").addClass('bg-danger');
        var errMsg = '<div class="col-md-12"><div class="alert alert-custom-warning alert-warning alert-dismissible fade show" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div></div>'
        $('#errorText').html(`Error ${xhr.status} ! ${respText} `);
        // $('#upload-result').html(errMsg);
        $(".btn-upload-file").attr("disabled", true);
      }
    });
  });

  $("#CrudAdjustUploadForm2").parsley();

  $("#CrudAdjustUploadForm2").submit(function(e) {
    var allData = $("#JqGridTempUpload").jqGrid('getRowData');
    $.ajax({
      url: "{{ url('jsonImportStock') }}",
      method: 'POST',
      cache: false,
      data: {
        "_token": "{{ csrf_token() }}",
        supplier_id: $("#suppliers_id").val(),
        allData: allData,
      },
      success: function(response) {
        if (response.success) {
          reloadGridList()
          reloadgridItem(dataTemp)
          $("#CrudAdjustUploadModalUpload").modal('hide');
          doSuccess('stock', $("#CrudActionStockUpload").val())
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
        var errMsg = '<div class="col-md-12"><div class="alert alert-custom-warning alert-warning alert-dismissible fade show" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div></div>'
        $('#ErrorInfoUpload').html(errMsg);
      },
    });
  })
</script>