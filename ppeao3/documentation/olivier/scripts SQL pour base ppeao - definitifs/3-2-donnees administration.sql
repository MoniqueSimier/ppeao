--
-- PostgreSQL database dump
--
-- Started on 2008-11-06 15:34:31 CEST

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET search_path = public, pg_catalog;

--
-- Name: admin_dictionary_domains_domain_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_dictionary_domains_domain_id_seq', 6, true);


--
-- Data for Name: admin_dictionary_domains; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_domains DISABLE TRIGGER ALL;

INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('art', 'pêche artisanale', 1);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('exp', 'pêche expérimentale', 2);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('especes', 'systématique', 3);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('geo', 'géographie', 4);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('access', 'droits d''accès', 5);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('config', 'configuration de l''application', 6);


ALTER TABLE admin_dictionary_domains ENABLE TRIGGER ALL;

--
-- Name: admin_tables_dictionary_dico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_tables_dictionary_dico_id_seq', 59, true);


--
-- Data for Name: admin_dictionary_tables; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_tables DISABLE TRIGGER ALL;

INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (3, 'lieu_peche', 'lieux de pêche', 'art_lieu_de_peche', 'id', 'libelle', 4, 1, 'pays,systeme,secteur,lieu_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (4, 'agglomeration', 'agglomérations', 'art_agglomeration', 'id', 'nom', 3, 1, 'pays,systeme,secteur,agglomeration', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (5, 'csp', 'catégories socio-professionnelles', 'art_categorie_socio_professionnelle', 'id', 'libelle', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (6, 'etat_ciel', 'état du ciel', 'art_etat_ciel', 'id', 'libelle', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (7, 'grand_type_engin', 'grands types d''engins', 'art_grand_type_engin', 'id', 'libelle', 3, 1, 'grand_type_engin,type_engin', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (8, 'milieu', 'milieux', 'art_millieu', 'id', 'libelle', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (9, 'type_activite', 'type d''activité', 'art_type_activite', 'id', 'raison', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (10, 'type_agglomeration', 'types d''agglomérations', 'art_type_agglomeration', 'id', 'libelle', 3, 1, 'type_agglomeration,agglomeration', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (11, 'type_engin', 'types d''engins', 'art_type_engin', 'id', 'libelle', 3, 1, '', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (12, 'type_sortie', 'types de sorties', 'art_type_sortie', 'id', 'libelle', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (13, 'vent', 'vent', 'art_vent', 'id', 'libelle', 3, 1, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (14, 'contenu', 'contenus stomacaux', 'exp_contenu', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (15, 'debris', 'débris', 'exp_debris', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (16, 'engins', 'engins', 'exp_engin', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (17, 'force_courant', 'force du courant', 'exp_force_courant', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (18, 'position', 'positions', 'exp_position', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (19, 'qualite_coup', 'qualité du coup', 'exp_qualite', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (20, 'remplissage', 'taux de remplissage stomacal', 'exp_remplissage', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (21, 'sediment', 'sédiment', 'exp_sediment', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (22, 'sens_courant', 'sens du courant', 'exp_sens_courant', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (23, 'sexe', 'sexe', 'exp_sexe', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (24, 'stade', 'stades de maturité', 'exp_stade', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (25, 'station', 'stations', 'exp_station', 'id', 'nom', 3, 2, 'pays,systeme,secteur,station', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (26, 'vegetation', 'végétation', 'exp_vegetation', 'id', 'libelle', 3, 2, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (27, 'ecologique', 'catégories écologiques', 'ref_categorie_ecologique', 'id', 'libelle', 2, 3, 'ecologique,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (28, 'trophique', 'catégories trophiques', 'ref_categorie_trophique', 'id', 'libelle', 2, 3, 'trophique,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (29, 'espece', 'espèces', 'ref_espece', 'id', 'libelle', 2, 3, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (30, 'famille', 'familles', 'ref_famille', 'id', 'libelle', 2, 3, 'ordre,famille,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (31, 'ordre', 'ordres', 'ref_ordre', 'id', 'libelle', 2, 3, 'ordre,famille,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (32, 'originekb', 'origines KB', 'ref_origine_kb', 'id', 'libelle', 2, 3, 'originekb,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (33, 'pays', 'pays', 'ref_pays', 'id', 'nom', 2, 4, 'pays,systeme,secteur', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (34, 'secteur', 'secteurs', 'ref_secteur', 'id', 'nom', 2, 4, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (35, 'systeme', 'systèmes', 'ref_systeme', 'id', 'libelle', 2, 4, 'systeme,secteur', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (39, 'log_modules', 'modules de l''application', 'admin_log_modules', 'module_id', 'module_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (42, 'zones', 'zones d''accès', 'admin_zones', 'zone_id', 'zone_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (43, 'activite', 'activité', 'art_activite', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,activite', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (44, 'debarquement', 'débarquements', 'art_debarquement', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (45, 'engin_activite', 'engin-activité', 'art_engin_activite', 'id', 'code', 4, 1, 'grand_type_engin,type_engin, engin_activite', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (46, 'engin_peche', 'engin de pêche', 'art_engin_peche', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,type_engin,engin_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (47, 'fraction_art', 'fractions artisanales', 'art_fraction', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (48, 'poisson_mesure', 'poissons mesurés', 'art_poisson_mesure', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction,poisson_mesure', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (49, 'unite_peche', 'unités de pêche', 'art_unite_peche', 'id', 'libelle', 4, 1, 'pays,systeme,secteur,agglomeration,csp,unite_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (50, 'biologie', 'biologie', 'exp_biologie', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (51, 'campagne', 'campagnes expérimentales', 'exp_campagne', 'id', 'libelle', 4, 2, '', false, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (52, 'coup_peche', 'coups de pêche', 'exp_coup_peche', 'id', 'numero_coup', 4, 2, 'pays,systeme,secteur,agglomeration,station,coup_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (53, 'environnement', 'environnement', 'exp_environnement', 'id', 'id', 4, 2, '', false, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (54, 'fraction_exp', 'fractions expérimentales', 'exp_fraction', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,station,coup_peche,espece,environnement', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (55, 'trophique_exp', 'trophique', 'exp_trophique', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art,biologie,trophique_exp', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (57, 'domaines', 'domaines thématiques', 'admin_dictionary_domains', 'domain_id', 'domaine_nom', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (58, 'tables', 'définition des tables de la base', 'admin_dictionary_tables', 'dico_id', 'label', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (59, 'typetables', 'les différentes catégories de tables', 'admin_dictionary_type_tables', 'type_table_id', 'type_table_nom', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (37, 'jusergroup', 'relations utilisateurs-groupes', 'admin_j_user_group', 'user_id', 'user_id', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (41, 'users', 'utilisateurs', 'admin_users', 'user_id', 'user_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (40, 'usergroups', 'groupes d''utilisateurs', 'admin_usergroups', 'group_id', 'group_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (36, 'jgroupzone', 'relations groupes-zones', 'admin_j_group_zone', 'jgroupzone_id', 'jgroupzone_id', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (38, 'juserzone', 'relations utilisateurs-zones', 'admin_j_user_zone', 'juserzone_id', 'juserzone_id', 1, 5, '', false, 1);




ALTER TABLE admin_dictionary_tables ENABLE TRIGGER ALL;

--
-- Name: admin_dictionary_type_tables_type_table_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_dictionary_type_tables_type_table_id_seq', 4, true);


--
-- Data for Name: admin_dictionary_type_tables; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_dictionary_type_tables DISABLE TRIGGER ALL;

INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (1, 'admin', 'tables d''administration du site');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (2, 'ref', 'tables de référence');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (3, 'param', 'tables de paramétrage');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (4, 'data', 'tables de données');


ALTER TABLE admin_dictionary_type_tables ENABLE TRIGGER ALL;

--
-- Name: admin_j_group_zone_jgroupzone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_j_group_zone_jgroupzone_id_seq', 4, true);


--
-- Data for Name: admin_j_group_zone; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_group_zone (group_id, zone_id, jgroupzone_id) VALUES (0, 0, 1);
INSERT INTO admin_j_group_zone (group_id, zone_id, jgroupzone_id) VALUES (1, 9999, 2);
INSERT INTO admin_j_group_zone (group_id, zone_id, jgroupzone_id) VALUES (2, 2, 3);
INSERT INTO admin_j_group_zone (group_id, zone_id, jgroupzone_id) VALUES (2, 3, 4);


ALTER TABLE admin_j_group_zone ENABLE TRIGGER ALL;

--
-- Name: admin_j_user_group_jusergroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_j_user_group_jusergroup_id_seq', 6, true);


--
-- Data for Name: admin_j_user_group; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (1, 2, 1);
INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (0, 0, 2);
INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (5, 1, 3);
INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (4, 1, 4);
INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (6, 1, 5);
INSERT INTO admin_j_user_group (user_id, group_id, jusergroup_id) VALUES (7, 1, 6);


ALTER TABLE admin_j_user_group ENABLE TRIGGER ALL;

--
-- Name: admin_j_user_zone_juserzone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_j_user_zone_juserzone_id_seq', 2, true);


--
-- Data for Name: admin_j_user_zone; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_user_zone (user_id, zone_id, juserzone_id) VALUES (0, 0, 1);
INSERT INTO admin_j_user_zone (user_id, zone_id, juserzone_id) VALUES (1, 9999, 2);


ALTER TABLE admin_j_user_zone ENABLE TRIGGER ALL;

--
-- Data for Name: admin_log_message_types; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_log_message_types DISABLE TRIGGER ALL;

INSERT INTO admin_log_message_types (message_type) VALUES ('error');
INSERT INTO admin_log_message_types (message_type) VALUES ('notice');
INSERT INTO admin_log_message_types (message_type) VALUES ('sql');
INSERT INTO admin_log_message_types (message_type) VALUES ('warning');


ALTER TABLE admin_log_message_types ENABLE TRIGGER ALL;

--
-- Name: admin_log_modules_module_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_log_modules_module_id_seq', 7, true);


--
-- Data for Name: admin_log_modules; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_log_modules DISABLE TRIGGER ALL;

INSERT INTO admin_log_modules (module_id, module_name) VALUES (0, 'inconnu');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (1, 'gestion des tables');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (3, 'gestion des utilisateurs');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (4, 'journal');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (5, 'session');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (6, 'Gestion de la documentation');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (7, 'Portage automatique');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (8, 'Export');

ALTER TABLE admin_log_modules ENABLE TRIGGER ALL;




--
-- Name: admin_sequences_sequence_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

--
-- Data for Name: admin_sequences; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_sequences VALUES (1, 4, 'id', 'art_agglomeration_id_seq');
INSERT INTO admin_sequences VALUES (2, 5, 'id', 'art_categorie_socio_professionnelle_id_seq');
INSERT INTO admin_sequences VALUES (3, 6, 'id', 'art_etat_ciel_id_seq');
INSERT INTO admin_sequences VALUES (4, 10, 'id', 'art_type_agglomeration_id_seq');
INSERT INTO admin_sequences VALUES (5, 12, 'id', 'art_type_sortie_id_seq');
INSERT INTO admin_sequences VALUES (6, 13, 'id', 'art_vent_id_seq');
INSERT INTO admin_sequences VALUES (7, 14, 'id', 'exp_contenu_id_seq');
INSERT INTO admin_sequences VALUES (8, 17, 'id', 'exp_force_courant_id_seq');
INSERT INTO admin_sequences VALUES (9, 18, 'id', 'exp_position_id_seq');
INSERT INTO admin_sequences VALUES (10, 19, 'id', 'exp_qualite_id_seq');
INSERT INTO admin_sequences VALUES (11, 22, 'id', 'exp_sens_courant_id_seq');
INSERT INTO admin_sequences VALUES (12, 24, 'id', 'exp_stade_id_seq');
INSERT INTO admin_sequences VALUES (13, 30, 'id', 'ref_famille_id_seq');
INSERT INTO admin_sequences VALUES (14, 31, 'id', 'ref_ordre_id_seq');
INSERT INTO admin_sequences VALUES (15, 32, 'id', 'ref_origine_kb_id_seq');
INSERT INTO admin_sequences VALUES (16, 34, 'id', 'ref_secteur_id_seq');
INSERT INTO admin_sequences VALUES (17, 35, 'id', 'ref_systeme_id_seq');
INSERT INTO admin_sequences VALUES (18, 43, 'id', 'art_activite_id_seq');
INSERT INTO admin_sequences VALUES (19, 44, 'id', 'art_debarquement_id_seq');
INSERT INTO admin_sequences VALUES (20, 45, 'id', 'art_engin_activite_id_seq');
INSERT INTO admin_sequences VALUES (21, 46, 'id', 'art_engin_peche_id_seq');
INSERT INTO admin_sequences VALUES (22, 3, 'id', 'art_lieu_de_peche_id_seq');
INSERT INTO admin_sequences VALUES (23, 48, 'id', 'art_poisson_mesure_id_seq');
INSERT INTO admin_sequences VALUES (24, 49, 'id', 'art_unite_peche_id_seq');
INSERT INTO admin_sequences VALUES (25, 51, 'id', 'exp_campagne_id_seq');
INSERT INTO admin_sequences VALUES (26, 52, 'id', 'exp_cp_peche_id_seq');
INSERT INTO admin_sequences VALUES (27, 55, 'id', 'exp_contenu_biologie_id_seq');

SELECT pg_catalog.setval('admin_sequences_sequence_id_seq', 27, true);

--
-- Name: admin_users_groups_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_users_groups_group_id_seq', 2, true);


--
-- Data for Name: admin_usergroups; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_usergroups DISABLE TRIGGER ALL;

INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (0, 'visiteurs', 'visiteurs non enregistrés, sans aucun privilèges', true);
INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (1, 'administrateurs', 'administrateurs du site, avec accès total Ã  l''ensemble des zones et des données', true);
INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (2, 'gestionnaires des données', 'accès à  l''ensemble des données et aux interfaces de gestion et de portage des données, mais pas à la gestion des utilisateurs', true);


ALTER TABLE admin_usergroups ENABLE TRIGGER ALL;

--
-- Name: admin_users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_users_user_id_seq', 7, true);


--
-- Data for Name: admin_users; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_users DISABLE TRIGGER ALL;

INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (0, 'visiteur', 'utilisateur non enregistré', true, 'l''utilisateur de base, qui ne peut pas se logguer...', '', NULL, NULL);
INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (1, 'olivier', 'olivier roux', true, 'compte administrateur total', 'olivier@otolithe.com', 'olTTHQQ1CGtEU', NULL);
INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (4, 'ylaurent', 'Yann Laurent', true, '', 'yann.laurent@pagre-it.com', 'ylDkGdeJkvZDM', NULL);
INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (5, 'bgranouillac', 'Bruno Granouillac', true, '', 'bruno.granouillac@ird.fr', 'bg7iz6cjbcx5k', '2008-11-14');
INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (6, 'jmecoutin', 'Jean-Marc Écoutin', true, '', 'Jean.Marc.Ecoutin@ifremer.fr', 'jmWVdB1tKciFg', NULL);
INSERT INTO admin_users (user_id, user_name, user_longname, user_active, user_comment, user_email, user_password, user_creation_date) VALUES (7, 'msimier', 'monique simier', true, '', 'Monique.Simier@ifremer.fr', 'msjsg8dorm7/U', NULL);


ALTER TABLE admin_users ENABLE TRIGGER ALL;

--
-- Name: admin_zones_zone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_zones_zone_id_seq', 7, true);


--
-- Data for Name: admin_zones; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE admin_zones DISABLE TRIGGER ALL;

INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (0, 'publique', 'zone publique');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (1, 'administration', 'administration du site, des utilisateurs etc.');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (2, 'gestion des données', 'interface de gestion des données (hors utilisateurs)');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (3, 'portage', 'interface de portage/recomposition des bases de données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (4, 'journal', 'interface de consultation du journal des opérations');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (5, 'documentation', 'gestion de la documentation sur les données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (6, 'extraction', 'extraction et consultatiopn des données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (7, 'gérer', 'zone globale d''accès à la gestion des tables');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (9999, 'toutes zones', 'accèss à l''ensemble des zones de l''application');


ALTER TABLE admin_zones ENABLE TRIGGER ALL;

