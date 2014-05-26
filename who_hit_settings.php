<div class="wrap">
	<h2>Settings - Who Hit The Page</h2>
	<div id="welcome-panel" class="welcome-panel">
    	<div class="welcome-panel-content">
        	<div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3>Uninstall Settings!</h3>
                    <?php
						if ( isset ( $_POST['update-uninstall-option'] ) ){
							$update_uninstall = stripslashes( $_POST['uninstall-action'] );
							if ( update_option ( "whtp_data_action",  $update_uninstall) ){
								echo '<div id="message" class="updated">
										<p>Settings updated.</p> 
									</div>';
							}							
						}
						$option = get_option('whtp_data_action');
					?>
                    <p class="about-description">What should happen when you un-install the plugin?</p>
                    <form action="" name="" method="post">
                        <p>
                            <label for="uninstall-action">Select One Option</label>
                        </p>
                        <p>
                            <input type="radio" name="uninstall-action" value="delete-all" 
                            <?php if ($option == "delete-all") echo "checked"; ?>  />
                            <strong>Delete all Tables</strong> and <strong>Data</strong>
                        </p>
                        <p>
                            <input type="radio" name="uninstall-action" value="clear-tables"
                            <?php if ($option == "clear-tables") echo "checked"; ?>  />
                            <strong>Clear data</strong>, leave table structures.
                        </p>
                        <p>
                            <input type="radio" name="uninstall-action" value="do-nothing"
                            <?php if ($option == "do-nothing") echo "checked"; ?>  />
                            <strong>Leave all</strong> tables and data
                        </p>
                        <p>
                            <input type="hidden" name="update-uninstall-option" value="update-uninstall-option" /> 
                            <input type="submit" value="Update Options" class="button button-primary" />
                        </p>
                    </form>
               </div>
               
               <!-- -->
               <div class="welcome-panel-column">
                    <h3>Backup Settings</h3>
                    <?php
						if ( isset ( $_POST['update-backup-options'] ) ){
							$update_backup = stripslashes( $_POST['backup-action'] );
							if ( update_option ( "whtp_data_export_action",  $update_backup) ){
								echo '<div id="message" class="updated">
										<p>Settings updated.</p> 
									</div>';
							}							
						}
						$export_option = get_option("whtp_data_export_action");
					?>
                    <p class="about-description">What should happen when you restore a backup?</p>
                    <form action="" name="" method="post">
                        <p>
                            <label for="uninstall-action">Select One Option</label>
                        </p>
                        <p>
                            <input type="radio" name="backup-action" value="delete-all"
                            <?php if ($export_option == "delete-all") echo "checked"; ?>  />
                            <strong>Override</strong> existing data 
                        </p>
                        <p>
                            <input type="radio" name="backup-action" value="clear-tables"
                            <?php if ($export_option == "clear-tables") echo "checked"; ?>  />
                            <strong>Update</strong> existing data, <strong>accumulate</strong> counts
                        </p>
                        <p>
                            <input type="radio" name="backup-action" value="do-nothing"
                            <?php if ($export_option == "do-nothing") echo "checked"; ?>  />
                            <strong>Skip/Ignore</strong> Existing Records
                        </p>
                        <p>
                            <input type="hidden" name="update-backup-options" value="update-backup-options" /> 
                            <input type="submit" value="Update Options" class="button button-primary" />
                        </p>
                    </form>
               </div>
           </div>
       </div>
   </div>
</div>