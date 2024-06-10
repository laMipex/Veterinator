<div class="container">
    <br><br>
    <h1 class="text-center my-5">Sign Up</h1>
    <form method="post" action=<?php echo $action;?> id="registerForm" class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto mt-4" >
        <div class="mb-3">
            <label for="registerFirstname" class="form-label">First name</label>
            <input type="text" name="firstname" class="form-control" id="registerFirstname" placeholder="John">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="registerLastname" class="form-label">Last name</label>
            <input type="text" name="lastname" class="form-control" id="registerLastname" placeholder="Dow">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="registerEmail" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="example@mail.com">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone">
            <small></small>
        </div>
        <div class="mb-3 field">
            <label for="registerPassword" class="form-label">Password <i class="bi bi-eye-slash-fill"
                                                                         id="passwordEye"></i></label>
            <input type="password" class="form-control passwordVisibiliy" name="password" id="registerPassword"
                   placeholder="Password (min 8 characters)">
            <small></small>
            <span id="strengthDisp" class="badge displayBadge mt-3">Weak</span>
        </div>
        <div class="mb-3">
            <label for="registerPasswordConfirm" class="form-label">Cofirm Password:</label>
            <input type="password" name="passwordConfirm" class="form-control" id="registerPasswordConfirm">
            <small></small>
            <!-- <input type="checkbox"  class="mt-3">Show Password-->
        </div>


        <div class="mb-5 text-center mt-5">
            <!-- <input type="hidden" name="action" value="register">-->
            <button type="submit"  class="btn btnSend btn-primary text-center">Sign Up</button>
            <button type="reset" class="btn btn-primary btnSend ms-5 resetButton" >Cancel</button>
            <small></small>
        </div>
    </form>
</div>