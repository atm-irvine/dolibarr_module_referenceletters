<?php

if(is_file('../main.inc.php'))$dir = '../';
else  if(is_file('../../../main.inc.php'))$dir = '../../../';
else $dir = '../../';

if(!defined('INC_FROM_DOLIBARR') && defined('INC_FROM_CRON_SCRIPT')) {
	include($dir."master.inc.php");
}
elseif(!defined('INC_FROM_DOLIBARR')) {
	include($dir."main.inc.php");
} else {
	global $dolibarr_main_db_host, $dolibarr_main_db_name, $dolibarr_main_db_user, $dolibarr_main_db_pass;
}
if(!defined('DB_HOST')) {
	define('DB_HOST',$dolibarr_main_db_host);
	define('DB_NAME',$dolibarr_main_db_name);
	define('DB_USER',$dolibarr_main_db_user);
	define('DB_PASS',$dolibarr_main_db_pass);
	define('DB_DRIVER',$dolibarr_main_db_type);
}

dol_include_once('/referenceletters/class/referenceletters.class.php');
dol_include_once('/referenceletters/class/referenceletterschapters.class.php');

global $db;

$rfltr = new ReferenceLetters($db);

/***********************************/
/************* Propal **************/
/***********************************/

$title = 'EDITION_PERSO_PROPOSITION';

if($rfltr->fetch('', $title) <= 0) {
	
	$rfltr->entity = $conf->entity;
	$rfltr->title = $title;
	$rfltr->element_type = 'propal';
	$rfltr->status = 1;
	$rfltr->fk_user_author = $user->id;
	$rfltr->datec = dol_now();
	$rfltr->fk_user_mod = $obj->fk_user_mod;
	$rfltr->tms = dol_now();
	$rfltr->header = '&nbsp;<br />
<br />
&nbsp;
<table cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
			<td>MON LOGO ENTREPRISE</td>
			<td style="text-align:right"><strong>Proposition commerciale<br />
			R&eacute;f. :&nbsp;{object_ref}</strong><br />
			Date :&nbsp;{object_date}<br />
			Date de fin de validit&eacute; :&nbsp;{object_date_end}<br />
			Code client :&nbsp;{cust_company_customercode}<br />
			{objets_lies}</td>
		</tr>
	</tbody>
</table>';
	$rfltr->use_custom_header = 1;
	$rfltr->footer = '<div style="text-align:center"><br />
<span style="font-size:8px">{mycompany_juridicalstatus} - SIRET :&nbsp;{mycompany_idprof2}<br />
NAF-APE :&nbsp;{mycompany_idprof3} - Num VA :&nbsp;{mycompany_vatnumber}</span><br />
&nbsp;</div>
';
	$rfltr->use_custom_footer = 1;
	$rfltr->use_landscape_format = 0;
	
	$id_rfltr = $rfltr->create($user);
	
	// Instanciation du contenu
	if(!empty($id_rfltr)) {
		
		$chapter = new ReferenceLettersChapters($db);
		$chapter->entity = $conf->entity;
		$chapter->fk_referenceletters = $id_rfltr;
		$chapter->lang = 'fr_FR';
		$chapter->sort_order = 1;
		$chapter->fk_user_author = $chapter->fk_user_mod = $user->id;
		$chapter->title = 'Contenu';
		$chapter->content_text = '<table cellpadding="1" cellspacing="1" style="width:550px">
	<tbody>
		<tr>
			<td style="width:50%">Emetteur :<br />
			&nbsp;
			<table cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="background-color:#e6e6e6; height:121px"><br />
						<strong>{mycompany_name}</strong><br />
						{mycompany_address}<br />
						{mycompany_zip}&nbsp;{mycompany_town}<br />
						<br />
						T&eacute;l. : {mycompany_phone} - Fax :&nbsp;{mycompany_fax}<br />
						Email : {mycompany_email}<br />
						Web :&nbsp;{mycompany_web}</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td style="width:50%">Adress&eacute; &agrave; :<br />
			&nbsp;
			<table border="1" style="width:245px">
				<tbody>
					<tr>
						<td style="height:121px"><br />
						<strong>{cust_company_name}</strong><br />
						{cust_company_address}<br />
						{cust_company_zip}&nbsp;{cust_company_town}</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<div style="text-align:right">Montants exprim&eacute;s en Euros</div>

<table border="1" style="cellpadding:1; cellspacing:1; width:530px">
	<tbody>
		<tr>
			<td style="width:50%">D&eacute;signation</td>
			<td style="width:10%">TVA</td>
			<td style="width:10%">P.U. HT</td>
			<td style="width:10%">Qt&eacute;</td>
			<td style="width:10%">R&eacute;duc.</td>
			<td style="width:10%">Total HT[!-- BEGIN lines --]</td>
		</tr>
		<tr>
			<td>{line_fulldesc}</td>
			<td style="text-align:right">{line_vatrate}</td>
			<td style="text-align:right">{line_up_locale}</td>
			<td style="text-align:right">{line_qty}</td>
			<td style="text-align:right">{line_discount_percent}</td>
			<td style="text-align:right">{line_price_ht_locale}[!-- END lines --]</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:500px">
	<tbody>
		<tr>
			<td rowspan="3" style="width:60%"><strong>Conditions de r&egrave;glement</strong> : {objvar_object_cond_reglement_doc}<br />
			<strong>Mode de r&egrave;glement</strong> : {objvar_object_mode_reglement}</td>
			<td style="width:20%">Total HT</td>
			<td style="text-align:right; width:20%">{objvar_object_total_ht}</td>
		</tr>
		<tr>
			<td style="background-color:#f5f5f5; width:20%">{tva_detail_titres}</td>
			<td style="background-color:#f5f5f5; text-align:right; width:20%">{tva_detail_montants}</td>
		</tr>
		<tr>
			<td style="background-color:#e6e6e6; width:20%">Total TTC</td>
			<td style="background-color:#e6e6e6; text-align:right; width:20%">{objvar_object_total_ttc}</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:500px">
	<tbody>
		<tr>
			<td style="width:55%">&nbsp;</td>
			<td style="width:45%"><br />
			Cachet, Date, Signature et mention &quot;Bon pour accord&quot;<br />
			&nbsp;
			<table border="1" cellpadding="1" cellspacing="1" style="width:200px">
				<tbody>
					<tr>
						<td style="height:70px; width:220px">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>';
		
		$chapter->create($user);
		
	}
	
}


