<!-- Navigation -->
<?php if ($nav && count($nav) > 0){?>
<div class="row-fluid">
	<div class="span12">
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">

					<!-- Toggle button -->
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<!-- Nav items here - collapses at 940px or less -->
					<div class="nav-collapse collapse">
						<ul class="nav nav-pills">
							<?php foreach ($nav as $i => $navitem){?>
							<li>
								<a href="<?php echo $navitem['url'];?>"
								   title="<?php echo $navitem['title'];?>">
									<?php echo $navitem['name'];?></a>
							</li>
							<?php } ?>
						</ul>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>