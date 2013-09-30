			<?php if ($this->left_sidebar) {?>
			<div id="main"  class="span8 offset4 content-block">
							<?=$this->display($this->main_block?$this->main_block:'index/main_block');?>
			</div>			
			<div id="leftsidebar" class="span4" style="position:fixed;margin-left: 0;">
				<div class="content-block"><?=$this->display($this->left_sidebar);?></div>
				</div><!--/span-->

			<?php } else {?>
			<div id="main" class="span12 content-block">
		
				<?=$this->display($this->main_block?$this->main_block:'index/main_block');?>
			</div>
				<?php } ?>