<div class="basemenu_wrapper">
	<div class ="basemenu_left">
		<?=$this->Html->link('発案', ['action'=>'input']);?>
    </div>
	<div class ="basemenu_right">
		<?=$this->Form->create($entity,['url'=>['action'=>'index']]) ?>
		<?=$this->Form->text("search", ['size'=>'15', 'placeholder'=>'講師IDで検索']) ?>
		<?=$this->Form->input(" ",
							['type'=>'select','empty'=>'選択してください',
							'options'=>$category]) ?>
		<?=$this->Form->button("検索") ?>
		<?=$this->Form->end() ?>
    </div>
</div>
<?php  foreach($data  as  $obj):  ?>
	<div class="timelinediv">
		<p class="p_date"><?=$obj->seminarTitle ?></p>
		<!--<p class="p_date"></p>-->
		<!--<p></p>-->
		<!--<a href>もっと見る</a>-->
	</div>
<?php  endforeach;  ?>
