--
-- PostgreSQL database dump
--

-- Started on 2008-11-06 15:34:31 CEST

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET search_path = public, pg_catalog;

--
-- Name: art_activite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_activite (
    id integer DEFAULT nextval(('"public"."art_activite_id_seq"'::text)::regclass) NOT NULL,
    art_unite_peche_id integer,
    art_agglomeration_id integer,
    art_type_sortie_id integer,
    art_grand_type_engin_id character varying(10),
    art_millieu_id integer,
    date_activite date,
    nbre_unite_recencee integer,
    annee integer,
    mois integer,
    code integer,
    nbre_hommes integer,
    nbre_femmes integer,
    nbre_enfants integer,
    art_type_activite_id character varying(10)
);


--
-- Name: art_artivite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_artivite_pkey PRIMARY KEY (id);


--
-- Name: art_activite_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_activite_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_activite_art_millieu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_millieu_id_fkey FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_activite_art_type_activite_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_type_activite_id_fkey FOREIGN KEY (art_type_activite_id) REFERENCES art_type_activite(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_activite_art_type_sortie_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_type_sortie_id_fkey FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_activite_art_unite_peche_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_unite_peche_id_fkey FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_debarquement; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_debarquement (
    id integer DEFAULT nextval(('"public"."art_debarquement_id_seq"'::text)::regclass) NOT NULL,
    art_millieu_id integer,
    art_vent_id integer,
    art_etat_ciel_id integer,
    art_agglomeration_id integer,
    art_lieu_de_peche_id integer,
    art_unite_peche_id integer,
    art_grand_type_engin_id character varying(10),
    art_type_sortie_id integer,
    date_depart date,
    heure_depart time without time zone,
    heure time without time zone,
    heure_pose_engin time without time zone,
    nbre_coups_de_peche integer,
    poids_total real,
    glaciere integer,
    distance_lieu_peche real,
    annee integer,
    mois integer,
    memo text,
    code integer,
    nbre_hommes integer,
    nbre_femmes integer,
    nbre_enfants integer,
    date_debarquement date
);


--
-- Name: art_debarquement_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_pkey PRIMARY KEY (id);


--
-- Name: art_debarquement_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_etat_ciel_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_etat_ciel_id_fkey FOREIGN KEY (art_etat_ciel_id) REFERENCES art_etat_ciel(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_lieu_de_peche_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_lieu_de_peche_id_fkey FOREIGN KEY (art_lieu_de_peche_id) REFERENCES art_lieu_de_peche(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_millieu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_millieu_id_fkey FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_type_sortie_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_type_sortie_id_fkey FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_unite_peche_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_unite_peche_id_fkey FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_debarquement_art_vent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_art_vent_id_fkey FOREIGN KEY (art_vent_id) REFERENCES art_vent(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_engin_activite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_engin_activite (
    id integer DEFAULT nextval(('"public"."art_engin_activite_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    nbre integer,
    art_activite_id integer,
    art_type_engin_id character varying(10)
);


--
-- Name: art_engin_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_pkey PRIMARY KEY (id);


--
-- Name: art_engin_activite_art_activite_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_art_activite_id_fkey FOREIGN KEY (art_activite_id) REFERENCES art_activite(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_engin_activite_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_engin_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_engin_peche (
    id integer DEFAULT nextval(('"public"."art_engin_peche_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    longueur integer,
    hauteur real,
    nbre_nap real,
    nombre real,
    nbre_eff integer,
    nbre_mail_ham real,
    num_ham integer,
    proprietaire integer,
    art_type_engin_id character varying(10),
    art_debarquement_id integer
);


--
-- Name: art_engin_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_pkey PRIMARY KEY (id);


--
-- Name: art_engin_peche_art_debarquement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_art_debarquement_id_fkey FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_engin_peche_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_fraction; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_fraction (
    id character varying(15) NOT NULL,
    code integer,
    poids real,
    nbre_poissons integer,
    debarquee integer,
    ref_espece_id character varying(10),
    art_debarquement_id integer,
    prix double precision,
    CONSTRAINT art_fraction_debarquee_check CHECK (((debarquee = 0) OR (debarquee = 1)))
);


--
-- Name: art_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_pkey PRIMARY KEY (id);


--
-- Name: art_fraction_art_debarquement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_art_debarquement_id_fkey FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_fraction_ref_espece_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_ref_espece_id_fkey FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_fraction_rec; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_fraction_rec (
    id character varying(20) NOT NULL,
    poids real NOT NULL,
    nbre_poissons integer NOT NULL,
    ref_espece_id character varying(10) NOT NULL
);


--
-- Name: art_fraction_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_pkey PRIMARY KEY (id);

--
-- Name: art_lieu_de_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_lieu_de_peche (
    id integer DEFAULT nextval(('"public"."art_lieu_de_peche_id_seq"'::text)::regclass) NOT NULL,
    ref_secteur_id integer,
    libelle character varying(50),
    code integer
);


--
-- Name: art_lieu_de_peche_id_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_id_pkey PRIMARY KEY (id);


--
-- Name: art_lieu_de_peche_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_poisson_mesure; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_poisson_mesure (
    id integer DEFAULT nextval(('"public"."art_poisson_mesure_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    taille integer,
    art_fraction_id character varying(15)
);


--
-- Name: art_poisson_mesure_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_pkey PRIMARY KEY (id);


--
-- Name: art_poisson_mesure_art_fraction_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_art_fraction_id_fkey FOREIGN KEY (art_fraction_id) REFERENCES art_fraction(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_gt; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_stat_gt (
    id integer NOT NULL,
    obs_gt_min real,
    obs_gt_max real,
    pue_gt_ecart_type real,
    pue_gt real,
    fpe_gt real,
    fm_gt real,
    cap_gt real,
    art_grand_type_engin_id character varying(10) NOT NULL,
    art_stat_totale_id integer NOT NULL,
    nbre_enquete_gt integer
);


--
-- Name: art_stat_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_pkey PRIMARY KEY (id);


--
-- Name: art_stat_gt_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_stat_gt_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_gt_sp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_stat_gt_sp (
    id integer NOT NULL,
    obs_gt_sp_min real,
    obs_gt_sp_max real,
    pue_gt_sp_ecart_type real,
    pue_gt_sp real,
    cap_gt_sp real,
    ref_espece_id character varying(10) NOT NULL,
    art_stat_gt_id integer NOT NULL,
    nbre_enquete_gt_sp integer
);


--
-- Name: art_stat_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_pkey PRIMARY KEY (id);

--
-- Name: art_stat_gt_sp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_stat_gt_sp (
    id integer NOT NULL,
    obs_gt_sp_min real,
    obs_gt_sp_max real,
    pue_gt_sp_ecart_type real,
    pue_gt_sp real,
    cap_gt_sp real,
    ref_espece_id character varying(10) NOT NULL,
    art_stat_gt_id integer NOT NULL,
    nbre_enquete_gt_sp integer
);


--
-- Name: art_stat_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_pkey PRIMARY KEY (id);

--
-- Name: art_stat_totale; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_stat_totale (
    id integer NOT NULL,
    annee integer NOT NULL,
    mois integer NOT NULL,
    nbre_obs integer,
    obs_min real,
    obs_max real,
    pue_ecart_type real,
    pue real,
    fpe real,
    fm real,
    cap real,
    art_agglomeration_id integer NOT NULL,
    nbre_unite_recensee_periode integer,
    nbre_jour_activite integer
);


--
-- Name: art_stat_totale_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_pkey PRIMARY KEY (id);


--
-- Name: art_stat_totale_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_totale; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_stat_totale (
    id integer NOT NULL,
    annee integer NOT NULL,
    mois integer NOT NULL,
    nbre_obs integer,
    obs_min real,
    obs_max real,
    pue_ecart_type real,
    pue real,
    fpe real,
    fm real,
    cap real,
    art_agglomeration_id integer NOT NULL,
    nbre_unite_recensee_periode integer,
    nbre_jour_activite integer
);


--
-- Name: art_stat_totale_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_pkey PRIMARY KEY (id);


--
-- Name: art_stat_totale_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_taille_sp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_taille_sp (
    id integer NOT NULL,
    li character varying(10),
    xi integer,
    art_stat_sp_id integer NOT NULL
);


--
-- Name: art_taille_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_pkey PRIMARY KEY (id);


--
-- Name: art_taille_sp_art_stat_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_art_stat_sp_id_fkey FOREIGN KEY (art_stat_sp_id) REFERENCES art_stat_sp(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_biologie; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_biologie (
    id integer NOT NULL,
    longueur integer,
    longueur_totale integer,
    poids integer,
    exp_sexe_id character varying(10),
    exp_stade_id integer,
    exp_remplissage_id character varying(10),
    exp_fraction_id integer,
    memo text,
    valeur_estimee integer,
    CONSTRAINT exp_biologie_poids_estime_check CHECK (((valeur_estimee = 0) OR (valeur_estimee = 1)))
);


--
-- Name: ref_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT ref_biologie_pkey PRIMARY KEY (id);


--
-- Name: exp_biologie_exp_fraction_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_biologie_exp_fraction_id_fkey FOREIGN KEY (exp_fraction_id) REFERENCES exp_fraction(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_biologie_exp_remplissage_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_biologie_exp_remplissage_id_fkey FOREIGN KEY (exp_remplissage_id) REFERENCES exp_remplissage(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_biologie_exp_sexe_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_biologie_exp_sexe_id_fkey FOREIGN KEY (exp_sexe_id) REFERENCES exp_sexe(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_biologie_exp_stade_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_biologie_exp_stade_id_fkey FOREIGN KEY (exp_stade_id) REFERENCES exp_stade(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_campagne; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_campagne (
    id integer DEFAULT nextval(('"public"."exp_campagne_id_seq"'::text)::regclass) NOT NULL,
    ref_systeme_id integer,
    numero_campagne integer,
    date_debut date,
    date_fin date,
    libelle character varying(100)
);


--
-- Name: exp_campagne_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_pkey PRIMARY KEY (id);


--
-- Name: exp_campagne_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_coup_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_coup_peche (
    id integer DEFAULT nextval(('"public"."exp_cp_peche_id_seq"'::text)::regclass) NOT NULL,
    date_cp date,
    longitude character varying(50),
    latitude character varying(50),
    memo text,
    profondeur real,
    exp_qualite_id integer,
    exp_campagne_id integer,
    exp_station_id character varying(10),
    numero_filet integer,
    numero_coup integer,
    exp_engin_id character varying(10),
    protocole integer,
    heure_debut time without time zone,
    heure_fin time without time zone,
    exp_environnement_id integer
);


--
-- Name: exp_cp_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_cp_peche_pkey PRIMARY KEY (id);


--
-- Name: exp_coup_peche_exp_campagne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_campagne_id_fkey FOREIGN KEY (exp_campagne_id) REFERENCES exp_campagne(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_coup_peche_exp_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_engin_id_fkey FOREIGN KEY (exp_engin_id) REFERENCES exp_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_coup_peche_exp_environnement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_environnement_id_fkey FOREIGN KEY (exp_environnement_id) REFERENCES exp_environnement(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_coup_peche_exp_qualite_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_qualite_id_fkey FOREIGN KEY (exp_qualite_id) REFERENCES exp_qualite(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_coup_peche_exp_station_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_station_id_fkey FOREIGN KEY (exp_station_id) REFERENCES exp_station(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_environnement; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_environnement (
    id integer NOT NULL,
    transparence real,
    salinite_surface real,
    salinite_fond real,
    temperature_surface real,
    temperature_fond real,
    oxygene_surface real,
    oxygene_fond real,
    chlorophylle_surface real,
    chlorophylle_fond real,
    mot_surface real,
    mop_surface real,
    mot_fond real,
    mop_fond real,
    conductivite_surface real,
    conductivite_fond real,
    memo text,
    exp_force_courant_id integer,
    exp_sens_courant_id integer
);


--
-- Name: exp_environnement_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_pkey PRIMARY KEY (id);


--
-- Name: exp_environnement_exp_force_courant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_exp_force_courant_id_fkey FOREIGN KEY (exp_force_courant_id) REFERENCES exp_force_courant(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_environnement_exp_sens_courant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_exp_sens_courant_id_fkey FOREIGN KEY (exp_sens_courant_id) REFERENCES exp_sens_courant(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_fraction; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_fraction (
    id integer NOT NULL,
    nombre_total integer,
    poids_total integer,
    memo text,
    ref_espece_id character varying(10),
    exp_coup_peche_id integer,
    nombre_estime integer,
    CONSTRAINT exp_fraction_nombre_estime_check CHECK (((nombre_estime = 0) OR (nombre_estime = 1)))
);


--
-- Name: ref_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_fraction_pkey PRIMARY KEY (id);


--
-- Name: exp_fraction_exp_coup_peche_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT exp_fraction_exp_coup_peche_id_fkey FOREIGN KEY (exp_coup_peche_id) REFERENCES exp_coup_peche(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_fraction_ref_espece_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT exp_fraction_ref_espece_id_fkey FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_trophique; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_trophique (
    id integer DEFAULT nextval(('"public"."exp_contenu_biologie_id_seq"'::text)::regclass) NOT NULL,
    exp_biologie_id integer NOT NULL,
    exp_contenu_id integer NOT NULL,
    quantite real
);


--
-- Name: exp_contenu_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_biologie_pkey PRIMARY KEY (id);


--
-- Name: exp_trophique_exp_biologie_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_trophique_exp_biologie_id_fkey FOREIGN KEY (exp_biologie_id) REFERENCES exp_biologie(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_trophique_exp_contenu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_trophique_exp_contenu_id_fkey FOREIGN KEY (exp_contenu_id) REFERENCES exp_contenu(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: pg_ts_dict; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE pg_ts_dict (
    dict_name text NOT NULL,
    dict_init regprocedure,
    dict_initoption text,
    dict_lexize regprocedure NOT NULL,
    dict_comment text
);

--
-- Name: pg_ts_parser; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE pg_ts_parser (
    prs_name text NOT NULL,
    prs_start regprocedure NOT NULL,
    prs_nexttoken regprocedure NOT NULL,
    prs_end regprocedure NOT NULL,
    prs_headline regprocedure NOT NULL,
    prs_lextype regprocedure NOT NULL,
    prs_comment text
);

--
-- Name: sys_activites_a_migrer; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE sys_activites_a_migrer (
    pays character varying(10),
    systeme integer,
    secteur integer,
    agglomeration integer,
    activite_source integer,
    activite_cible integer,
    id integer NOT NULL,
    base_pays character varying(50)
);


--
-- Name: sys_activites_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sys_activites_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE sys_activites_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_activites_a_migrer_id_seq'::regclass);

--
-- Name: sys_campagnes_a_migrer; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE sys_campagnes_a_migrer (
    pays character varying(10),
    systeme integer,
    campagne_source bigint,
    campagne_cible bigint,
    id integer NOT NULL
);


--
-- Name: sys_campagnes_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sys_campagnes_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE sys_campagnes_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_campagnes_a_migrer_id_seq'::regclass);

--
-- Name: sys_debarquements_a_migrer; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE sys_debarquements_a_migrer (
    pays character varying(10),
    systeme integer,
    secteur integer,
    agglomeration integer,
    debarquement_source integer,
    debarquement_cible integer,
    id integer NOT NULL,
    base_pays character varying(50)
);


--
-- Name: sys_debarquements_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sys_debarquements_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE sys_debarquements_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_debarquements_a_migrer_id_seq'::regclass);

--
-- Name: sys_periodes_enquete; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE sys_periodes_enquete (
    id integer NOT NULL,
    pays_id character varying(10),
    systeme_id integer,
    secteur_id integer,
    agglomeration_id integer,
    date_debut date,
    date_fin date,
    base_pays character varying(50)
);


--
-- Name: sys_periodes_enquete_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sys_periodes_enquete_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE sys_periodes_enquete ALTER COLUMN id SET DEFAULT nextval('sys_periodes_enquete_id_seq'::regclass);



--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO devppeao WITH GRANT OPTION;
GRANT ALL ON SCHEMA public TO PUBLIC;
