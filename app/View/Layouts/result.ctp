<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/docs-assets/ico/favicon.png">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <script src="/docs-assets/js/holder.js"></script>
    <script src="/dist/js/jquery.tablesorter.js"></script>
    <title><?php echo $title; ?></title>
    <link href="/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <?php if($asset_folder == ''){ ?>
	<!--DEFAULT PATH TO STYLES. USED FOR ALL SITES THAT DONT USE CUSTOM--> 
  	 <link href="/blue/listsort-style.css" rel="stylesheet">
    <?php }else{ ?> 
  	 <link href="/<?php echo $asset_folder; ?>/blue/listsort-style.css" rel="stylesheet">
    <?php }?>


    <?php if($asset_folder == ''){ ?> 
	<!--DEFAULT PATH TO STYLES. USED FOR ALL SITES THAT DONT USE CUSTOM--> 
        <link href="/theme.css" rel="stylesheet">
    <?php }else{ ?> 
        <link href="/<?php echo $asset_folder; ?>/theme.css" rel="stylesheet">
    <?php }?>

    <!--js for print pdf emailing page-->
    <script src="/js/outputpage.js"></script>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="/commandgeosearch/docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
  </head>

  <body>


			<?php echo $this->fetch('content'); ?>


    
  </body>
</html>
