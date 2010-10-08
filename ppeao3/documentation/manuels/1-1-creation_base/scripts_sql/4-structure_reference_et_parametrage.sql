--
-- Script permettant de créer la structure des tables de reference et de parametrage de l'application Web PPEAO
-- Ajout de commentaires sur les tables et colonnes (JME - 07/10 et MS 10/10)
--

SET client_encoding = 'LATIN9';
SET client_min_messages = warning;


-- ---------------------------------------------------------------------------
-- Name: art_agglomeration; Type: TABLE; Schema: public; Owner: -; Tablespace: 
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

-- ALTER TABLE public.art_agglomeration OWNER TO devppeao;

--
-- Name: TABLE art_agglomeration; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_agglomeration IS 'Table des agglomérations pour la pêche artisanale';

--
-- Name: COLUMN art_agglomeration.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.id IS 'id unique (num)';

--
-- Name: COLUMN art_agglomeration.art_type_agglomeration_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.art_type_agglomeration_id IS 'id de référence du type d''agglomération';

--
-- Name: COLUMN art_agglomeration.ref_secteur_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.ref_secteur_id IS 'id du secteur géographique de l''agglomération';

--
-- Name: COLUMN art_agglomeration.nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.nom IS 'nom de l''agglomération';

--
-- Name: COLUMN art_agglomeration.longitude; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.longitude IS 'longitude de l''agglomération (-XXX:XX:XX)';

--
-- Name: COLUMN art_agglomeration.latitude; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.latitude IS 'latitude de l''agglomération (+XX:XX:XX)';

--
-- Name: COLUMN art_agglomeration.memo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_agglomeration.memo IS 'commentaires sur l''agglomération';

--
-- Name: art_agglomeration_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_agglomeration_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_agglomeration_id_seq OWNER TO devppeao;


--
-- Name:  art_agglomeration; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE  art_agglomeration ALTER COLUMN id SET DEFAULT nextval('art_agglomeration_id_seq'::regclass);

--
-- Name: art_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_agglomeration_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_categorie_socio_professionnelle; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_categorie_socio_professionnelle (
    id integer NOT NULL,
    libelle character varying(50)
);

--
-- Name: TABLE art_categorie_socio_professionnelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_categorie_socio_professionnelle IS 'Table des catégories socio-professionnelles des unités de pêche';

--
-- Name: COLUMN art_categorie_socio_professionnelle.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_categorie_socio_professionnelle.id IS 'id unique (num)';

--
-- Name: COLUMN art_categorie_socio_professionnelle.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_categorie_socio_professionnelle.libelle IS 'libellé de la catégorie socio-professionnelle';

--
-- Name: art_categorie_socio_professionnelle_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_categorie_socio_professionnelle_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_categorie_socio_professionnelle_id_seq OWNER TO devppeao;


--
-- Name: art_categorie_socio_professionnelle; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_categorie_socio_professionnelle ALTER COLUMN id SET DEFAULT nextval('art_categorie_socio_professionnelle_id_seq'::regclass);

--
-- Name: art_categorie_socio_professionnelle_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_categorie_socio_professionnelle
    ADD CONSTRAINT art_categorie_socio_professionnelle_pkey PRIMARY KEY (id);





-- ---------------------------------------------------------------------------
-- Name: art_etat_ciel; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_etat_ciel (
    id integer NOT NULL,
    libelle character varying(50)
);

--
-- Name: TABLE art_etat_ciel; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_etat_ciel IS 'Table de description de l''état du ciel';

--
-- Name: COLUMN art_etat_ciel.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_etat_ciel.id IS 'id unique (num)';

--
-- Name: COLUMN art_etat_ciel.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_etat_ciel.libelle IS 'libellé de description dde l''état du ciel';

--
-- Name: art_etat_ciel_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_etat_ciel_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_etat_ciel_id_seq OWNER TO devppeao;


--
-- Name: art_etat_ciel; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_etat_ciel ALTER COLUMN id SET DEFAULT nextval('art_etat_ciel_id_seq'::regclass);

--
-- Name: art_etat_ciel_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_etat_ciel
    ADD CONSTRAINT art_etat_ciel_pkey PRIMARY KEY (id);




