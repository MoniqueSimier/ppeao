--
-- Name: ar_origine_kb_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_origine_kb
    ADD CONSTRAINT ar_origine_kb_pkey PRIMARY KEY (id);


--
-- Name: art_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_agglomeration_pkey PRIMARY KEY (id);


--
-- Name: art_artivite_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_artivite_pkey PRIMARY KEY (id);


--
-- Name: art_categorie_socio_professionnelle_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_categorie_socio_professionnelle
    ADD CONSTRAINT art_categorie_socio_professionnelle_pkey PRIMARY KEY (id);


--
-- Name: art_debarquement_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_pkey PRIMARY KEY (id);


--
-- Name: art_debarquement_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_pkey PRIMARY KEY (id);


--
-- Name: art_effort_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_gt
    ADD CONSTRAINT art_effort_gt_pkey PRIMARY KEY (id);


--
-- Name: art_effort_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_gt_sp
    ADD CONSTRAINT art_effort_gt_sp_pkey PRIMARY KEY (id);


--
-- Name: art_effort_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_sp
    ADD CONSTRAINT art_effort_sp_pkey PRIMARY KEY (id);


--
-- Name: art_effort_taille_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_taille
    ADD CONSTRAINT art_effort_taille_pkey PRIMARY KEY (id);



--
-- Name: art_engin_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_pkey PRIMARY KEY (id);


--
-- Name: art_engin_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_pkey PRIMARY KEY (id);


--
-- Name: art_etat_ciel_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_etat_ciel
    ADD CONSTRAINT art_etat_ciel_pkey PRIMARY KEY (id);


--
-- Name: art_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_pkey PRIMARY KEY (id);


--
-- Name: art_fraction_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_pkey PRIMARY KEY (id);


--
-- Name: art_grand_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_grand_type_engin
    ADD CONSTRAINT art_grand_type_engin_pkey PRIMARY KEY (id);


--
-- Name: art_lieu_de_peche_id_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_id_pkey PRIMARY KEY (id);


--
-- Name: art_millieu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_millieu
    ADD CONSTRAINT art_millieu_pkey PRIMARY KEY (id);


--
-- Name: art_poisson_mesure_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_pkey PRIMARY KEY (id);


--
-- Name: art_stat_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_pkey PRIMARY KEY (id);


--
-- Name: art_stat_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_pkey PRIMARY KEY (id);


--
-- Name: art_stat_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_pkey PRIMARY KEY (id);


--
-- Name: art_stat_totale_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_pkey PRIMARY KEY (id);


--
-- Name: art_taille_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_pkey PRIMARY KEY (id);


--
-- Name: art_taille_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_pkey PRIMARY KEY (id);


--
-- Name: art_tyepe_sortie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_type_sortie
    ADD CONSTRAINT art_tyepe_sortie_pkey PRIMARY KEY (id);


--
-- Name: art_type_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_type_activite
    ADD CONSTRAINT art_type_activite_pkey PRIMARY KEY (id);


--
-- Name: art_type_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_type_agglomeration
    ADD CONSTRAINT art_type_agglomeration_pkey PRIMARY KEY (id);


--
-- Name: art_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_type_engin
    ADD CONSTRAINT art_type_engin_pkey PRIMARY KEY (id);


--
-- Name: art_unite_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_pkey PRIMARY KEY (id);


--
-- Name: art_vent_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY art_vent
    ADD CONSTRAINT art_vent_pkey PRIMARY KEY (id);


--
-- Name: exp_campagne_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_pkey PRIMARY KEY (id);


--
-- Name: exp_contenu_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_biologie_pkey PRIMARY KEY (id);


--
-- Name: exp_contenu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_contenu
    ADD CONSTRAINT exp_contenu_pkey PRIMARY KEY (id);


--
-- Name: exp_cp_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_cp_peche_pkey PRIMARY KEY (id);


--
-- Name: exp_debris_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_debris
    ADD CONSTRAINT exp_debris_pkey PRIMARY KEY (id);


--
-- Name: exp_environnement_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_pkey PRIMARY KEY (id);


--
-- Name: exp_force_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_force_courant
    ADD CONSTRAINT exp_force_courant_pkey PRIMARY KEY (id);


