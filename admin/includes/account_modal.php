<?php include 'includes/conn.php'; ?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Add New Account</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="account_add.php" enctype="multipart/form-data">
                <div class="form-group row mx-0">
                    <label for="firstname" class="col-sm-3 col-form-label">First Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>


                <div class="form-group row mx-0">
                    <label for="password" class="col-sm-3 col-form-label">Password</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="photo" class="col-sm-3 col-form-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="role" class="col-sm-3 col-form-label">Role</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="role" name="role" required>
                        <option value="" selected hidden>Select Role</option>
                        <option id="selrole" value="1">Admin</option>
                        <option id="selrole" value="0">Staff</option>
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

$qry = mysqli_query($conn, "SELECT * FROM admin;");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<div class="modal fade" id="edit<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Edit Account</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
              $edit=mysqli_query($conn,"SELECT * FROM admin WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="account_edit.php?id=<?php echo $erow['id']; ?>" enctype="multipart/form-data">
              <div class="form-group row mx-0">
                    <label for="firstname" class="col-sm-3 col-form-label">First Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $erow['firstname']; ?>">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $erow['lastname']; ?>">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="username" name="username" value="<?php echo $erow['username']; ?>">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="password" name="password" value="<?php echo $erow['password']; ?>">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="photo" class="col-sm-3 col-form-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="Role" class="col-sm-3 col-form-label">Role</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="role" name="role">
                            <option value="0" <?php echo ($qry2['role'] == 0) ? 'selected' : ''; ?>>Admin</option>
                            <option value="1" <?php echo ($qry2['role'] == 1) ? 'selected' : ''; ?>>Staff</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="status" name="status" required>
                        <option value="" selected hidden>Change Status</option>
                        <option id="selstat" value="0" <?php echo ($qry2['status'] == 0) ? 'selected' : ''; ?>>Active</option>
                        <option id="selstat" value="1" <?php echo ($qry2['status'] == 1) ? 'selected' : ''; ?>>Inactive</option>
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

$qry = mysqli_query($conn, "SELECT * FROM admin;");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<!-- Delete -->
<div class="modal fade" id="delete<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"><b>Delete Account</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
              $edit=mysqli_query($conn,query: "SELECT * FROM admin WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="account_delete.php?id=<?php echo $erow['id']; ?>" enctype="multipart/form-data">
                <input type="hidden" class="studid" name="id">
                <div class="text-center">
                    <p>Confirm account deletion?</p>
                    <?php if (!empty($erow['photo'])): ?>
                      <img class="mb-3" src="<?php echo (!empty($erow['photo'])) ? '../images/'.$erow['photo'] : '../images/'; ?>" class="img-fluid rounded" alt="User Photo" style="max-width: 150px; max-height: 150px;">
                      <?php else: ?>
                        <p>No photo available</p>
                    <?php endif; ?>
                    
                    <h2 class="del_stu" style="font-weight: bold">
                        <?php echo $erow['username'] . ': ' . $erow['lastname'] . ', ' . $erow['firstname']; ?>
                    </h2>
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

<!-- Update Photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="del_stu"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="student_edit_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="studid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 col-form-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>
