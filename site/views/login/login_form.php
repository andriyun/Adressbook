			  <form class="form-signin" action="<?=SITE_URL?>/login" id="form-signin">
				<h2 class="form-signin-heading form_header">Please sign in</h2>
				<div class="control-group"><input type="text" name="login" class="require input-block-level" placeholder="Login"></div>
				<div class="control-group"><input type="password" name="password" class="require input-block-level" placeholder="Password"></div>
				<button class="btn btn-large btn-primary" type="submit">Sign in</button>
				<input type="hidden"  value="send" name="submit"  />
			  </form>
<script type="text/javascript">
$(function(){
	$('#form-signin').submit(function(){
		if (_ajax.validForm(this)) _ajax.post(this.action, $(this).serialize(),false,function(){ _ajax.load('<?php echo SITE_URL;?>/login/logged_block','#logged_block');});
		return false;
		});	
	});
</script>			  