--
-- Name: exp_position_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_position
    ADD CONSTRAINT exp_position_pkey PRIMARY KEY (id);


--
-- Name: exp_qualite_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_qualite
    ADD CONSTRAINT exp_qualite_pkey PRIMARY KEY (id);


--
-- Name: exp_remplissage_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_remplissage
    ADD CONSTRAINT exp_remplissage_pkey PRIMARY KEY (id);


--
-- Name: exp_sediment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_sediment
    ADD CONSTRAINT exp_sediment_pkey PRIMARY KEY (id);


--
-- Name: exp_sens_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_sens_courant
    ADD CONSTRAINT exp_sens_courant_pkey PRIMARY KEY (id);


--
-- Name: exp_sexe_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_sexe
    ADD CONSTRAINT exp_sexe_pkey PRIMARY KEY (id);


--
-- Name: exp_stade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_stade
    ADD CONSTRAINT exp_stade_pkey PRIMARY KEY (id);


--
-- Name: exp_station_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_pkey PRIMARY KEY (id);


--
-- Name: exp_vegetation_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_vegetation
    ADD CONSTRAINT exp_vegetation_pkey PRIMARY KEY (id);


--
-- Name: pg_ts_cfg_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pg_ts_cfg
    ADD CONSTRAINT pg_ts_cfg_pkey PRIMARY KEY (ts_name);


--
-- Name: pg_ts_cfgmap_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pg_ts_cfgmap
    ADD CONSTRAINT pg_ts_cfgmap_pkey PRIMARY KEY (ts_name, tok_alias);


--
-- Name: pg_ts_dict_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pg_ts_dict
    ADD CONSTRAINT pg_ts_dict_pkey PRIMARY KEY (dict_name);


--
-- Name: pg_ts_parser_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pg_ts_parser
    ADD CONSTRAINT pg_ts_parser_pkey PRIMARY KEY (prs_name);


--
-- Name: ref_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT ref_biologie_pkey PRIMARY KEY (id);


--
-- Name: ref_categorie_ecologique_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_ecologique
    ADD CONSTRAINT ref_categorie_ecologique_pkey PRIMARY KEY (id);


--
-- Name: ref_categorie_trophique_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_trophique
    ADD CONSTRAINT ref_categorie_trophique_pkey PRIMARY KEY (id);


--
-- Name: ref_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_engin
    ADD CONSTRAINT ref_engin_pkey PRIMARY KEY (id);


--
-- Name: ref_espece_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_pkey PRIMARY KEY (id);


--
-- Name: ref_famille_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_famille
    ADD CONSTRAINT ref_famille_pkey PRIMARY KEY (id);


--
-- Name: ref_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_fraction_pkey PRIMARY KEY (id);


--
-- Name: ref_ordre_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_ordre
    ADD CONSTRAINT ref_ordre_pkey PRIMARY KEY (id);


--
-- Name: ref_pays_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_pays
    ADD CONSTRAINT ref_pays_pkey PRIMARY KEY (id);


--
-- Name: ref_secteur_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_secteur
    ADD CONSTRAINT ref_secteur_pkey PRIMARY KEY (id);


--
-- Name: ref_systeme_date_butoir_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_systeme_date_butoir
    ADD CONSTRAINT ref_systeme_date_butoir_pkey PRIMARY KEY (id);


--
-- Name: ref_systeme_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ref_systeme
    ADD CONSTRAINT ref_systeme_pkey PRIMARY KEY (id);


--
-- Name: ref_utilisateurs_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_utilisateurs
    ADD CONSTRAINT ref_utilisateurs_pkey PRIMARY KEY ("login");


--
-- Name: sys_activites_a_migrer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY sys_activites_a_migrer
    ADD CONSTRAINT sys_activites_a_migrer_pkey PRIMARY KEY (id);


--
-- Name: sys_campagnes_a_migrer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY sys_campagnes_a_migrer
    ADD CONSTRAINT sys_campagnes_a_migrer_pkey PRIMARY KEY (id);


--
-- Name: sys_debarquements_a_migrer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY sys_debarquements_a_migrer
    ADD CONSTRAINT sys_debarquements_a_migrer_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--