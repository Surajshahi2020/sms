<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>

<!-- jQuery UI (only if you use datepicker, sortable, etc.) -->
<!-- If not used, comment out or remove -->
<!--
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button); // Only needed if using jQuery UI buttons
</script>
-->

<!-- Bootstrap 4 Bundle (Popper + Bootstrap JS) -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DataTables (Keep if you use tables) -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- OverlayScrollbars (Optional - for custom scrollbars in sidebar) -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- AdminLTE App (Required for layout/sidebar) -->
<script src="assets/dist/js/adminlte.min.js"></script>

<!-- ✅ REMOVED: dashboard.js — it was crashing your page -->
<!-- <script src="assets/dist/js/pages/dashboard.js"></script> -->

<!-- Optional: demo.js usually contains theme demos — remove in production -->
<!-- <script src="assets/dist/js/demo.js"></script> -->

<!-- html2pdf (Keep only if you generate PDFs on this page) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script> -->

<!-- Custom JS: DataTables initialization -->
<script>
$(document).ready(function() {
  // Initialize DataTables only if table exists
  if ($("#example1").length) {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  }

  if ($("#example2").length) {
    $("#example2").DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  }

  // Optional: Re-init modals for safety (fixes modal close issues)
  $('.modal').modal({ show: false });

  // Force modal close binding (extra safety)
  $(document).on('click', '[data-dismiss="modal"]', function() {
    $($(this).closest('.modal')).modal('hide');
  });
});
</script>