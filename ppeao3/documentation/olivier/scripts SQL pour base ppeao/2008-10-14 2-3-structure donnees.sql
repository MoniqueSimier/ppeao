--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: ppeao_template; Type: COMMENT; Schema: -; Owner: devppeao
--

COMMENT ON DATABASE ppeao_template IS 'modèle de base pour l''application PPEAO';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;


--
-- Name: art_activite; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_activite OWNER TO devppeao;

--
-- Name: art_debarquement; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_debarquement OWNER TO devppeao;

--
-- Name: art_debarquement_rec; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_debarquement_rec (
    id character varying(20) NOT NULL,
    poids_total real NOT NULL,
    art_debarquement_id integer NOT NULL
);


ALTER TABLE public.art_debarquement_rec OWNER TO devppeao;

--
-- Name: art_engin_activite; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_engin_activite (
    id integer DEFAULT nextval(('"public"."art_engin_activite_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    nbre integer,
    art_activite_id integer,
    art_type_engin_id character varying(10)
);


ALTER TABLE public.art_engin_activite OWNER TO devppeao;

--
-- Name: art_engin_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_engin_peche OWNER TO devppeao;

--
-- Name: art_fraction; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_fraction OWNER TO devppeao;

--
-- Name: art_fraction_rec; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_fraction_rec (
    id character varying(20) NOT NULL,
    poids real NOT NULL,
    nbre_poissons integer NOT NULL,
    ref_espece_id character varying(10) NOT NULL
);


ALTER TABLE public.art_fraction_rec OWNER TO devppeao;

--
-- Name: art_lieu_de_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_lieu_de_peche (
    id integer DEFAULT nextval(('"public"."art_lieu_de_peche_id_seq"'::text)::regclass) NOT NULL,
    ref_secteur_id integer,
    libelle character varying(50),
    code integer
);


ALTER TABLE public.art_lieu_de_peche OWNER TO devppeao;

ALTER TABLE public.art_millieu OWNER TO devppeao;

--
-- Name: art_poisson_mesure; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_poisson_mesure (
    id integer DEFAULT nextval(('"public"."art_poisson_mesure_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    taille integer,
    art_fraction_id character varying(15)
);


ALTER TABLE public.art_poisson_mesure OWNER TO devppeao;

--
-- Name: art_stat_gt; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_stat_gt OWNER TO devppeao;

--
-- Name: art_stat_gt_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_stat_gt_sp OWNER TO devppeao;

--
-- Name: art_stat_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_stat_sp (
    id integer NOT NULL,
    obs_sp_min real,
    obs_sp_max real,
    pue_sp_ecart_type real,
    pue_sp real,
    cap_sp real,
    ref_espece_id character varying(10) NOT NULL,
    art_stat_totale_id integer NOT NULL,
    nbre_enquete_sp integer
);


ALTER TABLE public.art_stat_sp OWNER TO devppeao;

--
-- Name: art_stat_totale; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.art_stat_totale OWNER TO devppeao;

--
-- Name: art_taille_gt_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_taille_gt_sp (
    id integer NOT NULL,
    li character varying(10),
    xi real,
    art_stat_gt_sp_id integer NOT NULL
);


ALTER TABLE public.art_taille_gt_sp OWNER TO devppeao;

--
-- Name: art_taille_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_taille_sp (
    id integer NOT NULL,
    li character varying(10),
    xi integer,
    art_stat_sp_id integer NOT NULL
);


ALTER TABLE public.art_taille_sp OWNER TO devppeao;


--
-- Name: art_unite_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_unite_peche (
    id integer DEFAULT nextval(('"public"."art_unite_peche_id_seq"'::text)::regclass) NOT NULL,
    art_categorie_socio_professionnelle_id integer,
    libelle character varying(50),
    libelle_menage character varying(50),
    code integer,
    art_agglomeration_id integer,
    base_pays character varying(50)
);


ALTER TABLE public.art_unite_peche OWNER TO devppeao;

--
-- Name: exp_biologie; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.exp_biologie OWNER TO devppeao;

--
-- Name: exp_campagne; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_campagne (
    id integer DEFAULT nextval(('"public"."exp_campagne_id_seq"'::text)::regclass) NOT NULL,
    ref_systeme_id integer,
    numero_campagne integer,
    date_debut date,
    date_fin date,
    libelle character varying(100)
);


ALTER TABLE public.exp_campagne OWNER TO devppeao;

--
-- Name: exp_contenu; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_contenu (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_contenu OWNER TO devppeao;

--
-- Name: exp_coup_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.exp_coup_peche OWNER TO devppeao;



--
-- Name: exp_environnement; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.exp_environnement OWNER TO devppeao;

--
-- Name: exp_fraction; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.exp_fraction OWNER TO devppeao;

--
-- Name: sys_activites_a_migrer; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.sys_activites_a_migrer OWNER TO devppeao;

--
-- Name: sys_campagnes_a_migrer; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE sys_campagnes_a_migrer (
    pays character varying(10),
    systeme integer,
    campagne_source bigint,
    campagne_cible bigint,
    id integer NOT NULL
);


ALTER TABLE public.sys_campagnes_a_migrer OWNER TO devppeao;

--
-- Name: sys_debarquements_a_migrer; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.sys_debarquements_a_migrer OWNER TO devppeao;

--
-- Name: sys_periodes_enquete; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
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


ALTER TABLE public.sys_periodes_enquete OWNER TO devppeao;



--
-- Name: art_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_activite_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_activite_id_seq OWNER TO postgres;

--
-- Name: art_caracteristiques_unite_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_caracteristiques_unite_peche_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_caracteristiques_unite_peche_id_seq OWNER TO postgres;

--
-- Name: art_condition_physico_chimique_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_condition_physico_chimique_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_condition_physico_chimique_id_seq OWNER TO postgres;

--
-- Name: art_debarquement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_debarquement_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_debarquement_id_seq OWNER TO postgres;

--
-- Name: art_engin_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_activite_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_activite_id_seq OWNER TO postgres;

--
-- Name: art_engin_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_peche_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_peche_id_seq OWNER TO postgres;

--
-- Name: art_enqueteur_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_enqueteur_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_enqueteur_id_seq OWNER TO postgres;


--
-- Name: art_fraction_debarquee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_fraction_debarquee_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_fraction_debarquee_id_seq OWNER TO postgres;

--
-- Name: art_fraction_id1_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_fraction_id1_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_fraction_id1_seq OWNER TO postgres;

--
-- Name: art_fraction_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_fraction_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_fraction_id_seq OWNER TO postgres;

--
-- Name: art_fraction_non_debarquee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_fraction_non_debarquee_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_fraction_non_debarquee_id_seq OWNER TO postgres;

--
-- Name: art_lieu_de_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_lieu_de_peche_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_lieu_de_peche_id_seq OWNER TO postgres;

--
-- Name: art_mode_calcul_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_mode_calcul_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_mode_calcul_id_seq OWNER TO postgres;

--
-- Name: art_poisson_mesure_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_poisson_mesure_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_poisson_mesure_id_seq OWNER TO postgres;


-- Name: art_type_agglomeration_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_type_agglomeration_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_agglomeration_id_seq OWNER TO postgres;

--
-- Name: art_type_engin_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_type_engin_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_engin_id_seq OWNER TO postgres;

--
-- Name: art_type_sortie_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_type_sortie_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_sortie_id_seq OWNER TO postgres;

--
-- Name: art_vent_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_vent_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_vent_id_seq OWNER TO postgres;

--
-- Name: art_village_attache_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_village_attache_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_village_attache_id_seq OWNER TO postgres;

--
-- Name: exp_biologie_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_biologie_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_biologie_id_seq OWNER TO postgres;

--
-- Name: exp_campagne_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_campagne_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_campagne_id_seq OWNER TO postgres;

--
-- Name: exp_contenu_biologie_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_contenu_biologie_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_contenu_biologie_id_seq OWNER TO postgres;

--
-- Name: exp_cp_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_cp_peche_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_cp_peche_id_seq OWNER TO postgres;

--
-- Name: exp_environnement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_environnement_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_environnement_id_seq OWNER TO postgres;

--
-- Name: exp_fraction_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_fraction_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_fraction_id_seq OWNER TO postgres;


--
-- Name: sys_activites_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_activites_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_activites_a_migrer_id_seq OWNER TO devppeao;

--
-- Name: sys_activites_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_activites_a_migrer_id_seq OWNED BY sys_activites_a_migrer.id;


--
-- Name: sys_campagnes_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_campagnes_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_campagnes_a_migrer_id_seq OWNER TO devppeao;

--
-- Name: sys_campagnes_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_campagnes_a_migrer_id_seq OWNED BY sys_campagnes_a_migrer.id;


--
-- Name: sys_debarquements_a_migrer_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_debarquements_a_migrer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_debarquements_a_migrer_id_seq OWNER TO devppeao;

--
-- Name: sys_debarquements_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_debarquements_a_migrer_id_seq OWNED BY sys_debarquements_a_migrer.id;


--
-- Name: sys_groupe_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_groupe_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_groupe_id_seq OWNER TO devppeao;

--
-- Name: sys_periodes_enquete_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_periodes_enquete_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_periodes_enquete_id_seq OWNER TO devppeao;

--
-- Name: sys_periodes_enquete_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_periodes_enquete_id_seq OWNED BY sys_periodes_enquete.id;


--
-- Name: sys_utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE sys_utilisateur_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_utilisateur_id_seq OWNER TO devppeao;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_activites_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_activites_a_migrer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_campagnes_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_campagnes_a_migrer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_debarquements_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_debarquements_a_migrer_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_periodes_enquete ALTER COLUMN id SET DEFAULT nextval('sys_periodes_enquete_id_seq'::regclass);


--
-- Name: art_artivite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_artivite_pkey PRIMARY KEY (id);

--
-- Name: art_debarquement_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_pkey PRIMARY KEY (id);


--
-- Name: art_debarquement_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_pkey PRIMARY KEY (id);


--
-- Name: art_engin_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_pkey PRIMARY KEY (id);


--
-- Name: art_engin_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_pkey PRIMARY KEY (id);


--
-- Name: art_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_pkey PRIMARY KEY (id);


--
-- Name: art_fraction_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_pkey PRIMARY KEY (id);


--
-- Name: art_lieu_de_peche_id_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_id_pkey PRIMARY KEY (id);


--
-- Name: art_poisson_mesure_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
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
-- Name: art_unite_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_pkey PRIMARY KEY (id);

--
-- Name: exp_campagne_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_pkey PRIMARY KEY (id);


--
-- Name: exp_contenu_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_biologie_pkey PRIMARY KEY (id);


--
-- Name: exp_cp_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_cp_peche_pkey PRIMARY KEY (id);


--
-- Name: exp_environnement_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_pkey PRIMARY KEY (id);


--
-- Name: exp_position_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_position
    ADD CONSTRAINT exp_position_pkey PRIMARY KEY (id);


--
-- Name: ref_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_fraction_pkey PRIMARY KEY (id);


--
-- Name: art_activite_art_type_activite_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_type_activite_id_fkey FOREIGN KEY (art_type_activite_id) REFERENCES art_type_activite(id);


--
-- Name: art_activite_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_activite_id FOREIGN KEY (art_activite_id) REFERENCES art_activite(id);


--
-- Name: art_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_agglomeration_id FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- Name: art_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_agglomeration_id FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- Name: art_categorie_socio_professionnelle_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_categorie_socio_professionnelle_id FOREIGN KEY (art_categorie_socio_professionnelle_id) REFERENCES art_categorie_socio_professionnelle(id);


--
-- Name: art_debarquement_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_debarquement_id FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- Name: art_debarquement_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_debarquement_id FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- Name: art_debarquement_rec_art_debarquement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_art_debarquement_id_fkey FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- Name: art_engin_activite_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id);


--
-- Name: art_engin_peche_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id);


--
-- Name: art_etat_ciel_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_etat_ciel_id FOREIGN KEY (art_etat_ciel_id) REFERENCES art_etat_ciel(id);


--
-- Name: art_fraction_rec_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_id_fkey FOREIGN KEY (id) REFERENCES art_fraction(id);


--
-- Name: art_grand_type_engin_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_grand_type_engin_id FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id);


--
-- Name: art_grand_type_engin_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_grand_type_engin_id FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id);


--
-- Name: art_lieu_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_lieu_peche_id FOREIGN KEY (art_lieu_de_peche_id) REFERENCES art_lieu_de_peche(id);


--
-- Name: art_millieu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_millieu_id FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id);


