<?php include 'includes/conn.php'; ?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Add New Faculty/Staff</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="faculty_add.php" enctype="multipart/form-data">
                <div class="form-group row mx-0">
                    <label for="firstname" class="col-sm-3 col-form-label">First Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="middlename" class="col-sm-3 col-form-label">Middle Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="middlename" name="middlename">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="position" class="col-sm-3 col-form-label">Position</label>

                    <div class="col-sm-9">
                        <select class="form-control" id="position" name="position" required>
                            <option value="" selected hidden>Select Position</option>
                            <option value="0">Faculty</option>
                            <option value="1">COS Faculty</option>
                            <option value="2">Faculty in External Campus</option>
                            <option value="3">Non-Teaching Staff</option>
                        </select>
                    </div>
                </div>

                <!-- Program Dropdown (initially hidden) -->
                <div class="form-group row mx-0" id="programDiv" style="display: none;">
                    <label for="program" class="col-sm-3 col-form-label">Program</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="program" id="program" style="width: 100%;" required>
                            <option value="" selected hidden>Select Program</option>
                            <?php
                                $qry = mysqli_query($conn, "SELECT * FROM program");
                                while ($qry2 = mysqli_fetch_array($qry)) {
                                    echo "<option value='".$qry2['id']."'>".$qry2['code']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group row mx-0">
                    <label for="created" class="col-sm-3 col-form-label">Created on</label>

                    <div class="col-sm-9">
                      <input type="date" class="form-control" id="created" name="created" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<?php

$qry = mysqli_query($conn, "SELECT faculty.id, faculty.faculty_id, faculty.firstname, faculty.middlename, faculty.lastname, faculty.position, program.code, program.title FROM faculty LEFT JOIN program ON program.id=faculty.program_id;");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<div class="modal fade" id="edit<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Edit Faculty</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
                $edit = mysqli_query($conn, "SELECT * FROM faculty WHERE id = '".$qry2['id']."'");
                $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="faculty_edit.php?id=<?php echo $erow['id']; ?>">
                <div class="form-group row mx-0">
                    <label for="facultyid" class="col-sm-3 col-form-label">Faculty ID</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="facultyid" name="facultyid" value="<?php echo $erow['faculty_id']; ?>" readonly required>
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="firstname" class="col-sm-3 col-form-label">First Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $erow['firstname']; ?>" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="middlename" class="col-sm-3 col-form-label">Middle Initial</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $erow['middlename']; ?>" >
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $erow['lastname']; ?>" required>
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="program" class="col-sm-3 col-form-label">Position</label>
                    <div class="col-sm-9">
                    <select class="form-control" id="position" name="position" required>
                        <option hidden <?php echo "value='".$qry2['position']."'>".($qry2['position'] == 1 ? 'Teaching' : 'Non-Teaching')."</option>"; ?> selected id="selposition"></option>
                        <option value="0" <?php echo $qry2['position'] == 0 ? "selected" : ""; ?>>Faculty</option>
                        <option value="1" <?php echo $qry2['position'] == 1 ? "selected" : ""; ?>>COS Faculty</option>
                        <option value="2" <?php echo $qry2['position'] == 2 ? "selected" : ""; ?>>Faculty in External Campus</option>
                        <option value="3" <?php echo $qry2['position'] == 3 ? "selected" : ""; ?>>Non-Teaching Staff</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php

$qry = mysqli_query($conn, "SELECT * FROM faculty;");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<!-- Delete -->
<div class="modal fade" id="delete<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"><b>Delete Faculty</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
              $edit=mysqli_query($conn,"SELECT * FROM faculty WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="faculty_delete.php?id=<?php echo $erow['id']; ?>">
                <input type="hidden" class="studid" name="id">
                <div class="text-center">
                    <p>Confirm deletion?</p>
                    <h2 class="del_stu" style="font-weight: bold"><?php echo $erow['lastname'] . ', ' . $erow['firstname'] . ' ' . $erow['middlename']; ?></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php
$qry = mysqli_query($conn, "SELECT * FROM faculty;");
while ($qry2 = mysqli_fetch_array($qry)) {
?>
<!-- View -->
<div class="modal fade" id="view<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Faculty Profile</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group row mx-0">
                        <div class="text-center col-xl-12 mt-3 mb-4">
                            <?php if (!empty($qry2['photo'])): ?>
                                <img src="../images/<?php echo $qry2['photo']; ?>" class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover;" alt="Faculty Photo">
                            <?php else: ?>
                                <img src="../images/default.jpg" class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover;" alt="Default Photo">
                            <?php endif; ?>
                        </div>
                        <div class="col-xl-12 mx-3">
                            <strong>Full Name:</strong> <?php echo ucwords($qry2['lastname']) . ', ' . ucwords($qry2['firstname']) . ' ' . ucwords($qry2['middlename']); ?><br>
                            <strong>Faculty ID:</strong> <?php echo $qry2['faculty_id']; ?><br>
                            <strong>Position:</strong> <?php echo ($qry2['position'] == 0 ? 'Faculty' : ($qry2['position'] == 1 ? 'COS Faculty' : ($qry2['position'] == 2 ? 'Faculty in External Campus' : 'Non-Teaching Staff'))); ?><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Import Records -->

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><b>Import Records</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- File upload form inside modal -->
        <form action="import_faculty.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="excel_file" class="control-label">Select Spreadsheet File:</label>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button type="submit" name="import" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i> Import</button>
        </form>
      </div>
    </div>
  </div>
</div>



<script>
    document.getElementById('position').addEventListener('change', function() {
        var selectedPosition = this.value;
        var programDiv = document.getElementById('programDiv');

        // Show program dropdown if Faculty or COS Faculty is selected
        if (selectedPosition == '0' || selectedPosition == '1') {
            programDiv.style.display = 'flex'; // Use 'flex' for proper alignment
        } else {
            programDiv.style.display = 'none'; // Hide the program dropdown
        }
    });
</script>