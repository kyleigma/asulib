<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;700;900&display=swap" rel="stylesheet">
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script></script>
<script>
 $('li').click(function() {
        $('li.active').removeClass('active');
        $(this).addClass('active');
    })
</script>
<!-- Data Table Initialize -->
<script>
  $(function () {
    $('#example1').DataTable({
      responsive: true
    })
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<!-- DataTables Configuration: Show 25 Entries and Replace 0 with Empty Cells -->
<script>
    $(document).ready(function() {
        $('#dataTableStats1').DataTable({
            "paging": true,      // Enable pagination
            "pageLength": 25,    // Set default number of entries per page to 25
            "ordering": true,    // Enable column ordering
            "info": true,        // Show table information display
            "searching": true,   // Enable search functionality
            "language": {
                "zeroRecords": "No records to display",  // Custom message for no records
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#dataTableStats2').DataTable({
            "paging": true,      // Enable pagination
            "pageLength": 25,    // Set default number of entries per page to 25
            "ordering": true,    // Enable column ordering
            "info": true,        // Show table information display
            "searching": true,   // Enable search functionality
            "language": {
                "zeroRecords": "No records to display",  // Custom message for no records
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#dataTableFac').DataTable({
            "paging": true,      // Enable pagination
            "pageLength": 50,    // Set default number of entries per page to 25
            "ordering": true,    // Enable column ordering
            "info": true,        // Show table information display
            "searching": true,   // Enable search functionality
            "language": {
                "zeroRecords": "No records to display",  // Custom message for no records
            }
        });
    });
</script>
<!-- Date and Timepicker -->
<script>
  //Date picker
  $('#datepicker_add').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })
  $('#datepicker_edit').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  }) 
</script>

<script>
  $(document).ready(function() {
      $('#edit').on('show.bs.modal', function(event) {
          var button = $(event.relatedTarget); // Button that triggered the modal
          var student_id = button.data('id');
          var student_firstname = button.data('firstname');
          var student_lastname = button.data('lastname');
          var student_course = button.data('course');

          var modal = $(this);
          modal.find('.studid').val(student_id);
          modal.find('#edit_firstname').val(student_firstname);
          modal.find('#edit_lastname').val(student_lastname);
          modal.find('#course').val(student_course);
      });
  });
</script>
