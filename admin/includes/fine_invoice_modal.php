
<!-- Fine Details Modal -->
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="fineDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fineDetailsModalLabel"><b>Fine Invoice</b></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Fine details will be populated here -->
                <div class="mb-0 mx-3">
                    <strong>Full Name:</strong> <span id="borrower_name"></span><br>
                    <strong id="borrower_id_label"></strong> <span id="borrower_id"></span><br>
                    <strong>Borrower Type:</strong> <span id="borrower_type"></span><br>
                    <strong id="borrower_program_label"></strong> <span id="borrower_program"></span><br>
                </div>
                <hr>
                <div class="mb-3 mx-3">
                    <strong>Accession No:</strong> <span id="accession"></span><br>
                    <strong>Title:</strong> <span id="title"></span><br>
                    <strong>Date Borrowed:</strong> <span id="date_borrowed"></span><br>
                    <strong>Due Date:</strong> <span id="due_date"></span><br>
                </div>
                <hr>
                <div class="mb-3 mx-3">
                    <strong>Fine Amount:</strong> ₱<span id="fineamount"></span><br>
                    <strong>Overdue:</strong> <span id="overdue_days"></span> days<br>
                    <strong>Status:</strong> <span id="status_badge"></span><br>
                    <strong>Date Paid:</strong> <span id="date_paid"></span><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fetchFineDetails(fine_id) {
    $.ajax({
        url: 'fetch_fine_details.php',
        type: 'GET',
        data: { fine_id: fine_id },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.error) {
                alert(data.error);
            } else {
                // Populate modal fields
                $('#borrower_name').text(data.borrower_name);
                $('#borrower_id').text(data.borrower_id);
                $('#borrower_id_label').text(data.borrower_type === 'Faculty' ? 'Faculty ID:' : 'Student ID:');
                $('#borrower_type').text(data.borrower_type);
                $('#borrower_program').text(data.borrower_program);
                $('#borrower_program_label').text(data.borrower_type === 'Faculty' ? 'Position:' : 'Program:');
                $('#accession').text(data.accession);
                $('#title').text(data.title);
                $('#date_borrowed').text(data.date_borrowed);
                $('#due_date').text(data.due_date);
                $('#fineamount').text(data.fineamount);
                $('#overdue_days').text(data.overdue_days);
                $('#status_badge').html(data.status_badge);
                $('#date_paid').text(data.date_paid);

                // Show or hide Mark as Paid button
                if (data.status === 'unpaid') {
                    $('#markAsPaidButton').show();
                } else {
                    $('#markAsPaidButton').hide();
                }

                // Open the corresponding modal
                $('#view' + fine_id).modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error('An error occurred while fetching fine details:', error);
        }
    });
}


</script>