-- ---------------------------------------------------------------------------
-- Name: art_grand_type_engin; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_grand_type_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);

--
-- Name: TABLE art_grand_type_engin; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_grand_type_engin IS 'Table d''identification du grand type d''engin de pêche';

--
-- Name: COLUMN art_grand_type_engin.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_grand_type_engin.id IS 'id unique (char)';

--
-- Name: COLUMN art_grand_type_engin.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_grand_type_engin.libelle IS 'libellé du grand type d''engin de pêche';

--
-- Name: art_grand_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_grand_type_engin
    ADD CONSTRAINT art_grand_type_engin_pkey PRIMARY KEY (id);




-- ---------------------------------------------------------------------------
-- Name: art_millieu; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_millieu (
    id integer NOT NULL,
    libelle character varying(50)
);

--
-- Name: TABLE art_millieu; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_millieu IS 'Table de description de la zone de pêche';

--
-- Name: COLUMN art_millieu.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_millieu.id IS 'id unique (num)';

--
-- Name: COLUMN art_millieu.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_millieu.libelle IS 'libellé de description de la zone de pêche';

--
-- Name: art_art_millieu_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_millieu_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_millieu_id_seq OWNER TO devppeao;


--
-- Name: art_millieu; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_millieu ALTER COLUMN id SET DEFAULT nextval('art_millieu_id_seq'::regclass);

--
-- Name: art_millieu_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_millieu
    ADD CONSTRAINT art_millieu_pkey PRIMARY KEY (id);


-- ----------------------------------------------------------------------------------------
--
-- Name: art_param_type_effort; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_param_type_effort (
    type_effort_id integer NOT NULL,
    type_effort_libelle character varying(255) NOT NULL,
    type_effort_description text
);


ALTER TABLE public.art_param_type_effort OWNER TO devppeao;

--
-- Name: TABLE art_param_type_effort; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_param_type_effort IS 'Table donnant les différents types de mesures d''effort de pêche';


--
-- Name: COLUMN art_param_type_effort.type_effort_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_id IS 'id unique';


--
-- Name: COLUMN art_param_type_effort.type_effort_libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_libelle IS 'libellé du type d''effort (unité)';


--
-- Name: COLUMN art_param_type_effort.type_effort_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_description IS 'description longue de l''unité d''effort';


--
-- Name: art_param_type_effort_type_effort_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_param_type_effort_type_effort_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_param_type_effort_type_effort_id_seq OWNER TO devppeao;


--
-- Name: type_effort_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_param_type_effort ALTER COLUMN type_effort_id SET DEFAULT nextval('art_param_type_effort_type_effort_id_seq'::regclass);


--
-- Name: art_param_type_effort_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_param_type_effort
    ADD CONSTRAINT art_param_type_effort_pkey PRIMARY KEY (type_effort_id);




-- ---------------------------------------------------------------------------
-- Name: art_type_activite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_type_activite (
    raison character varying(50),
    libelle character varying(255),
    id character varying(10) NOT NULL
);


--
-- Name: TABLE art_type_activite; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_type_activite IS 'Table d''explication des causes d''activité ou inactivité des unités de pêche';

--
-- Name: COLUMN art_type_activite.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_activite.id IS 'id unique (char)';

--
-- Name: COLUMN art_type_activite.raison; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_activite.raison IS 'explication courte de l''activité de l''unité de pêche';

--
-- Name: COLUMN art_type_activite.libellé; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_activite.libelle IS 'explication longue de l''activité de l''unité de pêche';

--
-- Name: art_type_activite_id_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_type_activite
    ADD CONSTRAINT art_type_activite_id_key UNIQUE (id);


--
-- Name: art_type_activite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_type_activite
    ADD CONSTRAINT art_type_activite_pkey PRIMARY KEY (id);





