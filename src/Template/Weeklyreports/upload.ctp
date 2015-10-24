<?php echo $this->Form->create(null, ['type' => 'file']); ?>
<label>Upload</label>
<?php 
	// a form for uploading a new weeklyreport as a txt file
        echo $this->Form->file('uploadfile.', ['single']);
	echo $this->Form->button('Submit', ['type' => 'submit']);
	echo $this->Form->end();
 ?>
