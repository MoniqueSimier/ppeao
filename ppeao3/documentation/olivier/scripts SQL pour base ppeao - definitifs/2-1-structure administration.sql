--
-- PostgreSQL database dump
--

-- Started on 2008-11-06 15:34:31 CEST

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: admin_dictionary_domains; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_dictionary_domains (
    domaine_nom character varying(255) NOT NULL,
    domaine_description character varying(255),
    domain_id integer NOT NULL
);


ALTER TABLE public.admin_dictionary_domains OWNER TO devppeao;

--
-- Name: TABLE admin_dictionary_domains; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_dictionary_domains IS 'domaine auquel appartient une table';


--
-- Name: COLUMN admin_dictionary_domains.domaine_nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_domains.domaine_nom IS 'le nom du domaine';


--
-- Name: COLUMN admin_dictionary_domains.domaine_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_domains.domaine_description IS 'description du domaine';


--
-- Name: COLUMN admin_dictionary_domains.domain_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_domains.domain_id IS 'identifiant unique';


--
-- Name: admin_dictionary_domains_domain_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_dictionary_domains_domain_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_dictionary_domains_domain_id_seq OWNER TO devppeao;


--
-- Name: domain_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_domains ALTER COLUMN domain_id SET DEFAULT nextval('admin_dictionary_domains_domain_id_seq'::regclass);


--
-- Name: admin_dictionary_domains_domain_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_domains
    ADD CONSTRAINT admin_dictionary_domains_domain_id_key UNIQUE (domain_id);


--
-- Name: admin_dictionary_domains_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_domains
    ADD CONSTRAINT admin_dictionary_domains_pkey PRIMARY KEY (domain_id);

ALTER TABLE admin_dictionary_domains CLUSTER ON admin_dictionary_domains_pkey;


