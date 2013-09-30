<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta charset="utf-8" />
	<title><?php echo ($this->document->title?$this->document->title.' | '.$this->document->title_suffix:$this->document->title_suffix); ?></title>
	<meta name="description" content="<?=str_replace('"',"'",$this->document->description);?>">
	<meta name="keywords" content="<?=str_replace('"',"'",$this->document->keywords);?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">	
<?php foreach ($this->document->getLinks() as $link) {?>
	<link <?php foreach ($link as $key=>$val) echo $key.'="'.$val.'" ';?>/>
	<?php } ?>	
<?php foreach ($this->document->getStyles() as $style) {?>
	<link type="text/css" href="<?=SITE_URL.$style['href'];?>?v=20120924" rel="<?=$style['rel']?>" /><?php } ?>
<?php foreach ($this->document->getScripts() as $script) {?>
	<script src="<?=SITE_URL.$script;?>?v=20120719" type="text/javascript" /></script>
<?php } ?>
</head>
  <body>
    <div id="topline" class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="#"><?=SITE_NAME;?></a><div id="filter_container" class="pull-left"></div>
          <div class="nav-collapse collapse" id="logged_block">
              <?=$this->display($this->logged_block);?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>	
    <!-- Wrap all page content here -->
    <div id="wrap">
		<div class="container-fluid">
		  <div class="row-fluid" id="main-container">
			<?=$this->display($this->container);?>
		  </div>
		</div>
	</div>

    <div id="footer" class="navbar-fixed-bottom">
       <div class="container-fluid">
		<p class="credit"><?=SITE_NAME;?> © <?=date('Y');?></p>
		</div>
    </div>
	<div id="ajaxWait" style="display:none"></div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>