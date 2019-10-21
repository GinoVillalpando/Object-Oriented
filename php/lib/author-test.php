<?php
//load author class
require_once ('../Classes/author.php');

use GinoVillalpando\ObjectOriented\Author;

//use the constructor
$george = new Author("27701c38-31be-4261-9ad2-c2a63d3fed2b",
	"12341234123412341234123412341234",
	"newAvatar.edu",
	"author1@cnm.edu",
	"2508b78e6df07bd85670289f7a5a86b0eacd45c33d6c33b120221e84051c482bbd23296a95f01340665dcb0969cc00489",
	"george",);
echo("Author ID: ");
echo($george -> getAuthorId());
echo(" <br>Author Activation Token: ");
echo($george -> getAuthorActivationToken());
echo(" <br>Author Avatar Url: ");
echo($george -> getAuthorAvatarUrl());
echo(" <br>Author Email: ");
echo($george -> getAuthorEmail());
echo(" <br>Author Hash: ");
echo($george -> getAuthorHash());
echo(" <br>Author Username: ");
echo($george -> getAuthorUsername());

