--
-- PostgreSQL database dump
--

-- Started on 2008-10-13 15:34:31 CEST

SET client_encoding = 'LATIN9';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;




SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1797 (class 1259 OID 33316)
-- Dependencies: 6
-- Name: admin_j_group_zone; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_group_zone (
    group_id integer NOT NULL,
    zone_id integer NOT NULL
);


ALTER TABLE public.admin_j_group_zone OWNER TO devppeao;

--
-- TOC entry 2315 (class 0 OID 0)
-- Dependencies: 1797
-- Name: TABLE admin_j_group_zone; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_group_zone IS 'table indiquant à quelles zones ont accès les différents groupes';


--
-- TOC entry 2316 (class 0 OID 0)
-- Dependencies: 1797
-- Name: COLUMN admin_j_group_zone.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_group_zone.group_id IS 'l''id du groupe';


--
-- TOC entry 2317 (class 0 OID 0)
-- Dependencies: 1797
-- Name: COLUMN admin_j_group_zone.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_group_zone.zone_id IS 'l''id de la zone à laquelle le groupe a accès';


--
-- TOC entry 1793 (class 1259 OID 33266)
-- Dependencies: 6
-- Name: admin_j_user_group; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.admin_j_user_group OWNER TO devppeao;

--
-- TOC entry 2318 (class 0 OID 0)
-- Dependencies: 1793
-- Name: TABLE admin_j_user_group; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_user_group IS 'table de jointure entre un utilisateur et les groupes dont il fait partie';


--
-- TOC entry 2319 (class 0 OID 0)
-- Dependencies: 1793
-- Name: COLUMN admin_j_user_group.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_group.user_id IS 'id de l''utilisateur';


--
-- TOC entry 2320 (class 0 OID 0)
-- Dependencies: 1793
-- Name: COLUMN admin_j_user_group.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_group.group_id IS 'id du groupe auquel l''utilisateur appartient';


--
-- TOC entry 1796 (class 1259 OID 33295)
-- Dependencies: 6
-- Name: admin_j_user_zone; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_user_zone (
    user_id integer NOT NULL,
    zone_id integer NOT NULL
);


ALTER TABLE public.admin_j_user_zone OWNER TO devppeao;

--
-- TOC entry 2322 (class 0 OID 0)
-- Dependencies: 1796
-- Name: TABLE admin_j_user_zone; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_user_zone IS 'à quelle(s) zone(s) un utilisateur a accès.';


--
-- TOC entry 2323 (class 0 OID 0)
-- Dependencies: 1796
-- Name: COLUMN admin_j_user_zone.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_zone.user_id IS 'ID de l''utilisateur';


--
-- TOC entry 2324 (class 0 OID 0)
-- Dependencies: 1796
-- Name: COLUMN admin_j_user_zone.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_zone.zone_id IS 'ID de la zone à laquelle il a accès';


--
-- TOC entry 1783 (class 1259 OID 25048)
-- Dependencies: 6
-- Name: admin_log; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_log (
    log_module_id integer NOT NULL,
    log_script_file character varying(255),
    log_message text NOT NULL,
    log_user_id integer NOT NULL,
    log_action_do text,
    log_action_undo text,
    log_time character varying(19) NOT NULL,
    log_message_type character varying(255)
);


ALTER TABLE public.admin_log OWNER TO devppeao;

--
-- TOC entry 2325 (class 0 OID 0)
-- Dependencies: 1783
-- Name: TABLE admin_log; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log IS 'cette table stocke les entrées du journal de l''application PPEAO';


--
-- TOC entry 2326 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_module_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_module_id IS 'le module de l''application qui écrit le message (table admin_log_modules)';


--
-- TOC entry 2327 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_script_file; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_script_file IS 'le chemin du script PHP qui a déclenché l''écriture ans le journal';


--
-- TOC entry 2328 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_message; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_message IS 'le message à écrire dans le journal';


--
-- TOC entry 2329 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_user_id IS 'l''id de l''utilisateur connecté qui a provoqué l''écriture dans le journal (table admin_users)';


--
-- TOC entry 2330 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_action_do; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_action_do IS 'l''action qui est réalisée (syntaxe SQL dans le cas d''une opération sur la base)';


--
-- TOC entry 2331 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_action_undo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_action_undo IS 'l''action permettant d''annuler l''actionDo (syntaxe SQL "inverse" dans le cas d''une opération sur la base)';


--
-- TOC entry 2332 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_time; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_time IS 'le timestamp de l''entrée du journal';


--
-- TOC entry 2333 (class 0 OID 0)
-- Dependencies: 1783
-- Name: COLUMN admin_log.log_message_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_message_type IS 'le type de message (table admin_log_message_types)';


--
-- TOC entry 1788 (class 1259 OID 33240)
-- Dependencies: 6
-- Name: admin_log_message_types; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_log_message_types (
    message_type character varying(255) NOT NULL
);


ALTER TABLE public.admin_log_message_types OWNER TO devppeao;

--
-- TOC entry 2334 (class 0 OID 0)
-- Dependencies: 1788
-- Name: TABLE admin_log_message_types; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log_message_types IS 'contient la liste des types de messages du journal';


--
-- TOC entry 1785 (class 1259 OID 25056)
-- Dependencies: 6
-- Name: admin_log_modules; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_log_modules (
    module_id integer NOT NULL,
    module_name character varying(255) NOT NULL
);


ALTER TABLE public.admin_log_modules OWNER TO devppeao;

--
-- TOC entry 2335 (class 0 OID 0)
-- Dependencies: 1785
-- Name: TABLE admin_log_modules; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log_modules IS 'liste des modules de l''application PPEAO, utilisés pour identifier la source des entrées du journal';


--
-- TOC entry 2336 (class 0 OID 0)
-- Dependencies: 1785
-- Name: COLUMN admin_log_modules.module_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log_modules.module_id IS 'id unique du module';


--
-- TOC entry 2337 (class 0 OID 0)
-- Dependencies: 1785
-- Name: COLUMN admin_log_modules.module_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log_modules.module_name IS 'le nom du module';


--
-- TOC entry 1790 (class 1259 OID 33252)
-- Dependencies: 2090 6
-- Name: admin_usergroups; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_usergroups (
    group_id integer NOT NULL,
    group_name character varying(255) NOT NULL,
    group_description text,
    group_active boolean DEFAULT true
);


ALTER TABLE public.admin_usergroups OWNER TO devppeao;

--
-- TOC entry 2338 (class 0 OID 0)
-- Dependencies: 1790
-- Name: TABLE admin_usergroups; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_usergroups IS 'table contenant les groupes d''utilisateurs';


--
-- TOC entry 2339 (class 0 OID 0)
-- Dependencies: 1790
-- Name: COLUMN admin_usergroups.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_id IS 'identifiant unique du groupe';


--
-- TOC entry 2340 (class 0 OID 0)
-- Dependencies: 1790
-- Name: COLUMN admin_usergroups.group_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_name IS 'le nom du groupe';


--
-- TOC entry 2341 (class 0 OID 0)
-- Dependencies: 1790
-- Name: COLUMN admin_usergroups.group_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_description IS 'description du groupe';


--
-- TOC entry 2342 (class 0 OID 0)
-- Dependencies: 1790
-- Name: COLUMN admin_usergroups.group_active; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_active IS 'le groupe est-il actif ou désactivé?';


--
-- TOC entry 1787 (class 1259 OID 25069)
-- Dependencies: 2088 6
-- Name: admin_users; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_users (
    user_id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    user_longname character varying(255),
    user_creation_date character varying(19) NOT NULL,
    user_active boolean DEFAULT true NOT NULL,
    user_comment text,
    user_email character varying(255),
    user_password character varying(255)
);


ALTER TABLE public.admin_users OWNER TO devppeao;

--
-- TOC entry 2344 (class 0 OID 0)
-- Dependencies: 1787
-- Name: TABLE admin_users; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_users IS 'cette table contient les utilisateurs enregistrés';


--
-- TOC entry 2345 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_id IS 'id unique de l''utilisateur';


--
-- TOC entry 2346 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_name IS 'nom court d''utilisateur ("login")';


--
-- TOC entry 2347 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_longname; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_longname IS 'nom complet de l''utilisateur ("paul hair")';


--
-- TOC entry 2348 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_creation_date; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_creation_date IS 'timestamp de la création de l''utilisateur';


--
-- TOC entry 2349 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_active; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_active IS 'true si l''utilisateur est activé, false si il est désactivé';


