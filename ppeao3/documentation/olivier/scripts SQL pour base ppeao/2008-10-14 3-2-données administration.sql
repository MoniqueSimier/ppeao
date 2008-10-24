--
-- PostgreSQL database dump
--

-- Started on 2008-10-24 22:42:28 CEST

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 2075 (class 0 OID 0)
-- Dependencies: 1800
-- Name: admin_dictionary_domains_domain_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_dictionary_domains_domain_id_seq', 6, true);


--
-- TOC entry 2072 (class 0 OID 103612)
-- Dependencies: 1799
-- Data for Name: admin_dictionary_domains; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('art', 'pêche artisanale', 1);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('exp', 'pêche expérimentale', 2);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('geo', 'géographie', 4);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('access', 'droits d''accès', 5);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('config', 'configuration de l''application', 6);
INSERT INTO admin_dictionary_domains (domaine_nom, domaine_description, domain_id) VALUES ('especes', 'systématique', 3);



--
-- TOC entry 2084 (class 0 OID 0)
-- Dependencies: 1793
-- Name: admin_tables_dictionary_dico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_tables_dictionary_dico_id_seq', 59, true);


--
-- TOC entry 2085 (class 0 OID 0)
-- Dependencies: 1795
-- Name: admin_tables_dictionary_domain_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_tables_dictionary_domain_id_seq', 1, false);


--
-- TOC entry 2086 (class 0 OID 0)
-- Dependencies: 1794
-- Name: admin_tables_dictionary_type_table_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_tables_dictionary_type_table_id_seq', 1, false);


