<?php //echo "<pre>";print_r($this->item);echo"</pre>";?>
<div class="contact-view form-horizontal">
  <?php if ($this->item['group_id']){ ?>
  <div class="control-group">
    <label class="control-label">Group</label>
    <div class="controls">
			<label class="control-label t-left" ><?php echo $this->item['group_name']?></label>
		</div>
  </div>
  <?php }
  if ($this->item['name']){ ?>   
  <div class="control-group">
    <label class="control-label" for="item[name]">Name</label>
    <div class="controls">
       <label class="control-label t-left" ><?php echo $this->item['name']?></label>
    </div>
  </div>
  <?php }
  if ($this->item['email']){ ?> 
  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
      <label class="control-label t-left" ><a href="mailto:<?php echo $this->item['email']?>"><?php echo $this->item['email']?></a></label>
    </div>
  </div>
  <?php }
  if ($this->item['phone']){ ?> 
  <div class="control-group">
    <label class="control-label" for="item_phone">Phone</label>
    <div class="controls">
      <label class="control-label t-left" ><?php echo $this->item['phone']?></label>
    </div>
  </div>
   <?php }
  if ($this->item['adress']){ ?> 
  <div class="control-group">
    <label class="control-label" for="item[adress]">Adress</label>
    <div class="controls">
		<p><?php echo str_replace(array("\n","\r\n"),array('<br/>','<br/>'),$this->item['adress']);?></p>
    </div>
  </div>
    <?php }
  if ($this->item['skype_name']){ ?>  
  <div class="control-group">
    <label class="control-label" for="item[skype_name]">Skype</label>
    <div class="controls">
       <label class="control-label t-left" ><?php echo $this->item['skype_name']?></label>
    </div>
  </div>
<?php }?>  
  <div class="control-group">
    <div class="controls">
<button class="btn" type="button" onclick="_ajax.load('<?php echo SITE_URL?>/contacts/edit/<?php echo $this->item['id'];?>','#main');">Edit</button>    </div>
  </div>	
</div>	