--
-- Name: art_millieu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_millieu_id FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id);


--
-- Name: art_poisson_mesure_art_fraction_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_art_fraction_id_fkey FOREIGN KEY (art_fraction_id) REFERENCES art_fraction(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_stat_gt_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id);


--
-- Name: art_stat_gt_sp_art_stat_gt_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_art_stat_gt_id_fkey FOREIGN KEY (art_stat_gt_id) REFERENCES art_stat_gt(id);


--
-- Name: art_stat_sp_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id);


--
-- Name: art_stat_totale_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- Name: art_taille_gt_sp_art_stat_gt_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_art_stat_gt_sp_id_fkey FOREIGN KEY (art_stat_gt_sp_id) REFERENCES art_stat_gt_sp(id);


--
-- Name: art_taille_sp_art_stat_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_art_stat_sp_id_fkey FOREIGN KEY (art_stat_sp_id) REFERENCES art_stat_sp(id);


--
-- Name: art_type_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_type_agglomeration_id FOREIGN KEY (art_type_agglomeration_id) REFERENCES art_type_agglomeration(id);


--
-- Name: art_type_sortie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_type_sortie_id FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id);


--
-- Name: art_type_sortie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_type_sortie_id FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id);


--
-- Name: art_unite_peche_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- Name: art_unite_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_unite_peche_id FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id);


