<?php include('server.php') ?>
<form id="signup" class="modal-window" method="post">
            <?php include('errors.php') ?>
            <div>
                <a href="#" class="close">&times;</a>
                <label><b>Username</b></label><br>
                <input type="text" name="username" placeholder="Enter Username" required>
                <br><br>
                <label><b>Email</b></label><br>
                <input type="text" name="email" placeholder="Enter Email" required>
                <br><br>
                <label><b>Password</b></label><br>
                <input type="password" name="password" placeholder="Enter Password"  required>
                <br><br>
                <label><b>Password</b></label><br>
                <input type="password" name="password_2" placeholder="Re-Enter Password"  required>
                <br><br>
                <button type="submit" name="reg_user">Submit</button>
                <button type="button" class="c" onclick="window.location.href = '#';">Cancel</button>
                <span class="psw">Already a Member? <a href="#login">Login</a></span>
            </div>
</form>