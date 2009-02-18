<?php 
	if (!$ArretTimeOut) {
	// ***************************
	// modification du CR pour l'integration dans un email
	$CRfichier = str_replace("<br/>","\r\n",$CRexecution);
	$CRfichier = str_replace ("<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>","*--> ",$CRfichier);
	$CRfichier = str_replace ("<b>","",$CRfichier);
	$CRfichier = str_replace ("</b>","",$CRfichier);
	// ***************************
	// Si on est dans le cas normal, on génère le compte rendu de fin de traitement.
		if ($_SESSION['s_erreur_process']) {
			$_SESSION['s_status_process_auto'] = 'ko';
		}	
		// On met à jour la table des logs avec les données
		logWriteTo(8,"notice","**- Compte rendu traitement ".$nomAction,"","","0");
		logWriteTo(8,"notice","*-- Nombre total de tables lues = ".$_SESSION['s_cpt_table_total'],$_SESSION['s_cpt_table_total'],"","0");
		logWriteTo(8,"notice","*-- Nombre de tables identiques = ".$_SESSION['s_cpt_table_egal'],$_SESSION['s_cpt_table_egal'],"","0");		
		logWriteTo(8,"notice","*-- Nombre de tables avec uniquement des donnees differences = ".$_SESSION['s_cpt_table_diff'],$_SESSION['s_cpt_champ_diff'],"","0");
		logWriteTo(8,"notice","*-- Nombre de tables avec uniquement des donnees manquantes = ".$_SESSION['s_cpt_table_manquant'],$_SESSION['s_cpt_table_manquant'],"","0");
				logWriteTo(8,"notice","*-- Nombre de tables avec des donnees manquantes et differentes = ".$_SESSION['s_cpt_table_diff_manquant'],$_SESSION['s_cpt_table_diff_manquant'],"","0");
		logWriteTo(8,"notice","*-- Nombre de tables vides = ".$_SESSION['s_cpt_table_vide'],$_SESSION['s_cpt_table_vide'],"","0");
		logWriteTo(8,"notice","*-- Nombre de tables de references vides = ".$_SESSION['s_cpt_table_source_vide'],$_SESSION['s_cpt_table_source_vide'],"","0");
		logWriteTo(8,"notice","*-- Nombre d'erreur lors de la maj = ".$_SESSION['s_cpt_erreurs_sql'],$_SESSION['s_cpt_erreurs_sql'],"","0");
	
		if ($EcrireLogComp ) {
			// Si on a choisi de générer le log complémentaire, alors
			// gestion fin de traitement 
			WriteCompLog ($logComp,"**----------------------------------------------------",$pasdefichier);
			WriteCompLog ($logComp,"* Compte rendu traitement ".$nomAction,$pasdefichier);
			WriteCompLog ($logComp,"* Nombre total de tables lues = ".$_SESSION['s_cpt_table_total'],$pasdefichier);						
			$FicCRexecution = str_replace ("<br/>","\r\n".date('y\-m\-d\-His')."-",$CRexecution);
			$FicCRexecution = str_replace ("<img src=\"/assets/warning.gif\" alt=\"Avertissement\"/>","*--> ",$FicCRexecution);
			$FicCRexecution = str_replace ("<b>","",$FicCRexecution);
			$FicCRexecution = str_replace ("</b>","",$FicCRexecution);
			WriteCompLog ($logComp,$FicCRexecution,$pasdefichier);
			
		}
		// Affichage d'avertissement si erreur dans le traitement
		if ($_SESSION['s_erreur_process']) {
			// L'avertissement est différent pour la mise à jour
				if ($EcrireLogComp ) {
					WriteCompLog ($logComp,"*------------------------------------------------------",$pasdefichier);
					WriteCompLog ($logComp,"* ATTENTION, il y a eu des erreurs sur des ajouts / mises a jour de table.",$pasdefichier);
					WriteCompLog ($logComp,"* Merci de controler avec l'admin BD les integrites des donnees a copier.",$pasdefichier);
				}
				logWriteTo(8,"error","*** Erreurs dans l'ajout / mise a jour des donnees : arret du traitement ==> contacter l'admin BD pour un controle des enregistrements de la base a mettre a jour","","","0");
			
		}
		if ($EcrireLogComp ) {
			WriteCompLog ($logComp,"*------------------------------------------------------",$pasdefichier);
			WriteCompLog ($logComp,"*- FIN TRAITEMENT ".$nomAction,$pasdefichier);
			WriteCompLog ($logComp,"*******************************************************",$pasdefichier);
			logWriteTo(8,"notice","*-- Log plus complet disponible dans <a href=\"".$nomLogLien."\" target=\"log\">".$nomFicLogComp."</a>","","","0");
		}
		// Fin de traitement dans le log
		logWriteTo(8,"notice","**- Fin traitement de ".$nomAction,$cptTableVide,"","","0");
	
	
		// ***********************************************
		// On gére l'affichage a l'ecran d'un compte rendu
		if ( $_SESSION['s_erreur_process']) {
		// Erreur dans la mise à jour
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/incomplete.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." Erreur dans l'ajout  des donn&eacute;es de r&eacute;f&eacute;rences.<br/>";
			if ($EcrireLogComp ) {
				echo "Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : <a href=\"".$nomLogLien."\" target=\"log\">".$nomLogLien."</a><br/>";
			}
			echo"</div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
			//echo"<div class=\"marginCR\">Compte Rendu&nbsp;<a id=\"v_slidein".$numFen."\" href=\"#\"> Afficher </a>|<a id=\"v_slideout".$numFen."\" href=\"#\"> Fermer </a>| <strong>status</strong>: <span id=\"vertical_status".$numFen."\">open</span>				</div>";
			echo"<div id=\"vertical_slide".$numFen."\"><br/>".$CRexecution." ".$messageGen."</div>";			
		} else {
		// Aucune erreur dans la mise à jour
			echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/completed.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">".$nomAction." ;  Les tables ont &eacute;t&eacute; mises &agrave; jour avec succ&egrave;s.";
			if ($EcrireLogComp ) {
				echo "<br/>Un compte rendu plus d&eacute;taill&eacute; est disponible dans le fichier de log : <a href=\"".$nomLogLien."\" target=\"log\">".$nomLogLien."</a><br/>";
			}
			echo"</div><div id=\"".$nomFenetre."_chk\">Exec= ".$Labelpasdetraitement."</div>" ;
			//echo"<div class=\"marginCR\">Compte Rendu&nbsp;<a id=\"v_slidein".$numFen."\" href=\"#\"> Afficher </a>|<a id=\"v_slideout".$numFen."\" href=\"#\"> Fermer </a>| <strong>status</strong>: <span id=\"vertical_status".$numFen."\">open</span>				</div>";
			echo"<div id=\"vertical_slide".$numFen."\">".$CRexecution." ".$messageGen."</div>";
		} // end of statement else of  if ( $ErreurProcess)

	

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
		logWriteTo(8,"notice","Interruption gestion timeout pour la table ".$tableEnLecture." et Id = ".$IDEnLecture,"","","0");
		// test
		echo "<div id=\"".$nomFenetre."_img\"><img src=\"/assets/dep.png\" alt=\"\"/></div><div id=\"".$nomFenetre."_txt\">Table ".$tableEnLecture." (".$_SESSION['s_cpt_table_total']." sur ".$NbrTableAlire." ) / enreg. ".$cptChampTotal." sur ".$totalLignes." <br/>".$nomAction." en cours (execution en ".$delai." time maxi = ".$max_time.") </div>";
		echo "<form id=\"formtest\"> 
		<input id=\"nomtable\" 	type=\"hidden\" value=\"".$tableEnLecture."\"/>
		<input id=\"numID\" 	type=\"hidden\" value=\"".$IDEnLecture."\"/>
		<input id=\"numproc\" 	type=\"hidden\" value=\"".$numProcess."\"/>
		<input id=\"execsql\" 	type=\"hidden\" value=\"".$ExecSQL."\"/>
		</form>";
	}

?>