--
-- Name: art_unite_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_unite_peche_id FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id);


--
-- Name: art_vent_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_vent_id FOREIGN KEY (art_vent_id) REFERENCES art_vent(id);


--
-- Name: exp_biologie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_biologie_id FOREIGN KEY (exp_biologie_id) REFERENCES exp_biologie(id);


--
-- Name: exp_campagne_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_campagne_id FOREIGN KEY (exp_campagne_id) REFERENCES exp_campagne(id);


--
-- Name: exp_contenu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_id FOREIGN KEY (exp_contenu_id) REFERENCES exp_contenu(id);


--
-- Name: exp_coup_peche_exp_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_engin_id_fkey FOREIGN KEY (exp_engin_id) REFERENCES exp_engin(id);


--
-- Name: exp_coup_peche_exp_environnement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_environnement_id_fkey FOREIGN KEY (exp_environnement_id) REFERENCES exp_environnement(id);


--
-- Name: exp_cp_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT exp_cp_peche_id FOREIGN KEY (exp_coup_peche_id) REFERENCES exp_coup_peche(id);



--
-- Name: exp_force_courant_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_force_courant_id FOREIGN KEY (exp_force_courant_id) REFERENCES exp_force_courant(id);


--
-- Name: exp_fraction_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_fraction_id FOREIGN KEY (exp_fraction_id) REFERENCES exp_fraction(id);



--
-- Name: exp_qualite_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_qualite_id FOREIGN KEY (exp_qualite_id) REFERENCES exp_qualite(id);


--
-- Name: exp_remplissage_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_remplissage_id FOREIGN KEY (exp_remplissage_id) REFERENCES exp_remplissage(id);


--
-- Name: exp_sexe_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_sexe_id FOREIGN KEY (exp_sexe_id) REFERENCES exp_sexe(id);


--
-- Name: exp_stade_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_stade_id FOREIGN KEY (exp_stade_id) REFERENCES exp_stade(id);


--
-- Name: exp_station_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_station_id FOREIGN KEY (exp_station_id) REFERENCES exp_station(id);

--
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id);

--
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id);


--
-- Name: ref_secteur_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT ref_secteur_id FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id);


--
-- Name: ref_systeme_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT ref_systeme_id FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO devppeao WITH GRANT OPTION;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

