<?php
if (isset($_POST['versionps'])){
	$version = $_POST['versionps'];
	if ($version>14){
// 		include dirname(__FILE__).'/../../classes/db/Db.php';
		include dirname(__FILE__).'/../../config/config.inc.php';
	}else{
		include dirname(__FILE__).'/../../classes/Db.php';
		include dirname(__FILE__).'/../../config/config.inc.php';
	}
}
	
if (isset($_POST['delete'])){
	$id_call = $_POST['id'];
	Db::getInstance()->delete(_DB_PREFIX_.'rappelezmoi', "id_call = '{$id_call}'"); 
	echo '1';
}
  
   if (isset($_POST['statuscall0']))
		{
		$id_call = $_POST['id'];
    Db::getInstance()->autoExecute( _DB_PREFIX_.'rappelezmoi',  array('status'=>'1'),
      'UPDATE', "id_call = '{$id_call}'");
		 echo '<a onclick="updateCall(\'statuscall1\','.$id_call.','.$version.');" class="button-call"><font color="##BB4433">d&eacute;j&agrave; appeler</font></a>';
		
		}
		
		 if (isset($_POST['statuscall1']))
		{
	$id_call = $_POST['id'];
    Db::getInstance()->autoExecute( _DB_PREFIX_.'rappelezmoi',  array('status'=>'0'),
      'UPDATE', "id_call = '{$id_call}'"); 
	 echo '<a onclick="updateCall(\'statuscall0\','.$id_call.','.$version.');" class="button-call"><font color="#44AA33">A appeler</font></a>';
		}
 ?>