/************************************/
/************* Facture **************/
/************************************/
$title = 'EDITION_PERSO_FACTURE';
if($rfltr->fetch('', $title) <= 0) {
	
	$rfltr->entity = $conf->entity;
	$rfltr->title = $title;
	$rfltr->element_type = 'invoice';
	$rfltr->status = 1;
	$rfltr->fk_user_author = $user->id;
	$rfltr->datec = dol_now();
	$rfltr->fk_user_mod = $obj->fk_user_mod;
	$rfltr->tms = dol_now();
	$rfltr->header = '&nbsp;<br />
<br />
&nbsp;
<table cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
			<td>MON LOGO ENTREPRISE</td>
			<td style="text-align:right"><strong>Facture<br />
			R&eacute;f. :&nbsp;{object_ref}</strong><br />
			Date facturation :&nbsp;{object_date}<br />
			Date &eacute;ch&eacute;ance :&nbsp;{object_date_limit}<br />
			Code client :&nbsp;{cust_company_customercode}<br />
			{objets_lies}</td>
		</tr>
	</tbody>
</table>';
	$rfltr->use_custom_header = 1;
	$rfltr->footer = '<div style="text-align:center"><br />
<span style="font-size:8px">{mycompany_juridicalstatus} - SIRET :&nbsp;{mycompany_idprof2}<br />
NAF-APE :&nbsp;{mycompany_idprof3} - Num VA :&nbsp;{mycompany_vatnumber}</span><br />
&nbsp;</div>
';
	$rfltr->use_custom_footer = 1;
	$rfltr->use_landscape_format = 0;
	
	$id_rfltr = $rfltr->create($user);
	
	// Instanciation du contenu
	if(!empty($id_rfltr)) {
		
		$chapter = new ReferenceLettersChapters($db);
		$chapter->entity = $conf->entity;
		$chapter->fk_referenceletters = $id_rfltr;
		$chapter->lang = 'fr_FR';
		$chapter->sort_order = 1;
		$chapter->fk_user_author = $chapter->fk_user_mod = $user->id;
		$chapter->title = 'Contenu';
		$chapter->content_text = '<table cellpadding="1" cellspacing="1" style="width:550px">
	<tbody>
		<tr>
			<td style="width:50%">Emetteur :<br />
			&nbsp;
			<table cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="background-color:#e6e6e6; height:121px"><br />
						<strong>{mycompany_name}</strong><br />
						{mycompany_address}<br />
						{mycompany_zip}&nbsp;{mycompany_town}<br />
						<br />
						T&eacute;l. : {mycompany_phone} - Fax :&nbsp;{mycompany_fax}<br />
						Email : {mycompany_email}<br />
						Web :&nbsp;{mycompany_web}</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td style="width:50%">Adress&eacute; &agrave; :<br />
			&nbsp;
			<table border="1" style="width:245px">
				<tbody>
					<tr>
						<td style="height:121px"><br />
						<strong>{cust_company_name}</strong><br />
						{cust_company_address}<br />
						{cust_company_zip}&nbsp;{cust_company_town}</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<div style="text-align:right">Montants exprim&eacute;s en Euros</div>

<table border="1" style="cellpadding:1; cellspacing:1; width:530px">
	<tbody>
		<tr>
			<td style="width:50%">D&eacute;signation</td>
			<td style="width:10%">TVA</td>
			<td style="width:10%">P.U. HT</td>
			<td style="width:10%">Qt&eacute;</td>
			<td style="width:10%">R&eacute;duc.</td>
			<td style="width:10%">Total HT[!-- BEGIN lines --]</td>
		</tr>
		<tr>
			<td>{line_fulldesc}</td>
			<td style="text-align:right">{line_vatrate}</td>
			<td style="text-align:right">{line_up_locale}</td>
			<td style="text-align:right">{line_qty}</td>
			<td style="text-align:right">{line_discount_percent}</td>
			<td style="text-align:right">{line_price_ht_locale}[!-- END lines --]</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:500px">
	<tbody>
		<tr>
			<td rowspan="6" style="width:60%"><strong>Conditions de r&egrave;glement</strong> : {objvar_object_cond_reglement_doc}<br />
			<strong>Mode de r&egrave;glement</strong> : {objvar_object_mode_reglement}</td>
			<td style="width:20%">Total HT</td>
			<td style="text-align:right; width:20%">{objvar_object_total_ht}</td>
		</tr>
		<tr>
			<td style="background-color:#f5f5f5; width:20%">{tva_detail_titres}</td>
			<td style="background-color:#f5f5f5; text-align:right; width:20%">{tva_detail_montants}</td>
		</tr>
		<tr>
			<td style="background-color:#e6e6e6; width:20%">Total TTC</td>
			<td style="background-color:#e6e6e6; text-align:right; width:20%">{objvar_object_total_ttc}</td>
		</tr>
		<tr>
			<td style="width:20%">Pay&eacute;</td>
			<td style="text-align:right; width:20%">{deja_paye}</td>
		</tr>
		<tr>
			<td style="width:20%">Avoirs</td>
			<td style="text-align:right; width:20%">{somme_avoirs}</td>
		</tr>
		<tr>
			<td style="background-color:#e6e6e6; width:20%">Reste &agrave; payer</td>
			<td style="background-color:#e6e6e6; text-align:right; width:20%">{reste_a_payer}</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:500px">
	<tbody>
		<tr>
			<td style="width:55%">&nbsp;</td>
			<td style="width:45%">&nbsp;
			<table cellpadding="1" cellspacing="1" style="width:200px">
				<tbody>
					<tr>
						<td style="height:70px; width:220px">{liste_paiements}</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>';
		
		$chapter->create($user);
		
	}
	
}


