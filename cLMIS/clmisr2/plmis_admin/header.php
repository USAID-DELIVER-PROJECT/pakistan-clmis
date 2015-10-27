<script type="text/javascript" src="Scripts/jquery-1.7.min.js"></script>
<script type="text/javascript" src="Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="Scripts/custom.js"></script>
<style>
    label.error{
        color: #B70000;
        display: block;
        font-family: "Comic Sans MS",cursive;
        font-size: 11px;
    }
    .sb1Exception {
        color: red;
        font-family: Arial,Verdana,Helvetica,sans-serif;
        font-size: 16px;
        text-decoration: none;
    }
</style>
<table width="100%">
    <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr><td align="center" bgcolor="#78B82E"><img src="<?php echo ADMIN_IMAGES.'adminheader.jpg';?>" alt="" width="1002"/></td></tr>
            <tr>
                <td>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td bgcolor="#CCC" align="center">
                                <?php
                                $m=$_SESSION['menu'];

                                include($m); ?>
                            </td>
                        </tr>
                    </table></td>
            </tr>
        </table></td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
