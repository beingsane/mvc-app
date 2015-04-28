<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title><?= $this->title ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/layouts/css/bootstrap.min.css" rel="stylesheet">
    <link href="/layouts/css/sticky-footer.css" rel="stylesheet">
    
    <link href="/layouts/css/layout.css" rel="stylesheet">
    <link href="/layouts/css/notifications.css" rel="stylesheet">
    
    <?php
        foreach($this->jsFiles as $file)
        {
            ?>
                <script src="<?= $file ?>"></script>
            <?php
        }
        
        foreach($this->cssFiles as $file)
        {
            ?>
                <link rel="stylesheet" type="text/css" href="<?= $file ?>"/>
            <?php
        }
    ?>
  </head>

  <body>
    <div class="container">
      <div class="header clearfix">
      </div>
      
      <?php include('menu.htm') ?>
      
      <div class="content">
        <?= $content ?>
      </div>
      
    </div>
    
    <footer class="footer">
      <div class="container">
        <div class="footer-data navbar navbar-default">
          <p class="text-muted">(c) 2015 - <?= date('Y') ?>. Все права защищены</p>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="/layouts/js/bootstrap.min.js"></script>
  </body>
</html>
