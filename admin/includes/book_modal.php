<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          	<div class="modal-header">
              <h4 class="modal-title"><b>Add New Book</b></h4>
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="book_add.php">
                <div class="form-group row mx-0">
                    <label for="category" class="col-sm-3 col-form-label">Category</label>

                    <div class="col-sm-9">
                        <select class="form-control" name="category" id="category" required>
                            <option value="" selected hidden>Select Category</option> 
                            <?php
                                // Fetch categories from the database
                                $qry = mysqli_query($conn, "SELECT * FROM category");
                                while ($qry2 = mysqli_fetch_array($qry)) {
                                    echo "<option value='" . htmlspecialchars($qry2['id']) . "'>" . htmlspecialchars($qry2['name']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mx-0">
                  	<label for="category_no" class="col-sm-3 col-form-label">Category Number</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="category_no" name="category_no" step=".00001" required>
                  	</div>
                </div>
                <div class="form-group row mx-0">
                  	<label for="accession" class="col-sm-3 col-form-label">Accession Number</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="accession" name="accession" required>
                  	</div>
                </div>
                <div class="form-group row mx-0">
                  	<label for="volume" class="col-sm-3 col-form-label">Volume</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="volume" name="volume" required>
                  	</div>
                </div>
                <div class="form-group row mx-0">
                    <label for="title" class="col-sm-3 col-form-label">Title</label>

                    <div class="col-sm-9">
                      <textarea class="form-control" name="title" id="title" required></textarea>
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="author" class="col-sm-3 col-form-label">Author</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="publisher" class="col-sm-3 col-form-label">Publisher</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="publisher" name="publisher" required>
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="publish_date" class="col-sm-3 col-form-label">Publish Date</label>

                    <div class="col-sm-9">
                      <div class="date">
                        <input type="number" class="form-control" id="publish_date" name="publish_date" required placeholder="YYYY" min="1500" max="2900">
                          <script>
                            document.querySelector("input[type=number]")
                            .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))
                          </script>
                      </div>
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

$qry = mysqli_query($conn, "SELECT b.id, b.category_no, b.accession, b.volume, b.title, b.author, b.publisher, b.publish_date, b.status, c.name FROM books b LEFT JOIN category c ON c.id = b.category_id");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<!-- Edit -->
<div class="modal fade" id="edit<?php echo $qry2['id']; ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          	<div class="modal-header">
              <h4 class="modal-title"><b>Edit Book</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
          	</div>
          	<div class="modal-body">
            <?php
              $edit=mysqli_query($conn,"SELECT * FROM books WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
            <form class="form-horizontal" method="POST" action="book_edit.php?id=<?php echo $erow['id']; ?>">
                <div class="form-group row mx-0">
                    <label for="category" class="col-sm-3 col-form-label">Category</label>

                    <div class="col-sm-9">
                      <select class="form-control" name="category" id="category" required>
                        <option hidden <?php echo "value='".$qry2['id']."'>".$qry2['name']."</option>"; ?> selected id="selcateg"></option>
                        <?php
                          $sql = "SELECT * FROM category";
                          $query = $conn->query($sql);
                          while($row = $query->fetch_array()){
                            echo "
                              <option value='".$row['id']."'>".$row['name']."</option>
                            ";
                          }
                        ?>
                      </select>
                    </div>
                </div>
                <div class="form-group row mx-0">
                  	<label for="category_no" class="col-sm-3 col-form-label">Category Number</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="category_no" name="category_no" value="<?php echo $erow['category_no']; ?>">
                  	</div>
                </div>
                <div class="form-group row mx-0">
                  	<label for="accession" class="col-sm-3 col-form-label">Accession Number</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="accession" name="accession" value="<?php echo $erow['accession']; ?>">
                  	</div>
                </div>

                <div class="form-group row mx-0">
                  	<label for="volume" class="col-sm-3 col-form-label">Volume</label>

                  	<div class="col-sm-9">
                    	<input type="number" class="form-control" id="volume" name="volume" value="<?php echo $erow['volume']; ?>">
                  	</div>
                </div>

                <div class="form-group row mx-0">
                    <label for="title" class="col-sm-3 col-form-label">Title</label>

                    <div class="col-sm-9">
                      <textarea class="form-control" name="title" id="title"><?php echo $erow['title']; ?></textarea>
                    </div>
                </div>
                
                <div class="form-group row mx-0">
                    <label for="author" class="col-sm-3 col-form-label">Author</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="author" name="author" value="<?php echo $erow['author']; ?>">
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="publisher" class="col-sm-3 col-form-label">Publisher</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo $erow['publisher']; ?>">
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="publish_date" class="col-sm-3 col-form-label">Publish Date</label>

                    <div class="col-sm-9">
                      <div class="date">
                        <input type="number" class="form-control" id="publish_date" name="publish_date" required placeholder="YYYY" min="1500" max="2900" value="<?php echo $erow['publish_date']; ?>">                      </div>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="edit"><i class="fa fa-save"></i> Save</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
<?php }?>

<?php

$qry = mysqli_query($conn, "SELECT b.id, b.category_no, b.accession, b.volume, b.title, b.author, b.publisher, b.publish_date, b.status, c.name FROM books b LEFT JOIN category c ON c.id = b.category_id");
while ($qry2 = mysqli_fetch_array($qry)) {

?>
<!-- Delete -->
<div class="modal fade" id="delete<?php echo $qry2['id']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
              <h4 class="modal-title"><b>Delete</b></h4>
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
          	</div>
          	<div class="modal-body">
            <?php
              $delete=mysqli_query($conn,"SELECT * FROM books WHERE id='".$qry2['id']."'");
              $erow=mysqli_fetch_array($edit);
            ?>
            	<form class="form-horizontal" method="POST" action="book_delete.php?id=<?php echo $qry2['id']; ?>">
            		<input type="hidden" class="bookid" name="id">
            		<div class="text-center">
	                	<p>Confirm deletion?</p>
	                	<h3 id="del_book" style="font-weight: bold" class="mb--0"><?php echo $qry2['accession']; ?></h3>
                    <h2 id="del_book" style="font-weight: bold" class="mt--0"><?php echo $qry2['title']; ?></h2>
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
        <form action="import_students.php" method="post" enctype="multipart/form-data">
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
