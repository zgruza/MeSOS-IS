
<div id="menu">
	<div class="menu-items">
		<ul>
			<li class="menu-item-vstup">
				<div class="menu-item-inner">
					<a href="admin.php" class="menu-link">
						<span class="menu-icon"><img src="imgs/menu-home.png" alt=""></span>
						<span class="menu-title">Vstup</span>
					</a>
				</div>
			</li>

						<li class="menu-item-tridni-kniha"> <!-- menu-item-open -->
				<div class="menu-item-inner">
					<a href="teachers.php" class="menu-link <?php if($active_menu == "admin" || $active_menu == "teachers" || $active_menu == "subjects" || $active_menu == "classes"){ echo "active"; } ?>">
						<span class="menu-icon"><img src="imgs/menu-tridnice.png" alt=""></span>
						<span class="menu-title">Třídní kniha</span>
						<span class="menu-tree-icon"></span>
					</a>

					<ul>
						<li>
							<a href="teachers.php"><?php if($active_menu == "teachers"){echo "<b>Učitelé</b>";}else{echo "Učitelé";}?></a>
						</li>
						<li>
							<a href="classes.php"><?php if($active_menu == "classes"){echo "<b>Třídy</b>";}else{echo "Třídy";}?></a>
						</li>
						<li>
							<a href="subjects.php"><?php if($active_menu == "subjects"){echo "<b>Předměty</b>";}else{echo "Předměty";}?></a>
						</li>
					</ul>
				</div>
			</li>
		
		<li class="menu-item-zakovska-knizka">
			<div class="menu-item-inner">
				<a href="admin.php" class="menu-link <?php if($active_menu == "zk"){ echo "active"; } ?>">
						<span class="menu-icon"><img src="images/menu-zakovska-knizka.png" alt=""></span>
						<span class="menu-title">Touchscreen</span>
						<span class="menu-tree-icon"></span>
					</a>

					<ul>
						<li>
							<a href="teachers.php">Učitelé</a>
						</li>
						<li>
							<a href="classes.php">Třídy</a>
						</li>
						<li>
							<a href="subjects.php">Předměty</a>
						</li>
					</ul>
				</div>
			</li>
			
			<li class="menu-item-admin">
				<div class="menu-item-inner">
					<a href="system.php" class="menu-link <?php if($active_menu == "system"  || $active_menu == "idsjmk" || $active_menu == "update"){ echo "active"; } ?>">
						<span class="menu-icon"><img src="imgs/menu-administrace.png" alt=""></span>
						<span class="menu-title">Systém</span>
						<span class="menu-tree-icon"></span>
					</a>

					<ul>
						<li><a href="system.php"><?php if($active_menu == "system"){echo "<b>Systém</b>";}else{echo "Systém";}?></a></li>
						<li><a href="idsjmk.php"><?php if($active_menu == "idsjmk"){echo "<b>Autobusy</b>";}else{echo "Autobusy";}?></a></li>
						<li><a href="update.php"><?php if($active_menu == "update"){echo "<b>Update</b>";}else{echo "Update";}?></a></li>
					</ul>
				</div>
			</li>
		</ul>
	</div>

	<div id="footer">
		<div id="footer-inner">
			<a href="https://www.mesos.cz/" target="_blank" rel="nofollow" class="manual-button">
				<img src="imgs/manual-icon.png" width="31" height="24" alt="">
				DOCS
			</a>
			<p class="footer-muted">
						Developed by <a href="">Robotika Gang</a>,<br>
						MěSOŠ Informační systém <span><?=$__VERSION__;?></span>.
					</p>
		</div>
	</div>
</div>
