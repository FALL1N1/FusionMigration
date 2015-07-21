
	<script type="text/javascript">

	var txt1   = "{$txt1}";
	var txt2   = "{$txt2}";
	var txt3   = "{$txt3}";
	var txt8   = "{$txt8}";
	var txt5   = "{$txt5}";
	var txt4   = "{$txt4}";
	var txt9   = "{$txt9}";
	var txt10  = "{$txt10}";
	var txt11  = "{$txt11}";
	var txt12  = "{$txt12}";
	var txt37  = "{$txt37}";
	var txt38  = "{$txt38}";
	var txt39  = '{$txt39}';
	var fname434 = '{$fname434}';
	var fname406a = '{$fname406a}';
	var fname335a = '{$fname335a}';

	</script>

	{if hasPermission("manage")}

	<script type="text/javascript" src="{$baseurl}application/modules/migration/js/actionsme.js"></script>

<table class="nice_table">

	<tr>

		<td>Old Name</td><td>Old Realm</td><td>Realmlist</td><td>Account</td><td>Password</td><td>W</td><td>Opciones</td>

	</tr>

	{foreach from=$getAccTrans item=row}

	<tr class="efecto">

		<td>{$row.cNameOLD}</td>

		<td>

			{$row.oRealm} 

		</td>

		<td>{$row.oRealmlist}</td><td>{$row.oAccount}</td><td>{base64_decode($row.oPassword)}</td><td><a target="{$row.oRealm}" href="http://{str_ireplace('http://', '', $row.oRealmlist)}"><img border="0" src="{$baseurl}application/images/icons/world.png" alt="{$row.oServer}"></a></td>

		<td>

			

			<div style="text-align:center" id="reescribir{$row.id}">

			{if $row.cStatus == 0}<a href="javascript:void(0)" onclick="Go.rechazar({$row.id})" class="nice_button">R</a>{/if}

			{if $row.cStatus == 0}<a href="javascript:void(0)" onclick="Go.aceptar({$row.id})" class="nice_button">A</a>{/if}

			{if $row.cStatus == 1}<a href="javascript:void(0)" onclick="Go.resend({$row.id})" class="nice_button">ReSend</a>{/if}

			{if $row.cStatus == 2}Rechazada{/if}

			{if $row.cStatus == 3}Cancelada{/if}

			{if $row.cStatus == 4}Error!{/if}

			{if $row.cStatus == 5}{if (strtotime(date('Y-m-d H:i:s')) - strtotime($row.date_created)) >= 1800} <a href="javascript:void(0)" onclick="Go.delete({$row.id})" class="nice_button">Click Pls</a>{else}Guardando...{/if}{/if}

			</div>

			

		</td>

	</tr>

	{/foreach}

</table>

	{else}

	<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script><script>var wowhead_tooltips = { "colorlinks": false, "iconizelinks": true, "renamelinks": true }</script>



	<br>

	<ul class="migra-pasos">

		<div id="steps_anlca"><a name="steps_ancla"></a></div>

		<li id="paso-li1" class="now">{$utxt100}</li>

		<li id="paso-li2">{$utxt101}</li>

		<li id="paso-li3">{$utxt102}</li>

		<li id="paso-li4">{$utxt103}</li>

	</ul>

	<div id="paso-1">

		<p>

			{$utxt104}

		</p>

			

		<h3>{$utxt105}</h3>

		<hr>

		{$utxt106}

		<p></p>



		<h3 class="info-der">{$utxt107}</h3>

		<hr>

		{$utxt108}

		<p></p>



		<h3>{$utxt109}</h3>

		<hr>

		{$utxt110}

		<p></p>



		<h3 class="info-der">{$utxt111}</h3>

		<hr>

		{$utxt112}

		<p></p>

	</div>

	{if $isOnline}

	<div id="paso-2">

		<h3>{$utxt113}</h3>

		<hr>

		<p>{$utxt114}</p>

		

		<br>

		<center>

		<a class="nice_button" direct="0"  href="{$335alink}" target="new" style="display: none;" id="descarga-wow335a">{$utxt115}</a>

		<a class="nice_button" direct="0" href="{$406alink}" target="new" style="display: none;" id="descarga-wow406a">{$utxt115}</a>

		<a class="nice_button" direct="0" href="{$434link}" target="new" style="display: none;" id="descarga-wow434">{$utxt115}</a>

		</center>

		<br>

		<p></p>

		<p>{$utxt116}.</p>

		<center><img src="{$baseurl}application/modules/migration/images/migration-steps-1b.jpg"></center>

		<p></p>

		<h3>{$utxt117}</h3>

		<hr>

		<p>{$utxt118}</p>

		<center><img src="{$baseurl}application/modules/migration/images/migration-steps-2c.jpg"></center>

		<p></p>

		<h3>{$utxt119}</h3>

		<hr>

		<p>{$utxt120}</p>

		<form enctype="multipart/form-data" name="formulario" class="formulario" >

		<div style="display:none">

			<input type="hidden" name="csrf_token_name" value="{$token}" />

		</div>

		<div style="float: left; width:50%;">{$utxt121}<br><input type="text" name="oUsername" /></div>

		<div style="float: left; width:50%;">{$utxt122}<br><input type="password" name="oPassword" /></div>

		<br><br><br><br>

		<p>{$utxt123}</p>

		<p></p>

		<div style="width: 40%; float: left;">

			<select id="to_realm_name" name="realm">

				<option value="0">{$utxt124}</option>

				{if $realms}{foreach from=$realms item=r}

				<option value="{$r.id}">{$r.name}</option>

				{/foreach}{/if}

			</select>

		</div>

		<div style="width: 60%; float: left;">

			<p>

			<div class="drop">

				<div class="drop-upload">

					<input name="userfile" type="file"  id="lua">

				</div>

			</div>

			</p>

		</div>

		</form>

		<br>

		<br>

		<br>

		<br>

		<div class="drop-checking">

				<p>{$utxt125}</p>

				<p><img src="{$baseurl}application/modules/migration/images/ajax-loader.gif"></p>

		</div>

		<div class="drop-valid">

		</div>

		<div class="drop-invalid">

				<p>{$utxt126}</p>

		</div>

		<br>



		<a id="anclab" href="#steps_ancla" class="backb nice_button izquierda">{$utxt127}</a>

		<a id="anclan" href="#steps_ancla" class="nextb nice_button derecha">{$utxt128}</a>



	</div>

	<div id="paso-3">

	<br>

	<p align="center" style="color:orange">{$utxt129}</p>

	<br>

		<div class="reading-data">

			

		</div>

		<div class="readed-data"></div>



