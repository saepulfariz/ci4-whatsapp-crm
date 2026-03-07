<!-- jQuery -->
<script src="<?= asset_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= asset_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="<?= asset_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= asset_url(); ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= asset_url(); ?>assets/dist/js/adminlte.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/select2/js/select2.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/select2/js/select2.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/coloris/dist/coloris.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/summernote/summernote-bs4.min.js"></script>

<script src="<?= asset_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/blockui/blokui.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/blockui/loader.js"></script>
<!-- <script src="<?= asset_url(); ?>assets/plugins/blockui/angular.js"></script> -->


<link href="<?= asset_url(); ?>assets/plugins/date/daterangepicker.css" rel="stylesheet">
<script type="text/javascript" src="<?= asset_url(); ?>assets/plugins/date/date_moment.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>assets/plugins/date/date_range.js"></script>

<!-- ChartJS -->
<!-- <script src="<?= asset_url(); ?>assets/plugins/chart.js/Chart.min.js"></script> -->
<script src="<?= asset_url(); ?>assets/plugins/chart.js/Chart3.js"></script>
<script src="<?= asset_url(); ?>assets/plugins/chart.js/chartjs-plugin-datalabels.min.js"></script>


<script>
  $('form').on('submit', function(e) {
    // Ubah button submit jadi loading
    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true);
    $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
    e.target.submit();
  })


  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary',
      cancelButton: 'btn btn-gray'
    },
    buttonsStyling: false
  });

  // if session error exists, show alert
  <?php if (session('error') !== null) : ?>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '<?= session('error'); ?>',
      confirmButtonText: 'OK'
    });
  <?php endif; ?>

  // if session success exists, show alert
  <?php if (session('success') !== null) : ?>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '<?= session('success'); ?>',
      confirmButtonText: 'OK'
    });
  <?php endif; ?>

  function confirmDelete(e) {
    const ket = e.getAttribute('data-ket');
    const href = e.getAttribute('data-href') ? e.getAttribute('data-href') : e.getAttribute('href');
    Swal.fire({
      title: '<?= temp_lang('app.confirm_title'); ?>',
      text: ket,
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: '<?= temp_lang('app.cancel'); ?>',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?= temp_lang('app.confirm_btn'); ?>'
    }).then((result) => {

      if (result.value) {
        if (href) {
          window.location.href = href;
        } else {
          e.parentElement.submit();
        }
      }
    })
    // e.preventDefault();
  }
</script>

<script>
  $.fn.select2.defaults.set("theme", "bootstrap");

  $('select.form-control').select2({
    theme: 'bootstrap4',
    width: '100%' // need to override the changed default
    // width: 'resolve' // need to override the changed default
  })

  function previewImage(input, previewDom) {

    if (input.files && input.files[0]) {

      $(previewDom).show();

      var reader = new FileReader();

      reader.onload = function(e) {
        $(previewDom).find('img').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    } else {
      $(previewDom).hide();
    }

  }

  $(function() {
    $("#table2").DataTable({
      "responsive": true,
      dom: 'Bflrtip',
      buttons: [{
        extend: 'excel',
        className: "btn bg-tranparent btn-sm btn-success",
        footer: true
      }, ],
      "pageLength": 5,
      "lengthMenu": [
        [5, 100, 1000, -1],
        [5, 100, 1000, "ALL"],
      ],
    });

  });

  function setDataTables(id) {
    $(id).DataTable({
      "responsive": true,
      dom: 'Bflrtip',
      buttons: [{
        extend: 'excel',
        className: "btn bg-tranparent btn-sm btn-success",
        footer: true
      }, ],
      "pageLength": 5,
      "lengthMenu": [
        [5, 100, 1000, -1],
        [5, 100, 1000, "ALL"],
      ],
    });
  }

  setTimeout(function() {
    rangetanggal();
  }, 100);

  function rangetanggal() {

    $('#f1').daterangepicker({
      "showDropdowns": true,
      // minDate: 0,
      // maxDate: 365, // max 1 tahun

      // bener bawah
      // minDate: new Date().setDate(new Date() - 3),
      // maxDate: new Date(),
      isInvalidDate: function(date) {
        const startDate = $('#f1').data('daterangepicker').startDate;

        // Jika startDate belum dipilih, semua tanggal valid
        if (!startDate) return false;

        // Hitung selisih hari
        const diffDays = date.diff(startDate, 'days') + 1;

        // Nonaktifkan tanggal jika rentang lebih dari 3 hari
        // return diffDays > 3;
      },
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        // '3 day ago': [moment().subtract(3, 'days'), moment()],
        '1 week ago': [moment().subtract(7, 'days'), moment()],
        '30 Hari yang lalu': [moment().subtract(29, 'days'), moment()],
        //    'This Month': [moment().startOf('month'), moment().endOf('month')],
        //    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Customize",
        "weekLabel": "W",
        "daysOfWeek": [
          "Min",
          "Sen",
          "Sel",
          "Rab",
          "Kam",
          "Jum",
          "Sab",

        ],
        "monthNames": [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember"
        ],
        "firstDay": 1
      },
      "startDate": moment(),
      "endDate": moment(),
      "opens": "left"
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY') + ' (predefined range: ' + label + ')');

    });
  }
</script>

<?= $this->renderSection('script'); ?>