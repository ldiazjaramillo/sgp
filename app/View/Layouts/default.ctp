<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Luis Diaz">
    <link rel="icon" href="/favicon.ico">

    <title>
      <?php echo "Fundación Instituto de Ingenieria - CPDI" ?>:
      <?php echo $title_for_layout; ?>
    </title>

    <?php 
      echo $this->Html->css(array(
        'bootstrap.min',
        'dashboard',
        'miestilo',
      ));
    ?>
    <?= $this->Html->script(array(
      'jquery.min',
      'bootstrap.min',
      'hc/highcharts',
      'uploader'
     ));?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style id="holderjs-style" type="text/css"></style>
 </head>

<body>

<?= $this->Element('navigation') ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2">
      <?= $this->Element('mainmenu'); ?>
    </div>
    <div class="col-sm-9 col-md-10">
      <?= $this->Element('breadcrumb'); ?>
      <?php echo $content_for_layout; ?>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<? echo $this->Js->writeBuffer(); ?>
<?= $this->Element('mensajes')?>
</body>
</html>
