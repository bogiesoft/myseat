<!-- Begin one column box -->
<div class='onecolumn'>
 <div class='header'>
	<a href="?selectedDate=<?php echo buildDate($settings['dbdate'],$sd,$sm,$sj,-1); ?>" class="navgroup">
		&laquo;
	</a>
	<div class="date dategroup">
		<div class="text" id="datetext"><?php echo $_SESSION['selectedDate_user']; ?></div>
		<input type="text" id="datepicker"/>
		<input type="hidden" id="dbdate" value="<?php echo $_SESSION['selectedDate']; ?>"/>
	</div>
	<a href="?selectedDate=<?php echo buildDate($settings['dbdate'],$sd,$sm,$sj,1); ?>" class="navgroup">
		&raquo;
	</a>
	<!-- Begin 2nd level tab -->
	<ul class='second_level_tab'>
		<li>
			<a href='?p=2' class='button_dark'> <?php echo _back;?>
			</a>
		<li/>
	</ul>
	<!-- End 2nd level tab -->
 </div>
<div id='content_wrapper'>
		<?php
			// MESSAGE boxes goes here
			include('includes/messagebox.inc.php'); 
		?>
</div>
</div>
<br class='cl' />
			<?php
			// ** print out the sparklines of all outlets **
			// memorize actual selected outlet
			$rem_outlet = $_SESSION['outletID'];
			
			echo"<div class='onecolumn'><div class='header'>\n";
			echo"<div class='dategroup_name'>"._statistics."</div>
			</div>\n<div class='center width-70 margin-top-20'>";
			
			//reset zebra containers
			$c = 0;
			
			$outlets = querySQL('db_outlets');
			foreach($outlets as $row) {
			 if ( ($row->saison_start<=$row->saison_end 
				 && $_SESSION['selectedDate_saison']>=$row->saison_start 
				 && $_SESSION['selectedDate_saison']<=$row->saison_end)
				){
					// outlet ID
					$_SESSION['outletID'] = $row->outlet_id;
					// outlet settings
					$rows = querySQL('db_outlet_info');
					if($rows){
						foreach ($rows as $key => $value) {
							$_SESSION['selOutlet'][$key] = $value;
						}
					}

					echo "<div class='";
					echo ($c++%2==1) ? 'right-side' : 'leftside' ;
					echo "'>\n";
					// get outlet maximum capacity
					$maxC = maxCapacity();
					
					include('includes/sparkline.inc.php');
					
					echo"<br/>\n</div>";
					echo ($c%2==1) ? "" : "<br class='cl' /><br/><br/><br/><br/>" ;
				}
			}
			echo"</div></div><br class='clear' />";
			
			// memorize actual selected outlet
			$_SESSION['outletID'] = $rem_outlet;
			// memorize selected outlet details
			$rows = querySQL('db_outlet_info');
			if($rows){
				foreach ($rows as $key => $value) {
					$_SESSION['selOutlet'][$key] = $value;
				}
			}
			
			// ** print out all reservations **
			echo"<div class='onecolumn'><div class='header'>\n";
			echo"<div class='dategroup_name'>"._confirmed_reservations."</div>
			</div>\n
			<div class='content'>\n";
			
			// no waitlist
			$_SESSION['wait'] = 0;
			include('includes/reservations_grid.inc.php');
			
			echo"\n<br class='cl' /><br/>\n</div></div><br/>";
			
			?>

<br class="clear"/><br/>