<style>
    div.menu ul li a
    {
        padding:0 20px !important;
    }
    div.menu ul li ul.navigation-2 a
    {
        padding:0 10px !important;
    }
</style>

<link href="http://localhost:80/clmis/plmis_css/styles.css" rel="stylesheet" type="text/css" />
<script src="http://localhost:80/clmis/plmis_js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="http://localhost:80/clmis/plmis_admin/Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="http://localhost:80/clmis/plmis_admin/Scripts/custom-admin.js"></script>


            <?php if (isset($_SESSION['user'])) {
                echo "<div style='color:Red;text-align:center'>For any problem or suggestions please contact Support at support@lmis.gov.pk or Call: 051-2655425-6</div>";
            } ?>
            <link href="../css/PAK.css" rel="stylesheet" type="text/css" />

            <div class="menu">
                <div class="wrraper">
                    <ul>
                        <li><a href="ManageUserSub.php">Users</a> </li>
                        <li><a href="data_entry_admin.php">Data Entry</a> </li>
                        <li><a href="changePass.php">Change Password</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
 
