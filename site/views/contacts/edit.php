<form method="post" class="contact-form form-horizontal" id="editItemForm" action="<?=$this->document->page_url?>">
  <div class="form_header"></div>
  <div class="control-group">
    <label class="control-label" for="item[group_id]">Group</label>
    <div class="controls">
			<select  name="item[group_id]" class="require" id="item[group_id]">
				<option value=""><?=$this->text_no_select?></option>							
			<?php foreach ($this->contact_groups as $group){?>
				<?php if ((isset($this->item['group_id']) && $group['id'] == $this->item['group_id']) || (isset($this->group_id) && $group['id'] == $this->group_id)) { ?>
				<option value="<?=$group['id']?>" selected><?=$group['name']?></option>							
				<?php } else { ?>
				<option value="<?=$group['id']?>" ><?=$group['name']?></option>							
				<?php } ?>
			<?php }?>	
			</select>
		</div>
  </div>
  <div class="control-group">
    <label class="control-label" for="item[name]">Name</label>
    <div class="controls">
      <input type="text" id="item[name]" value="<?php echo isset($this->item['name'])?$this->item['name']:''?>" class="input-xlarge require" name="item[name]" placeholder="Name">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="item[email]">Email</label>
    <div class="controls">
      <input type="text" id="item[email]" name="item[email]" value="<?php echo isset($this->item['email'])?$this->item['email']:''?>" class="input-xlarge"  placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="item_phone">Phone</label>
    <div class="controls">
      <input type="text" name="item[phone]" id="item_phone" class="input-xlarge require" value="<?php echo isset($this->item['phone'])?$this->item['phone']:''?>"  placeholder="Phone">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="item[adress]">Adress</label>
    <div class="controls">
		<textarea rows="3" style="width: 270px; " name="item[adress]"><?php echo isset($this->item['adress'])?$this->item['adress']:''?></textarea>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="item[skype_name]">Skype</label>
    <div class="controls">
      <input type="text" id="item[skype_name]" name="item[skype_name]" class="input-xlarge" value="<?php echo isset($this->item['skype_name'])?$this->item['skype_name']:''?>" placeholder="Skype">
    </div>
  </div>											
  <div class="control-group">
    <div class="controls">
	<input type="submit" class="btn btn-success" value="Save"/>
    </div>
  </div>											
<?php if (isset($this->item['id'])) { ?> <input type="hidden" id="item_id" name="id" value="<?=$this->item['id']?>"  /> <?php } ?>
</form>
<script type="text/javascript">
$(function(){
	$('#editItemForm').submit(function(){
		if (_ajax.validForm(this)) _ajax.post(this.action, $(this).serialize(),'#main'<?php if (isset($this->item['id'])) { ?>,_ajax.load('<?=SITE_URL?>/contacts/row/<?=$this->item['id'];?>','#view-<?=$this->item['id'];?>') <?php } else {?>,function(){ $('.refresh').click();}<?php }?>);
		return false;
		});	
	$("#item_phone").mask("+38 999 9999999");	
		
	});
</script>