--
-- Name: admin_dictionary_tables; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_dictionary_tables (
    dico_id integer NOT NULL,
    handle character varying(255) NOT NULL,
    label character varying(255) NOT NULL,
    table_db character varying(255) NOT NULL,
    id_col character varying(255) NOT NULL,
    noms_col character varying(255) NOT NULL,
    type_table_id integer NOT NULL,
    domain_id integer NOT NULL,
    selector_cascade character varying(255),
    selector boolean DEFAULT false NOT NULL,
    zone_id integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.admin_dictionary_tables OWNER TO devppeao;


ALTER TABLE admin_dictionary_tables DISABLE TRIGGER ALL;




--
-- Name: TABLE admin_dictionary_tables; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_dictionary_tables IS 'table contenant la définition des tables de données, référence et paramétrage de PPEAO';


--
-- Name: COLUMN admin_dictionary_tables.dico_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.dico_id IS 'identifiant unique de l''entrée de dico';


--
-- Name: COLUMN admin_dictionary_tables.handle; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.handle IS 'le "code" identifiant la table (ex. : csp pour "art_categorie_socio_professionnelle")';


--
-- Name: COLUMN admin_dictionary_tables.label; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.label IS 'nom descriptif complet de la table';


--
-- Name: COLUMN admin_dictionary_tables.table_db; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.table_db IS 'nom de la table dans la base';


--
-- Name: COLUMN admin_dictionary_tables.id_col; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.id_col IS 'nom de la colonne servant d''identifiant unique pour cette table';


--
-- Name: COLUMN admin_dictionary_tables.noms_col; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.noms_col IS 'nom de la colonne servant d''"étiquette" pour les valeurs de cette table';


--
-- Name: COLUMN admin_dictionary_tables.type_table_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.type_table_id IS 'identifiant du type de table';


--
-- Name: COLUMN admin_dictionary_tables.domain_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.domain_id IS 'identifiant du domaine auquel appartient la table';


--
-- Name: COLUMN admin_dictionary_tables.selector_cascade; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.selector_cascade IS 'cascade de tables pour sélectionner les valeurs de cette table (ex. : "originekb,espece")';


--
-- Name: COLUMN admin_dictionary_tables.selector; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.selector IS 'true si on veut que la table soit éditée par le biais d''un sélecteur en cascade';


--
-- Name: COLUMN admin_dictionary_tables.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_tables.zone_id IS 'identifiant de la zone d''accès à laquelle appartient la table';


--
-- Name: admin_tables_dictionary_dico_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_tables_dictionary_dico_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_tables_dictionary_dico_id_seq OWNER TO devppeao;


--
-- Name: admin_tables_dictionary_domain_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_tables_dictionary_domain_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_tables_dictionary_domain_id_seq OWNER TO devppeao;


--
-- Name: admin_tables_dictionary_type_table_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_tables_dictionary_type_table_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_tables_dictionary_type_table_id_seq OWNER TO devppeao;


--
-- Name: dico_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_tables ALTER COLUMN dico_id SET DEFAULT nextval('admin_tables_dictionary_dico_id_seq'::regclass);


--
-- Name: type_table_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_tables ALTER COLUMN type_table_id SET DEFAULT nextval('admin_tables_dictionary_type_table_id_seq'::regclass);


--
-- Name: domain_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_tables ALTER COLUMN domain_id SET DEFAULT nextval('admin_tables_dictionary_domain_id_seq'::regclass);


--
-- Name: admin_dictionary_tables_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_dictionary_tables_pkey PRIMARY KEY (dico_id);

ALTER TABLE admin_dictionary_tables CLUSTER ON admin_dictionary_tables_pkey;


--
-- Name: admin_tables_dictionary_handle_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_tables_dictionary_handle_key UNIQUE (handle);


--
-- Name: admin_tables_dictionary_table_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_tables_dictionary_table_key UNIQUE (table_db);


--
-- Name: admin_dictionary_tables_domain_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_dictionary_tables_domain_id_index ON admin_dictionary_tables USING btree (domain_id);


--
-- Name: admin_dictionary_type_tables; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_dictionary_type_tables (
    type_table_id integer NOT NULL,
    type_table_nom character varying(255) NOT NULL,
    type_table_description character varying(255)
);


ALTER TABLE public.admin_dictionary_type_tables OWNER TO devppeao;

--
-- Name: TABLE admin_dictionary_type_tables; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_dictionary_type_tables IS 'les types de tables';


--
-- Name: COLUMN admin_dictionary_type_tables.type_table_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_type_tables.type_table_id IS 'identifiant unique';


--
-- Name: COLUMN admin_dictionary_type_tables.type_table_nom; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_type_tables.type_table_nom IS 'le nom court du type';


--
-- Name: COLUMN admin_dictionary_type_tables.type_table_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_dictionary_type_tables.type_table_description IS 'description';


--
-- Name: admin_dictionary_type_tables_type_table_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_dictionary_type_tables_type_table_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_dictionary_type_tables_type_table_id_seq OWNER TO devppeao;

--
-- Name: type_table_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_type_tables ALTER COLUMN type_table_id SET DEFAULT nextval('admin_dictionary_type_tables_type_table_id_seq'::regclass);


--
-- Name: admin_dictionary_type_tables_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_type_tables
    ADD CONSTRAINT admin_dictionary_type_tables_pkey PRIMARY KEY (type_table_id);

ALTER TABLE admin_dictionary_type_tables CLUSTER ON admin_dictionary_type_tables_pkey;


--
-- Name: admin_dictionary_type_tables_type_table_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_type_tables
    ADD CONSTRAINT admin_dictionary_type_tables_type_table_id_key UNIQUE (type_table_id);


--
-- Name: admin_dictionary_type_tables_type_table_nom_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_dictionary_type_tables
    ADD CONSTRAINT admin_dictionary_type_tables_type_table_nom_key UNIQUE (type_table_nom);

--
-- Name: admin_j_group_zone; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_group_zone (
    group_id integer NOT NULL,
    zone_id integer NOT NULL,
    jgroupzone_id integer NOT NULL
);


ALTER TABLE public.admin_j_group_zone OWNER TO devppeao;

--
-- Name: TABLE admin_j_group_zone; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_group_zone IS 'table indiquant à quelles zones ont accès les différents groupes';


--
-- Name: COLUMN admin_j_group_zone.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_group_zone.group_id IS 'l''id du groupe';


--
-- Name: COLUMN admin_j_group_zone.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_group_zone.zone_id IS 'l''id de la zone à laquelle le groupe a accès';


--
-- Name: COLUMN admin_j_group_zone.jgroupzone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_group_zone.jgroupzone_id IS 'id unique de la relation groupe-zone';


--
-- Name: admin_j_group_zone_jgroupzone_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_j_group_zone_jgroupzone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_j_group_zone_jgroupzone_id_seq OWNER TO devppeao;

--
-- Name: jgroupzone_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_j_group_zone ALTER COLUMN jgroupzone_id SET DEFAULT nextval('admin_j_group_zone_jgroupzone_id_seq'::regclass);


--
-- Name: admin_j_group_zone_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_pkey PRIMARY KEY (jgroupzone_id);

ALTER TABLE admin_j_group_zone CLUSTER ON admin_j_group_zone_pkey;


--
-- Name: admin_j_group_zone_group_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_group_zone_group_id_index ON admin_j_group_zone USING btree (group_id);


--
-- Name: admin_j_group_zone_zone_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_group_zone_zone_id_index ON admin_j_group_zone USING btree (zone_id);


--
-- Name: admin_j_user_group; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL,
    jusergroup_id integer NOT NULL
);