/************* Commande **************/
$title = 'EDITION_PERSO_COMMANDE';
if($rfltr->fetch('', $title) <= 0) {
	
	$rfltr->entity = $conf->entity;
	$rfltr->title = $title;
	$rfltr->element_type ='order';
	$rfltr->status = 1;
	$rfltr->fk_user_author = $user->id;
	$rfltr->datec = dol_now();
	$rfltr->fk_user_mod = $obj->fk_user_mod;
	$rfltr->tms = dol_now();
	$rfltr->header = '&nbsp;<br />
<br />
&nbsp;
<table cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
			<td>MON LOGO ENTREPRISE</td>
			<td style="text-align:right"><strong>Commande<br />
			R&eacute;f. :&nbsp;{object_ref}</strong><br />
			Date de commande :&nbsp;{object_date}<br />
			{objets_lies}</td>
		</tr>
	</tbody>
</table>';
	$rfltr->use_custom_header = 1;
	$rfltr->footer = '<div style="text-align:center"><br />
<span style="font-size:8px">{mycompany_juridicalstatus} - SIRET :&nbsp;{mycompany_idprof2}<br />
NAF-APE :&nbsp;{mycompany_idprof3} - Num VA :&nbsp;{mycompany_vatnumber}</span><br />
&nbsp;</div>
';
	$rfltr->use_custom_footer = 1;
	$rfltr->use_landscape_format = 0;
	
	$id_rfltr = $rfltr->create($user);
	
	// Instanciation du contenu
	if(!empty($id_rfltr)) {
		
		$chapter = new ReferenceLettersChapters($db);
		$chapter->entity = $conf->entity;
		$chapter->fk_referenceletters = $id_rfltr;
		$chapter->lang = 'fr_FR';
		$chapter->sort_order = 1;
		$chapter->fk_user_author = $chapter->fk_user_mod = $user->id;
		$chapter->title = 'Contenu';
		$chapter->content_text = '<table cellpadding="1" cellspacing="1" style="width:550px">
	<tbody>
		<tr>
			<td style="width:50%">Emetteur :<br />
			&nbsp;
			<table cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="background-color:#e6e6e6; height:121px"><br />
						<strong>{mycompany_name}</strong><br />
						{mycompany_address}<br />
						{mycompany_zip}&nbsp;{mycompany_town}<br />
						<br />
						T&eacute;l. : {mycompany_phone} - Fax :&nbsp;{mycompany_fax}<br />
						Email : {mycompany_email}<br />
						Web :&nbsp;{mycompany_web}</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td style="width:50%">Adress&eacute; &agrave; :<br />
			&nbsp;
			<table border="1" style="width:245px">
				<tbody>
					<tr>
						<td style="height:121px"><br />
						<strong>{cust_company_name}</strong><br />
						{cust_company_address}<br />
						{cust_company_zip}&nbsp;{cust_company_town}</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<div style="text-align:right">Montants exprim&eacute;s en Euros</div>

<table border="1" style="cellpadding:1; cellspacing:1; width:530px">
	<tbody>
		<tr>
			<td style="width:50%">D&eacute;signation</td>
			<td style="width:10%">TVA</td>
			<td style="width:10%">P.U. HT</td>
			<td style="width:10%">Qt&eacute;</td>
			<td style="width:10%">R&eacute;duc.</td>
			<td style="width:10%">Total HT[!-- BEGIN lines --]</td>
		</tr>
		<tr>
			<td>{line_fulldesc}</td>
			<td style="text-align:right">{line_vatrate}</td>
			<td style="text-align:right">{line_up_locale}</td>
			<td style="text-align:right">{line_qty}</td>
			<td style="text-align:right">{line_discount_percent}</td>
			<td style="text-align:right">{line_price_ht_locale}[!-- END lines --]</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:500px">
	<tbody>
		<tr>
			<td rowspan="3" style="width:60%"><strong>Conditions de r&egrave;glement</strong> : {objvar_object_cond_reglement_doc}<br />
			<strong>Mode de r&egrave;glement</strong> : {objvar_object_mode_reglement}</td>
			<td style="width:20%">Total HT</td>
			<td style="text-align:right; width:20%">{objvar_object_total_ht}</td>
		</tr>
		<tr>
			<td style="background-color:#f5f5f5; width:20%">{tva_detail_titres}</td>
			<td style="background-color:#f5f5f5; text-align:right; width:20%">{tva_detail_montants}</td>
		</tr>
		<tr>
			<td style="background-color:#e6e6e6; width:20%">Total TTC</td>
			<td style="background-color:#e6e6e6; text-align:right; width:20%">{objvar_object_total_ttc}</td>
		</tr>
	</tbody>
</table>';
		
		$chapter->create($user);
		
	}
	
}


