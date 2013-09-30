<?php 
ob_start();
?>
<?php foreach ($this->document->messages as $key=>$messages) if (count($messages)){ ?>
<ul class="message <?=$key?>_message">
	<?php foreach ($messages as $message) {?>
	<li><?=$message?></li>
	<?php } ?>
</ul>
<? }

$message_html = ob_get_contents();
ob_end_clean;
if ($message_html) {
?>
<script type="text/javascript">
	$.blockUI({
		message: '<?=str_replace(array("'","\n"),array("\'",""), $message_html);?><br /><div class="t-center">	<input type="button" value="Close" class="stButton" onclick="$.unblockUI();" /></div>',
		css: {width:'30%',left:'35%',top:'20%',textAlign:'left','background':'#EEEEEE'},			
		timeout: 0
		});	
</script>
<? } ?>