ALTER TABLE public.admin_j_user_group OWNER TO devppeao;

--
-- Name: TABLE admin_j_user_group; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_user_group IS 'table de jointure entre un utilisateur et les groupes dont il fait partie';


--
-- Name: COLUMN admin_j_user_group.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_group.user_id IS 'id de l''utilisateur';


--
-- Name: COLUMN admin_j_user_group.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_group.group_id IS 'id du groupe auquel l''utilisateur appartient';


--
-- Name: COLUMN admin_j_user_group.jusergroup_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_group.jusergroup_id IS 'id unique de la relation user-groupe';


--
-- Name: admin_j_user_group_jusergroup_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_j_user_group_jusergroup_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_j_user_group_jusergroup_id_seq OWNER TO devppeao;

--
-- Name: jusergroup_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_j_user_group ALTER COLUMN jusergroup_id SET DEFAULT nextval('admin_j_user_group_jusergroup_id_seq'::regclass);


--
-- Name: admin_j_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT admin_j_user_group_pkey PRIMARY KEY (jusergroup_id);

ALTER TABLE admin_j_user_group CLUSTER ON admin_j_user_group_pkey;


--
-- Name: admin_j_user_group_group_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_user_group_group_id_index ON admin_j_user_group USING btree (group_id);


--
-- Name: admin_j_user_group_user_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_user_group_user_id_index ON admin_j_user_group USING btree (user_id);


--
-- Name: admin_j_user_zone; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_j_user_zone (
    user_id integer NOT NULL,
    zone_id integer NOT NULL,
    juserzone_id integer NOT NULL
);


ALTER TABLE public.admin_j_user_zone OWNER TO devppeao;

--
-- Name: TABLE admin_j_user_zone; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_j_user_zone IS 'à quelle(s) zone(s) un utilisateur a accès.';


--
-- Name: COLUMN admin_j_user_zone.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_zone.user_id IS 'ID de l''utilisateur';


--
-- Name: COLUMN admin_j_user_zone.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_zone.zone_id IS 'ID de la zone à laquelle il a accès';


--
-- Name: COLUMN admin_j_user_zone.juserzone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_j_user_zone.juserzone_id IS 'id unique de la relation user-zone';


--
-- Name: admin_j_user_zone_juserzone_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_j_user_zone_juserzone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_j_user_zone_juserzone_id_seq OWNER TO devppeao;


--
-- Name: juserzone_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_j_user_zone ALTER COLUMN juserzone_id SET DEFAULT nextval('admin_j_user_zone_juserzone_id_seq'::regclass);


--
-- Name: admin_j_user_zone_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_pkey PRIMARY KEY (juserzone_id);

ALTER TABLE admin_j_user_zone CLUSTER ON admin_j_user_zone_pkey;


