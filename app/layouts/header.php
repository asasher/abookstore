<?php
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The BookStore</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          </a>   
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>                  
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if(!empty($_USER_)) { ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Hey, <?=$_USER_['Login']?> <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <?php if(!empty($_USER_['Manager'])) { ?>
                        <li><a href="manager.php">Statistics</a></li>
                    <?php } else { ?>
                        <li><a href="user.php">Profile</a></li>
                    <?php } ?>                    
                    <li><a href="logout.php">Logout</a></li>
                  </ul>
                </li>    
            <?php  } else { ?>
                <li class="active"><a href="login.php">Login</a></li>            
            <?php } ?>            
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container-fluid">
          <div class="title row">
            <div class="col-md-12">
                <h1 class="hero-title stylish">The BookStore</h1>
            </div>          
          </div>
    </div>