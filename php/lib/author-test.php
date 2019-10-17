<?php
//load author class
require_once "..\vendor\autoload.php";
require_once "..\Classes\author.php";

use GinoVillalpando\ObjectOriented\author;

//use the constructor
$author = new author(1, 2, "newAvatar", "author1@cnm.edu", "cci2921", "george",);
echo ($author->getAuthorId());

