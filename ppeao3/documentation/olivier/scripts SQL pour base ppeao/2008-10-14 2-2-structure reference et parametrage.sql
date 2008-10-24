--
-- PostgreSQL database dump
--

-- Started on 2008-10-13 15:34:31 CEST

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;




SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;



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
-- TOC entry 2129 (class 2606 OID 18915)
-- Dependencies: 1703 1703
-- Name: art_etat_ciel_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_etat_ciel
    ADD CONSTRAINT art_etat_ciel_pkey PRIMARY KEY (id);



--
-- TOC entry 2133 (class 2606 OID 18921)
-- Dependencies: 1710 1710
-- Name: art_grand_type_engin_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_grand_type_engin
    ADD CONSTRAINT art_grand_type_engin_pkey PRIMARY KEY (id);


--
-- TOC entry 2137 (class 2606 OID 18925)
-- Dependencies: 1713 1713
-- Name: art_millieu_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_millieu
    ADD CONSTRAINT art_millieu_pkey PRIMARY KEY (id);


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
-- TOC entry 2163 (class 2606 OID 18951)
-- Dependencies: 1734 1734
-- Name: art_vent_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY art_vent
    ADD CONSTRAINT art_vent_pkey PRIMARY KEY (id);


--
-- TOC entry 2173 (class 2606 OID 18961)
-- Dependencies: 1746 1746
-- Name: exp_debris_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY exp_debris
    ADD CONSTRAINT exp_debris_pkey PRIMARY KEY (id);


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
-- TOC entry 2294 (class 2606 OID 19104)
-- Dependencies: 2212 1773 1778
-- Name: art_origine_kb_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT art_origine_kb_id FOREIGN KEY (ref_origine_kb_id) REFERENCES ref_origine_kb(id);


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
-- TOC entry 2287 (class 2606 OID 19209)
-- Dependencies: 1746 2172 1766
-- Name: exp_debris_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_debris_id FOREIGN KEY (exp_debris_id) REFERENCES exp_debris(id);


--
-- TOC entry 2288 (class 2606 OID 19224)
-- Dependencies: 1754 2182 1766
-- Name: exp_position_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_station
    ADD CONSTRAINT exp_position_id FOREIGN KEY (exp_position_id) REFERENCES exp_position(id);


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
-- TOC entry 2276 (class 2606 OID 19259)
-- Dependencies: 2194 1737 1764
-- Name: exp_stade_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY exp_biologie
    ADD CONSTRAINT exp_stade_id FOREIGN KEY (exp_stade_id) REFERENCES exp_stade(id);



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
-- TOC entry 2297 (class 2606 OID 19289)
-- Dependencies: 1773 1773 2206
-- Name: ref_espece_id; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY ref_espece
    ADD CONSTRAINT ref_espece_id FOREIGN KEY (id) REFERENCES ref_espece(id);

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



-- Completed on 2008-10-13 15:34:32 CEST

--
-- PostgreSQL database dump complete
--

