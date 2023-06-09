--
-- Script permettant de cr�er la structure des tables de reference et de parametrage de l'application Web PPEAO

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
-- Name: art_art_millieu_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE art_millieu_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_millieu_id_seq OWNER TO devppeao;


--
-- Name: art_type_agglomeration; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE art_millieu ALTER COLUMN id SET DEFAULT nextval('art_millieu_id_seq'::regclass);

--
-- Name: art_millieu_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY art_millieu
    ADD CONSTRAINT art_millieu_pkey PRIMARY KEY (id);



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

COMMENT ON TABLE art_param_type_effort IS 'les diff�rents types de mesures d''effort de p�che';


--
-- Name: COLUMN art_param_type_effort.type_effort_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_id IS 'id unique de l''enregistrement';


--
-- Name: COLUMN art_param_type_effort.type_effort_libelle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_libelle IS 'libell� du type d''effort (unit�)';


--
-- Name: COLUMN art_param_type_effort.type_effort_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN art_param_type_effort.type_effort_description IS 'description longue de l''unit� d''effort';


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
-- Name: ref_systeme_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE ref_systeme_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ref_systeme_id_seq OWNER TO devppeao;


-- Table: temp_extraction

CREATE TABLE temp_extraction
(
  id varchar, -- cl� unique
  key1 varchar, -- d�tail cl� unique 1
  key2 varchar, -- d�tail cl� unique 2
  key3 varchar, -- d�tail cl� unique 3
  key4 varchar, -- d�tail cl� unique 4
  key5 varchar, -- d�tail cl� unique 5
  key6 varchar, -- d�tail cl� unique 6
  key7 varchar, -- d�tail cl� unique 7
  valeur_ligne varchar, -- contenu de la ligne pour la valeur unique
  date_creation date, -- date de cr�ation de l'enreg
  "user" char(20), -- nom de l'utilisateur qui a cr�� la ligne
  CONSTRAINT temp_extraction_id_key UNIQUE (id)
) 
WITHOUT OIDS;
ALTER TABLE temp_extraction OWNER TO postgres;
COMMENT ON TABLE temp_extraction IS 'Table temporaire pour g�rer des calculs compl�mentaires lors des extractions';
COMMENT ON COLUMN temp_extraction.id IS 'cl� unique';
COMMENT ON COLUMN temp_extraction.key1 IS 'd�tail cl� unique 1';
COMMENT ON COLUMN temp_extraction.key2 IS 'd�tail cl� unique 2';
COMMENT ON COLUMN temp_extraction.key3 IS 'd�tail cl� unique 3';
COMMENT ON COLUMN temp_extraction.key4 IS 'd�tail cl� unique 4';
COMMENT ON COLUMN temp_extraction.key5 IS 'd�tail cl� unique 5';
COMMENT ON COLUMN temp_extraction.key6 IS 'd�tail cl� unique 6';
COMMENT ON COLUMN temp_extraction.key7 IS 'd�tail cl� unique 7';
COMMENT ON COLUMN temp_extraction.valeur_ligne IS 'contenu de la ligne pour la valeur unique';
COMMENT ON COLUMN temp_extraction.date_creation IS 'date de cr�ation de l''enreg';
COMMENT ON COLUMN temp_extraction."user" IS 'nom de l''utilisateur qui a cr�� la ligne';



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
-- utilisent des cl�s �trang�res issues de la table ref_systeme, ce qui oblige a d�clarer les deux contraintes
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