--
-- Name: admin_j_user_zone_user_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_user_zone_user_id_index ON admin_j_user_zone USING btree (user_id);


--
-- Name: admin_j_user_zone_zone_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_j_user_zone_zone_id_index ON admin_j_user_zone USING btree (zone_id);



--
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
-- Name: TABLE admin_log; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log IS 'cette table stocke les entrées du journal de l''application PPEAO';


--
-- Name: COLUMN admin_log.log_module_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_module_id IS 'le module de l''application qui écrit le message (table admin_log_modules)';


--
-- Name: COLUMN admin_log.log_script_file; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_script_file IS 'le chemin du script PHP qui a déclenché l''écriture ans le journal';


--
-- Name: COLUMN admin_log.log_message; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_message IS 'le message à écrire dans le journal';


--
-- Name: COLUMN admin_log.log_user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_user_id IS 'l''id de l''utilisateur connecté qui a provoqué l''écriture dans le journal (table admin_users)';


--
-- Name: COLUMN admin_log.log_action_do; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_action_do IS 'l''action qui est réalisée (syntaxe SQL dans le cas d''une opération sur la base)';


--
-- Name: COLUMN admin_log.log_action_undo; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_action_undo IS 'l''action permettant d''annuler l''actionDo (syntaxe SQL "inverse" dans le cas d''une opération sur la base)';


--
-- Name: COLUMN admin_log.log_time; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_time IS 'le timestamp de l''entrée du journal';


--
-- Name: COLUMN admin_log.log_message_type; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log.log_message_type IS 'le type de message (table admin_log_message_types)';



--
-- Name: admin_log_message_types; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_log_message_types (
    message_type character varying(255) NOT NULL
);


ALTER TABLE public.admin_log_message_types OWNER TO devppeao;

--
-- Name: TABLE admin_log_message_types; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log_message_types IS 'contient la liste des types de messages du journal';


--
-- Name: admin_log_message_types_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_log_message_types
    ADD CONSTRAINT admin_log_message_types_pkey PRIMARY KEY (message_type);

ALTER TABLE admin_log_message_types CLUSTER ON admin_log_message_types_pkey;

--
-- Name: admin_log_modules; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_log_modules (
    module_id integer NOT NULL,
    module_name character varying(255) NOT NULL
);


ALTER TABLE public.admin_log_modules OWNER TO devppeao;

--
-- Name: TABLE admin_log_modules; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_log_modules IS 'liste des modules de l''application PPEAO, utilisés pour identifier la source des entrées du journal';


--
-- Name: COLUMN admin_log_modules.module_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log_modules.module_id IS 'id unique du module';


--
-- Name: COLUMN admin_log_modules.module_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_log_modules.module_name IS 'le nom du module';


--
-- Name: admin_log_modules_module_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_log_modules_module_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_log_modules_module_id_seq OWNER TO devppeao;


--
-- Name: module_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_log_modules ALTER COLUMN module_id SET DEFAULT nextval('admin_log_modules_module_id_seq'::regclass);


--
-- Name: admin_log_modules_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_log_modules
    ADD CONSTRAINT admin_log_modules_pkey PRIMARY KEY (module_id);

ALTER TABLE admin_log_modules CLUSTER ON admin_log_modules_pkey;


--
-- Name: admin_sequences; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_sequences (
    sequence_id integer NOT NULL,
    table_id integer NOT NULL,
    column_name character varying(255) NOT NULL,
    sequence_name character varying(255) NOT NULL
);


ALTER TABLE public.admin_sequences OWNER TO devppeao;

--
-- Name: TABLE admin_sequences; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_sequences IS 'cette table contient une liste des séquences (pour le script de mise à jour manuelle des séquences)';


--
-- Name: COLUMN admin_sequences.sequence_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_sequences.sequence_id IS 'id unique de la séquence';


--
-- Name: COLUMN admin_sequences.table_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_sequences.table_id IS 'id de la table à laquelle la séquence est attachée';


--
-- Name: COLUMN admin_sequences.column_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_sequences.column_name IS 'nom de la colonne à laquelle la séquence est attachée';


