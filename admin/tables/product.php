<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/


// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Product Table class
 */
class jereverseauctionTableproduct extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__jereverseauction_products', 'id', $db);
	}
	/**
	 * Overloaded bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}
		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded load function
	 *
	 * @param       int $pk primary key
	 * @param       boolean $reset reset data
	 * @return      boolean
	 * @see JTable:load
	 */
	public function load($pk = null, $reset = true)
	{
		if (parent::load($pk, $reset))
		{
			if(@$this->params == '')
				$this->params = '';
			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadJSON($this->params);
			$this->params = $params;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function store($updateNulls = false)
	{
		// Joomla predefined functions.
		$user					= & JFactory::getUser();
		$post					= & JRequest::get('post');
		$start					= @$post['jform']['start_time'];
		$now_date  				= date("Y-m-d H:i:s",time());
		$start_new				= JFactory::getDate($start);
		$start_date				= trim($start_new->toFormat('%Y-%m-%d %H:%M:%S'));

		$diff = abs(strtotime($start_date) - strtotime($now_date));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		//image upload
		$multiple_file						= JRequest::getVar( 'prod_detail_image', '', 'files', 'array');

		if (!empty($multiple_file['name'][0])) {
			$image_files 					= $this->uploadmultiplefile($multiple_file);
			$this->prod_detail_image		= $image_files;
		}

		$avatar_file						= JRequest::getVar( 'bids_image', '', 'files', 'array');
		$ava_image							= $avatar_file['name'];


		if (!empty($ava_image)) {
			$avatar_image					= $this->uploadimage( $avatar_file );

			if(!empty($avatar_image)) {
				$this->prod_image			= $avatar_image;
			}
		}


		if($years > 0 || $months > 0 || $days > 0 ){
			$this->status     = 2;
		}else{
			$this->status     = 0;
		}


		$userGroups = $user->get('groups');
		foreach($userGroups as $groupid) {
		$gid = $groupid;
		}

		if(!$this->id){
			$this->user_id	 = $user->id;
		}
		$row_value = parent::store($updateNulls);

		if($row_value){
			if($gid == 8 || $gid == 7){

			}else{
				$this->sendnewproductmail($user->id);
			}
		}
		return $row_value;
	}

	public function sendnewproductmail($userid)
	{
		$mainframe = & Jfactory::getApplication();
		$email      = $mainframe->getCfg( 'mailfrom' );
		$site       = $mainframe->getCfg( 'fromname' );
		$user		= & JFactory::getUser();

		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_mailtemplates');
		$query->where('id =2');
		$db->setQuery($query);
		$mail_template			= $db->loadObject();

		$product		= $this->getdata($userid);

		$path			= JURI::Base();
		$link			= $path.'index.php?option=com_jereverseauction&view=products&layout=default_detail&id='.$product->id;
		$find           = array ("[productname]","[name]","[sitename]","[productid]","[price]","[link]","[customerid]","[email]");
		$replace        = array ("$product->prod_name","$user->name","$site","$product->id","$product->prod_price","$link","$user->id","$user->email");

		$body           = str_replace($find,$replace,$mail_template->mailbody);
		$subject        = str_replace($find,$replace,$mail_template->subject);

		$mail =& JFactory::getMailer();
		$mail->setSender($email);
		$mail->addRecipient($user->email);
		$mail->addCC( $email );
		$mail->setSubject($subject);
		$mail->setBody( $body );
		$mail->IsHTML('1');
		$mail->Send();
	}

	public function getdata($prod_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_products');
		$query->where('user_id ='.$prod_id);
		$query->order('id DESC');
		$db->setQuery($query);
		$faq			= $db->loadObject();

		return $faq;
	}

	public function uploadmultiplefile($cert_file)
	{
		$count = count($cert_file['name']);
		$documents = array();
		for($i=0;$i<$count;$i++) {
			if($cert_file['error'][$i] == 0)
			{
				if($cert_file['size'][$i] <= 8388608)   //8388608 byte = 8 mb
				{
					$upload_directory   =  JPATH_SITE.DS.'components'.DS.'com_jereverseauction'.DS.'assets'.DS.'images'.DS.'products';
					$format             =  strtolower(JFile::getExt($cert_file['name'][$i]));
					$file_name	        =  time().$i.".".$format;
					$cert_filepath_new  =  $upload_directory . DS . $file_name;
					
					//product image
					$cert_filepath_product	 = $upload_directory . DS ."product_".$file_name;

					// Thumbnail Image
					$cert_filepath_thumbnail = $upload_directory . DS ."thumbnail_".$file_name;
					
					if($format != "jpeg" && $format != "jpg" && $format != "png" && $format != "gif")
					{
						$msg = JText::_('Please Upload image file');
						JError::raiseWarning(100, $msg );
						return false;
					}
					else
					{
						if (!JFile::upload($cert_file['tmp_name'][$i],$cert_filepath_new))
						{
							JError::raiseWarning( 500, JText::_('JE_NOTUPLOADED'));
							return false;
						}else{

						$file = $cert_filepath_new;
			           	$save = $cert_filepath_new;

	                    //Display Resize
			           	$modwidth_display  = "352";
			            $modheight_display = "324";

			            //Thumbnail Resize
			           	$modwidth_thumb    = "70";
			            $modheight_thumb   = "70";

						 // Resize images Display..
						 $this->resize($cert_filepath_new,$cert_filepath_product,$cert_file['type'][$i],$modwidth_display,$modheight_display);

						 // Resize images for Thumbnail..
						 $this->resize($cert_filepath_new,$cert_filepath_thumbnail,$cert_file['type'][$i],$modwidth_thumb,$modheight_thumb);

						 $documents[] = $file_name;

						}
					}
				}
				else
				{
					JError::raiseWarning( 500, JText::_('JE_TOO_LARGE'));
					return false;
				}
			}
			else
			{
				JError::raiseWarning( 500, JText::_('JE_UPLOAD_ERROR'));
				return false;
			}
		}
		$documents = implode(",",$documents);
		return $documents;
	}

	public function uploadimage( $cert_file )
	{
		//print_r($cert_file);

		$upload_directory = JPATH_SITE .DS.'components'.DS.'com_jereverseauction'.DS.'assets'.DS.'images'.DS.'products';
		if (isset($cert_file['name'])  && $cert_file['name'] != '') {

			$format  	 = strtolower(JFile::getExt($cert_file['name']));
    		$date  		 =& JFactory::getDate();
			$file_name	 = time().".".$format;
			$cert_filepath_new = $upload_directory . DS . $file_name;
			
			//product image
			$cert_filepath_product	 = $upload_directory . DS ."product_".$file_name;

			// Thumbnail Image
			$cert_filepath_thumbnail = $upload_directory . DS ."thumbnail_".$file_name;

			// Check whether the file in an image format..
			if($format != "jpeg" && $format != "jpg" && $format != "png" && $format != "gif") {
				$msg = JText::_('JE_NOTSAVED');
				JError::raiseWarning(100, $msg );

				return false;
			} else {
				if (!JFile::upload($cert_file['tmp_name'], $cert_filepath_new)) {
					$msg 	= JText::_('JE_NOTUPLOADED');
					JError::raiseWarning(500, $msg);

					return false;
				} else {
					if(!$file_name) {
						return false;
					} else {
						$file = $cert_filepath_new;
			           	$save = $cert_filepath_new;

	                    //Display Resize
			           	$modwidth_display    = "352";
			            $modheight_display   = "324";

			            //Thumbnail Resize
			           	$modwidth_thumb    = "85";
			            $modheight_thumb   = "85";

						 // Resize images Display..
						 $this->resize($cert_filepath_new,$cert_filepath_product,$cert_file['type'],$modwidth_display,$modheight_display);

						 // Resize images for Thumbnali..
						 $this->resize($cert_filepath_new,$cert_filepath_thumbnail,$cert_file['type'],$modwidth_thumb,$modheight_thumb);


						 $photo = $file_name;

						return $photo;
					}
				}
			}
		}
	}
	
	public function resize($sourcefile, $destinationfile, $type, $width, $height )
	{
	    $img = false;

		switch ($type) {
		  case 'image/jpeg':
		  case 'image/jpg':
		  case 'image/pjpeg':
			$img = imagecreatefromjpeg($sourcefile);
			break;
		  case 'image/png':
			$img = imagecreatefrompng($sourcefile);
			break;
		  case 'image/gif':
			$img = imagecreatefromgif($sourcefile);
			break;
		}

		if(!$img) {
		  return false;
		}

		$curr = @getimagesize($sourcefile);
		if($curr[0] < $width ) {
			$height = (100 / ($curr[0] / $width)) * .01;
			$height = @round ($curr[1] * $height);
		}

		$nwimg = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($nwimg, 0, 0, 0);
        ImageColorTransparent($nwimg, $background);
		imagecopyresampled($nwimg, $img, 0, 0, 0, 0, $width, $height, $curr[0], $curr[1]);

		switch ($type)	{
		  case 'image/jpeg':
		  case 'image/jpg':
		  case 'image/pjpeg':
			imagejpeg($nwimg, $destinationfile);
			break;
		  case 'image/png':
			imagepng($nwimg, $destinationfile);
			break;
		  case 'image/gif':
			imagegif($nwimg, $destinationfile);
			break;
		}

		imagedestroy($nwimg);
		imagedestroy($img);

	}

}
