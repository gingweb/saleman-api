<?php

require_once 'controller/Controller.php';
// require_once 'controller/PlaceController.php';
// require_once 'controller/PlanController.php';

$controller = new Controller();
// $controller = new PlaceController();
// $controller = new PlanController();

$controller->handleRequest();

?>