--
-- Name: COLUMN admin_sequences.sequence_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_sequences.sequence_name IS 'nom de la séquence';


--
-- Name: admin_sequences_sequence_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_sequences_sequence_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_sequences_sequence_id_seq OWNER TO devppeao;

--

--
-- Name: sequence_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_sequences ALTER COLUMN sequence_id SET DEFAULT nextval('admin_sequences_sequence_id_seq'::regclass);


--
-- Name: admin_sequences_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_sequences
    ADD CONSTRAINT admin_sequences_pkey PRIMARY KEY (sequence_id);

ALTER TABLE admin_sequences CLUSTER ON admin_sequences_pkey;


--
-- Name: admin_sequences_table_id_index; Type: INDEX; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE INDEX admin_sequences_table_id_index ON admin_sequences USING btree (table_id);




--
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
-- Name: TABLE admin_usergroups; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_usergroups IS 'table contenant les groupes d''utilisateurs';


--
-- Name: COLUMN admin_usergroups.group_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_id IS 'identifiant unique du groupe';


--
-- Name: COLUMN admin_usergroups.group_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_name IS 'le nom du groupe';


--
-- Name: COLUMN admin_usergroups.group_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_description IS 'description du groupe';


--
-- Name: COLUMN admin_usergroups.group_active; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_usergroups.group_active IS 'le groupe est-il actif ou désactivé?';


--
-- Name: admin_users_groups_group_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_users_groups_group_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_users_groups_group_id_seq OWNER TO devppeao;

--
-- Name: group_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_usergroups ALTER COLUMN group_id SET DEFAULT nextval('admin_users_groups_group_id_seq'::regclass);


--
-- Name: admin_users_groups_group_id_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_usergroups
    ADD CONSTRAINT admin_users_groups_group_id_key UNIQUE (group_id);


--
-- Name: admin_users_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_usergroups
    ADD CONSTRAINT admin_users_groups_pkey PRIMARY KEY (group_id, group_name);

ALTER TABLE admin_usergroups CLUSTER ON admin_users_groups_pkey;

--
-- Name: admin_users; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_users (
    user_id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    user_longname character varying(255),
    user_active boolean DEFAULT true NOT NULL,
    user_comment text,
    user_email character varying(255),
    user_password character varying(255),
    user_creation_date date
);


ALTER TABLE public.admin_users OWNER TO devppeao;

--
-- Name: TABLE admin_users; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_users IS 'cette table contient les utilisateurs enregistrés';


--
-- Name: COLUMN admin_users.user_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_id IS 'id unique de l''utilisateur';


--
-- Name: COLUMN admin_users.user_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_name IS 'nom court d''utilisateur ("login")';


--
-- Name: COLUMN admin_users.user_longname; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_longname IS 'nom complet de l''utilisateur ("paul hair")';


--
-- Name: COLUMN admin_users.user_active; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_active IS 'true si l''utilisateur est activé, false si il est désactivé';


--
-- Name: COLUMN admin_users.user_comment; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_comment IS 'commentaire sur l''utilisateur';


--
-- Name: COLUMN admin_users.user_email; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_email IS 'adresse email de l''utilisateur';


--
-- Name: COLUMN admin_users.user_password; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_password IS 'le mot de passe de l''utilisateur, encrypté';


--
-- Name: COLUMN admin_users.user_creation_date; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_users.user_creation_date IS 'date de création de l''utilisateur';


--
-- Name: admin_users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_users_user_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_users_user_id_seq OWNER TO devppeao;

--
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_users ALTER COLUMN user_id SET DEFAULT nextval('admin_users_user_id_seq'::regclass);


--
-- Name: admin_users_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_users
    ADD CONSTRAINT admin_users_pkey PRIMARY KEY (user_id);

ALTER TABLE admin_users CLUSTER ON admin_users_pkey;


--
-- Name: admin_users_user_name_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_users
    ADD CONSTRAINT admin_users_user_name_key UNIQUE (user_name);

--
-- Name: admin_zones; Type: TABLE; Schema: public; Owner: devppeao; Tablespace: 
--

