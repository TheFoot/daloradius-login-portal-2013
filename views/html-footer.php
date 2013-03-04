
		</div>
	</div>

	<!-- Javascripts -->
	<script src="<?php echo SITE_URL;?>js/jquery.cookie.min.js"></script>
	<script src="<?php echo SITE_URL;?>js/bootstrap.min.js"></script>
	<script src="<?php echo SITE_URL;?>js/app.js"></script>

	<!-- Stuff lang tags etc in to namespace -->
	<script>
		consega.lang = <?php echo json_encode($lang);?>;
		consega.cookiename = '<?php echo $cookiename;?>';
	</script>

</body>
</html>