<p align="center" style="color:red;">{$utxt144} {$utxt128}. {$utxt145}</p>



		<a id="anclab" href="#steps_ancla" class="backb nice_button izquierda">{$utxt127}</a>

		<a id="anclan" href="#steps_ancla" class="nextb nice_button derecha">{$utxt128}</a>

	</div>

	<div id="paso-4">

	<p align="center" style="color:green">{$utxt130}</p>

		<p align="center"><img width="200" src="{$baseurl}application/modules/migration/images/success_512.png"></p>

		<p align="center" style="color:orange;">{$utxt131}</p>

		<p align="center"><a class="nice_button" href="migration">{$utxt132}</a></p>

	</div>

	<div id="selector">



		<center>

			<ul class="version-select">

                <li class="wotlk335a"><a href="#steps_ancla" id="wow335a"></a></li>

                <li class="cata406a"><a href="#steps_ancla" id="wow406a"></a></li>

                <li class="cata434"><a href="#steps_ancla" id="wow434"></a></li>

            </ul>

		{else}

			<center><p style="color: red;">{$utxt133}</p></center>

		{/if}

		</center>

	</div>



	

{if ($isOnline && $getAccTrans)}





<table class="nice_table">

<br><br><br>

<br><br><br><br><br><br>

	<tr>

		<td>N°</td><td>{$utxt134}</td><td>{$utxt135}</td><td>{$utxt136}</td><td>{$utxt137}</td>

	</tr>

	{foreach from=$getAccTrans item=row}

	<tr>

		<td>{$row.id}</td><td>{$row.cNameOLD}</td>

		<td>
			{if $realms}{foreach from=$realms item=r}
			{if $r.id == $row.cRealm}{$r.name}{/if}		
			{/foreach}{/if}
		</td>

		<td>

			<div id="reescribir{$row.id}">

			{if $row.cStatus == 0}<font color = "orange">{$utxt138}</font>{/if}

			{if $row.cStatus == 5}<font color = "orange">{$utxt138}</font>{/if}

			{if $row.cStatus == 1}<font color = "green">{$utxt139}</font>{/if}

			{if $row.cStatus == 3}<font color = "red">{$utxt140}</font>{/if}

			{if $row.cStatus == 2}<font color = "red">{$utxt141}{if $row.Reason != NULL}: {$row.Reason}{/if}</font>{/if}

			{if $row.cStatus == 4}<font color = "red">{$utxt142}</font>{/if}

			</div>

		</td>

		<td>

			{if $row.cStatus == 0}<div id="reescribir{$row.id}"><a href="javascript:void(0)" onclick="Migration.cancelar({$row.id})" class="nice_button">{$utxt143}</a></div>{/if}

		</td>

	</tr>

	{/foreach}

</table> 

{/if}

{/if}

	<div class="clear"></div>



