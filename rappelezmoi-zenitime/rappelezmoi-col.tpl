<!--Rappelezmoi column-->


{if $column == "true"}
<div id="cart_block" class="block products_block exclusive"> <!--  ID et CLASS a changer suivant votre theme  prenez example sur votre Cart.tpl-->
	<h4 style='margin-top:20px;'>{l s='Get a call' mod='rappelezmoi'}</h4>
	{if $confirmimg == "true"}
	<div class="block_content">
		<div id="rappelezmoi_block_on">
			<a href="#" onclick="showhide('rappelezmoiform');">
			<span>{l s='Call me' mod='rappelezmoi'}</span>
			</a>
		</div>
	</div>
	{else}
	<div class="block_content">
		<div id="rappelezmoi_block_off">
			<a   href="#" onclick="showhide('rappelezmoiform');">
			<span id="rappelezmoi_attente" >{l s='Call tratement' mod='rappelezmoi'}</span>
			</a>
		</div>
	</div>
	{/if}
	
</div>
{/if}

<div id="rappelezmoiform"  style="display: none;">
<div id="masque" name="masque">
<div id="box">
	 <form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" id="textbox" >
         <h3></br>{l s='Please enter your name, phone number, hours of recall and the reason for your request please. Thank you.' mod='rappelezmoi'}</h3>
        
		<table >
		<tr>
            <td><label for="name" class="text-rap">{l s='Name' mod='rappelezmoi'}<span class="req">&nbsp;&nbsp;*</<span></label></td>
            <td><input type="text-rap" id="name-rap" name="name" value="" placeholder="{l s='Your Name' mod='rappelezmoi'}" required="required" class="champ"/></td>
        </tr>
		<tr>
            <td><label for="phone" class="text-rap">{l s='Phone' mod='rappelezmoi'}<span class="req">&nbsp;&nbsp;*</<span></label></td>
            <td><input type="text-rap" id="phone-rap" name="phone" value="" placeholder="00-00-00-00-00" required="required" class="champ"/></td>
		</tr>
		<tr>
           <td><label for="hour" class="text-rap">{l s='callback me between' mod='rappelezmoi'} </span></td>
           <td> <input type="text-rap" id="hour_start" name="hour_start" value="" placeholder="00h00" required="required" class="champ" autocomplete="off" readonly/>
	         <span>{l s='and' mod='rappelezmoi'}</span>
            <input type="text-rap" id="hour_end" name="hour_end" value="" placeholder="00h00" required="required" class="champ" autocomplete="off" readonly/></td>
		</tr>
		 <tr>
			<td><label for="reason" class="text-rap">{l s='Reason for your request' mod='rappelezmoi'}<span class="req">&nbsp;&nbsp;*</<span></label></td>
            <td> <textarea id="reason-rap" name="reason" rows="7" cols="35" placeholder="{l s='Your message' mod='rappelezmoi'}"  required="required" class="champ"></textarea></td>
        </tr>
        <tr>
			<td></td>
           <td><input type="submit" name="submitrequestcol" id="submitrequestcol" value="{l s='Send' mod='rappelezmoi'}"/><input type="button" class="close_formulaire" value="{l s='Close' mod='rappelezmoi'}" class="close" onclick="showhide('rappelezmoiform');"></td>
        </tr>
		<tr>
		 <td><p class="text-rap">{l s='required field' mod='rappelezmoi'}<span class="req">&nbsp;&nbsp;*</span></p>
		</td>
		</tr>
		<tr>
		<td>
		</td>
		</tr>
	</table>
	</form>
</div>
</div>
</div>
<!--fin Rappelez-moi column-->

<!-- Affiche/cache le formulaire-->
<script type="text/javascript">
function showhide(id){ 
if (document.getElementById){ 
obj = document.getElementById(id); 
if (obj.style.display == "none"){ 
obj.style.display = ""; 
} else { 
obj.style.display = "none"; 
} 
} 
} 
 </script>
<!-- restriction champ imput que des chiffres et mask-->
<script type="text/javascript">
    jQuery(function($){
	$("#phone-rap").mask("99-99-99-99-99");
    $("#hour_start").mask("99h99");
	$("#hour_end").mask("99h99");
})
</script> 
 <!-- Affiche les heures en liste deroulante
  vous pouvez configurrer 
  defaultTime	: heure: minute selection par default de l'heure dans la liste deroulante 
  minTime		: heure et minute  ou commence le liste deroulante
  maxtime		: heure et minute ou fini la liste deroulante
  timeInterval	: interval entre les minutes 10,15,20,30 etc...
  isoTime		: affichage true= sans AM/PM false= avec AM/PM
  -->    
<script type="text/javascript">

		 $(function() {
        $('#hour_start, #hour_end')
            .calendricalTimeRange(
			{literal}
			{
            defaultTime:  {hour: 09, minute: 00},
            minTime: {hour: 9, minute: 00},
            maxTime: {hour: 18, minute: 00},
            timeInterval: 30,
			isoTime: true
			}
			{/literal}
        ); 
    });
</script>		


