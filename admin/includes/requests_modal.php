<!-- Approve -->
<?php

$qry = mysqli_query($conn, "SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, f.faculty_id AS id, f.firstname as faculty_firstname, f.lastname as faculty_lastname, b.accession as accession, b.title as title FROM requests r LEFT JOIN books b ON r.book_id = b.id LEFT JOIN faculty f ON r.faculty_id = f.id WHERE r.faculty_id IS NOT NULL UNION SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, s.student_id AS id, s.firstname as student_firstname, s.lastname as student_lastname, b.accession as accession, b.title as title FROM requests r LEFT JOIN books b ON r.book_id = b.id LEFT JOIN students s ON r.student_id = s.id WHERE r.student_id IS NOT NULL ORDER BY request_date DESC");

while ($row = mysqli_fetch_array($qry)) {

?>
<div class="modal fade" id="approve<?php echo $row['req_id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Approve Borrow Request</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    $req=mysqli_query($conn,"SELECT * FROM requests WHERE id='".$row['req_id']."'");
                    $erow=mysqli_fetch_array($req);
                ?>
                <form class="form-horizontal" method="POST" action="request_approve.php?id=<?php echo $erow['id']; ?>">
                    <input type="hidden" name="request_id" value="<?php echo $erow['id']; ?>">
                    <div class="text-center">
                        <p>Are you sure you want to approve this request?</p>
                        <p class="ml-4" style="margin-bottom: 0; padding-bottom: 0; text-align:left;"><strong>Borrower Name:</strong> 
                            <?php 
                                if (isset($row['faculty_firstname'])) {
                                    echo $row['faculty_firstname'] . ' ' . $row['faculty_lastname'];
                                } elseif (isset($row['student_firstname'])) {
                                    echo $row['student_firstname'] . ' ' . $row['student_lastname'];
                                }
                            ?>
                        </p>
                        <p class="ml-4" style="margin-bottom: 0; padding-bottom: 0; text-align:left;"><strong>Accession Number:</strong> <?php echo $row['accession']; ?></p>
                        <p class="ml-4" style="margin-bottom: 0; padding-bottom: 0; text-align:left;"><strong>Book Title:</strong> <?php echo $row['title']; ?></p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="approve"><i class="fa fa-check"></i> Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Decline -->
<?php
$qry = mysqli_query($conn, "SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, f.faculty_id AS id, f.firstname as faculty_firstname, f.lastname as faculty_lastname, b.accession as accession, b.title as title FROM requests r LEFT JOIN books b ON r.book_id = b.id LEFT JOIN faculty f ON r.faculty_id = f.id WHERE r.faculty_id IS NOT NULL UNION SELECT r.id AS req_id, r.request_date, r.decision_date, r.status, r.book_id, s.student_id AS id, s.firstname as student_firstname, s.lastname as student_lastname, b.accession as accession, b.title as title FROM requests r LEFT JOIN books b ON r.book_id = b.id LEFT JOIN students s ON r.student_id = s.id WHERE r.student_id IS NOT NULL ORDER BY request_date DESC");

while ($row = mysqli_fetch_array($qry)) {
?>
<div class="modal fade" id="decline<?php echo $row['req_id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Decline Request</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    $req = mysqli_query($conn, "SELECT * FROM requests WHERE id='" . $row['req_id'] . "'");
                    $erow = mysqli_fetch_array($req);
                ?>
                <form class="form-horizontal" method="POST" action="request_decline.php?id=<?php echo $erow['id']; ?>">
                    <input type="hidden" name="request_id" value="<?php echo $erow['id']; ?>">
                    <div class="text-center">
                        <p>Are you sure you want to decline this request?</p>
                        <h5 class="request_details" style="font-weight: bold;">
                            <?php 
                                if (isset($row['faculty_firstname'])) {
                                    echo $row['faculty_firstname'] . ' ' . $row['faculty_lastname'];
                                } elseif (isset($row['student_firstname'])) {
                                    echo $row['student_firstname'] . ' ' . $row['student_lastname'];
                                }
                            ?> for <?php echo $row['title']; ?>
                        </h5>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat" name="decline"><i class="fa fa-times"></i> Decline</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
