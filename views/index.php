<?php
	wp_register_style( 'poeditor-style', plugins_url( '_resources/style.css' , __FILE__ ), array(), '20120208', 'all' );
	wp_enqueue_style( 'poeditor-style' );
?>
<div class="wrap poeditor">
	<div id="poeditorTopLinks">
		<a class="button-secondary" href="<?php echo POEDITOR_PATH;?>&amp;do=changeApiKey" title="<?php _e( 'Change API Key' ); ?>"><?php _e( 'Change API Key' ); ?></a>
		<a class="button-secondary poeditorReset" href="#reset" title="<?php _e( 'Reset plugin' ); ?>"><?php _e( 'Reset plugin' ); ?></a>
	</div>
	<h1>
		<?php
			echo '<img src="' . plugins_url( '_resources/img/logo.png' , __FILE__ ) . '" alt="POEditor" > ';
		?>
	</h1>
	<br clear="all">
	<a class="button-secondary poeditorTableExtraLink" href="<?php echo POEDITOR_PATH;?>&amp;do=getProjects" title="<?php _e( 'Refresh online projects list' ); ?>">
		<span class="buttons-icon-refresh"></span>
		<?php _e( 'Refresh online projects list' ); ?>
	</a>
	<h2 class="title poeditorTableTitle">
		POEditor translations
	</h2>

	<br clear="all">
	<?php
	if( is_array($projects) && !empty( $projects) ) {
		?>
		<table class="widefat">
			<thead>
				<tr>
					<th>
						Project
					</th>
					<th width="30%">
						Language
					</th>
					<th>
						Progress
					</th>
					<th>
						Assigned file
					</th>
					<th>
						Actions
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$i = 1;
					$j = 0;

					foreach ($projects as $project) {
						?>
						<tr <?php if( $i % 2 == 0 ) echo 'class="alternate"';?>>
							<td><?php echo $project['name'];?></td>
							<td><?php echo $project['language'];?></td>
							<td><?php echo $project['percentage'];?>%</td>
							<td>
								<?php
									$key = $project['id'] . '_' . $project['code'];
									if( isset($assingments[$key]) ) {
										echo str_replace(WP_CONTENT_DIR, '', $assingments[$key]);
									} else {
										?>
										<a href="#assignFile" project="<?php echo $project['id'];?>" language="<?php echo $project['code'];?>" class="assignFile">Assign file</a>
										<?php
									}
								?>
							</td>
							<td>
								<?php
									if( isset($assingments[$key]) ) {
										?>
										<a href="<?php echo POEDITOR_PATH;?>&amp;do=import&amp;projectId=<?php echo $project['id'];?>&amp;language=<?php echo $project['code'];?>">Import</a> | 
										<a href="<?php echo POEDITOR_PATH;?>&amp;do=export&amp;projectId=<?php echo $project['id'];?>&amp;language=<?php echo $project['code'];?>&amp;type=export">Export</a> |
										<a href="<?php echo POEDITOR_PATH;?>&amp;do=export&amp;projectId=<?php echo $project['id'];?>&amp;language=<?php echo $project['code'];?>&amp;type=sync">Sync</a> | 
										<a href="<?php echo POEDITOR_PATH;?>&amp;do=unassignFile&amp;projectId=<?php echo $project['id'];?>&amp;language=<?php echo $project['code'];?>">Unassign file</a>
										<?php
									}
								?>
							</td>
						</tr>
						<?php
						if( !isset($projects[$j+1]['id']) || $project['id'] != $projects[$j+1]['id'] ) {
							?>
							<tr>
								<td></td>
								<td>
									<a href="#addLanguage" class="addLanguageButton button-secondary" rel="<?php echo $project['id'];?>">+ Add language to "<?php echo $project['name'];?>"</a>
									<form action="<?php echo POEDITOR_PATH;?>&amp;do=addLanguage" class="addLanguage" id="addLanguage_<?php echo $project['id'];?>" method="post">
										<select name="language">
											<?php
												foreach ($languages as $code => $language) {
													?>
													<option value="<?php echo $code;?>"><?php echo $language;?></option>
													<?php
												}
											?>
										</select>
										<input type="hidden" value="<?php echo $project['id'];?>" name="project">
										<input type="submit" name="submit" id="submit" class="button button-primary" value="Add language"> 
										<a href="#" class="cancelAddLanguage" rel="<?php echo $project['id'];?>">Cancel</a>
									</form>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<?php
						}
						$i++;
						$j++;
					}
				?>
				<tr>
					<td colspan="5">
						<a href="#addProject" class="addProjectButton button-secondary">+ Create project</a>
						<form action="<?php echo POEDITOR_PATH;?>&amp;do=addProject" class="addProject" method="post">
							<input type="text" name="project" id="projectNameInput">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="Create project">
						</form>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th>
						Project
					</th>
					<th>
						Language
					</th>
					<th>
						Progress
					</th>
					<th>
						Assigned file
					</th>
					<th>
						Actions
					</th>
				</tr>
			</tfoot>
		</table>
		<?php
	} else {
		?>
		<p><?php _e('Found no projects in your poeditor.com account.'); ?></p>
		
		<a href="#addProject" class="addProjectButton button-primary">+ Create project</a>
		<form action="<?php echo POEDITOR_PATH;?>&amp;do=addProject" class="addProject" method="post">
			<input type="text" name="project" id="projectNameInput">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Create project">
		</form>
		<?php
	}
	?>
	
	<h2 class="title poeditorTableTitle">
		Local language files
	</h2>
	<a class="button-secondary poeditorTableExtraLink" href="<?php echo POEDITOR_PATH;?>&amp;do=scan" title="<?php _e( 'Rescan for language files' ); ?>">
		<span class="buttons-icon-refresh"></span>
		<?php _e( 'Rescan for language files' ); ?>
	</a>
	<?php
	if( is_array($locations) && !empty( $locations) ) {
		?>
		<table class="widefat">
			<thead>
				<tr>
					<th>
						Location
					</th>
					<th>
						File
					</th>
					<th>
						Last changed
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$i = 1;
					foreach ($locations as $folder => $files) {
						$j = 1;
						$totalFiles = count($files);
						foreach ($files as $file) {
							?>
							<tr <?php if( $i % 2 == 0 ) echo 'class="alternate"';?>>
								<?php if( $j == 1 ) echo '<td rowspan="' . ($totalFiles) .  '" class="poeditorVerticalAlign">';?>
									<?php 
										if( $j == 1 ) {
											echo $folder;

											if( !is_writable(WP_CONTENT_DIR . $folder) ) {
												?>
												<img src="<?php echo plugins_url( '_resources/img/warning.png' , __FILE__ );?>" class="poeditorWarningIcon" alt="This folder is not writable">
												<?php
											}
										}

									?>
								<?php if( $j == 1 ) echo '</td>';?>
								<td>
									<?php 
										echo $file;

										if( !is_writable(WP_CONTENT_DIR . $folder . $file) ) {
											?>
											<img src="<?php echo plugins_url( '_resources/img/warning.png' , __FILE__ );?>" class="poeditorWarningIcon" alt="This folder is not writable">
											<?php
										}
									?>
								</td>
								<td>
									<?php
										$filemtime = filemtime(WP_CONTENT_DIR . $folder . $file);
										echo date(get_option('date_format') . ', ' . get_option('time_format'), $filemtime);
									?>
								</td>
							</tr>
							<?php
							$i++;
							$j++;
						}
						
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th>
						Location
					</th>
					<th>
						File
					</th>
					<th>
						Last changed
					</th>
				</tr>
			</tfoot>
		</table>
		<?php
	} else {
		?>
		<br clear="both" />
		<a class="button-secondary" href="<?php echo POEDITOR_PATH;?>&amp;do=scan" title="<?php _e( 'No language files found yet. Scan now' ); ?>"><?php _e( 'No language files found yet. Scan now' ); ?></a>
		<?php
	}
	?>
	<div id="assignFile">
		<input type="hidden" name="project" id="assignFileProjectId" value="0">
		<input type="hidden" name="language" id="assignFileLanguageCode" value="">
		<h2 class="title">
			Assign a local file to a POEditor project language
		</h2>
		<?php
		if( is_array($locations) && !empty( $locations) ) {
			?>
			<table class="widefat">
				<tr>
					<th>
						#
					</th>
					<th>
						Location
					</th>
					<th>
						File
					</th>
					<th>
					</th>
				</tr>
				<?php
					$i = 1;
					foreach ($locations as $folder => $files) {
						$j = 1;
						$totalFiles = count($files);
						foreach ($files as $file) {
							?>
							<tr <?php if( $i % 2 == 0 ) echo 'class="alternate"';?>>
								<td><?php echo $i;?></td>
								<td>
									<?php 
										if( $j == 1 ) echo $folder;
									?>
								</td>
								<td><?php echo $file;?></td>
								<td>
									<a class="button-secondary hasPath selectPath" rel="<?php echo base64_encode(WP_CONTENT_DIR.$folder.$file);?>" href="#select" title="<?php _e( 'Select' ); ?>"><?php _e( 'Select' ); ?></a>
								</td>
							</tr>
							<?php
							if( $j == $totalFiles ) {
								?>
								<tr <?php if( $i % 2 == 0 ) echo 'class="alternate"';?>>
									<td></td>
									<td>
									</td>
									<td>
										<?php _e('Add new');?>: 
										<input type="text" placeholder="filename.po" name="newFilename" class="all-options" id="addNewSelect_<?php echo $i . '_' . $j;?>">
									</td>
									<td>
										<a class="button-secondary selectPath" folder="<?php echo WP_CONTENT_DIR.$folder;?>" rel="addNewSelect_<?php echo $i . '_' . $j;?>" href="#select" title="<?php _e( 'Select' ); ?>"><?php _e( 'Select' ); ?></a>
									</td>
								</tr>
								<?php
							} 
							$i++;
							$j++;
						}
						
					}
				?>
					<tr>
						<td colspan="3">
							<?php _e('Add location manually');?>: 
							<input type="text" name="newFilename" class="regular-text" id="addNewSelect_0_0">
						</td>
						<td>
							<a class="button-secondary selectPath" rel="addNewSelect_0_0" folder="<?php echo WP_CONTENT_DIR;?>" href="<?php echo POEDITOR_PATH;?>&amp;do=scan" title="<?php _e( 'Select' ); ?>"><?php _e( 'Select' ); ?></a>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<small>
								Example: <i>/themes/twentyeleven/languages/test.po</i>
							</small>
						</td>
					</tr>
			</table>
			<?php
		} else {
			?>
			<table>
				<tr>
					<td colspan="3">
						<?php _e('Add location manually');?>: 
						<input type="text" name="newFilename" class="regular-text" id="addNewSelect_0_0">
					</td>
					<td>
						<a class="button-secondary selectPath" rel="addNewSelect_0_0" folder="<?php echo WP_CONTENT_DIR;?>" href="<?php echo POEDITOR_PATH;?>&amp;do=scan" title="<?php _e( 'Select' ); ?>"><?php _e( 'Select' ); ?></a>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<small>
							Example: <i>/themes/twentyeleven/languages/test.po</i>
						</small>
					</td>
				</tr>
			</table>
			<a class="button-secondary" href="<?php echo POEDITOR_PATH;?>&amp;do=scan" title="<?php _e( 'No language files found yet. Scan now' ); ?>"><?php _e( 'No language files found yet. Scan now' ); ?></a>
			<?php
		}
		?>
		<a href="#cancel" class="button button-primary" id="cancelFileAssign">Cancel</a>
	</div>
	<p>
		<img src="<?php echo plugins_url( '_resources/img/warning.png' , __FILE__ );?>" class="poeditorWarningIcon" alt="This folder is not writable"> = The folder or file is not writable (so we won't be able to update the files with the information from poeditor.com)
	</p>

	<div id="resetConfirm">
		<h4>
			Are you sure you want to reset the plugin?
		</h4>
		<p>
			This will delete all your local file assignments and it will detach your Wordpress installation from you account on POEditor.com
		</p>
		<a href="#cancel" class="button button-primary" id="poeditorCancelReset">Cancel</a>
		<a href="<?php echo POEDITOR_PATH;?>&amp;do=clean" class="button button-primary" id="poeditorProceedWithReset">Reset</a>
	</div>
</div>

<script src="<?php echo plugins_url( '_resources/js/jquery.base64.min.js' , __FILE__ );?>"></script>
<script>

	jQuery('.addLanguageButton').on('click', function(e){
		var projectId;

		projectId = jQuery(this).attr('rel');
		jQuery(this).hide();
		jQuery('#addLanguage_' + projectId).show();

		e.preventDefault();
	});

	jQuery('.cancelAddLanguage').on('click', function(e){
		var projectId;

		projectId = jQuery(this).attr('rel');
		jQuery('#addLanguage_' + projectId).hide();
		jQuery('.addLanguageButton').show();
		e.preventDefault();
	});

	jQuery('.addProjectButton').on('click', function(e){
		jQuery(this).hide();
		jQuery('.addProject').show();
		jQuery('#projectNameInput').focus();
		e.preventDefault();
	});

	jQuery('.selectPath').on('click', function(e){
		var projectId, language, path, path_raw, identifier, folder;

		projectId = jQuery("#assignFileProjectId").val();
		language = jQuery("#assignFileLanguageCode").val();

		if( jQuery(this).hasClass('hasPath') ) {
			path = jQuery(this).attr('rel');
		} else {
			identifier = jQuery(this).attr('rel');
			path_raw = jQuery("#" + identifier).val();

			if( path_raw == '' ) {
				jQuery("#" + identifier).addClass('error');
				return false;
			}

			folder = jQuery(this).attr('folder');
			path = jQuery.base64.encode(folder + path_raw);
		}

		window.location = '<?php echo POEDITOR_PATH;?>&do=assignFile&project=' + projectId + '&language='+language+'&path=' + path; 

		e.preventDefault();
	});

	jQuery('.assignFile').on('click', function(e){
		var projectId, language;

		projectId = jQuery(this).attr('project');
		language = jQuery(this).attr('language');
		jQuery("#assignFileProjectId").val(projectId);
		jQuery("#assignFileLanguageCode").val(language);

		jQuery("div#assignFile").fadeIn();
		e.preventDefault();
	});

	jQuery('#cancelFileAssign').on('click', function(e){
		
		jQuery("#assignFileProjectId").val(0);
		jQuery("#assignFileLanguageCode").val('');

		jQuery("div#assignFile").fadeOut();
		e.preventDefault();
	});

	jQuery('.poeditorReset').on('click', function(e){
		
		jQuery("div#resetConfirm").fadeIn();
		e.preventDefault();
	});

	jQuery('#poeditorCancelReset').on('click', function(e){
		jQuery("div#resetConfirm").fadeOut();
		e.preventDefault();
	});
</script>