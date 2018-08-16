<?php
require_once "session.php";

require_once "database.php";
require_once "customers.class.php";
$cust = new Customers();

if(isset($_POST["name"])) $cust->name = $_POST["name"];
if(isset($_POST["email"])) $cust->email = $_POST["email"];
if(isset($_POST["mobile"])) $cust->mobile = $_POST["mobile"];
if(isset($_FILES["pic1"])) $cust->pic1 = $_FILES["pic1"];
if(isset($_FILES["pic2"])) $cust->pic2 = $_FILES["pic2"];

if(isset($_GET["fun"])) $fun = $_GET["fun"];
else $fun = 0;

switch ($fun) {
    case 1: // create
        $cust->create_record();
        break;
    case 2: // read
        // to do
        break;
    case 3: // update
        // to do
        break;
    case 4: // delete
        // to do
        break;
    case 11: // insert database record from create_record()
        $cust->insert_record();
        break;
    case 33: // update database record from update_record()
        // to do
        break;
    case 44: // delete database record from delete_record()
        // to do
        break;
    case 0: // list
    default: // list
        $cust->list_records();
}
