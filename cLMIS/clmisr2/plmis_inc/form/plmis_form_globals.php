<?php
/*----------------------------------------------------------------------------------------
Global variables common to PLMIS form modules

Author: mahmed

Modification History (LIFO)
------------------------------------------------------------------------------------- */
// Oct 18, 2007 - mahmed added:   sysusrrec_id


//$breadcrumbgif = "plmis_img/breadcrumb.gif";

//***Manage Users table
$profile['user']=array
    (
    'sysusrrec_id' => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getUserTypeName(sysusr_type)'  => array
        (
        'title'           => 'User Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getWarehouseName(whrec_id)'     => array
        (
        'title'           => 'WH Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
     'usrlogin_id'   => array
        (
        'title'           => 'User Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getSktName(Stkid)'  => array
        (
        'title'           => 'Stakeholder',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'homepage'  => array
        (
        'title'           => 'Homepage',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'staticMenu'  => array
        (
        'title'           => 'Static Menu',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )       
        
    );

//***Manage User Group view
$profile['userGroup']=array
    (
    'sysgroup_id'   => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'sysgroup_name' => array
        (
        'title'           => 'User Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
    );


//***Manage menu
$profile['menu']=array
    (
    'menu_id'    => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'menu_name'  => array
        (
        'title'           => 'Menu Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'menu_order' => array
        (
        'title'           => 'Menu Order',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
   'UTILgetYesNo(active)' => array
        (
        'title'           => 'Active?',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'UTILgetYesNo(staticMenu)' => array
        (
        'title'           => 'Static Menu?',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
   );


//***Manage Sub Menu view
$profile['submenu']=array
    (
    'submenu_id'    => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getMenuNameByID(menu_id)'       => array
        (
        'title'           => 'Menu',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'submenu_name'  => array
        (
        'title'           => 'Submenu',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'submenu_order' => array
        (
        'title'           => 'Submenu Order',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'menu_filepath' => array
        (
        'title'           => 'Submenu File Path',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
   'UTILgetYesNo(active)' => array
        (
        'title'           => 'Active?',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'submenu_name_group' => array
        (
        'title'           => 'Group Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
	'extra' => array
        (
        'title'           => 'Help Page',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )    
    );

//***Manage Warehouse
$profile['warehouse']=array
    (
    'wh_id'       => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_name'        => array
        (
        'title'           => 'Warehouse Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getWhName(dist_id)'    => array
        (
        'title'           => 'District',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getProvName(prov_id)'     => array
        (
        'title'           => 'Province',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getSktName(Stkid)' => array
        (
        'title'           => 'Stakeholder',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'wh_type_id'      => array
        (
        'title'           => 'Warehouse Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
    );

//***Manage Moscale  
	$profile['mosscale']=array
    (
    'row_id'       => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '20',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(itmrec_id)'        => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getSktName(stkid)'        => array
        (
        'title'           => 'Stockholder',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'UTILgetLevelName(lvl_id)'        => array
        (
        'title'           => 'Distribution Level',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '1',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),                
    'shortterm'    => array
        (
        'title'           => 'Short Term',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'longterm'     => array
        (
        'title'           => 'Long Term',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'sclstart' => array
        (
        'title'           => 'Scale Start',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'sclsend'      => array
        (
        'title'           => 'Scale End',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
	 'extra' => array
        (
        'title'           => 'Extra',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
	 'getMosColorStr(colorcode)' => array
        (
        'title'           => 'Color Code',
        'titleClass'      => 'string',
        'type'            => 'HTML',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        )
    );
    
//***Manage waiting data      
$profile['waitingData']=array
    (
    'w_id'       => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '40',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getMonthNameByID(report_month)' => array
        (
        'title'           => 'Month',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'report_year'    => array
        (
        'title'           => 'Year',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '35',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(item_id)'     => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '10',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getWhName(getWhDistID(wh_id))' => array
        (
        'title'           => 'Warehouse',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '10',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
	'wh_obl_a'    => array
        (
        'title'           => 'Opening<br />Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_obl_c'    => array
        (
        'title'           => 'Closing<br />Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_received'    => array
        (
        'title'           => 'Recived',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_issue_up'    => array
        (
        'title'           => 'Issues',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_cbl_c'    => array
        (
        'title'           => 'Calc<br />Closing Bal.',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'mos'    => array
        (
        'title'           => 'MOS',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_cbl_a'    => array
        (
        'title'           => 'Closing<br />Bal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_adja'    => array
        (
        'title'           => 'Adj(+)',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'wh_adjb'    => array
        (
        'title'           => 'Adj(-)',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_obl_a'    => array
        (
        'title'           => 'Field<br />Opn. Bal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_obl_c'    => array
        (
        'title'           => 'Field<br />Closing Bal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_recieved'    => array
        (
        'title'           => 'Field<br />Recived',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'amc'    => array
        (
        'title'           => 'AMC',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_issue_up'    => array
        (
        'title'           => 'Field<br />Issue',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_cbl_c'    => array
        (
        'title'           => 'Field<br /> Closing<br />Bal Cal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_cbl_a'    => array
        (
        'title'           => 'Field<br />Closing Bal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_mos'    => array
        (
        'title'           => 'Closing<br />Bal',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_adja'    => array
        (
        'title'           => 'Field Adj(+)',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
	'fld_adjb'    => array
        (
        'title'           => 'Field Adj(-)',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        )
	
    ); 
//Manage Donors
$profile['donor']=array
    (
    'dnrec_id'       => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'donor_name'     => array
        (
        'title'           => 'Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'dn_country'     => array
        (
        'title'           => 'Country',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'dn_address'     => array
        (
        'title'           => 'Address',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'contact_person' => array
        (
        'title'           => 'Contact Person',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'contemail'      => array
        (
        'title'           => 'Contact Email',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
    );
	
	$profile['price']=array
    (
    'pricerec_id'       => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getSktName(stkid)'     => array
        (
        'title'           => 'Stakeholder',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(itmrec_id)'     => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'effective_date'     => array
        (
        'title'           => 'Effective Date',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'unit_price' => array
        (
        'title'           => 'Unit Price',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
    );

//***Manage Products
$profile['item']=array
    (
    'itmrec_id'   => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'itm_name'    => array
        (
        'title'           => 'Product Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'itm_type'    => array
        (
        'title'           => 'Product Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'field_color' => array
        (
        'title'           => 'Field Color',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'itm_status'  => array
        (
        'title'           => 'Product Status',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'itm_des'     => array
        (
        'title'           => 'Product Description',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'frmindex'     => array
        (
        'title'           => 'Product Index',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '15',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'extra*1'     => array
        (
        'title'           => 'Product Index',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        )        
        
        
    );

//  
$profile['districts']=array
    (
    'whrec_id'                     => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'wh_name'                 => array
        (
        'title'           => 'Name',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_address'                => array
        (
        'title'           => 'Address',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'contact_person' => array
        (
        'title'           => 'Contact Person',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'contemail'                      => array
        (
        'title'           => 'Contact Email',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getProvName(Province)'                      => array
        (
        'title'           => 'Province',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    
   
    );
//View Warehouse report
$profile['whsemonthly']=array
    (
    'w_id'                     => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getMonthNameByID(report_month)'                 => array
        (
        'title'           => 'Month',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'report_year'                => array
        (
        'title'           => 'Year',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(item_id)' => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getWhName(getWhDistID(wh_id))'                      => array
        (
        'title'           => 'Warehouse',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_obl_a'                      => array
        (
        'title'           => 'Open Bal.<br />Calculated',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_received'                   => array
        (
        'title'           => 'Received',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_issue_up'                   => array
        (
        'title'           => 'Issued',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wh_cbl_a'                     => array
        (
        'title'           => 'Closing<br />Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        )   
    );
$profile['sdpmonthly']=array
    (
    'w_id'                     => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getMonthNameByID(report_month)'                 => array
        (
        'title'           => 'Month',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'report_year'                => array
        (
        'title'           => 'Year',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(item_id)' => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getWhName(getWhDistID(wh_id))'                      => array
        (
        'title'           => 'Warehouse',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_obl_a'                      => array
        (
        'title'           => 'Open Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_recieved'                   => array
        (
        'title'           => 'Received',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_issue_up'                   => array
        (
        'title'           => 'Issued',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_cbl_a'                     => array
        (
        'title'           => 'Closing Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        )
    );
	
	$profile['sdpmonthlyvalue']=array
    (
    'w_id'                     => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'getMonthNameByID(report_month)'                 => array
        (
        'title'           => 'Month',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'report_year'                => array
        (
        'title'           => 'Year',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getItemNameByRecID(item_id)' => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'getWhName(getWhDistID(wh_id))'                      => array
        (
        'title'           => 'Warehouse',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '75',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'fld_obl_a'                      => array
        (
        'title'           => 'Open Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_recieved'                   => array
        (
        'title'           => 'Received',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_issue_up'                   => array
        (
        'title'           => 'Issued',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_cbl_a'                     => array
        (
        'title'           => 'Closing Balance',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),   
    'fld_mos'                   => array
        (
        'title'           => 'Months of Stock',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_adja'                     => array
        (
        'title'           => '(+) Adjustment',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'fld_adjb'                      => array
        (
        'title'           => '(-) Adjustment',
        'titleClass'      => 'string',
        'type'            => 'Number',
        'width'           => '75',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        )
    );
   
//Manage User Group view
$profile['wms']=array
    (
    'Id'   => array
        (
        'title'           => 'Action',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '50',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
     'wms_stock.transactiondate' => array
        (
        'title'           => 'Transaction Date',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'WMSgetStakeholderName(wms_stock.stakeholderid)' => array
        (
        'title'           => 'Stakeholder',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'WMSgetItemName(wms_stock.itemcode)' => array
        (
        'title'           => 'Product',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
 
     'WMSgetSupplierName(wms_stock.supplierid)' => array
        (
        'title'           => 'Supplier',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),

     'WMSgetSourceDocName(wms_stock.sourcedoc)' => array
        (
        'title'           => 'Transaction Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),        
    'wms_stock.lotno' => array
        (
        'title'           => 'Lot Number',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '15',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),

 
    'wms_stock.mfgdate' => array
        (
        'title'           => 'Manufactured Date',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wms_stock.expdate' => array
        (
        'title'           => 'Expiry Date',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
     'wms_stock.qtyin' => array
        (
        'title'           => 'Quantity In',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
    'wms_stock.qtyinun' => array
        (
        'title'           => 'Quantity In Unusable',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
   'wms_stock.qtyout' => array
        (
        'title'           => 'Quantity Out',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        ),
 
    'wms_stock.qtyoutun' => array
        (
        'title'           => 'Quantity Out Unsable',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'right',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '1'
        )
        
    );

//Manage User Group view
$profile['cms']=array
    (
    'tbl_cms.id'   => array
        (
        'title'           => 'ID',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '30',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'tbl_cms.title' => array
        (
        'title'           => 'Title',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'tbl_cms.stkid' => array
        (
        'title'           => 'Content Type',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )
        
    );

 
//Manage User Group view
$profile['reports']=array
    (
    'report_id'   => array
        (
        'title'           => 'ID',
        'titleClass'      => '',
        'type'            => 'HTML',
        'width'           => '30',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '0',
        'useSort'         => '0'
        ),
    'report_type' => array
        (
        'title'           => 'Type',
        'titleClass'      => 'string',
        'type'            => 'String',
        'width'           => '35',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'report_title' => array
        (
        'title'           => 'Title',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),

   'report_xaxis' => array
        (
        'title'           => 'X-axis Label',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'report_yaxix' => array
        (
        'title'           => 'Y-axis Label',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
    'report_units' => array
        (
        'title'           => 'Units',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'report_factor' => array
        (
        'title'           => 'Factor',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'report_field' => array
        (
        'title'           => 'Field',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'report_description' => array
        (
        'title'           => 'Description',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'center',
        'compareFunction' => 'compare',
        'isVisible'       => '0',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        ),
   'staticpage' => array
        (
        'title'           => 'Static Page',
        'titleClass'      => 'string',
        'type'            => 'string',
        'width'           => '25',
        'alignment'       => 'left',
        'compareFunction' => 'compare',
        'isVisible'       => '1',
        'useAutoIndex'    => '0',
        'useAutoFilter'   => '1',
        'useSort'         => '1'
        )        
    );
 
    
    // sq array contain query configuration information like from table,
// where clause and order by columns 

$sql=array
    (
    'user'        => array
        (
        'from'        => 'sysuser_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'sysusr_name',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
    'userGroup'   => array
        (
        'from'        => 'sysgroup_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => '',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
    'menu'        => array
        (
        'from'        => 'sysmenu_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'menu_id',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
    'submenu'     => array
        (
        'from'        => 'sysmenusub_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'menu_id,submenu_order',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
    'warehouse'   => array
        (
        'from'        => 'tbl_warehouse',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wh_name',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
		
	'mosscale'   => array
        (
        'from'        => 'mosscale_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'row_id',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
	 'waitingData'   => array
        (
        'from'        => 'tbl_waiting_data',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wh_id',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
	'districts'   => array
        (
        'from'        => 'tbl_districts',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wh_name',
        'allowEdit'   => '1',
        'allowDelete' => '1'
        ),
    'donor'       => array
        (
        'from'        => 'donorinfo_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'donor_name',
        'allowEdit'   => '1',
        'allowDelete' => '0'
        ),
	'price'       => array
        (
        'from'        => 'tbl_itemprice',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'pricerec_id',
        'allowEdit'   => '0',
        'allowDelete' => '0'
        ),	
    'item'        => array
        (
        'from'        => 'itminfo_tab',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'itm_status,frmindex',
        'allowEdit'   => '1',
        'allowDelete' => '0'
        ),
    'whsemonthly' => array
        (
        'from'        => 'tbl_wh_data',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wh_id ASC',
        'allowEdit'   => '0',
        'allowDelete' => '0',
        'allowfilter' => '1',
        'filterid'  => 'whsemonthly'
        ),
    'sdpmonthly'  => array
        (
        'from'        => 'tbl_wh_data',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wh_id ASC',
        'allowEdit'   => '0',
        'allowDelete' => '0',
        'allowfilter' => '1',
        'filterid'  => 'sdpmonthly'
        ),
    'wms'  => array
        (
        'from'        => 'wms_stock',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'wms_stock.transactiondate ASC',
        'allowEdit'   => '0',
        'allowDelete' => '0',
        'allowfilter' => '1',
        'filterid'  => 'wms'
        ),
     'cms'  => array
        (
        'from'        => 'tbl_cms',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'tbl_cms.id ASC',
        'allowEdit'   => '1',
        'allowDelete' => '0',
        'EscapeHTML'  => '1',
        'EditURL'     => 'plmis_static/lhw/content-edit.phpcid=18starting=0'    
        ),
     'reports'  => array
        (
        'from'        => 'reports',
        'join'        => '',
        'wherec'      => '',
        'orderby'     => 'report_title ASC',
        'allowEdit'   => '1',
        'allowDelete' => '0'   
        )
        
    );


// breadcrumbs array contains path to form and toggle command that stwiches view to view to add and vice versa.  
$breadcrumbs=array
    (
    'default'     => array
        (
        'title'
                   =>
            'LMIS',
        'subtitle' => '',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', '', '', '','add');"
        ),
   'url'     => array
        (
        'title'
                   =>
            'LMIS',
        'subtitle' => '',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', '', '', '','add');"
        ),
        
    'user'        => array
        (
        'title'    => 'Manage Users',
        'subtitle' => 'Add User',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', 'Add User', 'Edit User', 'View Users','add');"
        ),
    'userGroup'   => array
        (
        'title'    => 'Manage User Group',
        'subtitle' => 'Add User Group',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add User Group', 'Edit User Group', 'View User Group','add');"
        ),
    'menu'        => array
        (
        'title'    => 'Manage Menu',
        'subtitle' => 'Add Menu',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', 'Add Menu', 'Edit Menu', 'View Menu','add');"
        ),
    'submenu'     => array
        (
        'title'    => 'Manage Sub Menu',
        'subtitle' => 'Add Sub Menu',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Sub Menu', 'Edit Sub Menu', 'View Sub Menu','add');"
        ),
    'warehouse'   => array
        (
        'title'    => 'Manage Warehouse',
        'subtitle' => 'Add Warehouse',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Warehouse', 'Edit Warehouse', 'View Warehouse','add');"
        ),
	'mosscale'   => array
        (
        'title'    => 'Manage MOS Scale',
        'subtitle' => 'Add MOS Scale',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Mosscale', 'Edit Mosscale', 'View Mosscale','add');"
        ),
	'waitingData'   => array
        (
        'title'    => 'Approve Requests',
        'subtitle' => '',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Approve Request', 'Edit Approve Request', 'View Approve Request','add');"
        ),
	 'districts'   => array
        (
        'title'    => 'Manage Districts',
        'subtitle' => 'Add District',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add District', 'Edit District', 'View District','add');"
        ),
    'donor'       => array
        (
        'title'    => 'Manage Donor',
        'subtitle' => 'Add Donor',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', 'Add Donor', 'Edit Donor', 'View Donor','add');"
        ),
	'price'       => array
        (
        'title'    => 'Manage Price',
        'subtitle' => 'Add Price',
        'url'      => "javascript:toggleShowAdd('content','addedit','Action', 'Add Price', 'Edit Price', 'View Price','add');"
        ),
     'item'        => array
        (
        'title'    => 'Manage Product',
        'subtitle' => 'Add Product',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Product', 'Edit Product', 'View Product','add');"          
        ),
     'whsemonthly' => array
        (
        'title'    => 'Warehouse Report',
        'subtitle' => '',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', '', '', '','add');"
        ),
    'sdpmonthly'  => array
        (
        'title'    => 'Field Report',
        'subtitle' => '',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', '', '', '','add');"
        ),
    'wms'  => array
        (
        'title'    => 'WMS Report',
        'subtitle' => '',
        'url'      =>''            
        ),
    'cms'  => array
        (
        'title'    => 'View Content',
        'subtitle' => 'Add Content',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Content', 'Edit Content', 'View Content','add');"
            
        ),
    'reports'  => array
        (
        'title'    => 'View Reports',
        'subtitle' => 'Add Report',
        'url'      =>
            "javascript:toggleShowAdd('content','addedit','Action', 'Add Report', 'Edit Report', 'View Report','add');"
            
        )
        
    );

$modules=array
    (
    'default'     => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'Default.php',
        'actionform' => 'frmData'
        ),
   'url'     => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'Default.php',
        'actionform' => 'frmData'
        ),        
    'user'        => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditUser.php',
        'actionform' => 'frmData'
        ),
    'userGroup'   => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditUserGroup.php',
        'actionform' => 'frmData'
        ),
    'menu'        => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditMenu.php',
        'actionform' => 'frmData'
        ),
    'submenu'     => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditSubmenu.php',
        'actionform' => 'frmData'
        ),
    'warehouse'   => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditWarehouse.php',
        'actionform' => 'frmData'
        ),
	'mosscale'   => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditMosScale.php',
        'actionform' => 'frmData'
        ),
	'waitingData'   => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditWaitingData.php',
        'actionform' => 'frmData'
        ),
	 'districts'   => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditDistrict.php',
        'actionform' => 'frmData'
        ),
    'donor'       => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditDonor.php',
        'actionform' => 'frmData'
        ),
	 'price'       => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditPrice.php',
        'actionform' => 'frmData'
        ),
     'item'        => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditItem.php',
        'actionform' => 'frmData'
        ),
    'whsemonthly' => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditF7.php',
        'actionform' => 'frmData'
        ),
    'sdpmonthly'  => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditF7.php',
        'actionform' => 'frmData'
        ),
    'wms'  => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'Default.php',
        'actionform' => 'frmData'
        ),
    'cms'  => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditContent.php',
        'actionform' => 'frmData'
        ),
    'reports'  => array
        (
        'viewpath'   => 'operations',
        'editpath'   => 'operations',
        'filename'   => 'AddEditReports.php',
        'actionform' => 'frmData'
        )
        
    );
?>
