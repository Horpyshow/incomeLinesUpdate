<input type="hidden" name="amt_remitted" id="amt_remitted" value="<?php if (isset($amt_remitted)) echo $amt_remitted; ?>" maxlength="20" />
<input type="hidden" name="repost_id" id="repost_id" value="<?php if (isset($repost_id)) echo $repost_id; ?>" maxlength="20" />	

<?php
	echo '
	<button type="submit" name="btn_post_'.$income_line.'" class="btn btn-danger btn-sm">Post '.$income_line_desc.' <span class="glyphicon glyphicon-send"></span></button>
	<button type="reset" name="btn_clear" class="btn btn-primary btn-sm">Clear</button>';
?>