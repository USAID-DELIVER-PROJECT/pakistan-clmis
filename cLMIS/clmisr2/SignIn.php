<?php

session_start();
/*
if ( strtoupper($_REQUEST['LgID']) == 'GUEST' && strtoupper($_REQUEST['LgPW']) == 'GUEST' ) { 
    $_SESSION['user'] = 'Guest';
}
else if ( strtoupper($_REQUEST['LgID']) == 'PUNJAB' && strtoupper($_REQUEST['LgPW']) == 'PUNJAB' ) { 
    $_SESSION['user'] = 'PUNJAB';
}
else if ( strtoupper($_REQUEST['LgID']) == 'SINDH' && strtoupper($_REQUEST['LgPW']) == 'SINDH' ) {
    $_SESSION['user'] = 'SINDH';
}
else if ( strtoupper($_REQUEST['LgID']) == 'KPK' && strtoupper($_REQUEST['LgPW']) == 'KPK' ) { 
    $_SESSION['user'] = 'KPK';
}
else if ( strtoupper($_REQUEST['LgID']) == 'BALOCHISTAN' && strtoupper($_REQUEST['LgPW']) == 'BALOCHISTAN' ) {
    $_SESSION['user'] = 'BALOCHISTAN';
}
else if ( strtoupper($_REQUEST['LgID']) == 'GB' && strtoupper($_REQUEST['LgPW']) == 'GB' ) { 
    $_SESSION['user'] = 'GB';
}
else if ( strtoupper($_REQUEST['LgID']) == 'AJK' && strtoupper($_REQUEST['LgPW']) == 'AJK' ) { 
    $_SESSION['user'] = 'AJK';
}
else if ( strtoupper($_REQUEST['LgID']) == 'FATA' && strtoupper($_REQUEST['LgPW']) == 'FATA' ) { 
    $_SESSION['user'] = 'FATA';
}
else 
{*/
$URI="plmis_admin/index.php?login=".$_REQUEST['LgID']."&pass=".$_REQUEST['LgPW'];
header("location:$URI");
exit;
/*}
header('Location:Cpanel.php');*/
?>