--
-- TOC entry 2081 (class 0 OID 103577)
-- Dependencies: 1796
-- Data for Name: admin_dictionary_tables; Type: TABLE DATA; Schema: public; Owner: devppeao
--

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
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (28, 'trophique', 'catégories trophiques', 'ref_categorie_trophique', 'id', 'libelle', 2, 3, 'trophique,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (29, 'espece', 'espèces', 'ref_espece', 'id', 'libelle', 2, 3, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (31, 'ordre', 'ordres', 'ref_ordre', 'id', 'libelle', 2, 3, 'ordre,famille,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (32, 'originekb', 'origines KB', 'ref_origine_kb', 'id', 'libelle', 2, 3, 'originekb,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (33, 'pays', 'pays', 'ref_pays', 'id', 'nom', 2, 4, 'pays,systeme,secteur', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (34, 'secteur', 'secteurs', 'ref_secteur', 'id', 'nom', 2, 4, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (35, 'systeme', 'systèmes', 'ref_systeme', 'id', 'libelle', 2, 4, 'systeme,secteur', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (36, 'jgroupzone', 'droits groupe-zones', 'admin_j_group_zone', 'group_id', 'group_id', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (37, 'jusergroup', 'appartenance utilisateur-groupe', 'admin_j_user_group', 'user_id', 'user_id', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (38, 'juserzone', 'droits utilisateur-zone', 'admin_j_user_zone', 'user_id', 'user_id', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (39, 'log_modules', 'modules applicatifs', 'admin_log_modules', 'module_id', 'module_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (40, 'usergroups', 'groupes utilisateurs', 'admin_usergroups', 'group_id', 'group_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (41, 'users', 'utilisateurs', 'admin_users', 'user_id', 'user_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (42, 'zones', 'zones d''accès', 'admin_zones', 'zone_id', 'zone_name', 1, 5, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (51, 'campagne', 'campagnes expérimentales', 'exp_campagne', 'id', 'libelle', 4, 2, '', false, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (53, 'environnement', 'environnement', 'exp_environnement', 'id', 'id', 4, 2, '', false, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (47, 'fraction_art', 'fractions artisanales', 'art_fraction', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (27, 'ecologique', 'catégories écologiques', 'ref_categorie_ecologique', 'id', 'libelle', 2, 3, 'ecologique,espece', true, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (58, 'tables', 'tables', 'admin_dictionary_tables', 'dico_id', 'label', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (59, 'typetables', 'types de tables', 'admin_dictionary_type_tables', 'type_table_id', 'type_table_nom', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (57, 'domaines', 'domaines', 'admin_dictionary_domains', 'domain_id', 'domaine_nom', 1, 6, '', false, 1);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (43, 'activite', 'activité', 'art_activite', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,activite', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (44, 'debarquement', 'débarquements', 'art_debarquement', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (45, 'engin_activite', 'engin-activité', 'art_engin_activite', 'id', 'code', 4, 1, 'grand_type_engin,type_engin, engin_activite', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (46, 'engin_peche', 'engin de pêche', 'art_engin_peche', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,type_engin,engin_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (3, 'lieu_peche', 'lieux de pêche', 'art_lieu_de_peche', 'id', 'libelle', 4, 1, 'pays,systeme,secteur,lieu_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (48, 'poisson_mesure', 'poissons mesurés', 'art_poisson_mesure', 'id', 'code', 4, 1, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction,poisson_mesure', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (49, 'unite_peche', 'unités de pêche', 'art_unite_peche', 'id', 'libelle', 4, 1, 'pays,systeme,secteur,agglomeration,csp,unite_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (50, 'biologie', 'biologie', 'exp_biologie', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (52, 'coup_peche', 'coups de pêche', 'exp_coup_peche', 'id', 'numero_coup', 4, 2, 'pays,systeme,secteur,agglomeration,station,coup_peche', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (54, 'fraction_exp', 'fractions expérimentales', 'exp_fraction', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,station,coup_peche,espece,environnement', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (55, 'trophique_exp', 'trophique', 'exp_trophique', 'id', 'id', 4, 2, 'pays,systeme,secteur,agglomeration,debarquement,espece,fraction_art,biologie,trophique_exp', true, 2);
INSERT INTO admin_dictionary_tables (dico_id, handle, label, table_db, id_col, noms_col, type_table_id, domain_id, selector_cascade, selector, zone_id) VALUES (30, 'famille', 'familles', 'ref_famille', 'id', 'libelle', 2, 3, 'ordre,famille,espece', true, 1);



--
-- TOC entry 2075 (class 0 OID 0)
-- Dependencies: 1797
-- Name: admin_dictionary_type_tables_type_table_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_dictionary_type_tables_type_table_id_seq', 4, true);


--
-- TOC entry 2072 (class 0 OID 103598)
-- Dependencies: 1798
-- Data for Name: admin_dictionary_type_tables; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (3, 'param', 'tables de paramétrage');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (1, 'admin', 'tables d''administration du site');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (2, 'ref', 'tables de référence');
INSERT INTO admin_dictionary_type_tables (type_table_id, type_table_nom, type_table_description) VALUES (4, 'data', 'tables de données');


--
-- TOC entry 2071 (class 0 OID 102634)
-- Dependencies: 1673
-- Data for Name: admin_j_group_zone; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_group_zone (group_id, zone_id) VALUES (1, 9999);
INSERT INTO admin_j_group_zone (group_id, zone_id) VALUES (0, 0);
INSERT INTO admin_j_group_zone (group_id, zone_id) VALUES (2, 2);
INSERT INTO admin_j_group_zone (group_id, zone_id) VALUES (2, 3);



--
-- TOC entry 2074 (class 0 OID 0)
-- Dependencies: 1781
-- Name: j_user_group_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('j_user_group_group_id_seq', 1, false);


--
-- TOC entry 2075 (class 0 OID 0)
-- Dependencies: 1782
-- Name: j_user_group_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('j_user_group_user_id_seq', 1, false);


--
-- TOC entry 2071 (class 0 OID 102637)
-- Dependencies: 1674
-- Data for Name: admin_j_user_group; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_user_group (user_id, group_id) VALUES (1, 2);
INSERT INTO admin_j_user_group (user_id, group_id) VALUES (0, 0);
INSERT INTO admin_j_user_group (user_id, group_id) VALUES (5, 1);
INSERT INTO admin_j_user_group (user_id, group_id) VALUES (4, 1);
INSERT INTO admin_j_user_group (user_id, group_id) VALUES (6, 1);
INSERT INTO admin_j_user_group (user_id, group_id) VALUES (7, 1);


--
-- TOC entry 2071 (class 0 OID 102640)
-- Dependencies: 1675
-- Data for Name: admin_j_user_zone; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_j_user_zone (user_id, zone_id) VALUES (0, 0);
INSERT INTO admin_j_user_zone (user_id, zone_id) VALUES (1, 9999);


--
-- TOC entry 2070 (class 0 OID 102643)
-- Dependencies: 1676
-- Data for Name: admin_log; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  activité', 1, '', '', '2008-10-24 9:54:47', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:35:27', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:41:53', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:09:03', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:13:56', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:21:58', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:36:55', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:42:10', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:45:57', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:58:37', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  espèces', 1, '', '', '2008-10-24 10:23:14', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:36:51', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:42:05', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:09:23', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:15:11', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:24:32', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:37:35', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:44:33', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:48:13', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 12:07:40', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  espèces', 1, '', '', '2008-10-24 10:23:34', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:37:22', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:43:15', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:10:54', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:15:45', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:25:53', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:38:35', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:44:57', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:49:26', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 12:12:23', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  espèces', 1, '', '', '2008-10-24 10:24:19', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:37:33', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:44:45', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:10:59', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:16:14', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:29:06', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:39:44', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:45:11', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:50:06', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de données&nbsp;: pêche artisanale', 1, '', '', '2008-10-24 12:12:50', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  espèces', 1, '', '', '2008-10-24 10:31:58', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:38:42', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:45:04', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:12:38', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:17:45', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:35:19', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:39:57', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:45:21', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:57:16', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de données&nbsp;: pêche artisanale', 1, '', '', '2008-10-24 12:24:43', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  espèces', 1, '', '', '2008-10-24 10:33:25', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; l''&eacute;dition de la table  environnement', 1, '', '', '2008-10-24 10:39:13', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:08:32', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:13:06', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: espèces', 1, '', '', '2008-10-24 11:19:28', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:35:47', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (4, '/journal/delete_log.php', 'journal effac&eacute;', 1, '', '', '2008-10-23 22:47:38', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:41:47', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:45:55', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_table.php', 'acc&egrave;s &agrave; la gestion des tables de référence&nbsp;: systématique', 1, '', '', '2008-10-24 11:58:23', 'notice');
INSERT INTO admin_log (log_module_id, log_script_file, log_message, log_user_id, log_action_do, log_action_undo, log_time, log_message_type) VALUES (1, '/edition/edition_selector.php', 'acc&egrave;s &agrave; la gestion des tables de données&nbsp;: pêche artisanale', 1, '', '', '2008-10-24 14:14:28', 'notice');


--
-- TOC entry 2069 (class 0 OID 102649)
-- Dependencies: 1677
-- Data for Name: admin_log_message_types; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_log_message_types (message_type) VALUES ('error');
INSERT INTO admin_log_message_types (message_type) VALUES ('warning');
INSERT INTO admin_log_message_types (message_type) VALUES ('sql');
INSERT INTO admin_log_message_types (message_type) VALUES ('notice');



--
-- TOC entry 2073 (class 0 OID 0)
-- Dependencies: 1742
-- Name: admin_log_modules_module_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_log_modules_module_id_seq', 5, true);


--
-- TOC entry 2070 (class 0 OID 102652)
-- Dependencies: 1678
-- Data for Name: admin_log_modules; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_log_modules (module_id, module_name) VALUES (3, 'gestion des utilisateurs');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (4, 'journal');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (0, 'inconnu');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (5, 'session');
INSERT INTO admin_log_modules (module_id, module_name) VALUES (1, 'gestion des tables');


--
-- TOC entry 2076 (class 0 OID 0)
-- Dependencies: 1743
-- Name: admin_users_groups_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_users_groups_group_id_seq', 2, true);


--
-- TOC entry 2073 (class 0 OID 102655)
-- Dependencies: 1679
-- Data for Name: admin_usergroups; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (1, 'administrateurs', 'administrateurs du site, avec accès total à  l''ensemble des zones et des données', true);
INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (2, 'gestionnaires des données', 'accès à  l''ensemble des données et aux interfaces de gestion et de portage des données, mais pas à la gestion des utilisateurs', true);
INSERT INTO admin_usergroups (group_id, group_name, group_description, group_active) VALUES (0, 'visiteurs', 'visiteurs non enregistrés, sans aucun privilèges', true);

--
-- TOC entry 2076 (class 0 OID 0)
-- Dependencies: 1744
-- Name: admin_users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_users_user_id_seq', 1, false);


--
-- TOC entry 2073 (class 0 OID 102662)
-- Dependencies: 1680
-- Data for Name: admin_users; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (0, 'visiteur', 'utilisateur non enregistré', '1214901826', true, 'l''utilisateur de base, qui ne peut pas se logguer...', '', NULL);
INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (1, 'olivier', 'olivier roux', '1214901826', true, 'compte administrateur total', 'olivier@otolithe.com', 'olTTHQQ1CGtEU');
INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (4, 'ylaurent', 'Yann Laurent', '1214901826', true, '', 'yann.laurent@pagre-it.com', 'ylDkGdeJkvZDM');
INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (5, 'bgranouillac', 'Bruno Granouillac', '', true, '', 'bruno.granouillac@ird.fr', 'bg7iz6cjbcx5k');
INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (6, 'jmecoutin', 'Jean-Marc Écoutin', '', true, '', 'Jean.Marc.Ecoutin@ifremer.fr', 'jmWVdB1tKciFg');
INSERT INTO admin_users (user_id, user_name, user_longname, user_creation_date, user_active, user_comment, user_email, user_password) VALUES (7, 'msimier', 'monique simier', '', true, '', 'Monique.Simier@ifremer.fr', 'msjsg8dorm7/U');

--
-- TOC entry 2075 (class 0 OID 0)
-- Dependencies: 1745
-- Name: admin_zones_zone_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('admin_zones_zone_id_seq', 7, true);


--
-- TOC entry 2072 (class 0 OID 102669)
-- Dependencies: 1681
-- Data for Name: admin_zones; Type: TABLE DATA; Schema: public; Owner: devppeao
--

INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (1, 'administration', 'administration du site, des utilisateurs etc.');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (2, 'gestion des données', 'interface de gestion des données (hors utilisateurs)');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (3, 'portage', 'interface de portage/recomposition des bases de données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (4, 'journal', 'interface de consultation du journal des opérations');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (0, 'publique', 'zone publique');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (9999, 'toutes zones', 'accès à l''ensemble des zones de l''application');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (5, 'documentation', 'gestion de la documentation sur les données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (6, 'extraction', 'extraction et consultatiopn des données');
INSERT INTO admin_zones (zone_id, zone_name, zone_description) VALUES (7, 'gérer', 'zone globale d''accès à la gestion des tables');

