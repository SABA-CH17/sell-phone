<?php
$current_page = $_GET['page'] ?? 'home';
?>

<nav class="navbar navbar-expand-lg navbar-light my-2">
  <div class="container-fluid">

    <a href="index.php?page=home">
      <img class="navbar-img" src="imgs/logo.png" alt="Logo">
    </a>

    <button class="navbar-toggler" type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown"
      aria-expanded="false"
      aria-label="Toggle navigation">

      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">

      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'home') ? 'active' : ''; ?>"
            href="/customphp/">
            Home
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'about') ? 'active' : ''; ?>"
            href="/customphp/about">
            About
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'blogs') ? 'active' : ''; ?>"
            href="/customphp/blogs">
            Blogs
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'testimonials') ? 'active' : ''; ?>"
            href="/customphp/home#testimonials">
            Testimonials
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'contact') ? 'active' : ''; ?>"
            href="/customphp/contact">
            Contact Us
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>