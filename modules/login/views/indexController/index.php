<!-- Form Mixin-->
    <!-- Pen Title-->
    <div class="pen-title">
        <h1>LOGIN</h1>
    </div>

    <!-- BEGIN alert  -->
    <?php if(isset($error)):?>
    <div class="alert alert-error">
        <strong>Error! </strong>
		<?php echo $error;?>
    </div>
    <?php endif;?>

    <!-- BEGIN alert  -->

    <!-- Form Module-->
    <div class="module form-module">
        <div class="toggle"><i class="fa fa-times fa-pencil"></i>
            <div class="tooltip">Click Me</div>
        </div>
        <div class="form">
            <h2><a href="<?php echo $router->url( array( 'module' => 'fronend' ) )?>">Click here to home</a></h2>
            <form method="post" action="">
                <input value="<?php echo isset($_SESSION['tempUser'])?$_SESSION['tempUser']:''?>" name="username" type="text" placeholder="Username" />
                <input name="password" type="password" placeholder="Password" />
                <button name="login" value="login">Login</button>
            </form>
        </div>
        <div class="form">
            <h2>Create an account</h2>
            <form>
                <input type="text" placeholder="Username" />
                <input type="password" placeholder="Password" />
                <input type="email" placeholder="Email Address" />
                <input type="tel" placeholder="Phone Number" />
                <button>Register</button>
            </form>
        </div>
        <div class="cta"><a href="#">Forgot your password?</a>
        </div>
    </div>