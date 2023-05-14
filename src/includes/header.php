<?php 
	include './includes/title.php';
  //hide all php errors
  error_reporting(0);


?>	
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Marketio</title>
    <link
      rel="icon"
      type="image/x-icon"
      href="./images/icons8-shopping-cart-96.png"
    />
    <link rel="stylesheet" href="./css/output.css" />
    <style>
        #here {
            color:red;
        }
    </style>
  </head>
  <body >
    <div
    
        class="mt-5 mb-5 bg-orange-300 drop-shadow-md border-2 border-black rounded-full w-3/4 lg:w-1/2 mx-auto flex flex-col md:flex-row justify-around"
      >
        <div class="md:pl-4">
          <img
            src="./images/icons8-shopping-cart-96.png"
            class="m-auto"
            alt=""
          />
        </div>
        <div class="pl-5 pr-5">
          <header class="text-center text-4xl font-bold block">Marketio</header>
          <section class="text-center p-2">
            An online marketplace to purchase items
          </section>
        </div>
      </div>
   
<div id="wrapper" class="flex-row flex mt-10 min-h-1/2">
    <?php require './includes/menu.php'; ?>