--
-- TOC entry 2350 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_comment; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_comment IS 'commentaire sur l''utilisateur';


--
-- TOC entry 2351 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_email; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_email IS 'adresse email de l''utilisateur';


--
-- TOC entry 2352 (class 0 OID 0)
-- Dependencies: 1787
-- Name: COLUMN admin_users.user_password; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_password IS 'le mot de passe de l''utilisateur, encrypté';


--
-- TOC entry 1795 (class 1259 OID 33287)
-- Dependencies: 6
-- Name: admin_zones; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_zones (
    zone_id integer NOT NULL,
    zone_name character varying(255) NOT NULL,
    zone_description character varying(255)
);


ALTER TABLE public.admin_zones OWNER TO devppeao;

--
-- TOC entry 2353 (class 0 OID 0)
-- Dependencies: 1795
-- Name: TABLE admin_zones; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_zones IS 'liste des "zones" du site, pour contôler l''accès à certaines pages';


--
-- TOC entry 2354 (class 0 OID 0)
-- Dependencies: 1795
-- Name: COLUMN admin_zones.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_id IS 'l''ID unique de la zone';


--
-- TOC entry 2355 (class 0 OID 0)
-- Dependencies: 1795
-- Name: COLUMN admin_zones.zone_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_name IS 'le nom unique de la zone';


--
-- TOC entry 2356 (class 0 OID 0)
-- Dependencies: 1795
-- Name: COLUMN admin_zones.zone_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_description IS 'description de la zone';


--
-- TOC entry 1681 (class 1259 OID 18527)
-- Dependencies: 2075 6
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
-- TOC entry 1682 (class 1259 OID 18531)
-- Dependencies: 6
-- Name: art_agglomeration; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_agglomeration (
    id integer NOT NULL,
    art_type_agglomeration_id integer,
    ref_secteur_id integer,
    nom character varying(50),
    longitude character varying(50),
    latitude character varying(50),
    memo text
);


ALTER TABLE public.art_agglomeration OWNER TO devppeao;

