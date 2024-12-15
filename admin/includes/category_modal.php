<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Add New Category</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="category_add.php">
                <div class="form-group row mx-0">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" required>
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

<?php
    $qry = mysqli_query($conn, "SELECT * from category");
    while ($qry2 = mysqli_fetch_array($qry)) {
?>
<!-- Edit -->
<div class="modal fade" id="edit<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Edit Category</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
              $edit=mysqli_query($conn,"SELECT * FROM category WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="category_edit.php?id=<?php echo $erow['id']; ?>">
                <input type="hidden" class="catid" name="id">
                <div class="form-group row mx-0">
                    <label for="edit_name" class="col-sm-3 col-form-label">Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_name" name="name" value="<?php echo $erow['name']; ?>">
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
<?php }?>

<?php
    $qry = mysqli_query($conn, "SELECT * from category");
    while ($qry2 = mysqli_fetch_array($qry)) {
?>
<!-- Delete -->
<div class="modal fade" id="delete<?php echo $qry2['id']; ?>"">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
              <h4 class="modal-title"><b>Delete Category</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <?php
              $edit=mysqli_query($conn,"SELECT * FROM category WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
              <form class="form-horizontal" method="POST" action="category_delete.php?id=<?php echo $erow['id']; ?>">
                <input type="hidden" class="catid" name="id">
                <div class="text-center">
                    <p>Confirm deletion?</p>
                    <h2 id="del_cat" style="font-weight: bold"><?php echo $erow['name']; ?></h2>
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
<?php }?>

     