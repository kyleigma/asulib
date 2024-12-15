<!-- Login -->
<div class="modal fade" id="login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"><b>Login</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="login.php">
                <div class="form-group row mx-0">
                    <label for="id_number" class="col-sm-3 col-form-label">ID Number</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="id_number" name="id_number" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class=""></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="login"><i class=""></i> Login</button>
              </form>
            </div>
        </div>
    </div>
</div>