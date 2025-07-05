<style>
  /* Navbar container */
  #navbar {
    background-color: #b71c1c; /* deep red */
    color: white;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    font-family: Arial, sans-serif;
  }

  #navbar h3 {
    margin: 0;
    margin-right: 30px;
    font-weight: bold;
    font-size: 1.2rem;
    white-space: nowrap;
  }

  #navbar a {
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  #navbar a:hover {
    background-color: #7f0000;
    color: white;
  }

  #navbar a.logout {
    margin-left: auto; /* Push logout to right */
    color: #ff5252;
    font-weight: bold;
  }

  #navbar a.logout:hover {
    background-color: transparent;
    color: #ff0000;
    text-decoration: underline;
  }
</style>

<nav id="navbar">
  <h3>TravelEase Admin</h3>
  <a href="dashboard.php">Dashboard</a>
  <a href="manage-guides.php">Manage Guides</a>
  <a href="manage-packages.php">Manage Packages</a>
  <a href="manage-destinations.php">Manage Destinations</a>
  <a href="manage-blogs.php">Manage Blogs</a>
  <a href="manage-events.php">Manage Events</a>
  <a href="view-feedback.php">View Feedback</a>
  <a href="view-contacts.php">View Contacts</a>
  <a href="bookings.php">View Bookings</a>
  <a href="logout.php" class="logout">Logout</a>
</nav>
