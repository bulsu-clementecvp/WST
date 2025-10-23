<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="proj.css">
</head>
<body>
  <div class="container">
    <h1>Forgot Password</h1>
    <p>Enter your email or username and we'll send you reset instructions.</p>

    <?php if (isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
      <div style="color: green; margin-bottom: 15px;">
        A reset email has been sent to your address.
      </div>
    <?php endif; ?>

    <form action="reset.php" method="post" class="modal-form">
      <input type="text" name="user_email" placeholder="Email or Username" required>
      <button type="submit" class="but1">Send Reset Link</button>
    </form>


    <a href="loginpage.php">
      <button class="but1" style="margin-top: 20px;">Go Back</button>
    </a>
  </div>
</body>
</html>
