<?php
@session_start();
?>
<div style="font-size:50px; text-align:center; padding-top:50px; min-height:400px;">
	Welcome <?php echo $_SESSION['user']['name']; ?>!
	<p>All of the summaries will be housed here...</p>
</div>