<div class="well">
<?php
$page = isset($_GET['page']) ? $_GET["page"] : $home;
include ($page);
?>
</div>
