<?php

function index()
{    
    $content = file_get_contents(__DIR__.'/../views/home.php');    
    require_once 'templates/layout.php';
}

function buscarItem()
{
   header('Content-Type: application/json; charset=utf-8');
   require_once('classes/DatabaseException.php');
   require_once('classes/Query.php');
   require_once('classes/Connection.php');
   require_once('classes/Item.php');
   $item = new Item();
   $item->setAtributo('_code',$_POST['code']);
   echo json_encode($item->getDataCode());   
}
