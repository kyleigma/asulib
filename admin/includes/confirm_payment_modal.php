<!-- Confirmation Modal for Payment -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" role="dialog" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="confirmPaymentModalLabel"><b>Confirm Payment</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this fine as paid?
            </div>
            <div class="modal-footer">
                <form action="mark_as_paid.php" method="POST">
                    <input type="hidden" id="fine_id" name="fine_id">
                    <input type="hidden" id="student_id" name="student_id">
                    <input type="hidden" id="faculty_id" name="faculty_id"> <!-- Faculty ID -->
                    <input type="hidden" id="fine_amount" name="fine_amount">
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setPaymentData(fine_id, student_id, faculty_id, fine_amount) {
        document.getElementById('fine_id').value = fine_id;
        document.getElementById('student_id').value = student_id;
        document.getElementById('faculty_id').value = faculty_id;
        document.getElementById('fineamount').value = fine_amount;
    }

    function confirmDelete(fine_id) {
        if (confirm('Are you sure you want to delete this fine record?')) {
            window.location.href = 'delete_fine.php?id=' + fine_id;
        }
    }
</script>
