<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color:black;">
  <div class="container-fluid">
    <a class="navbar-brand" href="?page=home.php">
      <img style="width:35px; margin-right:0; margin-left:0;" src="asset/icon/Mon.ico" alt="Logo">
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav navbar-nav navbar-left">
        <li class="active"><a href="?page=home.php">Home</a></li>
        <li><a href="?page=make_reservation.php">Make a Reservation</a></li>
        <li><a href="?page=view_receipts.php">View Receipts</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-shopping-cart"></span> Cart
          </a>
          <div class="dropdown-menu" aria-labelledby="cartDropdown">
            <a class="dropdown-item" href="?page=shoppingcart.php">My Cart</a>
            <a class="dropdown-item" href="?page=my_orders.php">My Orders</a>
          </div>
        </li>
      </ul>
      <form class="d-flex">
        <ul class="nav navbar-nav navbar-right">
          <?php
          if (!isset($_SESSION['usname']) || $_SESSION['usname'] == "") {
          ?>
            <li><a href="?page=login.php">Login</a></li>
            <li><a href="?page=register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
          <?php
          } else {
          ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userProfileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user"></span>
                Welcome, <?php echo htmlspecialchars($_SESSION['usname']); ?>
              </a>
              <div class="dropdown-menu" aria-labelledby="userProfileDropdown">
                <a class="dropdown-item" href="?page=profile.php">Profile</a>
                <a class="dropdown-item" href="?page=logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
              </div>
            </li>
          <?php
          }
          ?>
        </ul>
      </form>
    </div>
  </div>
</nav>