CREATE TABLE admin_zones (
    zone_id integer NOT NULL,
    zone_name character varying(255) NOT NULL,
    zone_description character varying(255)
);


ALTER TABLE public.admin_zones OWNER TO devppeao;

--
-- Name: TABLE admin_zones; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON TABLE admin_zones IS 'liste des "zones" du site, pour contôler l''accès à certaines pages';


--
-- Name: COLUMN admin_zones.zone_id; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_id IS 'l''ID unique de la zone';


--
-- Name: COLUMN admin_zones.zone_name; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_name IS 'le nom unique de la zone';


--
-- Name: COLUMN admin_zones.zone_description; Type: COMMENT; Schema: public; Owner: devppeao
--

COMMENT ON COLUMN admin_zones.zone_description IS 'description de la zone';


--
-- Name: admin_zones_zone_id_seq; Type: SEQUENCE; Schema: public; Owner: devppeao
--

CREATE SEQUENCE admin_zones_zone_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.admin_zones_zone_id_seq OWNER TO devppeao;


--
-- Name: zone_id; Type: DEFAULT; Schema: public; Owner: devppeao
--

ALTER TABLE admin_zones ALTER COLUMN zone_id SET DEFAULT nextval('admin_zones_zone_id_seq'::regclass);


--
-- Name: admin_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_zones
    ADD CONSTRAINT admin_zones_pkey PRIMARY KEY (zone_id);

ALTER TABLE admin_zones CLUSTER ON admin_zones_pkey;


--
-- Name: admin_zones_zone_name_key; Type: CONSTRAINT; Schema: public; Owner: devppeao; Tablespace: 
--

ALTER TABLE ONLY admin_zones
    ADD CONSTRAINT admin_zones_zone_name_key UNIQUE (zone_name);


--
-- Name: admin_dictionary_tables_domain_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_dictionary_tables_domain_id_fkey FOREIGN KEY (domain_id) REFERENCES admin_dictionary_domains(domain_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_dictionary_tables_type_table_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_dictionary_tables_type_table_id_fkey FOREIGN KEY (type_table_id) REFERENCES admin_dictionary_type_tables(type_table_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_dictionary_tables_zone_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_dictionary_tables
    ADD CONSTRAINT admin_dictionary_tables_zone_id_fkey FOREIGN KEY (zone_id) REFERENCES admin_zones(zone_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_j_group_zone_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_group_id_fkey FOREIGN KEY (group_id) REFERENCES admin_usergroups(group_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_j_group_zone_zone_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_group_zone
    ADD CONSTRAINT admin_j_group_zone_zone_id_fkey FOREIGN KEY (zone_id) REFERENCES admin_zones(zone_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_j_user_group_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT admin_j_user_group_group_id_fkey FOREIGN KEY (group_id) REFERENCES admin_usergroups(group_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_j_user_group_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_group
    ADD CONSTRAINT admin_j_user_group_user_id_fkey FOREIGN KEY (user_id) REFERENCES admin_users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: admin_j_user_zone_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_user_id_fkey FOREIGN KEY (user_id) REFERENCES admin_users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_j_user_zone_zone_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_j_user_zone
    ADD CONSTRAINT admin_j_user_zone_zone_id_fkey FOREIGN KEY (zone_id) REFERENCES admin_zones(zone_id) ON UPDATE CASCADE ON DELETE CASCADE;
--
-- Name: admin_log_log_message_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_message_type_fkey FOREIGN KEY (log_message_type) REFERENCES admin_log_message_types(message_type) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_log_log_module_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_module_id_fkey FOREIGN KEY (log_module_id) REFERENCES admin_log_modules(module_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: admin_log_log_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_log
    ADD CONSTRAINT admin_log_log_user_id_fkey FOREIGN KEY (log_user_id) REFERENCES admin_users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- Name: admin_sequences_table_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: devppeao
--

ALTER TABLE ONLY admin_sequences
    ADD CONSTRAINT admin_sequences_table_id_fkey FOREIGN KEY (table_id) REFERENCES admin_dictionary_tables(dico_id) ON UPDATE CASCADE ON DELETE CASCADE;


