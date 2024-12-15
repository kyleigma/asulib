<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
			  <h4 class="modal-title"><b>Return Books</b></h4>
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="return_student_add.php">
          		  <div class="form-group row mx-0">
                  	<label for="student_id" class="col-sm-3 col-form-label">Student ID</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="student_id" name="student_id" required>
                  	</div>
                </div>
                <div class="form-group row mx-0">
                    <label for="accession" class="col-sm-3 col-form-label">Accession Number</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="accession" name="accession" required>
                    </div>
                </div>
                <span id="append-div"></span>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                      <button class="btn btn-primary btn-xs btn-flat" id="append"><i class="fa fa-plus"></i> Book Field</button>
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

<!-- Add -->
<div class="modal fade" id="addnew2">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
			  <h4 class="modal-title"><b>Return Books</b></h4>
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="return_faculty_add.php">
          		  <div class="form-group row mx-0">
                  	<label for="faculty_id" class="col-sm-3 col-form-label">Faculty ID</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="faculty_id" name="faculty_id" required>
                  	</div>
                </div>
                <div class="form-group row mx-0">
                    <label for="accession" class="col-sm-3 col-form-label">Accession Number</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="accession" name="accession" required>
                    </div>
                </div>
                <span id="append-div"></span>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                      <button class="btn btn-primary btn-xs btn-flat" id="append"><i class="fa fa-plus"></i> Book Field</button>
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

<script>
    // Get the button element
    const appendButton = document.getElementById('append');

    // Add an event listener to the button
    appendButton.addEventListener('click', function() {
        // Create a new form group with label and input
        const newFormGroup = document.createElement('div');
        newFormGroup.className = 'form-group row mx-0';

        const newLabel = document.createElement('label');
        newLabel.className = 'col-sm-3 col-form-label';
        newLabel.textContent = 'Accession Number';

        const newInputDiv = document.createElement('div');
        newInputDiv.className = 'col-sm-9';

        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.className = 'form-control';
        newInput.name = 'accession[]';
        newInput.required = true;

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger btn-xs btn-flat';
        deleteButton.innerHTML = '<i class="fa fa-trash"></i>';
        deleteButton.style.height = 'calc(1.5em + 0.75rem + 2px)';
        deleteButton.style.padding = '0.375rem 0.75rem';
        deleteButton.style.marginLeft = '10px'; // Add this line to add a margin to the left of the delete button
        deleteButton.onclick = function() {
            newFormGroup.remove();
        };

        const inputWrapper = document.createElement('div');
        inputWrapper.className = 'd-flex';
        inputWrapper.appendChild(newInput);
        inputWrapper.appendChild(deleteButton);

        newInputDiv.appendChild(inputWrapper);
        newFormGroup.appendChild(newLabel);
        newFormGroup.appendChild(newInputDiv);

        // Append the new form group to the #append-div element
        const appendDiv = document.getElementById('append-div');
        appendDiv.appendChild(newFormGroup);
    });
</script>