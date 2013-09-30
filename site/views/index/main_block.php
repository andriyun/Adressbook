				<?php if ($this->heading_title) {?>
				<div class="page-header">
					<h1><?php echo $this->heading_title?></h1>
				</div>
				<?php }?>
				<?=$this->display($this->main_view);?>