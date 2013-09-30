<div class="content-list-container">
<table class="contentList table table-bordered" >
	<thead>
		<tr>
			<th width="15px" class="t-center"><input type="checkbox" name="checkAll" value="1"/></th>
			<th class="t-left" colspan="2"><?php if ($this->sort_order == 'name') { ?>
                <a id="name_sort_link" href="<?php echo $this->sort_name_href; ?>" rel="<?php echo strtolower($this->sort_dir)?>" onclick="_ajax.load(this.href,'#list');return false;">Name <i class="<?php if (strtolower($this->sort_dir) == 'asc') echo 'icon-chevron-down'; else echo "icon-chevron-up"?>"></i></a>
                <?php } else { ?>
                <a href="<?php echo $this->sort_name_href; ?>" onclick="_ajax.load(this.href,'#list');return false;">Name</a>
                <?php } ?></th>		
				</tr>
	</thead>
<?php if (count($this->items)) { foreach ($this->items as $this->item){?>
<tr rel="<?=$this->item['id']?>" class="row-<?=$this->item['id']?>" id="view-<?=$this->item['id']?>">
	<?php $this->display('contacts/row');?>
</tr>
<?php }  
	} else {?>	
<tr>
	<td colspan="4"><?=$this->text_no_items_found?></td>
</tr>
<?php }?>	
</table>
</div>
<?php //echo "<pre>";print_r($this->filter);echo"</pre>";?>
<div class="list-control-button">
		<button class="btn btn-success" type="button" onclick="_ajax.load('<?php echo SITE_URL?>/contacts/add<?php if (isset($this->filter['group_id'])) echo '?group_id='.$this->filter['group_id'];?>','#main');">Add</button>
		<button class="btn btn-danger" name="delete" type="button">Remove</button>
</div>	
<script type="text/javascript">
function viewItem(id){
	_ajax.load('<?php echo SITE_URL?>/contacts/view/'+id,'#main');
	return false;
	}		
$(function(){

	function listHeight(){
		$(".content-list-container").height($(window).height() - $('#list').offset().top - $('#footer').height() - $('.list-control-button').height());
		}
	listHeight();
	$(window).resize(function(){
		listHeight();
		});
	$('.dropdown-toggle').dropdown();
	$('.listHeader .stButton').removeClass('active');
	$('.contentList tr').hover(
		function(){
			var id = $(this).attr('rel');
			$('.row-'+id).addClass('hover');
			},
		function(){
			var id = $(this).attr('rel');
			$('.row-'+id).removeClass('hover');
			}
		);
		
	$('input[name=checkAll]').change(function(){
		var state = this.checked
		$('input.selectedCheckbox').each(function(){ this.checked = state; if (state) $(this).attr('checked','checked'); else $(this).removeAttr('checked'); });
		});

	$('#reset_filter').click(function(){_ajax.load('<?=$this->document->page_url?>','#list');});
	$('#filter').click(function(){
		var filter_name = $('#filter_name').val();
		var filter_group_id = $('#filter_group_id').val();
		if (!filter_name && !filter_group_id){ alert('Not set search settings'); return false;	}
		_ajax.post('<?=$this->document->page_url?>', 
			{filter:{
				name:$('#filter_name').val(),
				group_id:$('#filter_group_id').val()
				}},
			'#list');
		return false;		
		});	
	$('button[name=delete]').unbind('click').click(function(){
		var selected = '';
		$('input.selectedCheckbox').each(function(){ 
			if (this.checked) {
				if (selected != '') selected += ',';
				selected += $(this).val();
				}
			});
		if (selected == '')	{
			alert('<?=$this->text_error_no_select_for_action;?>');
			return false;
			}
		if (confirm('<?=$this->text_error_confirm_delete;?>')) _ajax.post('<?=SITE_URL?>/contacts/delete',{selected:selected},false,function(){ $('.refresh').click();});
		});
	
	$('.refresh').unbind('click').click(function(){
		 _ajax.load('<?=$this->full_page_url;?>','#list');
		});
	});	
	
</script>