-- ---------------------------------------------------------------------------
-- Name: art_type_agglomeration; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_type_agglomeration (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE art_type_agglomeration; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_type_agglomeration IS 'Table de description des types d''agglomération';

--
-- Name: COLUMN art_type_agglomeration.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_agglomeration.id IS 'id unique (num)';

--
-- Name: COLUMN art_type_agglomeration.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_agglomeration.libelle IS 'libellé de description des types d''agglomération';

--
-- Name: art_type_agglomeration_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_type_agglomeration_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_agglomeration_id_seq OWNER TO devppeao;


--
-- Name: art_type_agglomeration; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_agglomeration ALTER COLUMN id SET DEFAULT nextval('art_type_agglomeration_id_seq'::regclass);

--
-- Name: art_type_agglomeration_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_type_agglomeration
    ADD CONSTRAINT art_type_agglomeration_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_type_engin; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_type_engin (
    id character varying(10) NOT NULL,
    art_grand_type_engin_id character varying(10),
    libelle character varying(50)
);


--
-- Name: TABLE art_type_engin; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_type_engin IS 'Catalogue des différents types d''engins de pêche rencontrés dans la pêche artisanale';

--
-- Name: COLUMN art_type_engin.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_engin.id IS 'id unique (char)';

--
-- Name: COLUMN art_type_engin.art_grand_type_engin_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_engin.art_grand_type_engin_id IS 'id de référence du grand type d''engin auquel est lié l''engin de pêche';

--
-- Name: COLUMN art_type_engin.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_engin.libelle IS 'libellé des types d''engin de pêche';


--
-- Name: art_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_type_engin
    ADD CONSTRAINT art_type_engin_pkey PRIMARY KEY (id);





-- ---------------------------------------------------------------------------
-- Name: art_type_sortie; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_type_sortie (
    id integer NOT NULL,
    libelle character varying(100)
);


--
-- Name: TABLE art_type_sortie; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_type_sortie IS 'Table de description des types de sorties de pêche';

--
-- Name: COLUMN art_type_sortie.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_sortie.id IS 'id unique (num)';

--
-- Name: COLUMN art_type_sortie.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_type_sortie.libelle IS 'libellé des types de sorties de pêche';

--
-- Name: art_type_sortie_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_type_sortie_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_type_sortie_id_seq OWNER TO devppeao;


--
-- Name: art_type_sortie; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_sortie ALTER COLUMN id SET DEFAULT nextval('art_type_sortie_id_seq'::regclass);

--
-- Name: art_tyepe_sortie_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_type_sortie
    ADD CONSTRAINT art_tyepe_sortie_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: art_vent; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE art_vent (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE art_vent; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE art_vent IS 'Table de description de l''importance du vent';

--
-- Name: COLUMN art_vent.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_vent.id IS 'id unique (num)';

--
-- Name: COLUMN art_vent.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_vent.libelle IS 'libellé des forces du vent';


--
-- Name: art_vent_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_vent_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_vent_id_seq OWNER TO devppeao;


--
-- Name: art_vent; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_vent ALTER COLUMN id SET DEFAULT nextval('art_vent_id_seq'::regclass);

--
-- Name: art_vent_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_vent
    ADD CONSTRAINT art_vent_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_contenu; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_contenu (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_contenu; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_contenu IS 'Table de description du contenu stomacal';

--
-- Name: COLUMN exp_contenu.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_contenu.id IS 'id unique (num)';

--
-- Name: COLUMN exp_contenu.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_contenu.libelle IS 'libellé du contenu stomacal';

--
-- Name: exp_contenu_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_contenu
    ADD CONSTRAINT exp_contenu_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_debris; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_debris (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_debris; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_debris IS 'Table de description du type de débris présents dans le sédiment';

--
-- Name: COLUMN exp_debris.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_debris.id IS 'id unique (char)';

--
-- Name: COLUMN exp_debris.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_debris.libelle IS 'libellé du type de débris';

--
-- Name: exp_debris_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_debris
    ADD CONSTRAINT exp_debris_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_engin; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50),
    longueur real,
    chute real,
    maille integer,
    memo text
);


--
-- Name: TABLE exp_engin; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_engin IS 'Table de description de l''engin d''échantillonnage';

--
-- Name: COLUMN exp_engin.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.id IS 'id unique (char)';

--
-- Name: COLUMN exp_engin.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.libelle IS 'libellé du type d''engin de pêche';

--
-- Name: COLUMN exp_engin.longueur; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.longueur IS 'longueur (m)';

--
-- Name: COLUMN exp_engin.chute; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.chute IS 'chute (m)';

--
-- Name: COLUMN exp_engin.maille; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.maille IS 'dimension de la maille (mm, côté)';

--
-- Name: COLUMN exp_engin.memo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_engin.memo IS 'commentaire descriptif de l''engin';

--
-- Name: ref_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_engin
    ADD CONSTRAINT ref_engin_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_force_courant; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_force_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_force_courant Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_force_courant IS 'Table de description de la force du courant';

--
-- Name: COLUMN exp_force_courant.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_force_courant.id IS 'id unique (num)';

--
-- Name: COLUMN exp_force_courant.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_force_courant.libelle IS 'libellé de la force du courant';

--
-- Name: exp_force_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_force_courant
    ADD CONSTRAINT exp_force_courant_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_position; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_position (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_position Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_position IS 'Table de description de la position du coup de pêche sur le plan d''eau';

--
-- Name: COLUMN exp_position.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_position.id IS 'id unique (num)';

--
-- Name: COLUMN exp_position.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_position.libelle IS 'libellé de la position sur le plan d''eau';

--
-- Name: exp_position_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_position
    ADD CONSTRAINT exp_position_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_qualite; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_qualite (
    id integer NOT NULL,
    libelle character varying(100)
);


--
-- Name: TABLE exp_qualite Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_qualite IS 'Table de description de la qualité de réussite du coup';

--
-- Name: COLUMN exp_qualite.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_qualite.id IS 'id unique (num)';

--
-- Name: COLUMN exp_qualite.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_qualite.libelle IS 'libellé de la qualité de réussite du coup';

--
-- Name: exp_qualite_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_qualite
    ADD CONSTRAINT exp_qualite_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_remplissage; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_remplissage (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


--
-- Name: TABLE exp_remplissage Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_remplissage IS 'Table de description du taux de remplissage de l''estomac';

--
-- Name: COLUMN exp_remplissage.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_remplissage.id IS 'id unique (char)';

--
-- Name: COLUMN exp_remplissage.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_remplissage.libelle IS 'libellé du taux de remplissage';

--
-- Name: exp_remplissage_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_remplissage
    ADD CONSTRAINT exp_remplissage_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_sediment; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_sediment (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_sediment Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_sediment IS 'Table de description de la nature des sédiments de la station';

--
-- Name: COLUMN exp_sediment.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sediment.id IS 'id unique (char)';

--
-- Name: COLUMN exp_sediment.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sediment.libelle IS 'libellé du type de sédiment';

--
-- Name: exp_sediment_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_sediment
    ADD CONSTRAINT exp_sediment_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: exp_sens_courant; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_sens_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_sens_courant Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_sens_courant IS 'Table de description du sens du courant';

--
-- Name: COLUMN exp_sens_courant.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sens_courant.id IS 'id unique (num)';

--
-- Name: COLUMN exp_sens_courant.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sens_courant.libelle IS 'libellé du sens du courant';

--
-- Name: exp_sens_courant_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_sens_courant
    ADD CONSTRAINT exp_sens_courant_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: exp_sexe; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_sexe (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


--
-- Name: TABLE exp_sexe Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_sexe IS 'Table de description du sexe';

--
-- Name: COLUMN exp_sexe.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sexe.id IS 'id unique (char)';

--
-- Name: COLUMN exp_sexe.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_sexe.libelle IS 'libellé du sexe';

--
-- Name: exp_sexe_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_sexe
    ADD CONSTRAINT exp_sexe_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: exp_stade; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_stade (
    id integer NOT NULL,
    libelle character varying(50)
);



--
-- Name: TABLE exp_stade Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_stade IS 'Table de description du stade de maturité sexuelle ';

--
-- Name: COLUMN exp_stade.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_stade.id IS 'id unique (num) ';

--
-- Name: COLUMN exp_stade.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_stade.libelle IS 'libellé du stade de maturité ';

--
-- Name: exp_stade_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_stade
    ADD CONSTRAINT exp_stade_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: exp_station; Type: TABLE; Schema: public; Owner: -; Tablespace: 
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


--
-- Name: TABLE exp_station Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_station IS 'Table de description de la station de pêche';

--
-- Name: COLUMN exp_station.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.id IS 'id unique (char)';

--
-- Name: COLUMN exp_station.nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.nom IS 'nom de la station';

--
-- Name: COLUMN exp_station.site; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.site IS 'nom du site regroupant 2 stations complémentaires (chenal et rive)';

--
-- Name: COLUMN exp_station.latitude; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.latitude IS 'latitude de la station (+xx:xx:xx)';

--
-- Name: COLUMN exp_station.longitude; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.longitude IS 'longitude de la station (-xxx:xx:xx)';

--
-- Name: COLUMN exp_station.memo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.memo IS 'commentaire descriptif sur la station';

--
-- Name: COLUMN exp_station.ref_secteur_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.ref_secteur_id IS 'id de référence du secteur dans le système';


--
-- Name: COLUMN exp_station.exp_position_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.exp_position_id IS 'id de référence de la position de la station';


--
-- Name: COLUMN exp_station.exp_vegetation_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.exp_vegetation_id IS 'id de référence de la végétation de bordure';


--
-- Name: COLUMN exp_station.exp_debris_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.exp_debris_id IS 'id de référence du type de débris dans le sédiment';


--
-- Name: COLUMN exp_station.exp_sediment_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.exp_sediment_id IS 'id de référence de la nature des sédiments de la station';


--
-- Name: COLUMN exp_station.distance_embouchure; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_station.distance_embouchure IS 'distance de la station à l''embouchure (km)';

--
-- Name: exp_station_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_pkey PRIMARY KEY (id);




-- ---------------------------------------------------------------------------

-- Name: exp_vegetation; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exp_vegetation (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE exp_vegetation Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE exp_vegetation IS 'Table de description du type de végétation de bordure ';

--
-- Name: COLUMN exp_vegetation.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_vegetation.id IS 'id unique (num) ';

--
-- Name: COLUMN exp_vegetation.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN exp_vegetation.libelle IS 'libellé du type de végétation';

--
-- Name: exp_vegetation_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exp_vegetation
    ADD CONSTRAINT exp_vegetation_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: ref_categorie_ecologique; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_categorie_ecologique (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE ref_categorie_ecologique; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_categorie_ecologique IS 'Table de description des catégories écologiques';

--
-- Name: COLUMN ref_categorie_ecologique.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_categorie_ecologique.id IS 'id unique (char)';

--
-- Name: COLUMN ref_categorie_ecologique.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_categorie_ecologique.libelle IS 'libellé des catégories écologiques';

--
-- Name: ref_categorie_ecologique_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_ecologique
    ADD CONSTRAINT ref_categorie_ecologique_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: ref_categorie_trophique; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_categorie_trophique (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


--
-- Name: TABLE ref_categorie_trophique; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_categorie_trophique IS 'Table de description des catégories trophiques';

--
-- Name: COLUMN ref_categorie_trophique.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_categorie_trophique.id IS 'id unique (char)';

--
-- Name: COLUMN ref_categorie_trophique.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_categorie_trophique.libelle IS 'libellé des catégories trophiques';

--
-- Name: ref_categorie_trophique_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_categorie_trophique
    ADD CONSTRAINT ref_categorie_trophique_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: ref_espece; Type: TABLE; Schema: public; Owner: -; Tablespace: 
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


--
-- Name: TABLE ref_espece; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_espece IS 'Table donnant la liste des espèces';

--
-- Name: COLUMN ref_espece.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.id IS 'id unique (char)';

--
-- Name: COLUMN ref_espece.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.libelle IS 'libellé des noms d''espèces';

--
-- Name: COLUMN ref_espece.info; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.info IS 'commentaire sur l''espèce';

--
-- Name: COLUMN ref_espece.ref_famille_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.ref_famille_id IS 'id de référence à la famille';

--
-- Name: COLUMN ref_espece.ref_categorie_ecologique_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.ref_categorie_ecologique_id IS 'id de référence à la catégorie écologique';

--
-- Name: COLUMN ref_espece.ref_categorie_trophique_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.ref_categorie_trophique_id IS 'id de référence à la catégorie trophique';

--
-- Name: COLUMN ref_espece.coefficient_k; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.coefficient_k IS 'coefficient k de la relation longueur:poids (cm,gr)';

--
-- Name: COLUMN ref_espece.coefficient_b; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.coefficient_b IS 'coefficient b de la relation longueur:poids (cm,gr)';

--
-- Name: COLUMN ref_espece.ref_origine_kb_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.ref_origine_kb_id IS 'id de référence à l''origine des coefficients k,b';

--
-- Name: COLUMN ref_espece.ref_espece_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_espece.ref_espece_id IS 'id de référence à une espèce comparable pour connaitre les coefficients k,b manquants';

--
-- Name: ref_espece_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: ref_famille; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_famille (
    id integer NOT NULL,
    libelle character varying(50),
    ref_ordre_id integer,
    non_poisson integer,
    CONSTRAINT ref_famille_non_poisson_check CHECK (((non_poisson = 0) OR (non_poisson = 1)))
);


--
-- Name: TABLE ref_famille; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_famille IS 'Table donnant la liste des familles';

--
-- Name: COLUMN ref_famille.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_famille.id IS 'id unique (num)';

--
-- Name: COLUMN ref_famille.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_famille.libelle IS 'libellé des noms de famille';

--
-- Name: COLUMN ref_famille.ref_ordre_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_famille.ref_ordre_id IS 'id de référence à l''ordre';

--
-- Name: COLUMN ref_famille.non_poisson; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_famille.non_poisson IS 'si famille est poisson = 0, est non poisson = 1';

--
-- Name: _famille_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_famille_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_famille_id_seq OWNER TO devppeao;


--
-- Name: ref_famille; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE ref_famille ALTER COLUMN id SET DEFAULT nextval('ref_famille_id_seq'::regclass);

--
-- Name: ref_famille_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_famille
    ADD CONSTRAINT ref_famille_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------

-- Name: ref_ordre; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_ordre (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE ref_ordre; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_ordre IS 'Table donnant la liste des ordres';

--
-- Name: COLUMN ref_ordre.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_ordre.id IS 'id unique (num)';

--
-- Name: COLUMN ref_ordre.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_ordre.libelle IS 'libellé des noms des ordres';

--
-- Name: ref_ordre_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_ordre_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_ordre_id_seq OWNER TO devppeao;


--
-- Name: ref_ordre; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE ref_ordre ALTER COLUMN id SET DEFAULT nextval('ref_ordre_id_seq'::regclass);

--
-- Name: ref_ordre_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_ordre
    ADD CONSTRAINT ref_ordre_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: ref_origine_kb; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_origine_kb (
    id integer NOT NULL,
    libelle character varying(50)
);


--
-- Name: TABLE ref_origine_kb; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_origine_kb IS 'Table des références des origines des couples k,b';

--
-- Name: COLUMN ref_origine_kb.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_origine_kb.id IS 'id unique (num)';

--
-- Name: COLUMN ref_origine_kb.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_origine_kb.libelle IS 'libellé des origines de l''information sur les couples k,b ';

--
-- Name: ref_origine_kb_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_origine_kb_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_origine_kb_id_seq OWNER TO devppeao;


--
-- Name: ref_origine_kb; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE ref_origine_kb ALTER COLUMN id SET DEFAULT nextval('ref_origine_kb_id_seq'::regclass);

--
-- Name: ar_origine_kb_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_origine_kb
    ADD CONSTRAINT ar_origine_kb_pkey PRIMARY KEY (id);




-- ---------------------------------------------------------------------------
-- Name: ref_pays; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_pays (
    id character varying(10) NOT NULL,
    nom character varying(50)
);


--
-- Name: TABLE ref_pays; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_pays IS 'Table donnant la liste des pays';

--
-- Name: COLUMN ref_pays.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_pays.id IS 'id unique (char)';

--
-- Name: COLUMN ref_pays.nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_pays.nom IS 'libellé des noms des pays';

--
-- Name: ref_pays_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_pays
    ADD CONSTRAINT ref_pays_pkey PRIMARY KEY (id);


-- ----------------------------------------------------------------------------
-- Name: ref_secteur; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_secteur (
    id integer NOT NULL,
    id_dans_systeme integer,
    nom character varying(50),
    superficie real,
    ref_systeme_id integer
);


--
-- Name: TABLE ref_secteur; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_secteur IS 'Table donnant la liste des secteurs';

--
-- Name: COLUMN ref_secteur.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_secteur.id IS 'id unique (num)';

--
-- Name: COLUMN ref_secteur.id_dans_systeme ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_secteur.id_dans_systeme  IS 'numéro du secteur dans un système';

--
-- Name: COLUMN ref_secteur.nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_secteur.nom IS 'libellé des noms de secteurs';

--
-- Name: COLUMN ref_secteur.superficie ; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_secteur.superficie  IS 'superficie du secteur (km2)';

--
-- Name: COLUMN ref_secteur.ref_systeme_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_secteur.ref_systeme_id IS 'id de référence au système du secteur';

--
-- Name: ref_secteur_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_secteur_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_secteur_id_seq OWNER TO devppeao;


--
-- Name: ref_secteur; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE ref_secteur ALTER COLUMN id SET DEFAULT nextval('ref_secteur_id_seq'::regclass);


--
-- Name: ref_secteur_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY ref_secteur
    ADD CONSTRAINT ref_secteur_pkey PRIMARY KEY (id);



-- ---------------------------------------------------------------------------
-- Name: ref_systeme; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ref_systeme (
    id integer  NOT NULL,
    libelle character varying(50),
    ref_pays_id character varying(10),
    superficie real
);


--
-- Name: TABLE ref_systeme; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE ref_systeme IS 'Table donnant la liste des systèmes aquatiques';

--
-- Name: COLUMN ref_systeme.id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_systeme.id IS 'id unique (num)';

--
-- Name: COLUMN ref_systeme.libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_systeme.libelle IS 'libellé des noms des systèmes';

--
-- Name: COLUMN ref_systeme.ref_pays_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_systeme.ref_pays_id IS 'id de référence au pays';

--
-- Name: COLUMN ref_systeme.superficie; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN ref_systeme.superficie IS 'superficie du système (km2)';

--
-- Name: ref_systeme_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_systeme_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_systeme_id_seq OWNER TO devppeao;

-- ----------------------------------------------------------------------

--
-- Table: temp_extraction
--

CREATE TABLE temp_extraction
(
  id varchar, -- clé unique
  key1 varchar, -- détail clé unique 1
  key2 varchar, -- détail clé unique 2
  key3 varchar, -- détail clé unique 3
  key4 varchar, -- détail clé unique 4
  key5 varchar, -- détail clé unique 5
  key6 varchar, -- détail clé unique 6
  key7 varchar, -- détail clé unique 7
  valeur_ligne varchar, -- contenu de la ligne pour la valeur unique
  date_creation date, -- date de création de l'enreg
  "user" char(20), -- nom de l'utilisateur qui a créé la ligne
  CONSTRAINT temp_extraction_id_key UNIQUE (id)
) 
WITHOUT OIDS;
ALTER TABLE temp_extraction OWNER TO postgres;
COMMENT ON TABLE temp_extraction IS 'Table temporaire pour gérer des calculs complémentaires lors des extractions';
COMMENT ON COLUMN temp_extraction.id IS 'clé unique';
COMMENT ON COLUMN temp_extraction.key1 IS 'détail clé unique 1';
COMMENT ON COLUMN temp_extraction.key2 IS 'détail clé unique 2';
COMMENT ON COLUMN temp_extraction.key3 IS 'détail clé unique 3';
COMMENT ON COLUMN temp_extraction.key4 IS 'détail clé unique 4';
COMMENT ON COLUMN temp_extraction.key5 IS 'détail clé unique 5';
COMMENT ON COLUMN temp_extraction.key6 IS 'détail clé unique 6';
COMMENT ON COLUMN temp_extraction.key7 IS 'détail clé unique 7';
COMMENT ON COLUMN temp_extraction.valeur_ligne IS 'contenu de la ligne pour la valeur unique';
COMMENT ON COLUMN temp_extraction.date_creation IS 'date de création de l''enreg';
COMMENT ON COLUMN temp_extraction."user" IS 'nom de l''utilisateur qui a créé la ligne';



--
-- Name: ref_systeme; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE ref_systeme ALTER COLUMN id SET DEFAULT nextval('ref_systeme_id_seq'::regclass);

--
-- Name: ref_systeme_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ref_systeme
    ADD CONSTRAINT ref_systeme_pkey PRIMARY KEY (id);



-- ****************************************************************************************
-- LES CONTRAINTES DE CLE ETRANGERES, AJOUTEES A LA FIN POUR NE PAS GENER LE PROCESSUS
-- ****************************************************************************************


-- note : les tables admin_acces_donnees_acteurs et admin_acces_donnees_systemes sont des tables d'administration mais qui 
-- utilisent des clés étrangères issues de la table ref_systeme, ce qui oblige a déclarer les deux contraintes
-- suivantes dans ce fichier
--
-- Name: admin_acces_donnees_acteurs_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_acces_donnees_acteurs
    ADD CONSTRAINT admin_acces_donnees_acteurs_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_acces_donnees_systemes_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_acces_donnees_systemes
    ADD CONSTRAINT admin_acces_donnees_systemes_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: art_agglomeration_art_type_agglomeration_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_agglomeration_art_type_agglomeration_id_fkey FOREIGN KEY (art_type_agglomeration_id) REFERENCES art_type_agglomeration(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_agglomeration_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_agglomeration
    ADD CONSTRAINT art_agglomeration_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: art_type_engin_art_grand_type_engin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY art_type_engin
    ADD CONSTRAINT art_type_engin_art_grand_type_engin_id_fkey FOREIGN KEY (art_grand_type_engin_id) REFERENCES art_grand_type_engin(id) ON UPDATE CASCADE ON DELETE CASCADE;
--
-- Name: exp_station_exp_debris_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_exp_debris_id_fkey FOREIGN KEY (exp_debris_id) REFERENCES exp_debris(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_station_exp_position_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_exp_position_id_fkey FOREIGN KEY (exp_position_id) REFERENCES exp_position(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_station_exp_sediment_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_exp_sediment_id_fkey FOREIGN KEY (exp_sediment_id) REFERENCES exp_sediment(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: exp_station_exp_vegetation_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_exp_vegetation_id_fkey FOREIGN KEY (exp_vegetation_id) REFERENCES exp_vegetation(id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: exp_station_ref_secteur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_station_ref_secteur_id_fkey FOREIGN KEY (ref_secteur_id) REFERENCES ref_secteur(id) ON UPDATE CASCADE ON DELETE CASCADE;




--
-- Name: ref_famille_ref_ordre_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_famille
    ADD CONSTRAINT ref_famille_ref_ordre_id_fkey FOREIGN KEY (ref_ordre_id) REFERENCES ref_ordre(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: ref_espece_ref_categorie_ecologique_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_ref_categorie_ecologique_id_fkey FOREIGN KEY (ref_categorie_ecologique_id) REFERENCES ref_categorie_ecologique(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ref_espece_ref_categorie_trophique_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_ref_categorie_trophique_id_fkey FOREIGN KEY (ref_categorie_trophique_id) REFERENCES ref_categorie_trophique(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ref_espece_ref_espece_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_ref_espece_id_fkey FOREIGN KEY (ref_espece_id) REFERENCES ref_espece(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ref_espece_ref_famille_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_ref_famille_id_fkey FOREIGN KEY (ref_famille_id) REFERENCES ref_famille(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ref_espece_ref_origine_kb_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_ref_origine_kb_id_fkey FOREIGN KEY (ref_origine_kb_id) REFERENCES ref_origine_kb(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ref_secteur_ref_systeme_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_secteur
    ADD CONSTRAINT ref_secteur_ref_systeme_id_fkey FOREIGN KEY (ref_systeme_id) REFERENCES ref_systeme(id) ON UPDATE CASCADE ON DELETE CASCADE;



--
-- Name: ref_systeme_ref_pays_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ref_systeme
    ADD CONSTRAINT ref_systeme_ref_pays_id_fkey FOREIGN KEY (ref_pays_id) REFERENCES ref_pays(id) ON UPDATE CASCADE ON DELETE CASCADE;
