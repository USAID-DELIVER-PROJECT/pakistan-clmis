<?



#configuration directives



class config {

    
	
	
	
	var $absolute_path="c:/apachefriends/xampp/htdocs/PAKLMIS/";#include ending /

	var $site_url="http://fserv89/121form/";#include ending /

	var $root_dir = "/PAKLMIS/";
	
	var $db_name="paklmis";

	var $db_user="lmis_pk";

	var $db_pass="12LmiS34"; 
	 
	var $db_host="192.168.1.254";
	
	/*var $admin_email="admin@q2qsolutionz.com";//email to use as FROM

	var $site_name="www.121solutionz.com";

	var $upload_size_allowed="1048576";//in bytes

	var $image_dir="client_images/";//spaw image director with ending slash
	
	var $file_dir="userfiles/files/";//spaw image director with ending slash
	
	var $newsletter_dir="newsletter_images/";//spaw image director with ending slash
	
	var $thumbnailWidth=100;//width of the thumbnails generated for product images

	var $mainImageWidth=500;
	
	var $mainImageHeight=500;
	
	var $largeThumbnailWidth=348;
	
	var $largeThumbnailHeight=348;
	
	var $mediumThumbnailWidth=178;
	
	var $mediumThumbnailHeight=178;
	
	var $smallThumbnailWidth=68;
	
	var $smallThumbnailHeight=68;
	
	//price settings
	var $price_handler="&pound;";
	
	var $hotel_comission = "20.00";

	//header for email

	var $email_header;


	//footer for email

	var $email_footer;

	//payment options



	var $payment_option;



	var $payment_option_name;



	



	//PAYPAL settings



	var $paypal_test=1;//0 or 1



	var $paypal_currency="GBP";



	var $paypal_success="order_success.php";



	var $paypal_notify="trans/paypal.php";



	var $paypal_cancel="order_cancel.php";



	var $paypal_id="atroniks@hotmail.co.uk";//business



	var $paypal_log_file="log/paypal_ipn_log.txt";//paypal log file



function conn()
	{



		$this->email_header="<link href='".$this->site_url."css/styles.css' rel='stylesheet' type='text/css'><table width='100%' border='0' cellpadding='0' cellspacing='1'>



			<tr>



			<td><img src='".$this->site_url."images/email-header.jpg' border='0'></td>



			</tr>



			<tr>



			<td class='blacktext'><br>";



			



		$this->email_footer="<br></td>



		</tr>



		</table>";



		



		



			//paypal init



			
			$this->paypal_success=$this->site_url.$this->paypal_success;



			$this->paypal_notify=$this->site_url.$this->paypal_notify;



			$this->paypal_cancel=$this->site_url.$this->paypal_cancel;



			$this->paypal_log_file=$this->absolute_path.$this->paypal_log_file;


	}

*/

	



}
?>