--
-- TOC entry 1689 (class 1259 OID 18556)
-- Dependencies: 6
-- Name: art_categorie_socio_professionnelle; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_categorie_socio_professionnelle (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_categorie_socio_professionnelle OWNER TO devppeao;

--
-- TOC entry 1678 (class 1259 OID 18508)
-- Dependencies: 2072 6
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
-- TOC entry 1693 (class 1259 OID 18565)
-- Dependencies: 6
-- Name: art_debarquement_rec; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_debarquement_rec (
    id character varying(20) NOT NULL,
    poids_total real NOT NULL,
    art_debarquement_id integer NOT NULL
);


ALTER TABLE public.art_debarquement_rec OWNER TO devppeao;

--
-- TOC entry 1694 (class 1259 OID 18568)
-- Dependencies: 6
-- Name: art_effort_gt; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_effort_gt (
    id integer NOT NULL,
    effort_tot real,
    effort_gt real,
    rapport_gt real,
    art_grand_type_engin character varying,
    secteur character varying
);


ALTER TABLE public.art_effort_gt OWNER TO devppeao;

--
-- TOC entry 1695 (class 1259 OID 18574)
-- Dependencies: 6
-- Name: art_effort_gt_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_effort_gt_sp (
    id integer NOT NULL,
    effort_tot real,
    effort_gt_sp real,
    rapport_gt_sp real,
    art_grand_type_engin character varying,
    ref_espece_id character varying,
    secteur character varying
);


ALTER TABLE public.art_effort_gt_sp OWNER TO devppeao;

--
-- TOC entry 1696 (class 1259 OID 18580)
-- Dependencies: 6
-- Name: art_effort_sp; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_effort_sp (
    id integer NOT NULL,
    effort_tot real,
    effort_sp real,
    rapport_sp real,
    ref_espece_id character varying,
    secteur character varying
);


ALTER TABLE public.art_effort_sp OWNER TO devppeao;

--
-- TOC entry 1697 (class 1259 OID 18586)
-- Dependencies: 6
-- Name: art_effort_taille; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_effort_taille (
    id integer NOT NULL,
    xi real,
    ni real,
    secteur character varying
);


ALTER TABLE public.art_effort_taille OWNER TO devppeao;

--
-- TOC entry 1698 (class 1259 OID 18592)
-- Dependencies: 2076 6
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
-- TOC entry 1700 (class 1259 OID 18598)
-- Dependencies: 2077 6
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
-- TOC entry 1703 (class 1259 OID 18606)
-- Dependencies: 6
-- Name: art_etat_ciel; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_etat_ciel (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_etat_ciel OWNER TO devppeao;

--
-- TOC entry 1679 (class 1259 OID 18515)
-- Dependencies: 2073 6
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
-- TOC entry 1709 (class 1259 OID 18619)
-- Dependencies: 6
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
-- TOC entry 1710 (class 1259 OID 18622)
-- Dependencies: 6
-- Name: art_grand_type_engin; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_grand_type_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_grand_type_engin OWNER TO devppeao;

--
-- TOC entry 1711 (class 1259 OID 18625)
-- Dependencies: 2078 6
-- Name: art_lieu_de_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_lieu_de_peche (
    id integer DEFAULT nextval(('"public"."art_lieu_de_peche_id_seq"'::text)::regclass) NOT NULL,
    ref_secteur_id integer,
    libelle character varying(50),
    code integer
);


ALTER TABLE public.art_lieu_de_peche OWNER TO devppeao;

--
-- TOC entry 1713 (class 1259 OID 18631)
-- Dependencies: 6
-- Name: art_millieu; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_millieu (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_millieu OWNER TO devppeao;

--
-- TOC entry 1680 (class 1259 OID 18519)
-- Dependencies: 2074 6
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
-- TOC entry 1718 (class 1259 OID 18642)
-- Dependencies: 6
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
-- TOC entry 1719 (class 1259 OID 18645)
-- Dependencies: 6
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
-- TOC entry 1720 (class 1259 OID 18648)
-- Dependencies: 6
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
-- TOC entry 1721 (class 1259 OID 18651)
-- Dependencies: 6
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
-- TOC entry 1722 (class 1259 OID 18654)
-- Dependencies: 6
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
-- TOC entry 1723 (class 1259 OID 18657)
-- Dependencies: 6
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
-- TOC entry 1724 (class 1259 OID 18660)
-- Dependencies: 6
-- Name: art_type_activite; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_type_activite (
    raison character varying(50),
    libelle character varying(255),
    id character varying(10) NOT NULL
);


ALTER TABLE public.art_type_activite OWNER TO devppeao;

--
-- TOC entry 1726 (class 1259 OID 18665)
-- Dependencies: 6
-- Name: art_type_agglomeration; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_type_agglomeration (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_type_agglomeration OWNER TO devppeao;

--
-- TOC entry 1728 (class 1259 OID 18670)
-- Dependencies: 6
-- Name: art_type_engin; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_type_engin (
    id character varying(10) NOT NULL,
    art_grand_type_engin_id character varying(10),
    libelle character varying(50)
);


ALTER TABLE public.art_type_engin OWNER TO devppeao;

--
-- TOC entry 1730 (class 1259 OID 18675)
-- Dependencies: 6
-- Name: art_type_sortie; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_type_sortie (
    id integer NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.art_type_sortie OWNER TO devppeao;

--
-- TOC entry 1732 (class 1259 OID 18680)
-- Dependencies: 2079 6
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
-- TOC entry 1734 (class 1259 OID 18686)
-- Dependencies: 6
-- Name: art_vent; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_vent (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_vent OWNER TO devppeao;

--
-- TOC entry 1737 (class 1259 OID 18693)
-- Dependencies: 2080 6
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
-- TOC entry 1739 (class 1259 OID 18702)
-- Dependencies: 2081 6
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
-- TOC entry 1741 (class 1259 OID 18708)
-- Dependencies: 6
-- Name: exp_contenu; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_contenu (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_contenu OWNER TO devppeao;

--
-- TOC entry 1744 (class 1259 OID 18715)
-- Dependencies: 2082 6
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
-- TOC entry 1746 (class 1259 OID 18724)
-- Dependencies: 6
-- Name: exp_debris; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_debris (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_debris OWNER TO devppeao;

--
-- TOC entry 1747 (class 1259 OID 18727)
-- Dependencies: 6
-- Name: exp_engin; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50),
    longueur real,
    chute real,
    maille integer,
    memo text
);


ALTER TABLE public.exp_engin OWNER TO devppeao;

--
-- TOC entry 1748 (class 1259 OID 18733)
-- Dependencies: 6
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
-- TOC entry 1750 (class 1259 OID 18741)
-- Dependencies: 6
-- Name: exp_force_courant; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_force_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_force_courant OWNER TO devppeao;

--
-- TOC entry 1752 (class 1259 OID 18746)
-- Dependencies: 2083 6
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
-- TOC entry 1754 (class 1259 OID 18755)
-- Dependencies: 6
-- Name: exp_position; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_position (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_position OWNER TO devppeao;

--
-- TOC entry 1756 (class 1259 OID 18760)
-- Dependencies: 6
-- Name: exp_qualite; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_qualite (
    id integer NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_qualite OWNER TO devppeao;

--
-- TOC entry 1758 (class 1259 OID 18765)
-- Dependencies: 6
-- Name: exp_remplissage; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_remplissage (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_remplissage OWNER TO devppeao;

--
-- TOC entry 1760 (class 1259 OID 18770)
-- Dependencies: 6
-- Name: exp_sediment; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_sediment (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_sediment OWNER TO devppeao;

--
-- TOC entry 1761 (class 1259 OID 18773)
-- Dependencies: 6
-- Name: exp_sens_courant; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_sens_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_sens_courant OWNER TO devppeao;

--
-- TOC entry 1763 (class 1259 OID 18778)
-- Dependencies: 6
-- Name: exp_sexe; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_sexe (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_sexe OWNER TO devppeao;

--
-- TOC entry 1764 (class 1259 OID 18781)
-- Dependencies: 6
-- Name: exp_stade; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_stade (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_stade OWNER TO devppeao;

--
-- TOC entry 1766 (class 1259 OID 18786)
-- Dependencies: 6
-- Name: exp_station; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_station (
    id character varying(10) NOT NULL,
    nom character varying(50),
    site character varying(50),
    latitude character varying(50),
    longitude character varying(50),
    memo text,
    ref_secteur_id integer,
    exp_position_id integer,
    exp_vegetation_id character varying(10),
    exp_debris_id character varying(10),
    exp_sediment_id character varying(10),
    distance_embouchure real
);


ALTER TABLE public.exp_station OWNER TO devppeao;

--
-- TOC entry 1767 (class 1259 OID 18792)
-- Dependencies: 2084 6
-- Name: exp_trophique; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_trophique (
    id integer DEFAULT nextval(('"public"."exp_contenu_biologie_id_seq"'::text)::regclass) NOT NULL,
    exp_biologie_id integer NOT NULL,
    exp_contenu_id integer NOT NULL,
    quantite real
);


ALTER TABLE public.exp_trophique OWNER TO devppeao;

--
-- TOC entry 1768 (class 1259 OID 18796)
-- Dependencies: 6
-- Name: exp_vegetation; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE exp_vegetation (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_vegetation OWNER TO devppeao;

--
-- TOC entry 1769 (class 1259 OID 18811)
-- Dependencies: 6
-- Name: pg_ts_dict; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pg_ts_dict (
    dict_name text NOT NULL,
    dict_init regprocedure,
    dict_initoption text,
    dict_lexize regprocedure NOT NULL,
    dict_comment text
);


ALTER TABLE public.pg_ts_dict OWNER TO postgres;

--
-- TOC entry 1770 (class 1259 OID 18817)
-- Dependencies: 6
-- Name: pg_ts_parser; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.pg_ts_parser OWNER TO postgres;

--
-- TOC entry 1771 (class 1259 OID 18823)
-- Dependencies: 6
-- Name: ref_categorie_ecologique; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_categorie_ecologique (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_categorie_ecologique OWNER TO devppeao;

--
-- TOC entry 1772 (class 1259 OID 18826)
-- Dependencies: 6
-- Name: ref_categorie_trophique; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_categorie_trophique (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.ref_categorie_trophique OWNER TO devppeao;

--
-- TOC entry 1773 (class 1259 OID 18829)
-- Dependencies: 6
-- Name: ref_espece; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_espece (
    id character varying(10) NOT NULL,
    libelle character varying(50),
    info character varying(255),
    ref_famille_id integer,
    ref_categorie_ecologique_id character varying(10),
    ref_categorie_trophique_id character varying(10),
    coefficient_k real,
    coefficient_b real,
    ref_origine_kb_id integer,
    ref_espece_id character varying(10)
);


ALTER TABLE public.ref_espece OWNER TO devppeao;

--
-- TOC entry 1774 (class 1259 OID 18832)
-- Dependencies: 2085 6
-- Name: ref_famille; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_famille (
    id integer NOT NULL,
    libelle character varying(50),
    ref_ordre_id integer,
    non_poisson integer,
    CONSTRAINT ref_famille_non_poisson_check CHECK (((non_poisson = 0) OR (non_poisson = 1)))
);


ALTER TABLE public.ref_famille OWNER TO devppeao;

--
-- TOC entry 1776 (class 1259 OID 18838)
-- Dependencies: 6
-- Name: ref_ordre; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_ordre (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_ordre OWNER TO devppeao;

--
-- TOC entry 1778 (class 1259 OID 18843)
-- Dependencies: 6
-- Name: ref_origine_kb; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_origine_kb (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_origine_kb OWNER TO devppeao;

--
-- TOC entry 1683 (class 1259 OID 18537)
-- Dependencies: 6
-- Name: ref_pays; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_pays (
    id character varying(10) NOT NULL,
    nom character varying(50)
);


ALTER TABLE public.ref_pays OWNER TO devppeao;

--
-- TOC entry 1684 (class 1259 OID 18540)
-- Dependencies: 6
-- Name: ref_secteur; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_secteur (
    id integer NOT NULL,
    id_dans_systeme integer,
    nom character varying(50),
    superficie real,
    ref_systeme_id integer
);


ALTER TABLE public.ref_secteur OWNER TO devppeao;

--
-- TOC entry 1685 (class 1259 OID 18543)
-- Dependencies: 6
-- Name: ref_systeme; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_systeme (
    id integer NOT NULL,
    libelle character varying(50),
    ref_pays_id character varying(10),
    superficie real
);


ALTER TABLE public.ref_systeme OWNER TO devppeao;

--
-- TOC entry 1799 (class 1259 OID 99875)
-- Dependencies: 6
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
-- TOC entry 1801 (class 1259 OID 99881)
-- Dependencies: 6
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
-- TOC entry 1803 (class 1259 OID 99887)
-- Dependencies: 6
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
-- TOC entry 1805 (class 1259 OID 99893)
-- Dependencies: 6
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
-- TOC entry 1784 (class 1259 OID 25054)
-- Dependencies: 1785 6
-- Name: admin_log_modules_module_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_log_modules_module_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_log_modules_module_id_seq OWNER TO devppeao;

--
-- TOC entry 2357 (class 0 OID 0)
-- Dependencies: 1784
-- Name: admin_log_modules_module_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE admin_log_modules_module_id_seq OWNED BY admin_log_modules.module_id;


--
-- TOC entry 1789 (class 1259 OID 33250)
-- Dependencies: 1790 6
-- Name: admin_users_groups_group_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_users_groups_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_users_groups_group_id_seq OWNER TO devppeao;

--
-- TOC entry 2358 (class 0 OID 0)
-- Dependencies: 1789
-- Name: admin_users_groups_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE admin_users_groups_group_id_seq OWNED BY admin_usergroups.group_id;


--
-- TOC entry 1786 (class 1259 OID 25067)
-- Dependencies: 6 1787
-- Name: admin_users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_users_user_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_users_user_id_seq OWNER TO devppeao;

--
-- TOC entry 2359 (class 0 OID 0)
-- Dependencies: 1786
-- Name: admin_users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE admin_users_user_id_seq OWNED BY admin_users.user_id;


--
-- TOC entry 1794 (class 1259 OID 33285)
-- Dependencies: 1795 6
-- Name: admin_zones_zone_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_zones_zone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_zones_zone_id_seq OWNER TO devppeao;

--
-- TOC entry 2360 (class 0 OID 0)
-- Dependencies: 1794
-- Name: admin_zones_zone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE admin_zones_zone_id_seq OWNED BY admin_zones.zone_id;


--
-- TOC entry 1686 (class 1259 OID 18550)
-- Dependencies: 6
-- Name: art_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_activite_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_activite_id_seq OWNER TO postgres;

--
-- TOC entry 1687 (class 1259 OID 18552)
-- Dependencies: 6
-- Name: art_agglomeration_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_agglomeration_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_agglomeration_id_seq OWNER TO postgres;

--
-- TOC entry 1688 (class 1259 OID 18554)
-- Dependencies: 6
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
-- TOC entry 1690 (class 1259 OID 18559)
-- Dependencies: 6
-- Name: art_categorie_socio_professionnelle_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_categorie_socio_professionnelle_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_categorie_socio_professionnelle_id_seq OWNER TO postgres;

--
-- TOC entry 1691 (class 1259 OID 18561)
-- Dependencies: 6
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
-- TOC entry 1692 (class 1259 OID 18563)
-- Dependencies: 6
-- Name: art_debarquement_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_debarquement_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_debarquement_id_seq OWNER TO postgres;

--
-- TOC entry 1699 (class 1259 OID 18596)
-- Dependencies: 6
-- Name: art_engin_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_activite_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_activite_id_seq OWNER TO postgres;

--
-- TOC entry 1701 (class 1259 OID 18602)
-- Dependencies: 6
-- Name: art_engin_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_peche_id_seq OWNER TO postgres;

--
-- TOC entry 1702 (class 1259 OID 18604)
-- Dependencies: 6
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
-- TOC entry 1704 (class 1259 OID 18609)
-- Dependencies: 6
-- Name: art_etat_ciel_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_etat_ciel_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_etat_ciel_id_seq OWNER TO postgres;

--
-- TOC entry 1705 (class 1259 OID 18611)
-- Dependencies: 6
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
-- TOC entry 1706 (class 1259 OID 18613)
-- Dependencies: 6
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
-- TOC entry 1707 (class 1259 OID 18615)
-- Dependencies: 6
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
-- TOC entry 1708 (class 1259 OID 18617)
-- Dependencies: 6
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
-- TOC entry 1712 (class 1259 OID 18629)
-- Dependencies: 6
-- Name: art_lieu_de_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_lieu_de_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_lieu_de_peche_id_seq OWNER TO postgres;

--
-- TOC entry 1714 (class 1259 OID 18634)
-- Dependencies: 6
-- Name: art_millieu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_millieu_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_millieu_id_seq OWNER TO postgres;

--
-- TOC entry 1715 (class 1259 OID 18636)
-- Dependencies: 6
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
-- TOC entry 1716 (class 1259 OID 18638)
-- Dependencies: 6
-- Name: art_origine_kb_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_origine_kb_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_origine_kb_id_seq OWNER TO postgres;

--
-- TOC entry 1717 (class 1259 OID 18640)
-- Dependencies: 6
-- Name: art_poisson_mesure_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_poisson_mesure_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_poisson_mesure_id_seq OWNER TO postgres;

--
-- TOC entry 1725 (class 1259 OID 18663)
-- Dependencies: 6
-- Name: art_type_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_type_activite_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_activite_id_seq OWNER TO postgres;

--
-- TOC entry 1727 (class 1259 OID 18668)
-- Dependencies: 6
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
-- TOC entry 1729 (class 1259 OID 18673)
-- Dependencies: 6
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
-- TOC entry 1731 (class 1259 OID 18678)
-- Dependencies: 6
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
-- TOC entry 1733 (class 1259 OID 18684)
-- Dependencies: 6
-- Name: art_unite_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_unite_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_unite_peche_id_seq OWNER TO postgres;

--
-- TOC entry 1735 (class 1259 OID 18689)
-- Dependencies: 6
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
-- TOC entry 1736 (class 1259 OID 18691)
-- Dependencies: 6
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
-- TOC entry 1738 (class 1259 OID 18700)
-- Dependencies: 6
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
-- TOC entry 1740 (class 1259 OID 18706)
-- Dependencies: 6
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
-- TOC entry 1742 (class 1259 OID 18711)
-- Dependencies: 6
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
-- TOC entry 1743 (class 1259 OID 18713)
-- Dependencies: 6
-- Name: exp_contenu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_contenu_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_contenu_id_seq OWNER TO postgres;

--
-- TOC entry 1745 (class 1259 OID 18722)
-- Dependencies: 6
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
-- TOC entry 1749 (class 1259 OID 18739)
-- Dependencies: 6
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
-- TOC entry 1751 (class 1259 OID 18744)
-- Dependencies: 6
-- Name: exp_force_courant_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_force_courant_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_force_courant_id_seq OWNER TO postgres;

--
-- TOC entry 1753 (class 1259 OID 18753)
-- Dependencies: 6
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
-- TOC entry 1755 (class 1259 OID 18758)
-- Dependencies: 6
-- Name: exp_position_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_position_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_position_id_seq OWNER TO postgres;

--
-- TOC entry 1757 (class 1259 OID 18763)
-- Dependencies: 6
-- Name: exp_qualite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_qualite_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_qualite_id_seq OWNER TO postgres;

--
-- TOC entry 1759 (class 1259 OID 18768)
-- Dependencies: 6
-- Name: exp_remplissage_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_remplissage_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_remplissage_id_seq OWNER TO postgres;

--
-- TOC entry 1762 (class 1259 OID 18776)
-- Dependencies: 6
-- Name: exp_sens_courant_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_sens_courant_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_sens_courant_id_seq OWNER TO postgres;

--
-- TOC entry 1765 (class 1259 OID 18784)
-- Dependencies: 6
-- Name: exp_stade_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_stade_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_stade_id_seq OWNER TO postgres;

--
-- TOC entry 1792 (class 1259 OID 33264)
-- Dependencies: 6 1793
-- Name: j_user_group_group_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE j_user_group_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.j_user_group_group_id_seq OWNER TO devppeao;

--
-- TOC entry 2361 (class 0 OID 0)
-- Dependencies: 1792
-- Name: j_user_group_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE j_user_group_group_id_seq OWNED BY admin_j_user_group.group_id;


--
-- TOC entry 1791 (class 1259 OID 33262)
-- Dependencies: 1793 6
-- Name: j_user_group_user_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE j_user_group_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.j_user_group_user_id_seq OWNER TO devppeao;

--
-- TOC entry 2362 (class 0 OID 0)
-- Dependencies: 1791
-- Name: j_user_group_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE j_user_group_user_id_seq OWNED BY admin_j_user_group.user_id;


--
-- TOC entry 1775 (class 1259 OID 18836)
-- Dependencies: 6
-- Name: ref_famille_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ref_famille_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_famille_id_seq OWNER TO postgres;

--
-- TOC entry 1777 (class 1259 OID 18841)
-- Dependencies: 6
-- Name: ref_ordre_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ref_ordre_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_ordre_id_seq OWNER TO postgres;

--
-- TOC entry 1779 (class 1259 OID 18846)
-- Dependencies: 6
-- Name: ref_secteur_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ref_secteur_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_secteur_id_seq OWNER TO postgres;

--
-- TOC entry 1780 (class 1259 OID 18848)
-- Dependencies: 6
-- Name: ref_systeme_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ref_systeme_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_systeme_id_seq OWNER TO postgres;

--
-- TOC entry 1798 (class 1259 OID 99873)
-- Dependencies: 1799 6
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
-- TOC entry 2363 (class 0 OID 0)
-- Dependencies: 1798
-- Name: sys_activites_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_activites_a_migrer_id_seq OWNED BY sys_activites_a_migrer.id;


--
-- TOC entry 1800 (class 1259 OID 99879)
-- Dependencies: 6 1801
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
-- TOC entry 2364 (class 0 OID 0)
-- Dependencies: 1800
-- Name: sys_campagnes_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_campagnes_a_migrer_id_seq OWNED BY sys_campagnes_a_migrer.id;


--
-- TOC entry 1802 (class 1259 OID 99885)
-- Dependencies: 6 1803
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
-- TOC entry 2365 (class 0 OID 0)
-- Dependencies: 1802
-- Name: sys_debarquements_a_migrer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_debarquements_a_migrer_id_seq OWNED BY sys_debarquements_a_migrer.id;


--
-- TOC entry 1781 (class 1259 OID 18868)
-- Dependencies: 6
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
-- TOC entry 1804 (class 1259 OID 99891)
-- Dependencies: 1805 6
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
-- TOC entry 2366 (class 0 OID 0)
-- Dependencies: 1804
-- Name: sys_periodes_enquete_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: devppeao
--

ALTER SEQUENCE sys_periodes_enquete_id_seq OWNED BY sys_periodes_enquete.id;


--
-- TOC entry 1782 (class 1259 OID 18876)
-- Dependencies: 6
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
-- TOC entry 2086 (class 2604 OID 25059)
-- Dependencies: 1785 1784 1785
-- Name: module_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_log_modules ALTER COLUMN module_id SET DEFAULT nextval('admin_log_modules_module_id_seq'::regclass);


--
-- TOC entry 2089 (class 2604 OID 33255)
-- Dependencies: 1790 1789 1790
-- Name: group_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_usergroups ALTER COLUMN group_id SET DEFAULT nextval('admin_users_groups_group_id_seq'::regclass);


--
-- TOC entry 2087 (class 2604 OID 25072)
-- Dependencies: 1786 1787 1787
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_users ALTER COLUMN user_id SET DEFAULT nextval('admin_users_user_id_seq'::regclass);


--
-- TOC entry 2091 (class 2604 OID 33290)
-- Dependencies: 1795 1794 1795
-- Name: zone_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_zones ALTER COLUMN zone_id SET DEFAULT nextval('admin_zones_zone_id_seq'::regclass);


--
-- TOC entry 2092 (class 2604 OID 99878)
-- Dependencies: 1798 1799 1799
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_activites_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_activites_a_migrer_id_seq'::regclass);


--
-- TOC entry 2093 (class 2604 OID 99884)
-- Dependencies: 1800 1801 1801
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_campagnes_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_campagnes_a_migrer_id_seq'::regclass);


--
-- TOC entry 2094 (class 2604 OID 99890)
-- Dependencies: 1803 1802 1803
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_debarquements_a_migrer ALTER COLUMN id SET DEFAULT nextval('sys_debarquements_a_migrer_id_seq'::regclass);


--
-- TOC entry 2095 (class 2604 OID 99896)
-- Dependencies: 1805 1804 1805
-- Name: id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE sys_periodes_enquete ALTER COLUMN id SET DEFAULT nextval('sys_periodes_enquete_id_seq'::regclass);


--
-- TOC entry 2235 (class 2606 OID 33350)
-- Dependencies: 1797 1797 1797
-- Name: admin_j_group_zone_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_pkey PRIMARY KEY (group_id, zone_id);


--
-- TOC entry 2233 (class 2606 OID 33299)
-- Dependencies: 1796 1796 1796
-- Name: admin_j_user_zone_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_pkey PRIMARY KEY (user_id, zone_id);


--
-- TOC entry 2221 (class 2606 OID 33244)
-- Dependencies: 1788 1788
-- Name: admin_log_message_types_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_log_message_types
    ADD CONSTRAINT admin_log_message_types_pkey PRIMARY KEY (message_type);


--
-- TOC entry 2215 (class 2606 OID 25061)
-- Dependencies: 1785 1785
-- Name: admin_log_modules_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_log_modules
    ADD CONSTRAINT admin_log_modules_pkey PRIMARY KEY (module_id);


--
-- TOC entry 2223 (class 2606 OID 33279)
-- Dependencies: 1790 1790
-- Name: admin_users_groups_group_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_usergroups
    ADD CONSTRAINT admin_users_groups_group_id_key UNIQUE (group_id);


--
-- TOC entry 2225 (class 2606 OID 33261)
-- Dependencies: 1790 1790 1790
-- Name: admin_users_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_usergroups
    ADD CONSTRAINT admin_users_groups_pkey PRIMARY KEY (group_id, group_name);


--
-- TOC entry 2217 (class 2606 OID 25078)
-- Dependencies: 1787 1787
-- Name: admin_users_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_users
    ADD CONSTRAINT admin_users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 2219 (class 2606 OID 25080)
-- Dependencies: 1787 1787
-- Name: admin_users_user_name_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_users
    ADD CONSTRAINT admin_users_user_name_key UNIQUE (user_name);


--
-- TOC entry 2229 (class 2606 OID 33292)
-- Dependencies: 1795 1795
-- Name: admin_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_zones
    ADD CONSTRAINT admin_zones_pkey PRIMARY KEY (zone_id);


--
-- TOC entry 2231 (class 2606 OID 33294)
-- Dependencies: 1795 1795
-- Name: admin_zones_zone_name_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_zones
    ADD CONSTRAINT admin_zones_zone_name_key UNIQUE (zone_name);


--
-- TOC entry 2213 (class 2606 OID 18891)
-- Dependencies: 1778 1778
-- Name: ar_origine_kb_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_origine_kb
    ADD CONSTRAINT ar_origine_kb_pkey PRIMARY KEY (id);


--
-- TOC entry 2105 (class 2606 OID 18893)
-- Dependencies: 1682 1682
-- Name: art_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_agglomeration_pkey PRIMARY KEY (id);


--
-- TOC entry 2103 (class 2606 OID 18895)
-- Dependencies: 1681 1681
-- Name: art_artivite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_artivite_pkey PRIMARY KEY (id);


--
-- TOC entry 2113 (class 2606 OID 18897)
-- Dependencies: 1689 1689
-- Name: art_categorie_socio_professionnelle_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_categorie_socio_professionnelle
    ADD CONSTRAINT art_categorie_socio_professionnelle_pkey PRIMARY KEY (id);


--
-- TOC entry 2097 (class 2606 OID 18899)
-- Dependencies: 1678 1678
-- Name: art_debarquement_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_pkey PRIMARY KEY (id);


--
-- TOC entry 2115 (class 2606 OID 18901)
-- Dependencies: 1693 1693
-- Name: art_debarquement_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_pkey PRIMARY KEY (id);


--
-- TOC entry 2117 (class 2606 OID 18903)
-- Dependencies: 1694 1694
-- Name: art_effort_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_gt
    ADD CONSTRAINT art_effort_gt_pkey PRIMARY KEY (id);


--
-- TOC entry 2119 (class 2606 OID 18905)
-- Dependencies: 1695 1695
-- Name: art_effort_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_gt_sp
    ADD CONSTRAINT art_effort_gt_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2121 (class 2606 OID 18907)
-- Dependencies: 1696 1696
-- Name: art_effort_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_sp
    ADD CONSTRAINT art_effort_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2123 (class 2606 OID 18909)
-- Dependencies: 1697 1697
-- Name: art_effort_taille_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_effort_taille
    ADD CONSTRAINT art_effort_taille_pkey PRIMARY KEY (id);


--
-- TOC entry 2125 (class 2606 OID 18911)
-- Dependencies: 1698 1698
-- Name: art_engin_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_pkey PRIMARY KEY (id);


--
-- TOC entry 2127 (class 2606 OID 18913)
-- Dependencies: 1700 1700
-- Name: art_engin_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_pkey PRIMARY KEY (id);


--
-- TOC entry 2129 (class 2606 OID 18915)
-- Dependencies: 1703 1703
-- Name: art_etat_ciel_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_etat_ciel
    ADD CONSTRAINT art_etat_ciel_pkey PRIMARY KEY (id);


--
-- TOC entry 2099 (class 2606 OID 18917)
-- Dependencies: 1679 1679
-- Name: art_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_pkey PRIMARY KEY (id);


--
-- TOC entry 2131 (class 2606 OID 18919)
-- Dependencies: 1709 1709
-- Name: art_fraction_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_pkey PRIMARY KEY (id);


--
-- TOC entry 2133 (class 2606 OID 18921)
-- Dependencies: 1710 1710
-- Name: art_grand_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_grand_type_engin
    ADD CONSTRAINT art_grand_type_engin_pkey PRIMARY KEY (id);


--
-- TOC entry 2135 (class 2606 OID 18923)
-- Dependencies: 1711 1711
-- Name: art_lieu_de_peche_id_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_id_pkey PRIMARY KEY (id);


--
-- TOC entry 2137 (class 2606 OID 18925)
-- Dependencies: 1713 1713
-- Name: art_millieu_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_millieu
    ADD CONSTRAINT art_millieu_pkey PRIMARY KEY (id);


--
-- TOC entry 2101 (class 2606 OID 18927)
-- Dependencies: 1680 1680
-- Name: art_poisson_mesure_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_pkey PRIMARY KEY (id);


--
-- TOC entry 2139 (class 2606 OID 18929)
-- Dependencies: 1718 1718
-- Name: art_stat_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_pkey PRIMARY KEY (id);


--
-- TOC entry 2141 (class 2606 OID 18931)
-- Dependencies: 1719 1719
-- Name: art_stat_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2143 (class 2606 OID 18933)
-- Dependencies: 1720 1720
-- Name: art_stat_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2145 (class 2606 OID 18935)
-- Dependencies: 1721 1721
-- Name: art_stat_totale_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_pkey PRIMARY KEY (id);


--
-- TOC entry 2147 (class 2606 OID 18937)
-- Dependencies: 1722 1722
-- Name: art_taille_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2149 (class 2606 OID 18939)
-- Dependencies: 1723 1723
-- Name: art_taille_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_pkey PRIMARY KEY (id);


--
-- TOC entry 2159 (class 2606 OID 18941)
-- Dependencies: 1730 1730
-- Name: art_tyepe_sortie_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_type_sortie
    ADD CONSTRAINT art_tyepe_sortie_pkey PRIMARY KEY (id);


--
-- TOC entry 2151 (class 2606 OID 90597)
-- Dependencies: 1724 1724
-- Name: art_type_activite_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_type_activite
    ADD CONSTRAINT art_type_activite_id_key UNIQUE (id);


--
-- TOC entry 2153 (class 2606 OID 18943)
-- Dependencies: 1724 1724
-- Name: art_type_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_type_activite
    ADD CONSTRAINT art_type_activite_pkey PRIMARY KEY (id);


--
-- TOC entry 2155 (class 2606 OID 18945)
-- Dependencies: 1726 1726
-- Name: art_type_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_type_agglomeration
    ADD CONSTRAINT art_type_agglomeration_pkey PRIMARY KEY (id);


--
-- TOC entry 2157 (class 2606 OID 18947)
-- Dependencies: 1728 1728
-- Name: art_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_type_engin
    ADD CONSTRAINT art_type_engin_pkey PRIMARY KEY (id);


--
-- TOC entry 2161 (class 2606 OID 18949)
-- Dependencies: 1732 1732
-- Name: art_unite_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_pkey PRIMARY KEY (id);


--
-- TOC entry 2163 (class 2606 OID 18951)
-- Dependencies: 1734 1734
-- Name: art_vent_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_vent
    ADD CONSTRAINT art_vent_pkey PRIMARY KEY (id);


--
-- TOC entry 2167 (class 2606 OID 18953)
-- Dependencies: 1739 1739
-- Name: exp_campagne_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_pkey PRIMARY KEY (id);


--
-- TOC entry 2199 (class 2606 OID 18955)
-- Dependencies: 1767 1767
-- Name: exp_contenu_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_biologie_pkey PRIMARY KEY (id);


--
-- TOC entry 2169 (class 2606 OID 18957)
-- Dependencies: 1741 1741
-- Name: exp_contenu_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_contenu
    ADD CONSTRAINT exp_contenu_pkey PRIMARY KEY (id);


--
-- TOC entry 2171 (class 2606 OID 18959)
-- Dependencies: 1744 1744
-- Name: exp_cp_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_cp_peche_pkey PRIMARY KEY (id);


--
-- TOC entry 2173 (class 2606 OID 18961)
-- Dependencies: 1746 1746
-- Name: exp_debris_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_debris
    ADD CONSTRAINT exp_debris_pkey PRIMARY KEY (id);


--
-- TOC entry 2177 (class 2606 OID 18963)
-- Dependencies: 1748 1748
-- Name: exp_environnement_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_pkey PRIMARY KEY (id);


--
-- TOC entry 2179 (class 2606 OID 18965)
-- Dependencies: 1750 1750
-- Name: exp_force_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_force_courant
    ADD CONSTRAINT exp_force_courant_pkey PRIMARY KEY (id);


--
-- TOC entry 2183 (class 2606 OID 18967)
-- Dependencies: 1754 1754
-- Name: exp_position_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_position
    ADD CONSTRAINT exp_position_pkey PRIMARY KEY (id);


--
-- TOC entry 2185 (class 2606 OID 18969)
-- Dependencies: 1756 1756
-- Name: exp_qualite_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_qualite
    ADD CONSTRAINT exp_qualite_pkey PRIMARY KEY (id);


--
-- TOC entry 2187 (class 2606 OID 18971)
-- Dependencies: 1758 1758
-- Name: exp_remplissage_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_remplissage
    ADD CONSTRAINT exp_remplissage_pkey PRIMARY KEY (id);


--
-- TOC entry 2189 (class 2606 OID 18973)
-- Dependencies: 1760 1760
-- Name: exp_sediment_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_sediment
    ADD CONSTRAINT exp_sediment_pkey PRIMARY KEY (id);


--
-- TOC entry 2191 (class 2606 OID 18975)
-- Dependencies: 1761 1761
-- Name: exp_sens_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_sens_courant
    ADD CONSTRAINT exp_sens_courant_pkey PRIMARY KEY (id);


--
-- TOC entry 2193 (class 2606 OID 18977)
-- Dependencies: 1763 1763
-- Name: exp_sexe_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_sexe
    ADD CONSTRAINT exp_sexe_pkey PRIMARY KEY (id);


--
-- TOC entry 2195 (class 2606 OID 18979)
-- Dependencies: 1764 1764
-- Name: exp_stade_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_stade
    ADD CONSTRAINT exp_stade_pkey PRIMARY KEY (id);


--
-- TOC entry 2197 (class 2606 OID 18981)
-- Dependencies: 1766 1766
-- Name: exp_station_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_pkey PRIMARY KEY (id);


--
-- TOC entry 2201 (class 2606 OID 18983)
-- Dependencies: 1768 1768
-- Name: exp_vegetation_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_vegetation
    ADD CONSTRAINT exp_vegetation_pkey PRIMARY KEY (id);


--
-- TOC entry 2227 (class 2606 OID 33272)
-- Dependencies: 1793 1793 1793
-- Name: j_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT j_user_group_pkey PRIMARY KEY (user_id, group_id);


--
-- TOC entry 2165 (class 2606 OID 18985)
-- Dependencies: 1737 1737
-- Name: ref_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT ref_biologie_pkey PRIMARY KEY (id);


--
-- TOC entry 2203 (class 2606 OID 18987)
-- Dependencies: 1771 1771
-- Name: ref_categorie_ecologique_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_ecologique
    ADD CONSTRAINT ref_categorie_ecologique_pkey PRIMARY KEY (id);


--
-- TOC entry 2205 (class 2606 OID 18989)
-- Dependencies: 1772 1772
-- Name: ref_categorie_trophique_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_trophique
    ADD CONSTRAINT ref_categorie_trophique_pkey PRIMARY KEY (id);


--
-- TOC entry 2175 (class 2606 OID 18991)
-- Dependencies: 1747 1747
-- Name: ref_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_engin
    ADD CONSTRAINT ref_engin_pkey PRIMARY KEY (id);


--
-- TOC entry 2207 (class 2606 OID 18993)
-- Dependencies: 1773 1773
-- Name: ref_espece_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_pkey PRIMARY KEY (id);


--
-- TOC entry 2209 (class 2606 OID 18995)
-- Dependencies: 1774 1774
-- Name: ref_famille_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_famille
    ADD CONSTRAINT ref_famille_pkey PRIMARY KEY (id);


--
-- TOC entry 2181 (class 2606 OID 18997)
-- Dependencies: 1752 1752
-- Name: ref_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_fraction_pkey PRIMARY KEY (id);


--
-- TOC entry 2211 (class 2606 OID 18999)
-- Dependencies: 1776 1776
-- Name: ref_ordre_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_ordre
    ADD CONSTRAINT ref_ordre_pkey PRIMARY KEY (id);


--
-- TOC entry 2107 (class 2606 OID 19001)
-- Dependencies: 1683 1683
-- Name: ref_pays_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_pays
    ADD CONSTRAINT ref_pays_pkey PRIMARY KEY (id);


--
-- TOC entry 2109 (class 2606 OID 19003)
-- Dependencies: 1684 1684
-- Name: ref_secteur_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_secteur
    ADD CONSTRAINT ref_secteur_pkey PRIMARY KEY (id);


--
-- TOC entry 2111 (class 2606 OID 19005)
-- Dependencies: 1685 1685
-- Name: ref_systeme_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_systeme
    ADD CONSTRAINT ref_systeme_pkey PRIMARY KEY (id);


--
-- TOC entry 2307 (class 2606 OID 33339)
-- Dependencies: 1790 1797 2222
-- Name: admin_j_group_zone_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_group_id_fkey FOREIGN KEY (group_id) REFERENCES admin_usergroups(group_id);


--
-- TOC entry 2308 (class 2606 OID 33344)
-- Dependencies: 1797 2228 1795
-- Name: admin_j_group_zone_zone_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_zone_id_fkey FOREIGN KEY (zone_id) REFERENCES admin_zones(zone_id);


--
-- TOC entry 2304 (class 2606 OID 33334)
-- Dependencies: 2222 1790 1793
-- Name: admin_j_user_group_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT admin_j_user_group_group_id_fkey FOREIGN KEY (group_id) REFERENCES admin_usergroups(group_id);


--
-- TOC entry 2303 (class 2606 OID 33329)
-- Dependencies: 1787 2216 1793
-- Name: admin_j_user_group_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT admin_j_user_group_user_id_fkey FOREIGN KEY (user_id) REFERENCES admin_users(user_id);


--
-- TOC entry 2305 (class 2606 OID 33300)
-- Dependencies: 1787 2216 1796
-- Name: admin_j_user_zone_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_user_id_fkey FOREIGN KEY (user_id) REFERENCES admin_users(user_id);


--
-- TOC entry 2306 (class 2606 OID 33305)
-- Dependencies: 2228 1795 1796
-- Name: admin_j_user_zone_zone_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_zone_id_fkey FOREIGN KEY (zone_id) REFERENCES admin_zones(zone_id);


--
-- TOC entry 2302 (class 2606 OID 33361)
-- Dependencies: 2220 1783 1788
-- Name: admin_log_log_message_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_message_type_fkey FOREIGN KEY (log_message_type) REFERENCES admin_log_message_types(message_type);


--
-- TOC entry 2300 (class 2606 OID 33351)
-- Dependencies: 2214 1785 1783
-- Name: admin_log_log_module_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_module_id_fkey FOREIGN KEY (log_module_id) REFERENCES admin_log_modules(module_id);


--
-- TOC entry 2301 (class 2606 OID 33356)
-- Dependencies: 1787 1783 2216
-- Name: admin_log_log_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_user_id_fkey FOREIGN KEY (log_user_id) REFERENCES admin_users(user_id);


--
-- TOC entry 2247 (class 2606 OID 19014)
-- Dependencies: 1724 1681 2152
-- Name: art_activite_art_type_activite_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_activite_art_type_activite_id_fkey FOREIGN KEY (art_type_activite_id) REFERENCES art_type_activite(id);


--
-- TOC entry 2258 (class 2606 OID 19019)
-- Dependencies: 2102 1681 1698
-- Name: art_activite_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_activite_id FOREIGN KEY (art_activite_id) REFERENCES art_activite(id);


--
-- TOC entry 2248 (class 2606 OID 19024)
-- Dependencies: 1682 2104 1681
-- Name: art_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_agglomeration_id FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- TOC entry 2236 (class 2606 OID 19029)
-- Dependencies: 2104 1682 1678
-- Name: art_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_agglomeration_id FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- TOC entry 2271 (class 2606 OID 19034)
-- Dependencies: 1689 2112 1732
-- Name: art_categorie_socio_professionnelle_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_categorie_socio_professionnelle_id FOREIGN KEY (art_categorie_socio_professionnelle_id) REFERENCES art_categorie_socio_professionnelle(id);


--
-- TOC entry 2260 (class 2606 OID 19039)
-- Dependencies: 1700 2096 1678
-- Name: art_debarquement_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_debarquement_id FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- TOC entry 2244 (class 2606 OID 19044)
-- Dependencies: 1678 2096 1679
-- Name: art_debarquement_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_debarquement_id FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- TOC entry 2257 (class 2606 OID 19049)
-- Dependencies: 2096 1693 1678
-- Name: art_debarquement_rec_art_debarquement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_art_debarquement_id_fkey FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id);


--
-- TOC entry 2259 (class 2606 OID 19054)
-- Dependencies: 1728 2156 1698
-- Name: art_engin_activite_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id);


--
-- TOC entry 2261 (class 2606 OID 19059)
-- Dependencies: 1700 1728 2156
-- Name: art_engin_peche_art_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_art_type_engin_id_fkey FOREIGN KEY (art_type_engin_id) REFERENCES art_type_engin(id);


--
-- TOC entry 2237 (class 2606 OID 19064)
-- Dependencies: 2128 1678 1703
-- Name: art_etat_ciel_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_etat_ciel_id FOREIGN KEY (art_etat_ciel_id) REFERENCES art_etat_ciel(id);


--
-- TOC entry 2246 (class 2606 OID 19069)
-- Dependencies: 1679 1680 2098
-- Name: art_fraction_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_fraction_id FOREIGN KEY (art_fraction_id) REFERENCES art_fraction(id) DEFERRABLE;


--
-- TOC entry 2262 (class 2606 OID 19074)
-- Dependencies: 1679 1709 2098
-- Name: art_fraction_rec_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_id_fkey FOREIGN KEY (id) REFERENCES art_fraction(id);


--
-- TOC entry 2238 (class 2606 OID 19079)
-- Dependencies: 1710 1678 2132
-- Name: art_grand_type_engin_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_grand_type_engin_id FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id);


--
-- TOC entry 2249 (class 2606 OID 19084)
-- Dependencies: 1710 1681 2132
-- Name: art_grand_type_engin_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_grand_type_engin_id FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id);


--
-- TOC entry 2239 (class 2606 OID 19089)
-- Dependencies: 1711 1678 2134
-- Name: art_lieu_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_lieu_peche_id FOREIGN KEY (art_lieu_de_peche_id) REFERENCES art_lieu_de_peche(id);


--
-- TOC entry 2250 (class 2606 OID 19094)
-- Dependencies: 1681 1713 2136
-- Name: art_millieu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_millieu_id FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id);


--
-- TOC entry 2240 (class 2606 OID 19099)
-- Dependencies: 1678 1713 2136
-- Name: art_millieu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_millieu_id FOREIGN KEY (art_millieu_id) REFERENCES art_millieu(id);


--
-- TOC entry 2294 (class 2606 OID 19104)
-- Dependencies: 2212 1773 1778
-- Name: art_origine_kb_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT art_origine_kb_id FOREIGN KEY (ref_origine_kb_id) REFERENCES ref_origine_kb(id);


--
-- TOC entry 2264 (class 2606 OID 19109)
-- Dependencies: 1718 2144 1721
-- Name: art_stat_gt_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id);


--
-- TOC entry 2265 (class 2606 OID 19114)
-- Dependencies: 1718 2138 1719
-- Name: art_stat_gt_sp_art_stat_gt_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_art_stat_gt_id_fkey FOREIGN KEY (art_stat_gt_id) REFERENCES art_stat_gt(id);


--
-- TOC entry 2266 (class 2606 OID 19119)
-- Dependencies: 2144 1720 1721
-- Name: art_stat_sp_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id);


--
-- TOC entry 2267 (class 2606 OID 19124)
-- Dependencies: 1721 2104 1682
-- Name: art_stat_totale_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- TOC entry 2268 (class 2606 OID 19129)
-- Dependencies: 2140 1719 1722
-- Name: art_taille_gt_sp_art_stat_gt_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_art_stat_gt_sp_id_fkey FOREIGN KEY (art_stat_gt_sp_id) REFERENCES art_stat_gt_sp(id);


--
-- TOC entry 2269 (class 2606 OID 19134)
-- Dependencies: 2142 1723 1720
-- Name: art_taille_sp_art_stat_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_art_stat_sp_id_fkey FOREIGN KEY (art_stat_sp_id) REFERENCES art_stat_sp(id);


--
-- TOC entry 2253 (class 2606 OID 19139)
-- Dependencies: 1682 1726 2154
-- Name: art_type_agglomeration_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_type_agglomeration_id FOREIGN KEY (art_type_agglomeration_id) REFERENCES art_type_agglomeration(id);


--
-- TOC entry 2270 (class 2606 OID 19144)
-- Dependencies: 2132 1728 1710
-- Name: art_type_engin_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_type_engin
    ADD CONSTRAINT art_type_engin_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id);


--
-- TOC entry 2251 (class 2606 OID 19149)
-- Dependencies: 1730 1681 2158
-- Name: art_type_sortie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_type_sortie_id FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id);


--
-- TOC entry 2241 (class 2606 OID 19154)
-- Dependencies: 1730 1678 2158
-- Name: art_type_sortie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_type_sortie_id FOREIGN KEY (art_type_sortie_id) REFERENCES art_type_sortie(id);


--
-- TOC entry 2272 (class 2606 OID 19159)
-- Dependencies: 1682 1732 2104
-- Name: art_unite_peche_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id);


--
-- TOC entry 2252 (class 2606 OID 19164)
-- Dependencies: 1732 1681 2160
-- Name: art_unite_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_unite_peche_id FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id);


--
-- TOC entry 2242 (class 2606 OID 19169)
-- Dependencies: 1732 1678 2160
-- Name: art_unite_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_unite_peche_id FOREIGN KEY (art_unite_peche_id) REFERENCES art_unite_peche(id);


--
-- TOC entry 2243 (class 2606 OID 19174)
-- Dependencies: 1734 1678 2162
-- Name: art_vent_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_vent_id FOREIGN KEY (art_vent_id) REFERENCES art_vent(id);


--
-- TOC entry 2292 (class 2606 OID 19179)
-- Dependencies: 1737 1767 2164
-- Name: exp_biologie_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_biologie_id FOREIGN KEY (exp_biologie_id) REFERENCES exp_biologie(id);


--
-- TOC entry 2278 (class 2606 OID 19184)
-- Dependencies: 1739 1744 2166
-- Name: exp_campagne_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_campagne_id FOREIGN KEY (exp_campagne_id) REFERENCES exp_campagne(id);


--
-- TOC entry 2293 (class 2606 OID 19189)
-- Dependencies: 1741 1767 2168
-- Name: exp_contenu_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_id FOREIGN KEY (exp_contenu_id) REFERENCES exp_contenu(id);


--
-- TOC entry 2279 (class 2606 OID 19194)
-- Dependencies: 2174 1747 1744
-- Name: exp_coup_peche_exp_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_engin_id_fkey FOREIGN KEY (exp_engin_id) REFERENCES exp_engin(id);


--
-- TOC entry 2280 (class 2606 OID 19199)
-- Dependencies: 1744 1748 2176
-- Name: exp_coup_peche_exp_environnement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_coup_peche_exp_environnement_id_fkey FOREIGN KEY (exp_environnement_id) REFERENCES exp_environnement(id);


--
-- TOC entry 2285 (class 2606 OID 19204)
-- Dependencies: 2170 1744 1752
-- Name: exp_cp_peche_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT exp_cp_peche_id FOREIGN KEY (exp_coup_peche_id) REFERENCES exp_coup_peche(id);


--
-- TOC entry 2287 (class 2606 OID 19209)
-- Dependencies: 1746 2172 1766
-- Name: exp_debris_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_debris_id FOREIGN KEY (exp_debris_id) REFERENCES exp_debris(id);


--
-- TOC entry 2283 (class 2606 OID 19214)
-- Dependencies: 2178 1750 1748
-- Name: exp_force_courant_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_force_courant_id FOREIGN KEY (exp_force_courant_id) REFERENCES exp_force_courant(id);


--
-- TOC entry 2273 (class 2606 OID 19219)
-- Dependencies: 1752 2180 1737
-- Name: exp_fraction_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_fraction_id FOREIGN KEY (exp_fraction_id) REFERENCES exp_fraction(id);


--
-- TOC entry 2288 (class 2606 OID 19224)
-- Dependencies: 1754 2182 1766
-- Name: exp_position_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_position_id FOREIGN KEY (exp_position_id) REFERENCES exp_position(id);


--
-- TOC entry 2281 (class 2606 OID 19229)
-- Dependencies: 1744 2184 1756
-- Name: exp_qualite_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_qualite_id FOREIGN KEY (exp_qualite_id) REFERENCES exp_qualite(id);


--
-- TOC entry 2274 (class 2606 OID 19234)
-- Dependencies: 2186 1737 1758
-- Name: exp_remplissage_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_remplissage_id FOREIGN KEY (exp_remplissage_id) REFERENCES exp_remplissage(id);


--
-- TOC entry 2289 (class 2606 OID 19239)
-- Dependencies: 1766 2108 1684
-- Name: exp_secteur_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_secteur_id FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id);


--
-- TOC entry 2290 (class 2606 OID 19244)
-- Dependencies: 1766 2188 1760
-- Name: exp_sediment_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_sediment_id FOREIGN KEY (exp_sediment_id) REFERENCES exp_sediment(id);


--
-- TOC entry 2284 (class 2606 OID 19249)
-- Dependencies: 1748 2190 1761
-- Name: exp_sens_courant_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_sens_courant_id FOREIGN KEY (exp_sens_courant_id) REFERENCES exp_sens_courant(id);


--
-- TOC entry 2275 (class 2606 OID 19254)
-- Dependencies: 1763 2192 1737
-- Name: exp_sexe_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_sexe_id FOREIGN KEY (exp_sexe_id) REFERENCES exp_sexe(id);


--
-- TOC entry 2276 (class 2606 OID 19259)
-- Dependencies: 2194 1737 1764
-- Name: exp_stade_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_stade_id FOREIGN KEY (exp_stade_id) REFERENCES exp_stade(id);


--
-- TOC entry 2282 (class 2606 OID 19264)
-- Dependencies: 1766 1744 2196
-- Name: exp_station_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_station_id FOREIGN KEY (exp_station_id) REFERENCES exp_station(id);


--
-- TOC entry 2291 (class 2606 OID 19269)
-- Dependencies: 1768 1766 2200
-- Name: exp_vegetation_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_vegetation_id FOREIGN KEY (exp_vegetation_id) REFERENCES exp_vegetation(id);


--
-- TOC entry 2295 (class 2606 OID 19274)
-- Dependencies: 1773 1771 2202
-- Name: ref_categorie_ecologique_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_categorie_ecologique_id FOREIGN KEY (ref_categorie_ecologique_id) REFERENCES ref_categorie_ecologique(id);


--
-- TOC entry 2296 (class 2606 OID 19279)
-- Dependencies: 2204 1773 1772
-- Name: ref_categorie_trophique_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_categorie_trophique_id FOREIGN KEY (ref_categorie_trophique_id) REFERENCES ref_categorie_trophique(id);


--
-- TOC entry 2286 (class 2606 OID 19284)
-- Dependencies: 1773 1752 2206
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id);


--
-- TOC entry 2297 (class 2606 OID 19289)
-- Dependencies: 1773 1773 2206
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (id) REFERENCES ref_espece(id);


--
-- TOC entry 2245 (class 2606 OID 19294)
-- Dependencies: 1679 2206 1773
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id);


--
-- TOC entry 2298 (class 2606 OID 19299)
-- Dependencies: 1773 2208 1774
-- Name: ref_famille_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_famille_id FOREIGN KEY (ref_famille_id) REFERENCES ref_famille(id);


--
-- TOC entry 2299 (class 2606 OID 19304)
-- Dependencies: 2210 1774 1776
-- Name: ref_ordre_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_famille
    ADD CONSTRAINT ref_ordre_id FOREIGN KEY (ref_ordre_id) REFERENCES ref_ordre(id);


--
-- TOC entry 2256 (class 2606 OID 19309)
-- Dependencies: 1685 1683 2106
-- Name: ref_pays_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_systeme
    ADD CONSTRAINT ref_pays_id FOREIGN KEY (ref_pays_id) REFERENCES ref_pays(id);


--
-- TOC entry 2254 (class 2606 OID 19314)
-- Dependencies: 1682 2108 1684
-- Name: ref_secteur_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT ref_secteur_id FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id);


--
-- TOC entry 2263 (class 2606 OID 19319)
-- Dependencies: 2108 1684 1711
-- Name: ref_secteur_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT ref_secteur_id FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id);


--
-- TOC entry 2277 (class 2606 OID 19324)
-- Dependencies: 1739 2110 1685
-- Name: ref_systeme_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT ref_systeme_id FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id);


--
-- TOC entry 2255 (class 2606 OID 19329)
-- Dependencies: 2110 1684 1685
-- Name: ref_systeme_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_secteur
    ADD CONSTRAINT ref_systeme_id FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id);


--
-- TOC entry 2314 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;
GRANT ALL ON SCHEMA public TO devppeao WITH GRANT OPTION;


--
-- TOC entry 2321 (class 0 OID 0)
-- Dependencies: 1793
-- Name: admin_j_user_group; Type: ACL; Schema: public; Owner: devppeao
--

REVOKE ALL ON TABLE admin_j_user_group FROM PUBLIC;


--
-- TOC entry 2343 (class 0 OID 0)
-- Dependencies: 1790
-- Name: admin_usergroups; Type: ACL; Schema: public; Owner: devppeao
--

REVOKE ALL ON TABLE admin_usergroups FROM PUBLIC;


-- Completed on 2008-10-13 15:34:32 CEST

--
-- PostgreSQL database dump complete
--

