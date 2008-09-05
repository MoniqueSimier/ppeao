<?php 
	if (!$ArretTimeOut) {
	// ***************************
	// Si on est dans le cas normal, on génère le compte rendu de fin de traitement.
		if ($_SESSION['s_erreur_process']) {
			$_SESSION['s_status_process_auto'] = 'ko';
		}	
		// On met à jour la table des logs avec les données
		logWriteTo(4,"notice","**- Compte rendu traitement ".$nomAction,"","","0");
		logWriteTo(4,"notice","*-- Nombre total de tables lues = ".$_SESSION['s_cpt_table_total'],$_SESSION['s_cpt_table_total'],"","0");
		logWriteTo(4,"notice","*-- Nombre de tables identiques = ".$_SESSION['s_cpt_table_egal'],$_SESSION['s_cpt_table_egal'],"","0");		
		logWriteTo(4,"notice","*-- Nombre de tables avec uniquement des donnees differences = ".$_SESSION['s_cpt_table_diff'],$_SESSION['s_cpt_champ_diff'],"","0");
		logWriteTo(4,"notice","*-- Nombre de tables avec uniquement des donnees manquantes = ".$_SESSION['s_cpt_table_manquant'],$_SESSION['s_cpt_table_manquant'],"","0");
				logWriteTo(4,"notice","*-- Nombre de tables avec des donnees manquantes et differentes = ".$_SESSION['s_cpt_table_diff_manquant'],$_SESSION['s_cpt_table_diff_manquant'],"","0");
		logWriteTo(4,"notice","*-- Nombre de tables de references vides = ".$_SESSION['s_cpt_table_vide'],$_SESSION['s_cpt_table_vide'],"","0");
		logWriteTo(4,"notice","*-- Nombre d'erreur lors de la maj = ".$_SESSION['s_cpt_erreurs_sql'],$_SESSION['s_cpt_erreurs_sql'],"","0");
	
		if ($EcrireLogComp ) {
			// Si on a choisi de générer le log complémentaire, alors
			// gestion fin de traitement 
			WriteCompLog ($logComp,"******************************************",$pasdefichier);
			WriteCompLog ($logComp,"* Compte rendu traitement ".$nomAction,$pasdefichier);
			WriteCompLog ($logComp,"******************************************",$pasdefichier);
			WriteCompLog ($logComp,"* Nombre total de tables lues = ".$_SESSION['s_cpt_table_total'],$pasdefichier);
			WriteCompLog ($logComp,"* Nombre de tables identiques = ".$_SESSION['s_cpt_table_egal'],$pasdefichier);
			WriteCompLog ($logComp,"* Nombre de tables avec uniquement des donnees differentes = ".$_SESSION['s_cpt_table_diff'],$pasdefichier);
			WriteCompLog ($logComp,"* Nombre de tables avec uniquement des donnees manquantes = ".$_SESSION['s_cpt_table_manquant'],$pasdefichier);
			WriteCompLog ($logComp,"* Nombre de tables avec des donnees manquantes et differentes = ".$_SESSION['s_cpt_table_diff_manquant'],$pasdefichier);
			WriteCompLog ($logComp,"* Nombre de tables de references vides = ".$_SESSION['s_cpt_table_vide'],$pasdefichier);
		}
		// Affichage d'avertissement si erreur dans le traitement
		if ($_SESSION['s_erreur_process']) {
			if ($typeAction == "comp" ) {
				// Avertissement dans le cas de la comparaison
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
					WriteCompLog ($logComp,"* ATTENTION, des mises a jour sont requises pour les tables ou des enregitrements manquent ou sont differents",$pasdefichier);
					WriteCompLog ($logComp,"* Scripts SQL pour ces mises a jours presents dans ".$dirLog."/Comparaison.sql",$pasdefichier);
				}
				logWriteTo(4,"error","*** Enregistrements differents ou manquants : arret du traitement ==> lancer les mises a jour des enregistrements.","","","0");
				logWriteTo(4,"error","*** Scripts SQL pour ces mises a jours presents dans ".$dirLog."/Comparaison.sql","","","0");
			} else {
			// L'avertissement est différent pour la mise à jour
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
					WriteCompLog ($logComp,"* ATTENTION, il y a eu des erreurs sur des ajouts / mises a jour de table.",$pasdefichier);
					WriteCompLog ($logComp,"* Merci de controler avec l'admin BD les integrites des donnees a copier.",$pasdefichier);
				}
				logWriteTo(4,"error","*** Erreurs dans l'ajout / mise a jour des donnees : arret du traitement ==> contacter l'admin BD pour un controle des enregistrements de la base a mettre a jour","","","0");
			}
				
		}
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
			WriteCompLog ($logComp,"*- FIN TRAITEMENT ".$nomAction,$pasdefichier);
			WriteCompLog ($logComp,"*---------------------------------------------",$pasdefichier);
			logWriteTo(4,"notice","*-- Log plus complet disponible dans ".$nomFicLogComp,"","","0");
		}
		// Fin de traitement dans le log
		logWriteTo(4,"notice","**- Fin traitement de ".$nomAction,$cptTableVide,"","","0");
	
	
		// ***********************************************
		// On gére l'affichage a l'ecran d'un compte rendu
		if ($typeAction == "comp") {
			if (!$_SESSION['s_erreur_process']) {
					// Pas de différences
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s et toutes les tables sont identiques .<br/>".$CRexecution." ".$messageGen;
				if ($EcrireLogComp ) {
				echo "<br/>Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : ".$nomFicLogComp;
				}
				echo"</div>" ;	
			} else {
				// Différences
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ex&eacute;cut&eacute;e avec succ&egrave;s mais des tables sont diff&eacute;rentes et/ou vides. <br/>Des mises &agrave; jour sont n&eacute;cessaires avant de relancer le traitement. (pour info = les scripts SQL pour ces mises a jours pr&eacute;sents dans ".$dirLog."/Comparaison.sql) <br/>".$CRexecution." ".$messageGen;
				if ($EcrireLogComp ) {
				echo "<br/>Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : ".$nomFicLogComp;
				}
				echo"</div>" ;
			}
		} else {
			if ( $_SESSION['s_erreur_process']) {
			// Erreur dans la mise à jour
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." Erreur dans l'ajout ou la modification des donn&eacute;es. <br/>".$CRexecution." ".$messageGen;
				if ($EcrireLogComp ) {
					echo "<br/>Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : ".$nomFicLogComp;
				}
				echo"</div>" ;			
			} else {
			// Aucune erreur dans la mise à jour
				echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." Le contenu des tables a &eacute;t&eacute; ajout&eacute; ou modifi&eacute; avec succ&egrave;s.<br/> ".$CRexecution." ".$messageGen;
				if ($EcrireLogComp ) {
					echo "<br/>Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : ".$nomFicLogComp;
				}
				echo"</div>" ;
			} // end of statement else of  if ( $ErreurProcess)
		} // end of statement else of  if ($typeAction = "comp")
		
		// ************************************
		// Fin du traitement, on reinitialise les compteurs pour la prochaine utilisation de ce programme
		$_SESSION['s_cpt_champ_total'] = 0 ;
		$_SESSION['s_cpt_champ_diff'] = 0 ;
		$_SESSION['s_cpt_champ_egal'] = 0 ;
		$_SESSION['s_cpt_champ_vide'] = 0 ;
		$_SESSION['s_cpt_table_total'] = 0 ;
		$_SESSION['s_cpt_table_diff'] = 0 ;
		$_SESSION['s_cpt_table_egal'] = 0 ;
		$_SESSION['s_cpt_table_vide'] = 0 ;
		$_SESSION['s_cpt_table_manquant'] = 0 ; 
		$_SESSION['s_num_encours_fichier_SQL'] = 1;
		$_SESSION['s_cpt_erreurs_sql']= 0;

			
	} else { // End for statement ($ArretTimeOut)
	// Le traitement est relancé pour cause de timeout, on met a jour le(s) log(s)
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,$pasdefichier);
		}
		logWriteTo(4,"notice","Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,"","","0");
		// test
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction."Arret Timeout : execution en ".$delai." time maxi = ".$max_time." </div>";
		echo "<form id=\"formtest\"> 
		<input id=\"nomtable\" 	type=\"hidden\" value=\"".$tableEnLecture."\"/>
		<input id=\"numID\" 	type=\"hidden\" value=\"".$IDEnLecture."\"/>
		<input id=\"numproc\" 	type=\"hidden\" value=\"".$numProcess."\"/>
		</form>";
	}
?>