<?php require(VIEW_DIR.DS.'_header_includes.php');  ?>
		<div id="wrapper" class="container">
<?php require(VIEW_DIR.DS.'_header.php'); ?>
	<br>
	 <img src="<?php echo ASSETS_DIR.DS."images".DS; ?>error-404.jpg" class="img-responsive" alt="Responsive image">
	 <h3>Go to <a href="<?php echo app::link_to(''); ?>">Home</a></h3>
	<?php require(VIEW_DIR.DS.'_footer.php'); ?>

</div>
<?php require(VIEW_DIR.DS.'_footer_includes.php'); ?>
