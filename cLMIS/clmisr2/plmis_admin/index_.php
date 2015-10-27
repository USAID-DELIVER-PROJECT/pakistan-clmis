<?php

/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file which will the contents from tbl_cms
/***********************************************************************************************************/


include ("plmis_inc/common/CnnDb.php");
include ("plmis_inc/classes/cCms.php");

/*$var=base64_decode('MTJKc2kxMjM0');
echo $var;*/

$db = new Database();
$db->connect();
$objContents = new cCms();

$articles_array = array();


$sql = $objContents->Select("tbl_cms", " * "," and title='Home'");
if($db->query($sql) and $db->get_num_rows()){
    for($i=0;$i<$db->get_num_rows();$i++)
    {
        $row = $db->fetch_one_assoc();
        array_push($articles_array,$row);
    }
}


include "plmis_inc/common/Global.php";

//include_once('config.inc.php');
include_once('html/html.inc.php');

if(isset($_GET['e']) && $_GET['e'] == -1){?>
<script type="text/javascript" language="javascript">
    alert("Invalid Login Id/Password");
</script>
<?php }


startHtml($system_title);
siteMenu("Home");
leftContents(stripslashes($articles_array[0]['description']));
rightContents();
footer();
endHtml();

?>
