<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Document</title>
    <link rel="stylesheet" href="proj.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
  <div class="navbar">
    <ul class="nav-menu">
      <div class="nav-left">
        <li><a href="loginpage.php"><img src="img/logo.png" alt="Logo" class="logo" /></a></li>
        <li><a href="loginpage.php">AUROVA</a></li>
      </div>
      <div class="nav-right">
        <li><a href="loginpage.php">HOME</a></li>
        <li><a href="all.php">ALL</a></li>
        <li>
          <a href="account.php" class="icons">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="currentColor"
            >
              <path
                d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM12.1597 16C10.1243 16 8.29182 16.8687 7.01276 18.2556C8.38039 19.3474 10.114 20 12 20C13.9695 20 15.7727 19.2883 17.1666 18.1081C15.8956 16.8074 14.1219 16 12.1597 16ZM12 4C7.58172 4 4 7.58172 4 12C4 13.8106 4.6015 15.4807 5.61557 16.8214C7.25639 15.0841 9.58144 14 12.1597 14C14.6441 14 16.8933 15.0066 18.5218 16.6342C19.4526 15.3267 20 13.7273 20 12C20 7.58172 16.4183 4 12 4ZM12 5C14.2091 5 16 6.79086 16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5ZM12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7Z"
              ></path>
            </svg>
          </a>
        </li>
      </div>
    </ul>
  </div>

  <div class="container">
    <h1>AUROVA</h1>
    <h1 class="tag">MAKE YOUR AURA MAUR</h1>
    <?php
    session_start();

    if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
        echo '<a href="all.php"><button class="but1">SHOP NOW</button></a>';
        echo '<a href="logout.php"><button class="but1">LOG OUT</button></a>';
    } else {
        echo '<button class="but1" id="loginBtn">LOG IN</button>';
        echo '<button class="but1" id="signupBtn">SIGN UP</button>';
    }
    ?>
  </div>

  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Login</h2>
      <form class="modal-form" action="login.php" method="post">
        <input type="text" placeholder="Username" name="username" required />
        <input type="password" placeholder="Password" name="password" required />

        <div class="forgot-password">
          <a href="fpass.php">Forgot Password?</a>
        </div>
        <button type="submit" class="but1">Submit</button>
      </form>
    </div>
  </div>

  <div id="signupModal" class="modal">
    <div class="modal-content">
      <span class="close-signup">&times;</span>
      <h2>Sign Up</h2>
      <form class="modal-form" action="signup.php" method="post">
        <input type="text" placeholder="First Name" name="fname" required />
        <input type="text" placeholder="Middle Name" name="mname" />
        <input type="text" placeholder="Last Name" name="lname" required />
        <input type="text" placeholder="Username" name="username" />
        <input type="password" placeholder="Password" name="password" />
        <input type="email" placeholder="Email" name="Email" />

        <div class="g-recaptcha" data-sitekey="6LeBJUkrAAAAAGV981rOb-JGnuQ0kippVZvziSlq" style="margin-top: 15px;"></div>
        <br> <button type="submit" class="but1">Register</button>
      </form>
    </div>
  </div>

  <div id="successModal" class="modal">
    <div class="modal-content">
      <span class="close-success">&times;</span>
      <h2>Registration Successful</h2>
      <p>You can now log in with your new account.</p>
      <button
        class="but1"
        onclick="document.getElementById('successModal').style.display='none'"
      >
        Close
      </button>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <img src="img/logo.png" alt="AUROVA Logo" class="footer-logo-img" />
        <h3>AUROVA</h3>
      </div>
      <div class="footer-links">
       
      </div>
      <div class="footer-social">
        <a href="#"><i class="ri-facebook-fill"></i></a>
        <a href="#"><i class="ri-instagram-line"></i></a>
        <a href="#"><i class="ri-twitter-x-line"></i></a>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 AUROVA. All rights reserved.</p>
    </div>
  </footer>
  <script src="proj.JS"></script>
</body>
</html>