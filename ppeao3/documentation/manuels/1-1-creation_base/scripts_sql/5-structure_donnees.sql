--
-- Script permettant de créer la structure des tables de donnees de l'application Web PPEAO
-- Ajout de commentaires sur les tables et colonnes (JME - 07/10 et MS 10/10)
--


SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;

-- ---------------------------------------------------------------------------
-- Name: art_activite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_activite (
    id integer  NOT NULL,
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
-- Name: TABLE art_activite; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_activite IS 'Table de données d''activité de pêche';

--
-- Name: COLUMN art_activite.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.id IS 'id unique (num)';

--
-- Name: COLUMN art_activite.art_unite_peche_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_unite_peche_id  IS 'id d''identification de l''unité de pêche';

--
-- Name: COLUMN art_activite.art_agglomeration_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_agglomeration_id IS 'id de référence à l''agglomération enquêtée';

--
-- Name: COLUMN art_activite.art_type_sortie_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_type_sortie_id IS 'id de référence au type de sortie effectuée';

--
-- Name: COLUMN art_activite.art_grand_type_engin_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_grand_type_engin_id IS 'id de référence au grand type d''engin de pêche)';

--
-- Name: COLUMN art_activite.art_millieu_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_millieu_id  IS 'id de référence au milieu de pêche';

--
-- Name: COLUMN art_activite.date_activite; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.date_activite IS 'date de l''enquête (aaaa-mm-jj)';

--
-- Name: COLUMN art_activite.nbre_unite_recencee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.nbre_unite_recencee IS 'nombre d''unités recensées au cours de la periode d''enquête';

--
-- Name: COLUMN art_activite.annee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.annee IS 'année (aaaa)';

--
-- Name: COLUMN art_activite.mois; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.mois  IS 'mois - période d''enquête (mm)';

--
-- Name: COLUMN art_activite.code; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.code IS 'variable non nécessaire au niveau de bdppeao';

--
-- Name: COLUMN art_activite.nbre_hommes; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.nbre_hommes IS 'nombre d''hommes dans l''unité de pêche';

--
-- Name: COLUMN art_activite.nbre_femmes; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.nbre_femmes IS 'nombre de femmes dans l''unité de pêche';

--
-- Name: COLUMN art_activite.nbre_enfants; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.nbre_enfants  IS 'nombre d''enfants dans l''unité de pêche';

--
-- Name: COLUMN art_activite.art_type_activite_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_activite.art_type_activite_id IS 'id de référence à l''activité déployée par l''unité';

--
-- Name: art_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_activite_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_activite_id_seq OWNER TO devppeao;


--
-- Name: art_activite; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_activite ALTER COLUMN id SET DEFAULT nextval('art_activite_id_seq'::regclass);

--
-- Name: art_artivite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_activite
    ADD CONSTRAINT art_artivite_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_debarquement; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_debarquement (
    id integer NOT NULL,
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
-- Name: TABLE art_debarquement; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_debarquement IS 'Table de données sur les enquêtes de débarquement';

--
-- Name: COLUMN art_debarquement.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.id IS 'id unique (num)';

--
-- Name: COLUMN art_debarquement.art_millieu_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_millieu_id  IS 'id de référence au milieu de pêche';

--
-- Name: COLUMN art_debarquement.art_vent_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_vent_id   IS 'id de référence de la force du vent';

--
-- Name: COLUMN art_debarquement.art_etat_ciel_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_etat_ciel_id  IS 'id de référence de l''état du ciel';

--
-- Name: COLUMN art_debarquement.art_agglomeration_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_agglomeration_id IS 'id de référence de l''agglomération enquêtée';

--
-- Name: COLUMN art_debarquement.art_lieu_de_peche_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_lieu_de_peche_id IS 'id de référence de la zone de pêche';

--
-- Name: COLUMN art_debarquement.art_unite_peche_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_unite_peche_id  IS 'id d''identification de l''unité de pêche';

--
-- Name: COLUMN art_debarquement.art_grand_type_engin_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_grand_type_engin_id IS 'id de référence au grand type d''engin de pêche)';

--
-- Name: COLUMN art_debarquement.art_type_sortie_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.art_type_sortie_id IS 'id de référence au type de sortie effectuée';

--
-- Name: COLUMN art_debarquement.date_depart; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.date_depart IS 'date de début de la sortie de pêche enquêtée (aaaa-mm-jj)';

--
-- Name: COLUMN art_debarquement.heure_depart; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.heure_depart IS 'heure de début de la sortie de pêche (hh:mm:00)';

--
-- Name: COLUMN art_debarquement.heure; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.heure IS 'heure de retour au débarquement de l''unité de pêche (hh:mm:00)';

--
-- Name: COLUMN art_debarquement.heure_pose_engin; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.heure_pose_engin IS 'heure de pose de l''engin de pêche (hh:mm:00)';

--
-- Name: COLUMN art_debarquement.nbre_coups_de_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.nbre_coups_de_peche IS 'nombre de coups de pêche réalisés';

--
-- Name: COLUMN art_debarquement.poids_total; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.poids_total IS 'capture totale estimée (kg)';

--
-- Name: COLUMN art_debarquement.glaciere; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.glaciere IS 'présence d''une glacière à bord';

--
-- Name: COLUMN art_debarquement.distance_lieu_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.distance_lieu_peche IS 'distance du point d''enquête au lieu de pêche (mn)';

--
-- Name: COLUMN art_debarquement.annee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.annee IS 'année (aaaa)';

--
-- Name: COLUMN art_debarquement.mois; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.mois  IS 'mois - période d''enquête (mm)';

--
-- Name: COLUMN art_debarquement.memo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.memo IS 'commentaire sur le débarquement';

--
-- Name: COLUMN art_debarquement.code; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.code IS 'variable non nécessaire au niveau de bdppeao';

--
-- Name: COLUMN art_debarquement.nbre_hommes; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.nbre_hommes IS 'nombre d''hommes dans l''unité de pêche';

--
-- Name: COLUMN art_debarquement.nbre_femmes; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.nbre_femmes IS 'nombre de femmes dans l''unité de pêche';

--
-- Name: COLUMN art_debarquement.nbre_enfants; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.nbre_enfants  IS 'nombre d''enfants dans l''unité de pêche';

--
-- Name: COLUMN art_debarquement.date_debarquement; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement.date_debarquement IS 'date de l''enquête (aaaa-mm-jj)';

--
-- Name: art_debarquement_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_debarquement_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_debarquement_id_seq OWNER TO devppeao;


--
-- Name: art_debarquement; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_debarquement ALTER COLUMN id SET DEFAULT nextval('art_debarquement_id_seq'::regclass);

--
-- Name: art_debarquement_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_debarquement
    ADD CONSTRAINT art_debarquement_pkey PRIMARY KEY (id);

-- ---------------------------------------------------------------------------
-- Name: art_debarquement_rec; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_debarquement_rec (
    id character varying(20) NOT NULL,
    poids_total real NOT NULL,
    art_debarquement_id integer NOT NULL
);


ALTER TABLE public.art_debarquement_rec OWNER TO devppeao;


--
-- Name: TABLE art_debarquement_rec; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_debarquement_rec IS 'Table de données de captures totales recomposées';

--
-- Name: COLUMN art_debarquement_rec.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement_rec.id IS 'id unique (char)';

--
-- Name: COLUMN art_debarquement_rec.poids_total ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement_rec.poids_total  IS 'capture totale recomposée';

--
-- Name: COLUMN art_debarquement_rec.art_debarquement_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_debarquement_rec.art_debarquement_id   IS 'id de référence à l''enquête de débarquement correspondante';

--
-- Name: art_debarquement_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
-- Name: art_engin_activite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_engin_activite (
    id integer NOT NULL,
    code integer,
    nbre integer,
    art_activite_id integer,
    art_type_engin_id character varying(10)
);


ALTER TABLE public.art_engin_activite OWNER TO devppeao;


--
-- Name: TABLE art_engin_activite; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_engin_activite IS 'Table des engins de pêche observés lors des enquêtes sur l''activité';

--
-- Name: COLUMN art_engin_activite.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_activite.id IS 'id unique (num)';

--
-- Name: COLUMN art_engin_activite.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_activite.code  IS 'variable non utilisée par bdppeao';

--
-- Name: COLUMN art_engin_activite.nbre  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_activite.nbre   IS 'nombre d''engins de pêche déclarés';

--
-- Name: COLUMN art_engin_activite.art_activite_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_activite.art_activite_id  IS 'id de référence à l''enquête d''activité concernée';

--
-- Name: COLUMN art_engin_activite.art_type_engin_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_activite.art_type_engin_id   IS 'id de référence à la table des engins de pêche';

--
-- Name: art_engin_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_engin_activite_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_activite_id_seq OWNER TO devppeao;


--
-- Name: art_engin_activite; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_engin_activite ALTER COLUMN id SET DEFAULT nextval('art_engin_activite_id_seq'::regclass);

--
-- Name: art_engin_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_engin_activite
    ADD CONSTRAINT art_engin_activite_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_engin_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_engin_peche (
    id integer  NOT NULL,
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
-- Name: TABLE art_engin_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_engin_peche IS 'Table des engins de pêche observés lors d''une enquête de débarquement';

--
-- Name: COLUMN art_engin_peche.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.id IS 'id unique (num)';

--
-- Name: COLUMN art_engin_peche.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.code  IS 'variable non utilisée par bdppeao';

--
-- Name: COLUMN art_engin_peche.longueur  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.longueur  IS 'longueur totale de l''engin de pêche (m)';

--
-- Name: COLUMN art_engin_peche.hauteur ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.hauteur IS 'Chute du filet de pêche (decim)';

--
-- Name: COLUMN art_engin_peche.nbre_nap ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.nbre_nap  IS 'nombre de nappes de filet';

--
-- Name: COLUMN art_engin_peche.nombre  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.nombre  IS 'nombre d''engins de pêche';

--
-- Name: COLUMN art_engin_peche.nbre_eff  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.nbre_eff  IS 'nombre d''effort de pêche';

--
-- Name: COLUMN art_engin_peche.nbre_mail_ham ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.nbre_mail_ham IS 'dimension de la maille (mm) ou de l''hamecon';

--
-- Name: COLUMN art_engin_peche.num_ham ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.num_ham  IS 'nombre d''hamecons';

--
-- Name: COLUMN art_engin_peche.proprietaire  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.proprietaire  IS 'relation avec le propriétaire';

--
-- Name: COLUMN art_engin_peche.art_debarquement_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.art_debarquement_id  IS 'id de référence à l''enquête de débarquement';

--
-- Name: COLUMN art_engin_peche.art_type_engin_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_engin_peche.art_type_engin_id   IS 'id de référence à la table des engins de pêche';


--
-- Name: art_engin_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_engin_peche_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_peche_id_seq OWNER TO devppeao;


--
-- Name: art_engin_peche; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_engin_peche ALTER COLUMN id SET DEFAULT nextval('art_engin_peche_id_seq'::regclass);

--
-- Name: art_engin_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_engin_peche
    ADD CONSTRAINT art_engin_peche_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
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


ALTER TABLE public.art_fraction OWNER TO devppeao;


--
-- Name: TABLE art_fraction; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_fraction IS 'Table des fractions composant une enquête de débarquement';

--
-- Name: COLUMN art_fraction.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.id IS 'id unique (char)';

--
-- Name: COLUMN art_fraction.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.code  IS 'variable non utilisée par bdppeao';

--
-- Name: COLUMN art_fraction.poids  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.poids  IS 'poids de la fraction débarquée (kg)';

--
-- Name: COLUMN art_fraction.nbre_poissons ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.nbre_poissons IS 'nombre d''individus de la fraction débarquée';

--
-- Name: COLUMN art_fraction.debarquee ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.debarquee  IS 'fraction observée (=0) ou signalée par le pêcheur (=1)';

--
-- Name: COLUMN art_fraction.ref_espece_id   ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.ref_espece_id   IS 'id de référence à l''espèce';

--
-- Name: COLUMN art_fraction.art_debarquement_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.art_debarquement_id  IS 'id de référence à l''enquête de débarquement';

--
-- Name: COLUMN art_fraction.prix ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.prix IS 'prix de la fraction débarquée (FCFA)';


--
-- Name: art_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_fraction
    ADD CONSTRAINT art_fraction_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
-- Name: art_fraction_rec; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_fraction_rec (
    id character varying(20) NOT NULL,
    poids real NOT NULL,
    nbre_poissons integer NOT NULL,
    ref_espece_id character varying(10) NOT NULL
);


ALTER TABLE public.art_fraction_rec OWNER TO devppeao;

--
-- Name: TABLE art_fraction_rec; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_fraction_rec IS 'Table des fractions recomposéees ';

--
-- Name: COLUMN art_fraction_rec.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction_rec.id IS 'id unique (char)';

--
-- Name: COLUMN art_fraction_rec.poids  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction_rec.poids  IS 'poids recomposé de la fraction débarquée (kg)';

--
-- Name: COLUMN art_fraction_rec.nbre_poissons ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction_rec.nbre_poissons IS 'nombre d''individus recomposé de la fraction débarquée';

--
-- Name: COLUMN art_fraction.ref_espece_id   ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_fraction.ref_espece_id   IS 'id de référence à l''espèce';

--
-- Name: art_fraction_rec_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_fraction_rec
    ADD CONSTRAINT art_fraction_rec_pkey PRIMARY KEY (id);

-- ---------------------------------------------------------------------------
-- Name: art_lieu_de_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_lieu_de_peche (
    id integer NOT NULL,
    ref_secteur_id integer,
    libelle character varying(50),
    code integer
);



ALTER TABLE public.art_lieu_de_peche OWNER TO devppeao;

--
-- Name: TABLE art_lieu_de_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_lieu_de_peche IS 'Table des lieux de pêche';

--
-- Name: COLUMN art_lieu_de_peche.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_lieu_de_peche.id IS 'id unique (num)';

--
-- Name: COLUMN art_lieu_de_peche.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_lieu_de_peche.code  IS 'variable non utilisée par bdppeao';

--
-- Name: COLUMN art_lieu_de_peche.ref_secteur_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_lieu_de_peche.ref_secteur_id  IS 'id de référence au secteur du lieu de pêche';

--
-- Name: COLUMN art_lieu_de_peche.libelle ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_lieu_de_peche.libelle IS 'libellé du lieu de pêche';


--
-- Name: art_lieu_de_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_lieu_de_peche_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_lieu_de_peche_id_seq OWNER TO devppeao;


--
-- Name: art_lieu_de_peche; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_lieu_de_peche ALTER COLUMN id SET DEFAULT nextval('art_lieu_de_peche_id_seq'::regclass);

--
-- Name: art_lieu_de_peche_id_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_id_pkey PRIMARY KEY (id);


-- -------------------------------------------------------------------------------------
-- Name: art_periode_enquete; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_periode_enquete (
    id integer NOT NULL,
    art_agglomeration_id integer NOT NULL,
    annee integer NOT NULL,
    mois integer NOT NULL,
    date_debut date,
    date_fin date,
    description character varying(100),
    exec_recomp boolean DEFAULT false,
    date_recomp date,
    exec_stat boolean DEFAULT false,
    date_stat date
);


ALTER TABLE public.art_periode_enquete OWNER TO devppeao;

--
-- Name: TABLE art_periode_enquete; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_periode_enquete IS 'Table recensant les périodes d''enquête (agglomération / année / mois )';


--
-- Name: COLUMN art_periode_enquete.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.id IS 'id unique (num)';


--
-- Name: COLUMN art_periode_enquete.art_agglomeration_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.art_agglomeration_id IS 'id de référence à l''agglomération  (clé unique 1/3)';


--
-- Name: COLUMN art_periode_enquete.annee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.annee IS 'annee de la période d''enquête (aaaa)  (clé unique 2/3)';


--
-- Name: COLUMN art_periode_enquete.mois; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.mois IS 'mois de la période d''enquête (mm) (clé unique 3/3)';


--
-- Name: COLUMN art_periode_enquete.date_debut; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.date_debut IS 'date de début de la période d''enquête (aaaa-mm-jj)';


--
-- Name: COLUMN art_periode_enquete.date_fin; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.date_fin IS 'date de fin de la période d''enquête (aaaa-mm-jj)';


--
-- Name: COLUMN art_periode_enquete.description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.description IS 'description de la période d''enquête';


--
-- Name: COLUMN art_periode_enquete.exec_recomp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.exec_recomp IS 'la recomposition a t elle été effectuée pour cette période';


--
-- Name: COLUMN art_periode_enquete.date_recomp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.date_recomp IS 'date de la recomposition quand effectuée';


--
-- Name: COLUMN art_periode_enquete.exec_stat; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.exec_stat IS 'les statistiques de pêche ont elles été estimées pour cette période';


--
-- Name: COLUMN art_periode_enquete.date_stat; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_periode_enquete.date_stat IS 'date utile pour les calculs';

--
-- Name: art_periode_enquete_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_periode_enquete
    ADD CONSTRAINT art_periode_enquete_pkey PRIMARY KEY (id);


--
-- Name: Aggl_annee_mois; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE UNIQUE INDEX "Aggl_annee_mois" ON art_periode_enquete USING btree (art_agglomeration_id, annee, mois);



-- ---------------------------------------------------------------------------
-- Name: art_poisson_mesure; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_poisson_mesure (
    id integer NOT NULL,
    code integer,
    taille integer,
    art_fraction_id character varying(15)
);



ALTER TABLE public.art_poisson_mesure OWNER TO devppeao;

--
-- Name: TABLE art_poisson_mesure; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_poisson_mesure IS 'Table des longueurs des individus mesurés ';

--
-- Name: COLUMN art_poisson_mesure.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_poisson_mesure.id IS 'id unique (num)';

--
-- Name: COLUMN art_poisson_mesure.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_poisson_mesure.code  IS 'variable non utilisée par bdppeao';

--
-- Name: COLUMN art_poisson_mesure.taille ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_poisson_mesure.taille  IS 'longueur des individus (cm)';

--
-- Name: COLUMN art_poisson_mesure.art_fraction_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_poisson_mesure.art_fraction_id IS 'id de référence de l''indivdu mesuré à sa fraction débarquée';

--
-- Name: art_poisson_mesure_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_poisson_mesure_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_poisson_mesure_id_seq OWNER TO devppeao;


--
-- Name: art_poisson_mesure; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_poisson_mesure ALTER COLUMN id SET DEFAULT nextval('art_poisson_mesure_id_seq'::regclass);

--
-- Name: art_poisson_mesure_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_pkey PRIMARY KEY (id);



-- --------------------------------------------------------------------------------
-- Name: art_stat_effort; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_stat_effort (
    effort_id integer NOT NULL,
    ref_systeme_id integer  NOT NULL,
    ref_secteur_id integer  NOT NULL,
    art_grand_type_engin_id character varying(10) NOT NULL,
    annee integer NOT NULL,
    ref_mois_id integer NOT NULL,
    effort_date date,
    art_param_type_effort_id integer NOT NULL,
    effort_valeur integer NOT NULL,
    commentaire text
);


ALTER TABLE public.art_stat_effort OWNER TO devppeao;

--
-- Name: TABLE art_stat_effort; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_stat_effort IS 'Table des efforts de pêche saisis par les administrateurs, pour le calcul de statistiques';


--
-- Name: COLUMN art_stat_effort.effort_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.effort_id IS 'id unique (num)';


--
-- Name: COLUMN art_stat_effort.ref_systeme_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.ref_systeme_id IS 'id du système pour lequel l''effort est enregistré  (note : on saisit un secteur OU un système)';


--
-- Name: COLUMN art_stat_effort.ref_secteur_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.ref_secteur_id IS 'id du secteur pour lequel l''effort est enregistré (note : on saisit un secteur OU un système)';


--
-- Name: COLUMN art_stat_effort.art_grand_type_engin_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.art_grand_type_engin_id IS 'id du grand type d''engin de pêche';


--
-- Name: COLUMN art_stat_effort.annee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.annee IS 'année d''estimation de l''effort de pêche (aaaa)';


--
-- Name: COLUMN art_stat_effort.ref_mois_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.ref_mois_id IS 'mois d''estimation de l''effort de pêche (mm)';


--
-- Name: COLUMN art_stat_effort.effort_date; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.effort_date IS 'date de l''estimation de l''effort de pêche';


--
-- Name: COLUMN art_stat_effort.art_param_type_effort_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.art_param_type_effort_id IS 'id du type de mesure d''effort';


--
-- Name: COLUMN art_stat_effort.effort_valeur; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.effort_valeur IS 'valeur de l''effort mesuré';


--
-- Name: COLUMN art_stat_effort.commentaire; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_effort.commentaire IS 'commentaire ';


--
-- Name: art_stat_effort_effort_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_stat_effort_effort_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_stat_effort_effort_id_seq OWNER TO devppeao;

ALTER TABLE art_stat_effort ALTER COLUMN effort_id SET DEFAULT nextval('art_stat_effort_effort_id_seq'::regclass);

--
-- Name: art_stat_effort_effort_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_effort_id_key UNIQUE (effort_id);



-- ---------------------------------------------------------------------------
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


ALTER TABLE public.art_stat_gt OWNER TO devppeao;

--
-- Name: TABLE art_stat_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_stat_gt IS 'Table des statistiques de pêche par grand type d''engin de pêche';


--
-- Name: COLUMN art_stat_gt.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.id IS 'id unique (num)';


--
-- Name: COLUMN art_stat_gt.obs_gt_min; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.obs_gt_min IS 'capture minimale observée pour ce GT';


--
-- Name: COLUMN art_stat_gt.obs_gt_max; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.obs_gt_max IS 'capture maximale observée pour ce GT';


--
-- Name: COLUMN art_stat_gt.pue_gt_ecart_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.pue_gt_ecart_type IS 'écart type de la pue du GT)';


--
-- Name: COLUMN art_stat_gt.pue_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.pue_gt IS 'pue du GT (kg)';


--
-- Name: COLUMN art_stat_gt.fpe_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.fpe_gt IS 'effort de pêche du GT, estimé pour la période d''enquêtes ';

--
-- Name: COLUMN art_stat_gt.fm_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.fm_gt IS 'effort de pêche mensuel pour ce GT';


--
-- Name: COLUMN art_stat_gt.cap_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.cap_gt IS 'captures totales mensuelles pour ce GT (kg)';

--
-- Name: COLUMN art_stat_gt.art_grand_type_engin_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.art_grand_type_engin_id IS 'id du grand type d''engin de pêche';


--
-- Name: COLUMN art_stat_gt.art_stat_totale_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.art_stat_totale_id IS 'id de référence à la table de statistiques générales';


--
-- Name: COLUMN art_stat_gt.nbre_enquete_gt; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt.nbre_enquete_gt IS 'nombre d''enquêtes réalisées sur ce GT';


--
-- Name: art_stat_gt_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt
    ADD CONSTRAINT art_stat_gt_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
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



ALTER TABLE public.art_stat_gt_sp OWNER TO devppeao;

--
-- Name: TABLE art_stat_gt_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_stat_gt_sp IS 'Table des statistiques de pêche par espèce et par grand type d''engin de pêche';


--
-- Name: COLUMN art_stat_gt_sp.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.id IS 'id unique (num)';


--
-- Name: COLUMN art_stat_gt_sp.obs_gt_sp_min; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.obs_gt_sp_min IS 'capture minimale par espèce observée pour ce GT';


--
-- Name: COLUMN art_stat_gt_sp.obs_gt_sp_max; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.obs_gt_sp_max IS 'capture maximale par espèce observée pour ce GT';


--
-- Name: COLUMN art_stat_gt_sp.pue_gt_sp_ecart_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.pue_gt_sp_ecart_type IS 'écart type de la pue spécifique du GT)';


--
-- Name: COLUMN art_stat_gt_sp.pue_gt_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.pue_gt_sp IS 'pue spécifique du GT (kg)';


--
-- Name: COLUMN art_stat_gt_sp.cap_gt_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.cap_gt_sp IS 'captures mensuelles par espèce pour ce GT (kg)';

--
-- Name: COLUMN art_stat_gt_sp.ref_espece_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.ref_espece_id IS 'id de référence à la table des espèces';


--
-- Name: COLUMN art_stat_gt_sp.art_stat_gt_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.art_stat_gt_id IS 'id de référence à la table de statistiques par GT';


--
-- Name: COLUMN art_stat_gt_sp.nbre_enquete_gt_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_gt_sp.nbre_enquete_gt_sp IS 'nombre d''enquêtes avec présence de l''espèce dans le GT';

--
-- Name: art_stat_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_gt_sp
    ADD CONSTRAINT art_stat_gt_sp_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
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
-- Name: TABLE art_stat_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_stat_sp IS 'Table des statistiques de pêche par espèce ';


--
-- Name: COLUMN art_stat_sp.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.id IS 'id unique (num)';


--
-- Name: COLUMN art_stat_sp.obs_sp_min; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.obs_sp_min IS 'capture minimale observée par espèce';


--
-- Name: COLUMN art_stat_sp.obs_sp_max; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.obs_sp_max IS 'capture maximale observée par espèce ';


--
-- Name: COLUMN art_stat_sp.pue_sp_ecart_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.pue_sp_ecart_type IS 'écart type de la pue par espèce)';


--
-- Name: COLUMN art_stat_sp.pue_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.pue_sp IS 'pue par espèce (kg)';


--
-- Name: COLUMN art_stat_sp.cap_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.cap_sp IS 'captures mensuelles par espèce (kg)';

--
-- Name: COLUMN art_stat_sp.ref_espece_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.ref_espece_id IS 'id de référence à la table des espèces';


--
-- Name: COLUMN art_stat_sp.art_stat_totale_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.art_stat_totale_id IS 'id de référence à la table de statistiques générales';


--
-- Name: COLUMN art_stat_sp.nbre_enquete_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_sp.nbre_enquete_sp IS 'nombre d''enquêtes avec présence de l''espèce ';


--
-- Name: art_stat_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_pkey PRIMARY KEY (id);

-- ---------------------------------------------------------------------------
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
    nbre_jour_activite integer,
    nbre_jour_enq_deb integer
);


ALTER TABLE public.art_stat_totale OWNER TO devppeao;

--
-- Name: TABLE art_stat_totale; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_stat_totale IS 'Table des statistiques générales de pêche  ';


--
-- Name: COLUMN art_stat_totale.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.id IS 'id unique (num)';


--
-- Name: COLUMN art_stat_totale.annee; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.annee IS 'année (aaaa) ';


--
-- Name: COLUMN art_stat_totale.mois; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.mois IS 'mois, période d''enquêtes (mm) ';


--
-- Name: COLUMN art_stat_totale.nbre_obs; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.nbre_obs IS 'nombre d''enquêtes de débarquement';

--
-- Name: COLUMN art_stat_totale.obs_min; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.obs_min IS 'capture minimale observée ';


--
-- Name: COLUMN art_stat_totale.obs_max; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.obs_max IS 'capture maximale observée ';


--
-- Name: COLUMN art_stat_totale.pue_ecart_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.pue_ecart_type IS 'écart type de la pue )';


--
-- Name: COLUMN art_stat_totale.pue; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.pue IS 'pue (kg)';


--
-- Name: COLUMN art_stat_totale.fpe; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.fpe IS 'effort de pêche estimé au cours de la période d''enquêtes ';

--
-- Name: COLUMN art_stat_totale.fm; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.fm IS 'effort de pêche total mensuel ';

--
-- Name: COLUMN art_stat_totale.cap; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.cap IS 'captures mensuelles (kg)';

--
-- Name: COLUMN art_stat_totale.art_agglomeration_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.art_agglomeration_id IS 'id de référence à la table des agglomérations';


--
-- Name: COLUMN art_stat_totale.nbre_unite_recensee_periode ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.nbre_unite_recensee_periode  IS 'nombre d''unités de pêche recensées au cours de la période d''enquêtes';

--
-- Name: COLUMN art_stat_totale.nbre_jour_activite ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.nbre_jour_activite  IS 'nombre de jours d''enquêtes sur l''activité de pêche';

--
-- Name: COLUMN art_stat_totale.nbre_jour_enq_deb; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_stat_totale.nbre_jour_enq_deb IS 'nombre total d''enquêtes de débarquement';




--
-- Name: art_stat_totale_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_taille_sp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_taille_sp (
    id integer NOT NULL,
    li character varying(10),
    xi integer,
    art_stat_sp_id integer NOT NULL
);



ALTER TABLE public.art_taille_sp OWNER TO devppeao;

--
-- Name: TABLE art_taille_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_taille_sp IS 'Table des distributions de fréquence de taille par espèce (rapportées à la capture estimée pour l''espèce)  ';


--
-- Name: COLUMN art_taille_sp.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_sp.id IS 'id unique (num)';


--
-- Name: COLUMN art_taille_sp.li; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_sp.li IS 'classe de taille (cm) ';


--
-- Name: COLUMN art_taille_sp.xi; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_sp.xi IS 'effectif ';


--
-- Name: COLUMN art_taille_sp.art_stat_sp_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_sp.art_stat_sp_id IS 'id de référence à la table des fractions débarquées';


--
-- Name: art_taille_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
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
-- Name: TABLE art_taille_gt_sp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_taille_gt_sp IS 'Table des distributions de fréquence de taille par espèce et par Grand Type d''engin (rapportées à la capture estimée pour l''espèce)  ';


--
-- Name: COLUMN art_taille_gt_sp.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_gt_sp.id IS 'id unique (num)';


--
-- Name: COLUMN art_taille_gt_sp.li; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_gt_sp.li IS 'classe de taille (cm) ';


--
-- Name: COLUMN art_taille_gt_sp.xi; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_gt_sp.xi IS 'effectif ';


--
-- Name: COLUMN art_taille_gt_sp.art_stat_gt_sp_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_taille_gt_sp.art_stat_gt_sp_id  IS 'id de référence à la table des fractions débarquées par GT';


--
-- Name: art_taille_gt_sp_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_pkey PRIMARY KEY (id);


-- -------------------------------------------------------------------------------
-- Name: art_unite_peche; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_unite_peche (
    id integer  NOT NULL,
    art_categorie_socio_professionnelle_id integer,
    libelle character varying(50),
    libelle_menage character varying(50),
    code integer,
    art_agglomeration_id integer,
    base_pays character varying(50)
);



ALTER TABLE public.art_unite_peche OWNER TO devppeao;

--
-- Name: TABLE art_unite_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_unite_peche IS 'Table des unités de pêche identifiées au cours des enquêtes de pêche artisanale ';


--
-- Name: COLUMN art_unite_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.id IS 'id unique (num)';


--
-- Name: COLUMN art_unite_peche.art_categorie_socio_professionnelle_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.art_categorie_socio_professionnelle_id IS 'id de référence à la table des catégories socio-prof. ';


--
-- Name: COLUMN art_unite_peche.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.libelle IS 'identification de l''unité de pêche ';


--
-- Name: COLUMN art_unite_peche.libelle_menage ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.libelle_menage  IS 'identification complémentaire';


--
-- Name: COLUMN art_unite_peche.code ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.code  IS 'variable inutilisée dans PPPEAO';


--
-- Name: COLUMN art_unite_peche.art_agglomeration_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.art_agglomeration_id   IS 'id de référence à l''agglomération d''origine de l''unité';


--
-- Name: COLUMN art_unite_peche.base_pays ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_unite_peche.base_pays  IS 'variable inutilisée dans PPPEAO';


--
-- Name: art_unite_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_unite_peche_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_unite_peche_id_seq OWNER TO devppeao;


--
-- Name: art_unite_peche; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_unite_peche ALTER COLUMN id SET DEFAULT nextval('art_unite_peche_id_seq'::regclass);

--
-- Name: art_unite_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_pkey PRIMARY KEY (id);


-- ---------------------------------------------------------------------------
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



ALTER TABLE public.exp_biologie OWNER TO devppeao;

--
-- Name: TABLE exp_biologie; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_biologie IS 'Table des individus échantillonnés pour la biologie';


--
-- Name: COLUMN exp_biologie.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.id IS 'id unique (num)';


--
-- Name: COLUMN exp_biologie.longueur; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.longueur IS 'Longueur à la fourche (mm)';


--
-- Name: COLUMN exp_biologie.longueur_totale; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.longueur_totale IS 'Longueur totale (mm)';


--
-- Name: COLUMN exp_biologie.poids ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.poids IS 'poids individuel (g)';


--
-- Name: COLUMN exp_biologie.exp_sexe_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.exp_sexe_id  IS 'code du sexe de l''individu';


--
-- Name: COLUMN exp_biologie.exp_stade_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.exp_stade_id   IS 'code du stade de maturité sexuelle de l''individu';


--
-- Name: COLUMN exp_biologie.exp_remplissage_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.exp_remplissage_id  IS 'code du taux de remplissage stomacal ';


--
-- Name: COLUMN exp_biologie.exp_fraction_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.exp_fraction_id   IS 'numéro de la fraction pêchée à laquelle appartient l''individu ';


--
-- Name: COLUMN exp_biologie.memo ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.memo  IS 'commentaire sur l''individu';

-
-- Name: COLUMN exp_biologie.valeur_estimee ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_biologie.valeur_estimee  IS 'le poids ou la longueur a été estimé (1) ou mesuré (0)';


--
-- Name: ref_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT ref_biologie_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_campagne; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_campagne (
    id integer NOT NULL,
    ref_systeme_id integer,
    numero_campagne integer,
    date_debut date,
    date_fin date,
    libelle character varying(100)
);



ALTER TABLE public.exp_campagne OWNER TO devppeao;

--
-- Name: TABLE exp_campagne; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_campagne IS 'Table des campagnes de pêche scientifique ';


--
-- Name: COLUMN exp_campagne.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.id IS 'id unique (num)';


--
-- Name: COLUMN exp_campagne.ref_systeme_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.ref_systeme_id IS 'système aquatique où a eu lieu la campagne';


--
-- Name: COLUMN exp_campagne.numero_campagne; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.numero_campagne IS 'numéro de la campagne à l''intérieur du système  ';


--
-- Name: COLUMN exp_campagne.date_debut ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.date_debut IS 'date de début de la campagne (aaaa-mm-jj)';


--
-- Name: COLUMN exp_campagne.date_fin ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.date_fin IS 'date de fin de la campagne (aaaa-mm-jj)';


--
-- Name: COLUMN exp_campagne.libelle ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_campagne.libelle   IS 'libellé de la campagne';



--
-- Name: exp_campagne_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE exp_campagne_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_campagne_id_seq OWNER TO devppeao;


--
-- Name: exp_campagne; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE exp_campagne ALTER COLUMN id SET DEFAULT nextval('exp_campagne_id_seq'::regclass);

--
-- Name: exp_campagne_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_pkey PRIMARY KEY (id);


---------------------------------------------------------------------------
-- Name: exp_coup_peche; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_coup_peche (
    id integer NOT NULL,
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
-- Name: TABLE exp_coup_peche; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_coup_peche IS 'Table des coups de pêche'; 


--
-- Name: COLUMN exp_coup_peche.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.id IS 'id unique (num)';


--
-- Name: COLUMN exp_coup_peche.date_cp; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.date_cp IS 'date du coup de pêche';


--
-- Name: COLUMN exp_coup_peche.longitude; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.longitude IS 'longitude du coup de pêche (-XXX:XX:XX)';


--
-- Name: COLUMN exp_coup_peche.latitude ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.latitude IS 'latitude du coup de pêche (+XX:XX:XX)    ';


--
-- Name: COLUMN exp_coup_peche.memo ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.memo IS 'commentaire sur le coup de pêche';


--
-- Name: COLUMN exp_coup_peche.profondeur ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.profondeur  IS 'profondeur du coup de pêche (m)   ';


--
-- Name: COLUMN exp_coup_peche.exp_qualite_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.exp_qualite_id  IS 'code qualité (réussite) du coup de pêche';


--
-- Name: COLUMN exp_coup_peche.exp_campagne_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.exp_campagne_id  IS 'code de la campagne d''appartenance du coup de pêche ';


--
-- Name: COLUMN exp_coup_peche.exp_station_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.exp_station_id  IS 'code station';


--
-- Name: COLUMN exp_coup_peche.numero_filet ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.numero_filet  IS 'numéro du filet dans la batterie si filet maillant';


--
-- Name: COLUMN exp_coup_peche.numero_coup  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.numero_coup   IS 'numéro d''ordre du coup de pêche dans la campagne';


--
-- Name: COLUMN exp_coup_peche.exp_engin_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.exp_engin_id   IS 'code engin de pêche';

--
-- Name: COLUMN exp_coup_peche.protocole ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.protocole  IS 'appartenance du coup de pêche au protocole d''échantillonnage standard (1) ou pas (0) ';

--
-- Name: COLUMN exp_coup_peche.heure_debut ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.heure_debut  IS 'heure de début du coup de pêche (hh:mn:00)';


--
-- Name: COLUMN exp_coup_peche.heure_fin  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.heure_fin   IS 'heure de fin du coup de pêche (hh:mn:00)';


--
-- Name: COLUMN exp_coup_peche.exp_environnement_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_coup_peche.exp_environnement_id   IS 'numéro de l''enregistrement correspondant dans exp_environnement';



--
-- Name: exp_cp_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE exp_cp_peche_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_cp_peche_id_seq OWNER TO devppeao;


--
-- Name: exp_coup_peche; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE exp_coup_peche ALTER COLUMN id SET DEFAULT nextval('exp_cp_peche_id_seq'::regclass);

--
-- Name: exp_cp_peche_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_coup_peche
    ADD CONSTRAINT exp_cp_peche_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

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



ALTER TABLE public.exp_environnement OWNER TO devppeao;

--
-- Name: TABLE exp_environnement; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_environnement IS 'Table des relevés environnementaux associés aux coups de pêche ';


--
-- Name: COLUMN exp_environnement.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.id IS 'id unique (num)';


--
-- Name: COLUMN exp_environnement.transparence; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.transparence IS 'transparence (m)';


--
-- Name: COLUMN exp_environnement.salinite_surface; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.salinite_surface IS 'salinité de surface';


--
-- Name: COLUMN exp_environnement.salinite_fond ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.salinite_fond IS 'salinité de fond';


--
-- Name: COLUMN exp_environnement.temperature_surface ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.temperature_surface IS 'température de surface (degrés C)';


--
-- Name: COLUMN exp_environnement.temperature_fond ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.temperature_fond  IS 'température de fond (degrés C)';


--
-- Name: COLUMN exp_environnement.oxygene_surface ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.oxygene_surface  IS 'pourcentage d''oxygène ensurface';


--
-- Name: COLUMN exp_environnement.oxygene_fond ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.oxygene_fond  IS 'pourcentage d''oxygène au fond';


--
-- Name: COLUMN exp_environnement.chlorophylle_surface ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.chlorophylle_surface  IS 'chlorophylle en surface (microg par l)';


--
-- Name: COLUMN exp_environnement.chlorophylle_fond ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.chlorophylle_fond  IS 'chlorophylle au fond (microg par l) ';


--
-- Name: COLUMN exp_environnement.mot_surface ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.mot_surface   IS 'Matière Organique Totale en surface (microg par l)';


--
-- Name: COLUMN exp_environnement.mot_fond  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.mot_fond   IS 'Matière Organique Totale au fond (microg par l) ';

--
-- Name: COLUMN exp_environnement.mop_surface ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.mop_surface  IS 'Matière Organique Particulaire en surface (microg par l) ';


--
-- Name: COLUMN exp_environnement.mop_fond ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.mop_fond  IS 'Matière Organique Particulaire au fond (microg par l) ';


--
-- Name: COLUMN exp_environnement.conductivite_surface  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.conductivite_surface   IS 'conductivité en surface (en dixièmes de microSiemens) ';


--
-- Name: COLUMN exp_environnement.conductivite_fond  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.conductivite_fond   IS 'conductivité au fond (en dixièmes de microSiemens) ';


--
-- Name: COLUMN exp_environnement.memo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.memo  IS 'commentaire sur le relevé environnemental ';


--
-- Name: COLUMN exp_environnement.exp_force_courant_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.exp_force_courant_id   IS 'code force du courant ';


--
-- Name: COLUMN exp_environnement.exp_sens_courant_id  ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_environnement.exp_sens_courant_id  IS ' code sens du courant';



--
-- Name: exp_environnement_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_environnement
    ADD CONSTRAINT exp_environnement_pkey PRIMARY KEY (id);




-- ---------------------------------------------------------------------------
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




ALTER TABLE public.exp_fraction OWNER TO devppeao;

--
-- Name: TABLE exp_fraction; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_fraction IS 'Table des fractions pêchées (groupe d''individus d''une même espèce dans un coup de pêche)';


--
-- Name: COLUMN exp_fraction.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.id IS 'id unique (num)';


--
-- Name: COLUMN exp_fraction.nombre_total; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.nombre_total IS 'effectif total de la fraction';


--
-- Name: COLUMN exp_fraction.poids_total; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.poids_total IS 'poids total de la fraction (g)';


--
-- Name: COLUMN exp_fraction.memo ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.memo IS 'commentaire sur la fraction';


--
-- Name: COLUMN exp_fraction.ref_espece_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.ref_espece_id IS 'code espèce';


--
-- Name: COLUMN exp_fraction.exp_coup_peche_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.exp_coup_peche_id  IS 'coup de pêche d''appartenance';


--
-- Name: COLUMN exp_fraction.nombre_estime ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_fraction.nombre_estime  IS 'le nombre d''individus a été estimé (1) ou compté (0) ';



--
-- Name: ref_fraction_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_fraction
    ADD CONSTRAINT ref_fraction_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_trophique; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_trophique (
    id integer NOT NULL,
    exp_biologie_id integer NOT NULL,
    exp_contenu_id integer NOT NULL,
    quantite real
);


ALTER TABLE public.exp_trophique OWNER TO devppeao;

--
-- Name: TABLE exp_trophique; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_trophique IS 'Table des couples associant individu échantillonné et type de contenu stomacal ';


--
-- Name: COLUMN exp_trophique.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_trophique.id IS 'id unique (num)';


--
-- Name: COLUMN exp_trophique.exp_biologie_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_trophique.exp_biologie_id IS 'numéro de l''individu dans la table exp_biologie';


--
-- Name: COLUMN exp_trophique.exp_contenu_id ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_trophique.exp_contenu_id  IS 'code du type de contenu stomacal dans la table exp_contenu';


--
-- Name: COLUMN exp_trophique.quantite ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_trophique.quantite  IS 'code quantité de contenu stomacal';


--
-- Name: exp_trophique_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE exp_trophique_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_trophique_id_seq OWNER TO devppeao;


--
-- Name: exp_trophique; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE exp_trophique ALTER COLUMN id SET DEFAULT nextval('exp_trophique_id_seq'::regclass);

--
-- Name: exp_contenu_biologie_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_trophique
    ADD CONSTRAINT exp_contenu_biologie_pkey PRIMARY KEY (id);




--
-- Name: meta_pays; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE meta_pays (
    meta_id integer NOT NULL,
    ref_pays_id character varying NOT NULL,
    doc_type character varying NOT NULL,
    file_path character varying NOT NULL,
    doc_titre character varying NOT NULL,
    doc_description text,
    CONSTRAINT meta_pays_doc_type_check CHECK (((((doc_type)::text = 'document'::text) OR ((doc_type)::text = 'carte'::text)) OR ((doc_type)::text = 'figure'::text)))
);


ALTER TABLE public.meta_pays OWNER TO devppeao;

--
-- Name: TABLE meta_pays; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE meta_pays IS 'Table contenant la liste des documents associés à des pays';


--
-- Name: COLUMN meta_pays.meta_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.meta_id IS 'id unique du document';


--
-- Name: COLUMN meta_pays.ref_pays_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.ref_pays_id IS 'id du pays (ref_pays.id)';


--
-- Name: COLUMN meta_pays.doc_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.doc_type IS 'type de document (document, figure, carte)';


--
-- Name: COLUMN meta_pays.file_path; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.file_path IS 'le chemin du fichier par rapport au dossier /work/documentation/metadata/';


--
-- Name: COLUMN meta_pays.doc_titre; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.doc_titre IS 'le titre du document';


--
-- Name: COLUMN meta_pays.doc_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_pays.doc_description IS 'la description du document';


--
-- Name: meta_pays_meta_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE meta_pays_meta_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.meta_pays_meta_id_seq OWNER TO devppeao;


--
-- Name: meta_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE meta_pays ALTER COLUMN meta_id SET DEFAULT nextval('meta_pays_meta_id_seq'::regclass);


--
-- Name: meta_pays_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY meta_pays
    ADD CONSTRAINT meta_pays_pkey PRIMARY KEY (meta_id);



--
-- Name: meta_secteurs; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE meta_secteurs (
    meta_id integer NOT NULL,
    ref_secteur_id integer NOT NULL,
    doc_type character varying NOT NULL,
    file_path character varying NOT NULL,
    doc_titre character varying NOT NULL,
    doc_description text,
    CONSTRAINT meta_secteurs_doc_type_check CHECK (((((doc_type)::text = 'document'::text) OR ((doc_type)::text = 'carte'::text)) OR ((doc_type)::text = 'figure'::text)))
);


ALTER TABLE public.meta_secteurs OWNER TO devppeao;

--
-- Name: TABLE meta_secteurs; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE meta_secteurs IS 'Table contenant la liste des documents associés à des secteurs';


--
-- Name: COLUMN meta_secteurs.meta_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.meta_id IS 'id unique du document';


--
-- Name: COLUMN meta_secteurs.ref_secteur_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.ref_secteur_id IS 'id du secteur (ref_secteur.id)';


--
-- Name: COLUMN meta_secteurs.doc_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.doc_type IS 'type de document (document, figure, carte)';


--
-- Name: COLUMN meta_secteurs.file_path; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.file_path IS 'le chemin du fichier par rapport au dossier /work/documentation/metadata/';


--
-- Name: COLUMN meta_secteurs.doc_titre; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.doc_titre IS 'le titre du document';


--
-- Name: COLUMN meta_secteurs.doc_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_secteurs.doc_description IS 'la description du document';


--
-- Name: meta_secteurs_meta_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE meta_secteurs_meta_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.meta_secteurs_meta_id_seq OWNER TO devppeao;


--
-- Name: meta_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE meta_secteurs ALTER COLUMN meta_id SET DEFAULT nextval('meta_secteurs_meta_id_seq'::regclass);


--
-- Name: meta_secteurs_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY meta_secteurs
    ADD CONSTRAINT meta_secteurs_pkey PRIMARY KEY (meta_id);



--
-- Name: meta_systemes; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE meta_systemes (
    meta_id integer NOT NULL,
    ref_systeme_id integer NOT NULL,
    doc_type character varying NOT NULL,
    file_path character varying NOT NULL,
    doc_titre character varying NOT NULL,
    doc_description text,
    CONSTRAINT meta_systemes_doc_type_check CHECK (((((doc_type)::text = 'document'::text) OR ((doc_type)::text = 'carte'::text)) OR ((doc_type)::text = 'figure'::text)))
);


ALTER TABLE public.meta_systemes OWNER TO devppeao;

--
-- Name: TABLE meta_systemes; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE meta_systemes IS 'Table contenant la liste des documents associés à des systèmes';


--
-- Name: COLUMN meta_systemes.meta_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.meta_id IS 'id unique du document';


--
-- Name: COLUMN meta_systemes.ref_systeme_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.ref_systeme_id IS 'id du système (ref_systeme.id)';


--
-- Name: COLUMN meta_systemes.doc_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.doc_type IS 'type de document (document, figure, carte)';


--
-- Name: COLUMN meta_systemes.file_path; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.file_path IS 'le chemin du fichier par rapport au dossier /work/documentation/metadata/';


--
-- Name: COLUMN meta_systemes.doc_titre; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.doc_titre IS 'le titre du document';


--
-- Name: COLUMN meta_systemes.doc_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN meta_systemes.doc_description IS 'la description du document';


--
-- Name: meta_systemes_meta_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE meta_systemes_meta_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.meta_systemes_meta_id_seq OWNER TO devppeao;

--
-- Name: meta_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE meta_systemes ALTER COLUMN meta_id SET DEFAULT nextval('meta_systemes_meta_id_seq'::regclass);


--
-- Name: meta_systemes_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY meta_systemes
    ADD CONSTRAINT meta_systemes_pkey PRIMARY KEY (meta_id);


-- ---------------------------------------------------------------------------
-- Name: pg_ts_dict; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE pg_ts_dict (
    dict_name text NOT NULL,
    dict_init regprocedure,
    dict_initoption text,
    dict_lexize regprocedure NOT NULL,
    dict_comment text
);

-- ---------------------------------------------------------------------------
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

-- ---------------------------------------------------------------------------
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

-- ---------------------------------------------------------------------------
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

-- ---------------------------------------------------------------------------
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

-- ---------------------------------------------------------------------------
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


-- DÉFINITION DES CONTRAINTES DE CLÉS ÉTRANGÈRES (À LA FIN POUR ÉVITER LES PROBLÈMES D'INTÉGRITÉ) ----------
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
-- Name: art_debarquement_rec_art_debarquement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_debarquement_rec
    ADD CONSTRAINT art_debarquement_rec_art_debarquement_id_fkey FOREIGN KEY (art_debarquement_id) REFERENCES art_debarquement(id) ON UPDATE CASCADE ON DELETE CASCADE;


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
-- Name: art_lieu_de_peche_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_lieu_de_peche
    ADD CONSTRAINT art_lieu_de_peche_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_periode_enquete_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_periode_enquete
    ADD CONSTRAINT art_periode_enquete_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;




--
-- Name: art_poisson_mesure_art_fraction_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_poisson_mesure
    ADD CONSTRAINT art_poisson_mesure_art_fraction_id_fkey FOREIGN KEY (art_fraction_id) REFERENCES art_fraction(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: art_stat_effort_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_pkey PRIMARY KEY (effort_id);

--
-- Name: art_stat_effort_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_effort_ref_mois_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_ref_mois_id_fkey FOREIGN KEY (ref_mois_id) REFERENCES admin_config_mois(mois_id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_effort_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_effort_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_stat_effort_ref_type_effort_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_effort
    ADD CONSTRAINT art_stat_effort_ref_type_effort_id_fkey FOREIGN KEY (art_param_type_effort_id) REFERENCES art_param_type_effort(type_effort_id) ON UPDATE CASCADE ON DELETE CASCADE;




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
-- Name: art_stat_sp_art_stat_totale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_art_stat_totale_id_fkey FOREIGN KEY (art_stat_totale_id) REFERENCES art_stat_totale(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_stat_sp_ref_espece_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_stat_sp
    ADD CONSTRAINT art_stat_sp_ref_espece_id_fkey FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_stat_totale_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_stat_totale
    ADD CONSTRAINT art_stat_totale_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: art_taille_sp_art_stat_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_taille_sp
    ADD CONSTRAINT art_taille_sp_art_stat_sp_id_fkey FOREIGN KEY (art_stat_sp_id) REFERENCES art_stat_sp(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: art_taille_sp_art_stat_sp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_taille_gt_sp
    ADD CONSTRAINT art_taille_gt_sp_art_stat_gt_sp_id_fkey FOREIGN KEY (art_stat_gt_sp_id) REFERENCES art_stat_gt_sp(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_unite_peche_art_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_art_agglomeration_id_fkey FOREIGN KEY (art_agglomeration_id) REFERENCES art_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_unite_peche_art_categorie_socio_professionnelle_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY art_unite_peche
    ADD CONSTRAINT art_unite_peche_art_categorie_socio_professionnelle_id_fkey FOREIGN KEY (art_categorie_socio_professionnelle_id) REFERENCES art_categorie_socio_professionnelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


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
-- Name: exp_campagne_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_campagne
    ADD CONSTRAINT exp_campagne_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;

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
-- Name: meta_pays_ref_pays_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY meta_pays
    ADD CONSTRAINT meta_pays_ref_pays_id_fkey FOREIGN KEY (ref_pays_id) REFERENCES ref_pays(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: meta_secteurs_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY meta_secteurs
    ADD CONSTRAINT meta_secteurs_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: meta_systemes_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY meta_systemes
    ADD CONSTRAINT meta_systemes_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;
