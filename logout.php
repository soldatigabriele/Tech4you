<?php

require_once 'inc/init.php';

$user = new User();
$user->logout();

Redirect::to('home.php');