<div class="container">
    <div class="row m-2">
        <div class="col-6 p-3">
            <h1>Reset password</h1>
            <form action="reset_form.php" method="post" id="resetForm">

                <div class="pt-3 field">
                    <label for="resetEmail" class="form-label">E-mail address</label>
                    <input type="text" class="form-control" id="resetEmail"
                           placeholder="Enter valid e-mail address" name="resetEmail">
                    <small></small>
                </div>

                <div class="pt-3 field">
                    <label for="resetPassword" class="form-label">New Password <i class="bi bi-eye-slash-fill"
                                                                                  id="passwordEye"></i></label>
                    <input type="password" class="form-control passwordVisibiliy" name="resetPassword" id="resetPassword"
                           placeholder="Password (min 8 characters)">
                    <small></small>
                </div>

                <div class="pt-3 field">
                    <label for="resetPasswordConfirm" class="form-label">New Password Confirm</label>
                    <input type="password" class="form-control" name="resetPasswordConfirm" id="resetPasswordConfirm"
                           placeholder="Password again">
                    <small></small>
                </div>

                <div class="pt-3">

                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class="btn btn-primary">Send</button>
                    <button type="reset" class="btn btn-primary resetButton" >Cancel</button>
                </div>
            </form>
