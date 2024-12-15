<div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script>$(document).ready(function() {
    // Trigger modal open and pass the accession number dynamically
    $('#dataTableBooks').on('click', '.btn-primary', function(e) {
        e.preventDefault();

        var accession = $(this).closest('tr').find('td').eq(0).text(); // Get the Accession No.
        $('#requestAccession').text(accession); // Set the Accession No. in the modal

        // Open the modal
        $('#requestModal').modal('show');
    });

    // Manually trigger closing the modal when the close button is clicked
    $('.close, .btn-secondary').on('click', function() {
        $('#requestModal').modal('hide');
    });

    // Confirm button
    $('#confirmRequest').on('click', function() {
        // You can handle the request logic here and redirect or update the page
    });
});
</script>