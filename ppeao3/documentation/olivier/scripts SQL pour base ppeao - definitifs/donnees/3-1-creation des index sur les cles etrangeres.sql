
-- creation des index sur les colonnes utilisees comme cles etrangeres
CREATE INDEX art_activite_art_agglomeration_id_index ON art_activite (art_agglomeration_id);
CREATE INDEX art_agglomeration_art_type_agglomeration_id_index ON art_agglomeration (art_type_agglomeration_id);
CREATE INDEX art_debarquement_art_agglomeration_id_index ON art_debarquement (art_agglomeration_id);
CREATE INDEX art_engin_activite_art_activite_id_index ON art_engin_activite (art_activite_id);
CREATE INDEX art_engin_peche_art_debarquement_id_index ON art_engin_peche (art_debarquement_id);
CREATE INDEX art_fraction_art_debarquement_id_index ON art_fraction (art_debarquement_id);
CREATE INDEX art_lieu_de_peche_ref_secteur_id_index ON art_lieu_de_peche (ref_secteur_id);
CREATE INDEX art_poisson_mesure_art_fraction_id_index ON art_poisson_mesure (art_fraction_id);
CREATE INDEX art_type_engin_art_grand_type_engin_id_index ON art_type_engin (art_grand_type_engin_id);
CREATE INDEX art_unite_peche_art_agglomeration_id_index ON art_unite_peche (art_agglomeration_id);
CREATE INDEX exp_biologie_exp_fraction_id_index ON exp_biologie (exp_fraction_id);
CREATE INDEX exp_campagne_ref_systeme_id_index ON exp_campagne (ref_systeme_id);
CREATE INDEX exp_coup_peche_exp_campagne_id_index ON exp_coup_peche (exp_campagne_id);
CREATE INDEX exp_environnement_exp_force_courant_id_index ON exp_environnement (exp_force_courant_id);
CREATE INDEX exp_fraction_exp_coup_peche_id_index ON exp_fraction (exp_coup_peche_id);
CREATE INDEX exp_station_exp_debris_id_index ON exp_station (exp_debris_id);
CREATE INDEX exp_trophique_exp_biologie_id_index ON exp_trophique (exp_biologie_id);
CREATE INDEX ref_espece_ref_categorie_ecologique_id_index ON ref_espece (ref_categorie_ecologique_id);
CREATE INDEX ref_famille_ref_ordre_id_index ON ref_famille (ref_ordre_id);
CREATE INDEX ref_secteur_ref_systeme_id_index ON ref_secteur (ref_systeme_id);
CREATE INDEX ref_systeme_ref_pays_id_index ON ref_systeme (ref_pays_id);