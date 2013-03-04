<?php

	/**
	 * This is the generic landing page themed to the default theme.
	 *
	 * If local ad fields are set in hotspots table, it will also display
	 * a local ad imglink
	 *
	 * @copyright 2013 Bluepod Media Ltd
	 * @author Barry Jones <barry@onalldevices.com>
	 */

	// Initialisation
	require_once('../php/app.php');

	// Fetch the local ad page URL from config
	$local_ad_cfg = getLocalAdvertSettings();

	// Build the template top
	include($root.'/views/html-header.php');
	include($root.'/views/top.php');
?>

<!-- Show media placeholders for linked advert images -->
<div class="row-fluid bannerads">
	<div class="span12">
		<ul class="unstyled">
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Movies</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Sport</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Games</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">News</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Movies</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Sport</div>
			</li>
			<li>
				<a href="#">
					<img src="<?php echo SITE_URL;?>img/140x112.gif" alt="Accessible description" />
				</a>
				<div class="bottom-caption">Games</div>
			</li>

			<!-- Include the local ad page if specified in config -->
			<?php if (is_array($local_ad_cfg)){?>
				<li>
					<a href="<?php echo $local_ad_cfg['url'];?>">
						<img src="<?php echo SITE_URL;?>img/<?php echo $local_ad_cfg['img'];?>" alt="<?php echo $local_ad_cfg['caption'];?>" />
					</a>
					<div class="bottom-caption"><?php echo $local_ad_cfg['caption'];?></div>
				</li>
			<?php } ?>

		</ul>
	</div>
</div>

<?php
	include($root.'/views/html-footer.php');
