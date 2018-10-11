<div id="inputdiv">
	<!--<? /*=$this->Html->image('biglogo.png', array('alt'=>'logo'))*/?>-->
	<?=$this->Form->create($entity,['url'=>['action'=>'addRecord']]) ?>
	<table class="inputtable" style="width: 100%" cellspacing="10">
	<div class="addbtndiv">
		<?=$this->Form->button('戻る', ['onclick' => 'history.back()', 'type' => 'button'])?>
	</div>

		<tr>
			<td colspan="2">発案登録</td>
		</tr>
		<tr>
			<td style="width: 68px">タイトル</td>
			<td>	
				<?=$this->Form->text("costdate",['id'=>'textform']) ?>
				
			</td>
		</tr>
		<tr>
			<td style="width: 68px">開催日時</td>
			<td><?=$this->Form->text("usedetail",['id'=>'textform']) ?></td>
		</tr>
		<tr>
			<td style="width: 68px">参加費</td>
			<td><?=$this->Form->text("value",['id'=>'textform']) ?></td>
		</tr>
		<tr>
		<tr>
			<td style="width: 68px">要求詳細</td>
			<td><?=$this->Form->text("value",['id'=>'textform']) ?></td>
		</tr>
		<tr>
			<td style="width: 68px">分類</td>
			<td><?=$this->Form->control(" ",
									['type'=>'select','empty'=>'選択してください',
									 'options'=>'ビジネス']) ?></td>
		</tr>
		<tr>

		<tr>

			<td colspan="2">
					<?=$this->Form->button("登録", ['id'=>'submit']) ?>
					
			</td>
		</tr>
	</table>
	<?=$this->Form->end() ?>
	</div>
