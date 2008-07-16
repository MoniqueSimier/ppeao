--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: 
--

CREATE PROCEDURAL LANGUAGE plpgsql;


SET search_path = public, pg_catalog;

--
-- Name: dblink_pkey_results; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE dblink_pkey_results AS (
	"position" integer,
	colname text
);


ALTER TYPE public.dblink_pkey_results OWNER TO postgres;

--
-- Name: dblink(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink(text, text) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_record'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink(text, text) OWNER TO postgres;

--
-- Name: dblink(text, text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink(text, text, boolean) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_record'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink(text, text, boolean) OWNER TO postgres;

--
-- Name: dblink(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink(text) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_record'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink(text) OWNER TO postgres;

--
-- Name: dblink(text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink(text, boolean) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_record'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink(text, boolean) OWNER TO postgres;

--
-- Name: dblink_build_sql_delete(text, int2vector, integer, text[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_build_sql_delete(text, int2vector, integer, text[]) RETURNS text
    AS '$libdir/dblink', 'dblink_build_sql_delete'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_build_sql_delete(text, int2vector, integer, text[]) OWNER TO postgres;

--
-- Name: dblink_build_sql_insert(text, int2vector, integer, text[], text[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_build_sql_insert(text, int2vector, integer, text[], text[]) RETURNS text
    AS '$libdir/dblink', 'dblink_build_sql_insert'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_build_sql_insert(text, int2vector, integer, text[], text[]) OWNER TO postgres;

--
-- Name: dblink_build_sql_update(text, int2vector, integer, text[], text[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_build_sql_update(text, int2vector, integer, text[], text[]) RETURNS text
    AS '$libdir/dblink', 'dblink_build_sql_update'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_build_sql_update(text, int2vector, integer, text[], text[]) OWNER TO postgres;

--
-- Name: dblink_close(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_close(text) RETURNS text
    AS '$libdir/dblink', 'dblink_close'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_close(text) OWNER TO postgres;

--
-- Name: dblink_close(text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_close(text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_close'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_close(text, boolean) OWNER TO postgres;

--
-- Name: dblink_close(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_close(text, text) RETURNS text
    AS '$libdir/dblink', 'dblink_close'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_close(text, text) OWNER TO postgres;

--
-- Name: dblink_close(text, text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_close(text, text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_close'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_close(text, text, boolean) OWNER TO postgres;

--
-- Name: dblink_connect(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_connect(text) RETURNS text
    AS '$libdir/dblink', 'dblink_connect'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_connect(text) OWNER TO postgres;

--
-- Name: dblink_connect(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_connect(text, text) RETURNS text
    AS '$libdir/dblink', 'dblink_connect'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_connect(text, text) OWNER TO postgres;

--
-- Name: dblink_current_query(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_current_query() RETURNS text
    AS '$libdir/dblink', 'dblink_current_query'
    LANGUAGE c;


ALTER FUNCTION public.dblink_current_query() OWNER TO postgres;

--
-- Name: dblink_disconnect(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_disconnect() RETURNS text
    AS '$libdir/dblink', 'dblink_disconnect'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_disconnect() OWNER TO postgres;

--
-- Name: dblink_disconnect(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_disconnect(text) RETURNS text
    AS '$libdir/dblink', 'dblink_disconnect'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_disconnect(text) OWNER TO postgres;

--
-- Name: dblink_exec(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_exec(text, text) RETURNS text
    AS '$libdir/dblink', 'dblink_exec'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_exec(text, text) OWNER TO postgres;

--
-- Name: dblink_exec(text, text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_exec(text, text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_exec'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_exec(text, text, boolean) OWNER TO postgres;

--
-- Name: dblink_exec(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_exec(text) RETURNS text
    AS '$libdir/dblink', 'dblink_exec'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_exec(text) OWNER TO postgres;

--
-- Name: dblink_exec(text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_exec(text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_exec'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_exec(text, boolean) OWNER TO postgres;

--
-- Name: dblink_fetch(text, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_fetch(text, integer) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_fetch'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_fetch(text, integer) OWNER TO postgres;

--
-- Name: dblink_fetch(text, integer, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_fetch(text, integer, boolean) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_fetch'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_fetch(text, integer, boolean) OWNER TO postgres;

--
-- Name: dblink_fetch(text, text, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_fetch(text, text, integer) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_fetch'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_fetch(text, text, integer) OWNER TO postgres;

--
-- Name: dblink_fetch(text, text, integer, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_fetch(text, text, integer, boolean) RETURNS SETOF record
    AS '$libdir/dblink', 'dblink_fetch'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_fetch(text, text, integer, boolean) OWNER TO postgres;

--
-- Name: dblink_get_pkey(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_get_pkey(text) RETURNS SETOF dblink_pkey_results
    AS '$libdir/dblink', 'dblink_get_pkey'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_get_pkey(text) OWNER TO postgres;

--
-- Name: dblink_open(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_open(text, text) RETURNS text
    AS '$libdir/dblink', 'dblink_open'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_open(text, text) OWNER TO postgres;

--
-- Name: dblink_open(text, text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_open(text, text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_open'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_open(text, text, boolean) OWNER TO postgres;

--
-- Name: dblink_open(text, text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_open(text, text, text) RETURNS text
    AS '$libdir/dblink', 'dblink_open'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_open(text, text, text) OWNER TO postgres;

--
-- Name: dblink_open(text, text, text, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION dblink_open(text, text, text, boolean) RETURNS text
    AS '$libdir/dblink', 'dblink_open'
    LANGUAGE c STRICT;


ALTER FUNCTION public.dblink_open(text, text, text, boolean) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: art_debarquement; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_debarquement OWNER TO postgres;

--
-- Name: art_fraction; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_fraction OWNER TO postgres;

--
-- Name: art_poisson_mesure; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_poisson_mesure (
    id integer DEFAULT nextval(('"public"."art_poisson_mesure_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    taille integer,
    art_fraction_id character varying(15)
);


ALTER TABLE public.art_poisson_mesure OWNER TO postgres;

--
-- Name: Bour02; Type: VIEW; Schema: public; Owner: devpechartexp
--

CREATE VIEW "Bour02" AS
    SELECT art_debarquement.code, art_debarquement.id, art_debarquement.mois, art_debarquement.art_grand_type_engin_id AS grdtyp, art_debarquement.poids_total AS pt, art_fraction.ref_espece_id AS esp, art_fraction.poids AS fdbq, art_fraction.nbre_poissons AS nfdbq, count(art_poisson_mesure.taille) AS nbdft FROM art_debarquement, art_fraction, art_poisson_mesure WHERE ((art_fraction.art_debarquement_id = art_debarquement.id) AND ((art_poisson_mesure.art_fraction_id)::text = (art_fraction.id)::text)) GROUP BY art_debarquement.code, art_debarquement.id, art_debarquement.mois, art_debarquement.art_grand_type_engin_id, art_debarquement.poids_total, art_fraction.ref_espece_id, art_fraction.poids, art_fraction.nbre_poissons ORDER BY art_debarquement.code;


ALTER TABLE public."Bour02" OWNER TO devpechartexp;

--
-- Name: art_activite; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_activite OWNER TO postgres;

--
-- Name: art_agglomeration; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_agglomeration OWNER TO postgres;

--
-- Name: ref_pays; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_pays (
    id character varying(10) NOT NULL,
    nom character varying(50)
);


ALTER TABLE public.ref_pays OWNER TO postgres;

--
-- Name: ref_secteur; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_secteur (
    id integer NOT NULL,
    id_dans_systeme integer,
    nom character varying(50),
    superficie real,
    ref_systeme_id integer
);


ALTER TABLE public.ref_secteur OWNER TO postgres;

--
-- Name: ref_systeme; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_systeme (
    id integer NOT NULL,
    libelle character varying(50),
    ref_pays_id character varying(10),
    superficie real
);


ALTER TABLE public.ref_systeme OWNER TO postgres;

--
-- Name: ComptACT02; Type: VIEW; Schema: public; Owner: devpechartexp
--

CREATE VIEW "ComptACT02" AS
    SELECT ref_pays.nom AS pays, ref_systeme.libelle, ref_secteur.nom AS secteur, art_agglomeration.nom AS agglo, art_agglomeration.id AS num1, count(art_activite.id) AS n1 FROM ref_pays, ref_systeme, ref_secteur, art_agglomeration, art_activite WHERE (((((ref_pays.id)::text = (ref_systeme.ref_pays_id)::text) AND (ref_systeme.id = ref_secteur.ref_systeme_id)) AND (ref_secteur.id = art_agglomeration.ref_secteur_id)) AND (art_agglomeration.id = art_activite.art_agglomeration_id)) GROUP BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom, art_agglomeration.nom, art_agglomeration.id ORDER BY ref_pays.nom, ref_systeme.libelle, ref_secteur.nom, art_agglomeration.nom;


ALTER TABLE public."ComptACT02" OWNER TO devpechartexp;

--
-- Name: art_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_activite_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_activite_id_seq OWNER TO postgres;

--
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
-- Name: art_categorie_socio_professionnelle; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_categorie_socio_professionnelle (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_categorie_socio_professionnelle OWNER TO postgres;

--
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
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_debarquement_id_seq OWNER TO postgres;

--
-- Name: art_debarquement_rec; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE art_debarquement_rec (
    id character varying(20) NOT NULL,
    poids_total real NOT NULL,
    art_debarquement_id character varying(20) NOT NULL
);


ALTER TABLE public.art_debarquement_rec OWNER TO devppeao;


--
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
-- Name: art_engin_activite; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_engin_activite (
    id integer DEFAULT nextval(('"public"."art_engin_activite_id_seq"'::text)::regclass) NOT NULL,
    code integer,
    nbre integer,
    art_activite_id integer,
    art_type_engin_id character varying(10)
);


ALTER TABLE public.art_engin_activite OWNER TO postgres;

--
-- Name: art_engin_activite_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_activite_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_engin_activite_id_seq OWNER TO postgres;

--
-- Name: art_engin_peche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_engin_peche OWNER TO postgres;

--
-- Name: art_engin_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_engin_peche_id_seq
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
-- Name: art_etat_ciel; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_etat_ciel (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_etat_ciel OWNER TO postgres;

--
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
-- Name: art_grand_type_engin; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_grand_type_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_grand_type_engin OWNER TO postgres;

--
-- Name: art_lieu_de_peche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_lieu_de_peche (
    id integer DEFAULT nextval(('"public"."art_lieu_de_peche_id_seq"'::text)::regclass) NOT NULL,
    ref_secteur_id integer,
    libelle character varying(50),
    code integer
);


ALTER TABLE public.art_lieu_de_peche OWNER TO postgres;

--
-- Name: art_lieu_de_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_lieu_de_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_lieu_de_peche_id_seq OWNER TO postgres;

--
-- Name: art_millieu; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_millieu (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_millieu OWNER TO postgres;

--
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
-- Name: art_poisson_mesure_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_poisson_mesure_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_poisson_mesure_id_seq OWNER TO postgres;

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
    nbre_jour_activite integer,
    nbre_jour_enq_deb integer
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
    xi real,
    art_stat_sp_id integer NOT NULL
);


ALTER TABLE public.art_taille_sp OWNER TO devppeao;

--
-- Name: art_type_activite; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_type_activite (
    raison character varying(50),
    libelle character varying(255),
    id character varying(10) NOT NULL
);


ALTER TABLE public.art_type_activite OWNER TO postgres;

--
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
-- Name: art_type_agglomeration; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_type_agglomeration (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_type_agglomeration OWNER TO postgres;

--
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
-- Name: art_type_engin; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_type_engin (
    id character varying(10) NOT NULL,
    art_grand_type_engin_id character varying(10),
    libelle character varying(50)
);


ALTER TABLE public.art_type_engin OWNER TO postgres;

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
-- Name: art_type_sortie; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_type_sortie (
    id integer NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.art_type_sortie OWNER TO postgres;

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
-- Name: art_unite_peche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.art_unite_peche OWNER TO postgres;

--
-- Name: art_unite_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE art_unite_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.art_unite_peche_id_seq OWNER TO postgres;

--
-- Name: art_vent; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE art_vent (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.art_vent OWNER TO postgres;

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
-- Name: exp_biologie; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.exp_biologie OWNER TO postgres;

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
-- Name: exp_campagne; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_campagne (
    id integer DEFAULT nextval(('"public"."exp_campagne_id_seq"'::text)::regclass) NOT NULL,
    ref_systeme_id integer,
    numero_campagne integer,
    date_debut date,
    date_fin date,
    libelle character varying(100)
);


ALTER TABLE public.exp_campagne OWNER TO postgres;

--
-- Name: exp_campagne_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_campagne_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_campagne_id_seq OWNER TO postgres;

--
-- Name: exp_contenu; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_contenu (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_contenu OWNER TO postgres;

--
-- Name: exp_contenu_biologie_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_contenu_biologie_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_contenu_biologie_id_seq OWNER TO postgres;

--
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
-- Name: exp_coup_peche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.exp_coup_peche OWNER TO postgres;

--
-- Name: exp_cp_peche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exp_cp_peche_id_seq
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.exp_cp_peche_id_seq OWNER TO postgres;

--
-- Name: exp_debris; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_debris (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_debris OWNER TO postgres;

--
-- Name: exp_engin; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_engin (
    id character varying(10) NOT NULL,
    libelle character varying(50),
    longueur real,
    chute real,
    maille integer,
    memo text
);


ALTER TABLE public.exp_engin OWNER TO postgres;

--
-- Name: exp_environnement; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.exp_environnement OWNER TO postgres;

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
-- Name: exp_force_courant; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_force_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_force_courant OWNER TO postgres;

--
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
-- Name: exp_fraction; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.exp_fraction OWNER TO postgres;

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
-- Name: exp_position; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_position (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_position OWNER TO postgres;

--
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
-- Name: exp_qualite; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_qualite (
    id integer NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_qualite OWNER TO postgres;

--
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
-- Name: exp_remplissage; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_remplissage (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_remplissage OWNER TO postgres;

--
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
-- Name: exp_sediment; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_sediment (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_sediment OWNER TO postgres;

--
-- Name: exp_sens_courant; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_sens_courant (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_sens_courant OWNER TO postgres;

--
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
-- Name: exp_sexe; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_sexe (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.exp_sexe OWNER TO postgres;

--
-- Name: exp_stade; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_stade (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_stade OWNER TO postgres;

--
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
-- Name: exp_station; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.exp_station OWNER TO postgres;

--
-- Name: exp_trophique; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_trophique (
    id integer DEFAULT nextval(('"public"."exp_contenu_biologie_id_seq"'::text)::regclass) NOT NULL,
    exp_biologie_id integer NOT NULL,
    exp_contenu_id integer NOT NULL,
    quantite real
);


ALTER TABLE public.exp_trophique OWNER TO postgres;

--
-- Name: exp_vegetation; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exp_vegetation (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.exp_vegetation OWNER TO postgres;

--
-- Name: pg_ts_cfg; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pg_ts_cfg (
    ts_name text NOT NULL,
    prs_name text NOT NULL,
    locale text
);


ALTER TABLE public.pg_ts_cfg OWNER TO postgres;

--
-- Name: pg_ts_cfgmap; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pg_ts_cfgmap (
    ts_name text NOT NULL,
    tok_alias text NOT NULL,
    dict_name text[]
);


ALTER TABLE public.pg_ts_cfgmap OWNER TO postgres;

--
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
-- Name: ref_autorisation_exploitation; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_autorisation_exploitation (
    "login" character varying(30),
    pointeur integer
);


ALTER TABLE public.ref_autorisation_exploitation OWNER TO devppeao;

--
-- Name: ref_categorie_ecologique; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_categorie_ecologique (
    id character varying(10) NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_categorie_ecologique OWNER TO postgres;

--
-- Name: ref_categorie_trophique; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_categorie_trophique (
    id character varying(10) NOT NULL,
    libelle character varying(100)
);


ALTER TABLE public.ref_categorie_trophique OWNER TO postgres;

--
-- Name: ref_espece; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
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


ALTER TABLE public.ref_espece OWNER TO postgres;

--
-- Name: ref_famille; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_famille (
    id integer NOT NULL,
    libelle character varying(50),
    ref_ordre_id integer,
    non_poisson integer,
    CONSTRAINT ref_famille_non_poisson_check CHECK (((non_poisson = 0) OR (non_poisson = 1)))
);


ALTER TABLE public.ref_famille OWNER TO postgres;

--
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
-- Name: ref_ordre; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_ordre (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_ordre OWNER TO postgres;

--
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
-- Name: ref_origine_kb; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ref_origine_kb (
    id integer NOT NULL,
    libelle character varying(50)
);


ALTER TABLE public.ref_origine_kb OWNER TO postgres;

--
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
-- Name: ref_systeme_date_butoir; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_systeme_date_butoir (
    id integer NOT NULL,
    type_echant integer,
    systeme character varying(30),
    date_butoire character varying(30)
);


ALTER TABLE public.ref_systeme_date_butoir OWNER TO devppeao;

--
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
-- Name: ref_utilisateurs; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE ref_utilisateurs (
    "login" character varying(30) NOT NULL,
    "password" character varying(30),
    nom character varying(30),
    prenom character varying(30),
    adresse character varying(150)
);


ALTER TABLE public.ref_utilisateurs OWNER TO devppeao;

--
-- Name: sys_activites_a_migrer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE sys_activites_a_migrer (
    pays character varying(10),
    systeme integer,
    secteur integer,
    agglomeration integer,
    activite_source integer,
    activite_cible integer,
    id serial NOT NULL,
    base_pays character varying(50)
);


ALTER TABLE public.sys_activites_a_migrer OWNER TO postgres;

--
-- Name: sys_campagnes_a_migrer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE sys_campagnes_a_migrer (
    pays character varying(10),
    systeme integer,
    campagne_source bigint,
    campagne_cible bigint,
    id serial NOT NULL
);


ALTER TABLE public.sys_campagnes_a_migrer OWNER TO postgres;

--
-- Name: sys_debarquements_a_migrer; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE sys_debarquements_a_migrer (
    pays character varying(10),
    systeme integer,
    secteur integer,
    agglomeration integer,
    debarquement_source integer,
    debarquement_cible integer,
    id serial NOT NULL,
    base_pays character varying(50)
);


ALTER TABLE public.sys_debarquements_a_migrer OWNER TO postgres;

--
-- Name: sys_groupe_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE sys_groupe_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_groupe_id_seq OWNER TO postgres;

--
-- Name: sys_periodes_enquete; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE sys_periodes_enquete (
    id serial NOT NULL,
    pays_id character varying(10),
    systeme_id integer,
    secteur_id integer,
    agglomeration_id integer,
    date_debut date,
    date_fin date,
    base_pays character varying(50)
);


ALTER TABLE public.sys_periodes_enquete OWNER TO postgres;

--
-- Name: sys_utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE sys_utilisateur_id_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 2147483647
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sys_utilisateur_id_seq OWNER TO postgres;

