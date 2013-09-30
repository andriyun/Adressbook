<p class="navbar-text pull-right">
<?php if ($this->isLogged){?>
 Hello <?php echo $this->user->getUserName();?> <a href="javascript:void(0);" onclick="_ajax.load('<?php echo SITE_URL;?>/login/logout',false,function(){ _ajax.load('<?php echo SITE_URL;?>/login/logged_block','#logged_block');});">Logout</a>
<?php } ?>
</p>
