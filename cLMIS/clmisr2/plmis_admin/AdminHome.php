<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
	<div class="page-container">
	<?php include "template/".$_SESSION['menu'];?>
	
		<div class="page-content-wrapper">
			<div class="page-content">
				<h3 class="heading">
				<?php
					$ud=$_SESSION['userdata'];
					echo "<h1>Welcome: ".$ud[2]." </h1>";
				?>
                </h3>
                
			</div>
		</div>
    </div>
<?php include "template/footer.php";?>
</body>
</html>