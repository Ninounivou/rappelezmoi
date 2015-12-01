<?php
if (!defined('_PS_VERSION_'))
	exit;
	
class rappelezmoi extends Module
{

	public function __construct()
	{
		$this->name = 'rappelezmoi';		
		$this->tab = 'front_office_features';
		$this->version = '2.0';
		$this->author = 'loulou66)';
		parent::__construct();
		$this->displayName = $this->l('Block rappelez-moi!');
		$this->description = $this->l('Install image on all your pages and manage callbacks.');	
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall this module, all data will be lost?');
		global $smarty;
		$errors = false;
		$confirm = false;
	}
	
	public function instDB()
	{
		Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rappelezmoi` (
				`id_call` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`phone` varchar(50) NOT NULL ,
				`hour` varchar(50) NOT NULL ,
				`name` varchar(50) NOT NULL ,
				`date` timestamp NOT NULL,
				`reason` varchar(500) NOT NULL,
				`status` tinyint NOT NULL)
				');
		return true;
	}
	
	public function install()
	{
		if(!parent::install() OR 
		  !$this->registerHook('header') OR 
		  !$this->registerHook('top') OR
		  !$this->registerHook('leftColumn') OR
		  !$this->registerHook('rightColumn') OR
		  !$this->instDB())
		  return false;
		  
		if(!Configuration::updateValue($this->name.'_left',true) OR 
			!Configuration::updateValue($this->name.'_header',true) OR
			!Configuration::updateValue($this->name.'_column',true))
			
		  return false;	 		
	 	return true;
	}
	
	public function uninstall()
	{	
			Configuration::deleteByName($this->name.'_left'); 
			Configuration::deleteByName($this->name.'_header');
			Configuration::deleteByName($this->name.'_column');
			
		if (!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.$this->name))
		 
		 return false;
		 
		return parent::uninstall();
	}
	
	public function getContent()
	{
		$this->_html = '';
		$this->_postProcess();
		$this->_html .= '<h2>'.$this->displayName.'</h2>';
		$this->_displayForm();	
		return $this->_html;
	}
	
	public function _postProcess()
	{
   /* Save settings */     	
		if (isset($_POST['submitSettings']))
		{
  			Configuration::updateValue($this->name.'_left' ,(Tools::getValue('left') ? 'true' : 'false'));
  			Configuration::updateValue($this->name.'_header'  , (Tools::getValue('header') ? 'true' : 'false'));
  			Configuration::updateValue($this->name.'_column',	 (Tools::getValue('column') ? 'true' : 'false'));
			
			 $this->_html .= $this->displayConfirmation($this->l('Settings updated'));                
		}
	}
	
	private function _displayForm()
	{
	$this->_html .= '
				<style type="text/css">
				
				.button-call
				{
				-moz-border-radius:11px 11px 11px 11px;
				-webkit-border-radius:11px 11px 11px 11px;
				border-radius:11px 11px 11px 11px;
				-moz-box-sizing:content-box;
				background:url("'.__PS_BASE_URI__.'modules/'.$this->name.'/graphics/white-grad.png") repeat-x scroll left top #eee;
				border:1px solid;
				color:#464646;
				cursor:pointer;
				font-size:12px !important;
				padding:4px 15px;
				text-shadow:0 1px 0 #fff;	
				text-decoration:none;
				vertical-align:middle;
				margin-left:10px;
				display: inline-block;
				width:75px;
				text-align:center;
				}		

				
		</style>
		
		<script type="text/javascript">

		function updateCall(action,id,version) 
			{
				$.ajax({
				   type: "POST",
				   url: "'.__PS_BASE_URI__.'modules/'.$this->name.'/update.php",
				   data: action + "=1&id=" + id + "&versionps=" + version,
				  success: function(result)
				  {
						if (result == "1")
						{
						$("#call-tr-" + id).fadeOut();	
						}
						else
						{
						$("#call-" + id).empty();
						$("#call-" + id).append(result);					
						}
				   }
				 });
			}
		</script>
		
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
                <fieldset><legend><img src="' . __PS_BASE_URI__ . 'img/admin/cog.gif" alt="" title="" /> '.$this->l('Settings').'</legend>
				<table cellspacing="0" cellpadding="0" class="table" style="width:100%" >
				<tr>
					<th colspan="2" align="center">'.$this->l('Image position').'</th>
					<th colspan="3" align="center">'.$this->l('display block').'</th>
				</tr>	
				<tr>
					<td>'.$this->l('header left image').'</td>
					<td>'.$this->l('header right image').'</td>
					<td>'.$this->l('display in Header').'</td>
                    <td>
						<input type="radio" name="header" id="header_on" value="1" '.(Configuration::get($this->name.'_header') == "true" ? 'checked="checked" ' : '').'/>
						<label class="t" for="header_on"> <img src="'._PS_ADMIN_IMG_.'enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
                    </td>
					<td>
						<input type="radio" name="header" id="header_off" value="0" '.(Configuration::get($this->name.'_header') == "false" ? 'checked="checked" ' : '').'/>
						<label class="t" for="header_off"> <img src="'._PS_ADMIN_IMG_.'disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
                    </td>	
				</tr>          					
                <tr>
					 <td>
						<input type="radio" name="left" id="left_on" value="1" '.(Configuration::get($this->name.'_left') == "true" ? 'checked="checked" ' : '').' style="margin-left:70px"/>
                    </td>
					<td>
						<input type="radio" name="left" id="left_off" value="0" '.(Configuration::get($this->name.'_left') == "false" ? 'checked="checked" ' : '').' style="margin-left:60px"/>
                     </td>
					<td>'.$this->l('display in column ').'</td>
					<td>
						<input type="radio" name="column" id="column_on" value="1" '.(Configuration::get($this->name.'_column') == "true" ? 'checked="checked" ' : '').'/>
                        <label class="t" for="column_on"> <img src="'._PS_ADMIN_IMG_.'enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					</td>
					<td>
						<input type="radio" name="column" id="column_off" value="0" '.(Configuration::get($this->name.'_column') == "false" ? 'checked="checked" ' : '').'/>
                        <label class="t" for="column_off"> <img src="'._PS_ADMIN_IMG_.'disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label> 
					</td>
				</tr>
				<tr>
					<td colspan="5"><div class="space"></div>
                      <div class="center"><input type="submit" class="button" name="submitSettings" value="'.$this->l('Update settings').'" /></div></td>
				</tr>
			</table>
		</fieldset>
		
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" name="comment_phone">
			<fieldset>
			<legend><img src="' . __PS_BASE_URI__ . 'img/admin/tab-customers.gif" alt="" title="" />'.$this->l('Management application call').'</legend>
			<label style="padding-top: 0;"> '.$this->l('Manage your callbacks').'</label>
				<table cellspacing="0" cellpadding="0" class="table" style="width:100%" >
					<tr>
						<th style="width:90px;text-align:center">'.$this->l('Name of person').'</th>
						<th style="width:110px;text-align:center">'.$this->l('Number').'</th>
						<th style="width:95px;text-align:center">'.$this->l('Date').'</th>
						<th style="width:85px;text-align:center">'.$this->l('callbacks time').'</th>
						<th style="width:380px;text-align:center">'.$this->l('Reason for the request').'</th>
						<th style="width:115px;text-align:center">'.$this->l('Status').'</th>
						<th style="width:115px;text-align:center">'.$this->l('Action').'</th>
					</tr>';
						$results = Db::getInstance()->ExecuteS('
						SELECT c.*, DATE_FORMAT(c.date,"%d / %m / %Y") as date
						FROM '._DB_PREFIX_.'rappelezmoi c ORDER BY date DESC');
		
						foreach ($results as $call)
						{
						if(_PS_VERSION_ < "1.5.*.*")
						{
						$version = 14;
						}	
						else
						{
						$version =15;
						}
						$tocall = $this->l('to call');
						$alreadycall = $this->l('alredy call');
						$call['status'] == 0 ? $status = '<a onclick="updateCall(\'statuscall0\','.$call['id_call'].','.$version.');" class="button-call"><font color="#44AA33">'.$tocall.'</font></a>'
											: $status = '<a onclick="updateCall(\'statuscall1\','.$call['id_call'].','.$version.');" class="button-call"><font color="##BB4433">'.$alreadycall.'</font></a>';
		
		$this->_html .= '<tr id="call-tr-'.$call['id_call'].'">
							<td style="font-size: 12px">'.$call['name'].'</td>
							<td style="font-size: 12px">'.$call['phone'].'</td>
							<td style="font-size: 12px">'.$call['date'].'</td>
							<td style="font-size: 12px">'.$call['hour'].'</td>
							<td style="font-size: 12px">'.$call['reason'].'</td>
							<td style="font-size: 12px" id="call-'.$call['id_call'].'">'.$status.'</td>
							<td >
							<a onclick="updateCall(\'delete\','.$call['id_call'].','.$version.');" class="button-call"><font color="#BB4433">'.$this->l('Delete').'</font></a>
							</td>
						</tr>';
		}
		$this->_html.= '
				</table>
			</fieldset>
		</form>';
	}
		
	private function newsPhone()
	{
		$exist = Db::getInstance()->ExecuteS('
		SELECT COUNT(*) as count
		FROM '._DB_PREFIX_.'rappelezmoi 
		WHERE (phone = "'.$_POST['phone'].'" 
		AND status = 0)');
		if ($exist[0]['count'] == 0)
		{
			/* phone not registred then  */
			 
			$name = $_POST['name'];
			if (strlen($_POST['name']) == 0 ){$name = $this->l('unspecified;');}
		
			$hour = 'Entre '.$_POST['hour_start'].' et '.$_POST['hour_end'];
			if (strlen($_POST['hour_start']) == 0 && strlen($_POST['hour_end']) == 0) {
			$hour = $this->l('unspecified;');}
			$reason =$_POST['reason'];
			if (strlen($_POST['reason']) == 0 ){$reason =$this->l('unspecified;');}
		
			Db::getInstance()->autoExecute(
			_DB_PREFIX_.'rappelezmoi',
			array(
			'status' => 0,
			'name' => $_POST['name'], 
			'phone' => $_POST['phone'],
			'hour' => $hour,
			'reason' => $_POST['reason']
			),
			'INSERT');
			
		}
			
	}
	
	public function hookHeader($params)
	{
		global $smarty ,$cookie;
		$smarty->assign('confirmimg','true');
		if (version_compare(_PS_VERSION_,"1.4.0.0",">=") && version_compare(_PS_VERSION_,"1.5","<"))
			{
			Tools::addCSS(($this->_path).'css/rappelezmoi_14.css', 'all');
			Tools::addCSS(($this->_path).'css/calendrical.css', 'all');
			Tools::addJS(($this->_path).'js/mask.js');
			Tools::addJS(($this->_path).'js/calendrical.js');
			}	
			else
			{
			$this->context->controller->addCss($this->_path.'css/rappelezmoi_15.css', 'all');
			$this->context->controller->addCSs(($this->_path).'css/calendrical.css', 'all');
			$this->context->controller->addJs($this->_path.'js/mask.js');
			$this->context->controller->addJs($this->_path.'js/rappelezmoi.js');
			$this->context->controller->addJs(($this->_path).'js/calendrical.js');
			}
	}
	
	private function rappelezmoitop($params)	
	{
		global $smarty ,$cookie;
	
		$smarty->assign(array(
         'left'         => Configuration::get($this->name.'_left'),
         'header' 	    => Configuration::get($this->name.'_header'),
    	 'column'       => Configuration::get($this->name.'_column')
		));
		
		if (Tools::isSubmit('submitrequesttop'))
		{
			// vars for email 
			$this->newsPhone();
			$shopname = Configuration::get('PS_SHOP_NAME');
			$shopmail = Configuration::get('PS_SHOP_EMAIL');
			$phone = $_POST['phone'];
			$template = 'rappelezmoi';
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
			$sujet = $this->l('test');
			$namedest = NULL;
			$fileatt = NULL;
			$methodSMTP = NULL;
			$name = $_POST['name'];
			if (strlen($_POST['name']) == 0 ){$name = $this->l('unspecified;');}
			$hour = 'Entre '.$_POST['hour_start'].' et '.$_POST['hour_end'];
			if (strlen($_POST['hour_start']) == 0 && strlen($_POST['hour_end']) == 0) {
			$hour = $this->l('unspecified;');}
			$reason =$_POST['reason'];
			if (strlen($_POST['reason']) == 0 ){$reason =$this->l('unspecified;');}
			$iso = Language::getIsoById((int)($id_lang));
			
			if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.html') AND file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.txt')){
				if(!Mail::Send(
			 		$id_lang,
			 		$template,
					Mail::l($sujet, (int)$cookie->id_lang),
					array(
						'{name}' => $name,
						'{phone}' => $phone,
						'{reason}' => $reason,
						'{hour}' => $hour
					),
					$shopmail,
			 		NULL,NULL,NULL,NULL,NULL,
					dirname(__FILE__).'/mails/'
			 	)){
				 	$smarty->assign('confirmimg',false);
				 	$smarty->assign('callback_confirmimg','false');
				 	return false;
				} else {
				 	$smarty->assign('confirmimg',true);
				 	$smarty->assign('callback_confirmimg','true');
				 	return true;
				}
			}
			
		}
		
	}
	
	public function hookTop($params)
	{
		global $smarty ,$cookie;
		$this->rappelezmoitop($params);
		return $this->display(__FILE__,'rappelezmoi-header.tpl');
	}
	
	private function rappelezmoicol($params)	
	{
		global $smarty ,$cookie;
	
		$smarty->assign(array(
         'left'         => Configuration::get($this->name.'_left'),
         'header' 	    => Configuration::get($this->name.'_header'),
    	 'column'       => Configuration::get($this->name.'_column')
		));
		
		if (Tools::isSubmit('submitrequestcol'))
		{
			// vars for email 
			$this->newsPhone();
			$shopname = Configuration::get('PS_SHOP_NAME');
			$shopmail = Configuration::get('PS_SHOP_EMAIL');
			$phone = $_POST['phone'];
			$template = 'rappelezmoi';
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
			$sujet = $this->l('A person wants to join - Your Store');
			$namedest = NULL;
			$fileatt = NULL;
			$methodSMTP = NULL;
			$name = $_POST['name'];
			if (strlen($_POST['name']) == 0 ){$name = $this->l('unspecified;');}
			$hour = 'Entre '.$_POST['hour_start'].' et '.$_POST['hour_end'];
			if (strlen($_POST['hour_start']) == 0 && strlen($_POST['hour_end']) == 0) {
			$hour = $this->l('unspecified;');}
			$reason =$_POST['reason'];
			if (strlen($_POST['reason']) == 0 ){$reason =$this->l('unspecified;');}
			$iso = Language::getIsoById((int)($id_lang));
			
			if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.html') AND file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.txt'))
			
			 if(!Mail::Send(
			 $id_lang,
			 $template,
			 Mail::l($sujet, (int)$cookie->id_lang),
			 array(
			 '{name}' => $name,
			 '{phone}' => $phone,
			 '{reason}' => $reason,
			 '{hour}' => $hour),
			 $shopmail,
			 NULL,
			 NULL,
			 NULL,
			 NULL,
			 NULL,
			 dirname(__FILE__).'/mails/'
			 ))
			 return false;
			 
			 $smarty->assign('confirmimg','false');
			
			
		}
		
	}
	
	function hookLeftColumn($params)
	{
		$this->rappelezmoicol($params);
		return $this->display(__FILE__,'rappelezmoi-col.tpl');
	}
	
	function hookRightColumn($params)
	{
		return $this->hookleftColumn($params);
	}

}
