<script type="text/javascript">
$(function(){
	_ajax.load('<?=SITE_URL?>/contacts/_list','#list');
	$('#group_change').change(function(){
		var sort_dir = $('#name_sort_link').attr('rel');
		$('#serachtext').val('');
		_ajax.load('<?php echo SITE_URL?>/contacts/_list?sort_dir='+sort_dir+'&filter[group_id]='+$(this).val(),'#list');
		});
	$('#serach_go').click(function(){
		var sort_dir = $('#name_sort_link').attr('rel');
		var url = '<?php echo SITE_URL?>/contacts/_list?sort_dir='+sort_dir;
		if ($('#group_change').val()) url += '&filter[group_id]='+$('#group_change').val();
		url += '&filter[name]='+$('#serachtext').val();
		_ajax.load(url,'#list');
		});	
	});			
</script>
<div class="list-filter">
		<select id="group_change" class="span5">
			<option value="">All contacts</option>							
		<?php foreach ($this->contact_groups as $group){?>
		<option value="<?=$group['id']?>" ><?=$group['name']?></option>							
		<?php }?>	
		</select>
		<div class="input-prepend input-append">
			<button class="btn" type="button" onclick="$('#serachtext').val('');$('#serach_go').click();">x</button>
			<input class="span8" id="serachtext" type="text">
			<button class="btn" id="serach_go" type="button">Go!</button>
		</div>
	</div>
	<a href="" style="display:none;" class="refresh"><i class="icon-refresh"></i></a>
</div><div id="list" ></div>