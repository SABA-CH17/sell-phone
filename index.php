<?php

$page = $_GET['page'] ?? 'home';

include 'Includes/header.php';


switch ($page) {

    case 'home':
        include 'home.php';
        break;

    case 'about':
        include 'about.php';
        break;

    case 'blogs':
        include 'blogs.php';
        break;

    case 'testimonials':
        include 'testimonials.php';
        break;

    case 'contact':
        include 'contact.php';
        break;

    default:
        include '404.php';
        break;
}

include 'Includes/footer.php';

?>