/************* Contrat **************/
$title = 'EDITION_PERSO_CONTRAT';
if($rfltr->fetch('', $title) <= 0) {
	
	$rfltr->entity = $conf->entity;
	$rfltr->title = $title;
	$rfltr->element_type = 'contract';
	$rfltr->status = 1;
	$rfltr->fk_user_author = $user->id;
	$rfltr->datec = dol_now();
	$rfltr->fk_user_mod = $obj->fk_user_mod;
	$rfltr->tms = dol_now();
	$rfltr->header = '&nbsp;<br />
<br />
&nbsp;
<table cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
			<td>MON LOGO ENTREPRISE</td>
			<td style="text-align:right"><strong>Fiche contrat<br />
			R&eacute;f. :&nbsp;{object_ref}</strong><br />
			Date :&nbsp;{object_date_creation}<br />
			Code client :&nbsp;{cust_company_customercode}</td>
		</tr>
	</tbody>
</table>';
	$rfltr->use_custom_header = 1;
	$rfltr->footer = '<div style="text-align:center"><br />
<span style="font-size:8px">{mycompany_juridicalstatus} - SIRET :&nbsp;{mycompany_idprof2}<br />
NAF-APE :&nbsp;{mycompany_idprof3} - Num VA :&nbsp;{mycompany_vatnumber}</span><br />
&nbsp;</div>
';
	$rfltr->use_custom_footer = 1;
	$rfltr->use_landscape_format = 0;
	
	$id_rfltr = $rfltr->create($user);
	
	// Instanciation du contenu
	if(!empty($id_rfltr)) {
		
		$chapter = new ReferenceLettersChapters($db);
		$chapter->entity = $conf->entity;
		$chapter->fk_referenceletters = $id_rfltr;
		$chapter->lang = 'fr_FR';
		$chapter->sort_order = 1;
		$chapter->fk_user_author = $chapter->fk_user_mod = $user->id;
		$chapter->title = 'Contenu';
		$chapter->content_text = '<table cellpadding="1" cellspacing="1" style="width:550px">
	<tbody>
		<tr>
			<td style="width:50%">Emetteur :<br />
			&nbsp;
			<table cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="background-color:#e6e6e6; height:121px"><br />
						<strong>{mycompany_name}</strong><br />
						{mycompany_address}<br />
						{mycompany_zip}&nbsp;{mycompany_town}<br />
						<br />
						T&eacute;l. : {mycompany_phone} - Fax :&nbsp;{mycompany_fax}<br />
						Email : {mycompany_email}<br />
						Web :&nbsp;{mycompany_web}</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td style="width:50%">Adress&eacute; &agrave; :<br />
			&nbsp;
			<table border="1" style="width:245px">
				<tbody>
					<tr>
						<td style="height:121px"><br />
						<strong>{cust_company_name}</strong><br />
						{cust_company_address}<br />
						{cust_company_zip}&nbsp;{cust_company_town}</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
&nbsp;
<table border="1" style="width:530px">
	<tbody>
		<tr>
			<td>[!-- BEGIN lines --]{line_product_ref} -&nbsp;{line_product_label}<br />
			Quantit&eacute; :&nbsp;<strong>{line_qty}</strong> - Prix unitaire :&nbsp;<strong>{line_price_ht_locale}</strong><br />
			Date d&eacute;but pr&eacute;vue : <strong>{date_ouverture_prevue}</strong> - Date pr&eacute;vue fin de service : <strong>{date_fin_validite}</strong><br />
			Date d&eacute;but : <strong>{date_ouverture}</strong><br />
			{line_desc}<br />
			<br />
			[!-- END lines --]</td>
		</tr>
	</tbody>
</table>
&nbsp;<br />
&nbsp;<br />
<br />
<br />
<br />
&nbsp;
<table cellpadding="1" cellspacing="1" style="width:530px">
	<tbody>
		<tr>
			<td style="width:55%"><br />
			Pour&nbsp;{mycompany_name}, nom et signature :<br />
			&nbsp;
			<table border="1" cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="height:70px; width:220px">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td style="width:45%"><br />
			Pour&nbsp;{cust_company_name}, nom et signature :<br />
			&nbsp;
			<table border="1" cellpadding="1" cellspacing="1" style="width:242px">
				<tbody>
					<tr>
						<td style="height:70px; width:220px">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>';
		
		$chapter->create($user);
		
	}
	
}
