--
-- Script permettant de peupler les tables de parametrage et de referecne de l'application Web PPEAO
-- Ces donnees proviennent de la base ppeao_refparam sur le serveur VmPpeao, en date du 12/12/2008.
--
-- 

SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;


--
-- Data for Name: art_agglomeration; Type: TABLE DATA; Schema: public; Owner: devppeao
-- Latitude et longitude corrig�e le 17/05/2010 (JME)
--

ALTER TABLE art_agglomeration DISABLE TRIGGER ALL;

INSERT INTO art_agglomeration VALUES (1, 0, 1, 'Inconnu', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (2, 2, 14, 'Salengou', '-010:18:59', '+13:10:46', NULL);
INSERT INTO art_agglomeration VALUES (3, 2, 14, 'Kerwane daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (4, 2, 14, 'Nigui', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (5, 2, 15, 'Kita daga', '-010:17:76', '+13:02:76', 'Visite mission 10 2001');
INSERT INTO art_agglomeration VALUES (6, 2, 15, 'Ladji', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (7, 2, 15, 'Tentible', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (8, 2, 16, 'Drame', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (9, 2, 16, 'Ngougny daga', '-010:15:96', '+12:56:39', NULL);
INSERT INTO art_agglomeration VALUES (10, 2, 16, 'Sebekoro daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (14, 1, 8, 'Barikondaga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (15, 3, 8, 'Batamani daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (16, 2, 8, 'Boumana daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (17, 1, 8, 'Camara daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (18, 0, 8, 'Galacine daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (19, 3, 8, 'Kemien daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (20, 1, 8, 'Kotaka', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (21, 2, 8, 'Koubi daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (22, 1, 8, 'Kumara daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (23, 0, 8, 'Mamadou daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (24, 3, 8, 'Mierou daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (25, 0, 8, 'Mopti hinde', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (26, 0, 8, 'Nambara daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (27, 1, 8, 'Nimitogo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (28, 0, 8, 'Nouh-bozo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (29, 0, 8, 'Ouromodi daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (30, 0, 8, 'Sahona', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (31, 0, 8, 'Tebena', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (32, 2, 8, 'Toala daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (33, 1, 8, 'Touala', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (34, 0, 8, 'Welibana', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (35, 2, 9, 'Chouery daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (36, 2, 9, 'Diokore daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (37, 1, 9, 'Komba diamla', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (38, 1, 9, 'Kuara', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (39, 0, 9, 'Mountou daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (40, 1, 9, 'Tomina', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (41, 2, 9, 'Wu daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (42, 3, 10, 'Idourou boly', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (43, 1, 10, 'Kakagnan', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (44, 2, 10, 'Kinieye daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (45, 2, 10, 'M bouba n diam', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (46, 3, 10, 'Pinguina daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (47, 2, 10, 'Sinde daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (48, 1, 11, 'Ankoye', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (49, 1, 11, 'Dissore', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (50, 3, 11, 'Dounde wendou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (51, 1, 11, 'Gourao bozo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (52, 3, 11, 'Mayo saore', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (53, 2, 11, 'Sagoye', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (54, 3, 12, 'Bougouberi daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (55, 2, 12, 'Daga toga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (56, 1, 12, 'Gunanbougou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (57, 2, 13, 'Arabebe daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (58, 3, 13, 'Dyoma', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (59, 2, 13, 'Gamou daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (60, 1, 13, 'Gindigata nare', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (61, 3, 13, 'Mekore daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (62, 1, 13, 'Sebi', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (63, 2, 18, 'Bourlaye', '-008:11:24', '+11:32:47', NULL);
INSERT INTO art_agglomeration VALUES (64, 2, 18, 'Bozola', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (65, 1, 18, 'Carriere', '-008:11:53', '+11:37:05', NULL);
INSERT INTO art_agglomeration VALUES (66, 2, 19, 'Banando', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (67, 2, 19, 'Bugoudale', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (68, 2, 19, 'Faraba', '-008:18:58', '+11:22:85', NULL);
INSERT INTO art_agglomeration VALUES (69, 2, 19, 'Kabaya', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (70, 2, 19, 'Komana', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (71, 2, 19, 'Kona', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (72, 2, 20, 'Dossola', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (73, 2, 20, 'Goualafara', '-008:10:00', '+11:18:19', NULL);
INSERT INTO art_agglomeration VALUES (74, 2, 20, 'Kangare', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (75, 2, 20, 'Sodala', '-008:09:69', '+11:23:09', NULL);
INSERT INTO art_agglomeration VALUES (76, 2, 20, 'Soumaila', '-008:10:49', '+11:30:71', NULL);
INSERT INTO art_agglomeration VALUES (77, 1, 6, 'Nigui assoko', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (78, 1, 6, 'Nigui assa', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (79, 1, 6, 'Boubo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (80, 2, 7, 'Tefredji', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (81, 2, 6, 'Tiebiessou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (82, 2, 6, 'Ahikakro', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (83, 1, 7, 'Azan', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (84, 1, 7, 'Tiagba', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (86, 1, 6, 'Abraco', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (87, 2, 6, 'Atoutou a', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (88, 2, 7, 'N`goume', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (89, 2, 6, 'Atoutou b', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (90, 2, 4, 'Vridi', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (91, 1, 21, 'Amedehoueve', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (94, 1, 6, 'N`goyem', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (96, 1, 21, 'Sevatonou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (97, 1, 22, 'Agbodrafo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (98, 1, 22, 'Bagoudbe', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (99, 1, 21, 'Afidenyigban', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (100, 1, 22, 'Aneho', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (102, 1, 21, 'Sevatono', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (103, 2, 19, 'Tiemba', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (104, 2, 19, 'Tagankoro', '-008:17:55', '+11:28:43', NULL);
INSERT INTO art_agglomeration VALUES (106, 2, 20, 'Kolinda', '-008:05:64', '+11:30:62', NULL);
INSERT INTO art_agglomeration VALUES (107, 2, 20, 'Babougou', '-008:07:59', '+11:27:45', NULL);
INSERT INTO art_agglomeration VALUES (108, 1, 23, 'Barra', '-016:32:88', '+13:29:11', NULL);
INSERT INTO art_agglomeration VALUES (109, 1, 25, 'Ballingo', '-015:35:92', '+13:29:30', NULL);
INSERT INTO art_agglomeration VALUES (110, 1, 25, 'Jappeni', '-015:26:64', '+13:27:33', NULL);
INSERT INTO art_agglomeration VALUES (111, 1, 25, 'Kanikunda', '-015:22:47', '+13:32:90', NULL);
INSERT INTO art_agglomeration VALUES (112, 1, 24, 'Tendaba', '-015:48:47', '+13:26:40', NULL);
INSERT INTO art_agglomeration VALUES (113, 1, 24, 'Tankular','-016:02:08',  '+13:25:12', NULL);
INSERT INTO art_agglomeration VALUES (114, 1, 24, 'Kerewan', '-016:06:06', '+13:29:86', NULL);
INSERT INTO art_agglomeration VALUES (115, 1, 24, 'Bintang', '-016:12:66', '+13:15:04', NULL);
INSERT INTO art_agglomeration VALUES (116, 1, 23, 'Albreda', '-016:23:10', '+13:19:97', NULL);
INSERT INTO art_agglomeration VALUES (117, 1, 23, 'Pirang', '-016:31:05', '+13:16:69', NULL);
INSERT INTO art_agglomeration VALUES (118, 1, 23, 'Toubakolong', '-016:23:10', '+13:20:01', NULL);
INSERT INTO art_agglomeration VALUES (119, 2, 15, 'Adou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (120, 2, 15, 'Burkina daga', '-010:23:37', '+13:00:01', 'Campement � 500m du point GPS');
INSERT INTO art_agglomeration VALUES (121, 2, 15, 'Mama', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (122, 1, 14, 'Manantali', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (123, 2, 14, 'Fryakoro', '-010:15:04', '+13:12:45', NULL);
INSERT INTO art_agglomeration VALUES (124, 2, 16, 'Lassina daga', '-010:16:41', '+12:57:48', NULL);
INSERT INTO art_agglomeration VALUES (125, 2, 16, 'Bokadary daga', '-010:16:50', '+12:57:96', NULL);
INSERT INTO art_agglomeration VALUES (126, 2, 15, 'Soumana daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (127, 2, 15, 'Dukankono', '-010:17:97', '+13:05:69', NULL);
INSERT INTO art_agglomeration VALUES (128, 2, 14, 'Woclogoun', '-010:24:41', '+13:14:41', NULL);
INSERT INTO art_agglomeration VALUES (129, 3, 16, 'Kemba daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (130, 3, 16, 'Samai Ladji daga', '-010:19:19', '+12:58:81', NULL);
INSERT INTO art_agglomeration VALUES (132, 2, 20, 'Aoure daga', '-008:09:46', '+11:26:49', NULL);
INSERT INTO art_agglomeration VALUES (133, 2, 20, 'Sourakata daga', '-008:07:77', '+11:29:91', NULL);
INSERT INTO art_agglomeration VALUES (134, 2, 20, 'Dolen daga', '-008:10:34', '+11:25:70', NULL);
INSERT INTO art_agglomeration VALUES (135, 2, 20, 'Sodala koro', '-008:10:14', '+11:21:15', NULL);
INSERT INTO art_agglomeration VALUES (136, 2, 20, 'Dadie daga', '-008:09:87', '+11:22:09', NULL);
INSERT INTO art_agglomeration VALUES (137, 2, 19, 'Sagnoumale I', '-008:21:77', '+11:22:28', NULL);
INSERT INTO art_agglomeration VALUES (138, 2, 19, 'Sagnoumale II', '-008:25:51', '+11:22:12', NULL);
INSERT INTO art_agglomeration VALUES (139, 2, 19, 'Sagnoumale III', '-008:23:89', '+11:20:89', NULL);
INSERT INTO art_agglomeration VALUES (140, 2, 19, 'Sagnoumale IV', '-008:22:81', '+11:19:82', NULL);
INSERT INTO art_agglomeration VALUES (141, 2, 19, 'Faraba Koro daga', '-008:17:43', '+11:23:17', NULL);
INSERT INTO art_agglomeration VALUES (142, 2, 19, 'Kakaye daga', '-008:15:49', '+11:26:01', NULL);
INSERT INTO art_agglomeration VALUES (143, 2, 19, 'Nagui daga', '-008:15:28', '+11:28:57', NULL);
INSERT INTO art_agglomeration VALUES (144, 2, 18, 'Kouroubleni daga', '-008:13:48', '+11:32:32', NULL);
INSERT INTO art_agglomeration VALUES (145, 2, 18, 'Daforo daga', '-008:14:21', '+11:33:26', NULL);
INSERT INTO art_agglomeration VALUES (146, 1, 18, 'Carriere 2',  '-008:11:53', '+11:37:05', 'Village de Carriere enquete 2 fois par mois au cours de la periode 11-2002 a 05-200"');
INSERT INTO art_agglomeration VALUES (147, 1, 26, 'Djinack', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (148, 1, 26, 'Missirah', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (149, 1, 26, 'Kathior', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (150, 1, 27, 'Ndangane fali', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (151, 1, 27, 'Nema ba', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (152, 1, 27, 'Toubacouta', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (153, 1, 27, 'Soukouta', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (154, 1, 28, 'Sokone', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (155, 1, 28, 'Ndjoundiou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (156, 1, 29, 'Babandiane', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (157, 1, 30, 'Djifere', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (158, 1, 30, 'Ndangane sambou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (159, 1, 31, 'Djirnda', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (160, 1, 32, 'Baout', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (161, 1, 32, 'Foundiougne', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (162, 1, 33, 'Kaolack', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (163, 1, 33, 'Lindiane', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (164, 1, 30, 'Sangomar', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (165, 1, 30, 'Dionewar', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (166, 1, 31, 'Fambine', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (167, 1, 32, 'Felir', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (168, 1, 26, 'Bandiala', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (169, 1, 27, 'Sipo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (170, 1, 27, 'Dassilame', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (171, 1, 27, 'Medina sangako', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (172, 1, 28, 'Bangalere', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (173, 1, 28, 'Bambougar malick', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (174, 1, 29, 'Bossingkang', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (175, 1, 29, 'Betanti', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (176, 1, 29, 'Diofandor', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (177, 1, 29, 'Diogane', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (178, 1, 29, 'Bakhalou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (179, 1, 29, 'Fandiongue', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (180, 1, 29, 'Gouk', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (181, 1, 29, 'Koulouk', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (182, 1, 30, 'Dinde', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (183, 1, 30, 'Falia', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (184, 1, 30, 'Fimela', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (186, 1, 30, 'Niodior', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (187, 1, 31, 'Bassar', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (188, 1, 31, 'Bassoul', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (189, 1, 31, 'Maya', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (190, 1, 31, 'Mounde', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (191, 1, 31, 'Ngadior', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (192, 1, 31, 'Siwo', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (193, 1, 31, 'Thialane', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (194, 1, 32, 'Dakhonga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (195, 1, 32, 'Diamniadio', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (196, 1, 32, 'Ndolette', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (197, 1, 32, 'Rofangue', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (198, 1, 32, 'Velingara', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (199, 1, 33, 'Fayaco', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (200, 1, 33, 'Djilor', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (201, 1, 33, 'Fatick', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (202, 1, 33, 'Guague cherif', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (203, 1, 33, 'Sibassor', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (204, 1, 33, 'Tournal nonal', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (205, 1, 33, 'Velor keur demba', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (206, 1, 33, 'Yoga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (207, 1, 33, 'Thiangane', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (208, 1, 33, 'Ndiafatte toucouleur', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (209, 1, 33, 'Latmingue', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (210, 1, 38, 'Palmarin diakhanor', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (211, 1, 38, 'Palmarin facao', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (212, 1, 38, 'Palmarin ngallou', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (213, 1, 38, 'Palmarin nguethie', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (214, 1, 27, 'Bani', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (215, 1, 28, 'Sandikoli', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (216, 1, 28, 'Bambougar massemba', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (217, 1, 29, 'Diogaye', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (218, 1, 32, 'Wandie', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (219, 0, 30, 'Mar lothie', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (220, 1, 39, 'Elinkine', '-016:39:93', '+12:30:38', 'D�barcadaire de pirogues de mer principalement; quelques unit�s de fleuve.');
INSERT INTO art_agglomeration VALUES (221, 1, 39, 'Tendouk', '-016:27:78', '+12:13:49', NULL);
INSERT INTO art_agglomeration VALUES (222, 4, 39, 'Ziguinchor', '-016:16:40', '+12:36:31', NULL);
INSERT INTO art_agglomeration VALUES (223, 2, 39, 'Pointe St Georges', '-016:33:47', '+12:37:93', NULL);
INSERT INTO art_agglomeration VALUES (224, 1, 39, 'Kamobeul', '-016:26:15', '+12:29:79', NULL);
INSERT INTO art_agglomeration VALUES (225, 1, 39, 'Etime', '-016:21:55', '+12:28:82', NULL);
INSERT INTO art_agglomeration VALUES (226, 1, 39, 'Brin', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (227, 1, 40, 'Adeane', '-016:00:90', '+12:38:13', NULL);
INSERT INTO art_agglomeration VALUES (228, 1, 40, 'Djibanar', '-015:48:59', '+12:33:12', NULL);
INSERT INTO art_agglomeration VALUES (229, 1, 40, 'Simbendi Balent', '-015:46:36', '+12:33:55', NULL);
INSERT INTO art_agglomeration VALUES (230, 1, 40, 'Diattakunda', '-015:40:54', '+12:34:32', NULL);
INSERT INTO art_agglomeration VALUES (231, 2, 40, 'Samine Escale', '-015:37:47', '+12:31:85', NULL);
INSERT INTO art_agglomeration VALUES (232, 4, 41, 'Sedhiou', '-015:33:04', '+12:42:47', NULL);
INSERT INTO art_agglomeration VALUES (233, 4, 41, 'Sefa', '-015:32:55', '+12:47:01', NULL);
INSERT INTO art_agglomeration VALUES (234, 1, 41, 'Simbandi Brassou', '-015:31:31', '+12:37:60', NULL);
INSERT INTO art_agglomeration VALUES (235, 1, 41, 'Saker', '-015:26:40', '+12:50:15', NULL);
INSERT INTO art_agglomeration VALUES (236, 1, 41, 'Diana Malari', '-015:15:10', '+12:50:96', NULL);
INSERT INTO art_agglomeration VALUES (237, 1, 41, 'Toubacouta', '-015:08:81', '+12:48:48', NULL);
INSERT INTO art_agglomeration VALUES (238, 4, 41, 'Kolda', '-014:56:50', '+12:53:50', NULL);
INSERT INTO art_agglomeration VALUES (239, 1, 40, 'Goudomp', '-015:52:55', '+13:34:78', NULL);
INSERT INTO art_agglomeration VALUES (240, 2, 14, 'Aruma daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (241, 2, 14, 'Bakina daga', '-010:23:37', '+13:08:01', NULL);
INSERT INTO art_agglomeration VALUES (242, 2, 14, 'Bocar daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (243, 2, 14, 'Madinadi daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (244, 2, 14, 'Mamatuo suobo', NULL, NULL, 'Maison isol�e proche de Mama daga');
INSERT INTO art_agglomeration VALUES (245, 2, 14, 'Soumaina daga', '-010:17:27', '+13:11:75', 'point pris � 500m au sud');
INSERT INTO art_agglomeration VALUES (246, 2, 15, 'Balamine daga', '-010:18:20', '+13:01:06', NULL);
INSERT INTO art_agglomeration VALUES (247, 2, 15, 'Demenika', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (248, 2, 15, 'Sory daga', '-010:16:36', '+13:05:72', NULL);
INSERT INTO art_agglomeration VALUES (249, 2, 15, 'Tondidji daga', '-010:17:37', '+13:03:04', NULL);
INSERT INTO art_agglomeration VALUES (250, 2, 14, 'Mama daga', '-010:16:51', '+13:10:25', NULL);
INSERT INTO art_agglomeration VALUES (251, 2, 15, 'Kerouane daga', NULL, NULL, NULL);
INSERT INTO art_agglomeration VALUES (252, 2, 15, 'Noumoke daga', '-010:18:75', '+13:03:36', NULL);

SELECT pg_catalog.setval('art_agglomeration_id_seq', 252, true);

ALTER TABLE art_agglomeration ENABLE TRIGGER ALL;


--
-- Data for Name: art_categorie_socio_professionnelle; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_categorie_socio_professionnelle DISABLE TRIGGER ALL;

INSERT INTO art_categorie_socio_professionnelle VALUES (0, 'Inconnu');
INSERT INTO art_categorie_socio_professionnelle VALUES (1, 'Professionnels');
INSERT INTO art_categorie_socio_professionnelle VALUES (2, 'Saisonniers');
INSERT INTO art_categorie_socio_professionnelle VALUES (3, 'Occasionnels');

SELECT pg_catalog.setval('art_categorie_socio_professionnelle_id_seq', 3, true);

ALTER TABLE art_categorie_socio_professionnelle ENABLE TRIGGER ALL;


--
-- Data for Name: art_etat_ciel; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_etat_ciel DISABLE TRIGGER ALL;

INSERT INTO art_etat_ciel VALUES (0, 'Inconnu');
INSERT INTO art_etat_ciel VALUES (1, 'Ciel bleu ou degage');
INSERT INTO art_etat_ciel VALUES (2, 'Ciel legerement nuageux, quelques nuages');
INSERT INTO art_etat_ciel VALUES (3, 'Ciel nuageux');
INSERT INTO art_etat_ciel VALUES (4, 'Pluie');

SELECT pg_catalog.setval('art_etat_ciel_id_seq', 4, true);

ALTER TABLE art_etat_ciel ENABLE TRIGGER ALL;



--
-- Data for Name: art_grand_type_engin; Type: TABLE DATA; Schema: public; Owner: devppeao
-- creation code PASGT
--

ALTER TABLE art_grand_type_engin DISABLE TRIGGER ALL;

INSERT INTO art_grand_type_engin VALUES ('BALAN', 'Balances');
INSERT INTO art_grand_type_engin VALUES ('BAMBO', 'Bambous');
INSERT INTO art_grand_type_engin VALUES ('DIVER', 'Divers');
INSERT INTO art_grand_type_engin VALUES ('EPERV', 'Epervier');
INSERT INTO art_grand_type_engin VALUES ('FCREV', 'Filet a etalage crevette');
INSERT INTO art_grand_type_engin VALUES ('FMCL', 'Filet Maillant a clochettes');
INSERT INTO art_grand_type_engin VALUES ('FMCLg', 'Filet Maillant a clochettes grandes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMCLm', 'Filet Maillant a clochettes moyennes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMCLp', 'Filet Maillant a clochettes petites mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDE', 'Filet Maillant Derivant');
INSERT INTO art_grand_type_engin VALUES ('FMDEg', 'Filet Maillant Derivant grandes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDEm', 'Filet Maillant Derivant moyennes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDEp', 'Filet Maillant Derivant petites mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDO', 'Filet Maillant Dormant');
INSERT INTO art_grand_type_engin VALUES ('FMDOg', 'Filet Maillant Dormant grandes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDOm', 'Filet Maillant Dormant moyennes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMDOp', 'Filet Maillant Dormant petites mailles');
INSERT INTO art_grand_type_engin VALUES ('FMEN', 'Filet Maillant Encerclant');
INSERT INTO art_grand_type_engin VALUES ('FMENg', 'Filet Maillant Encerclant grandes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMENm', 'Filet Maillant Encerclant moyennes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMENp', 'Filet Maillant Encerclant petites mailles');
INSERT INTO art_grand_type_engin VALUES ('FMMEL', 'Filet Maillant Melange');
INSERT INTO art_grand_type_engin VALUES ('FMMO', 'Filet Maillant Monofilament');
INSERT INTO art_grand_type_engin VALUES ('FMMOg', 'Filet Maillant Monofilament grandes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMMOm', 'Filet Maillant Monofilament moyennes mailles');
INSERT INTO art_grand_type_engin VALUES ('FMMOp', 'Filet Maillant Monofilament petites mailles');
INSERT INTO art_grand_type_engin VALUES ('GA/SW', 'Ganga/Swanya');
INSERT INTO art_grand_type_engin VALUES ('INCON', 'Inconnu');
INSERT INTO art_grand_type_engin VALUES ('LANCE', 'Harpon ou fusil de peche');
INSERT INTO art_grand_type_engin VALUES ('LIGNE', 'Ligne');
INSERT INTO art_grand_type_engin VALUES ('MELAN', 'Melange engins de peche');
INSERT INTO art_grand_type_engin VALUES ('NASSE', 'Nasse');
INSERT INTO art_grand_type_engin VALUES ('PA_EP', 'Melange palangres et eperviers');
INSERT INTO art_grand_type_engin VALUES ('PALAN', 'Palangre');
INSERT INTO art_grand_type_engin VALUES ('PIEGE', 'Pieges, barrages');
INSERT INTO art_grand_type_engin VALUES ('SE_PL', 'Senne de plage');
INSERT INTO art_grand_type_engin VALUES ('SE_SY', 'Senne syndicat');
INSERT INTO art_grand_type_engin VALUES ('SE_TO', 'Senne tournante');
INSERT INTO art_grand_type_engin VALUES ('TOUS', '-Tous-');
INSERT INTO art_grand_type_engin VALUES ('PASGT', 'Pas d engin de peche');


ALTER TABLE art_grand_type_engin ENABLE TRIGGER ALL;

--
-- Data for Name: art_param_type_effort; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_param_type_effort DISABLE TRIGGER ALL;

INSERT INTO art_param_type_effort VALUES (1, 'nombre d''unit�s de p�che', '');
INSERT INTO art_param_type_effort VALUES (2, 'nombre de sorties par mois', '');

ALTER TABLE art_param_type_effort ENABLE TRIGGER ALL;

--
-- Name: art_param_type_effort_type_effort_id_seq; Type: SEQUENCE SET; Schema: public; Owner: devppeao
--

SELECT pg_catalog.setval('art_param_type_effort_type_effort_id_seq', 2, true);


--
-- Data for Name: art_millieu; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_millieu DISABLE TRIGGER ALL;

INSERT INTO art_millieu VALUES (0, 'Inconnu');
INSERT INTO art_millieu VALUES (1, 'Fleuve');
INSERT INTO art_millieu VALUES (2, 'Mare');
INSERT INTO art_millieu VALUES (3, 'Lac');
INSERT INTO art_millieu VALUES (4, 'Chenal');
INSERT INTO art_millieu VALUES (5, 'Zone inondee');
INSERT INTO art_millieu VALUES (6, 'Bolong');
INSERT INTO art_millieu VALUES (7, 'Bordure');

SELECT pg_catalog.setval('art_millieu_id_seq',7,true);    

ALTER TABLE art_millieu ENABLE TRIGGER ALL;


--
-- Data for Name: art_type_activite; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_activite DISABLE TRIGGER ALL;

INSERT INTO art_type_activite VALUES ('peche avec capture', 'unite de peche de retour de peche debarquant sa capture', '111');
INSERT INTO art_type_activite VALUES ('peche sans capture', 'unite de peche de retour de peche avec prise nulle', '112');
INSERT INTO art_type_activite VALUES ('retour de peche puis depart', 'unite de peche effectuant au cours de la meme journee un retour de peche puis un depart', '113');
INSERT INTO art_type_activite VALUES ('2 sorties de peche', 'unite de peche effectuant au cours de la meme journee 2 sorties de peche _ soit deux retours', '114');
INSERT INTO art_type_activite VALUES ('depart d une sortie de peche', 'unite de peche au depart d une sortie de peche', '121');
INSERT INTO art_type_activite VALUES ('autre engin utilise', 'unite de peche sortie avec un autre engin de peche que l habituel', '130');
INSERT INTO art_type_activite VALUES ('non peche sans explication', 'unite de peche  n ayant pas effectuee de sortie dans la journee sans explication', '200');
INSERT INTO art_type_activite VALUES ('environnement climatique', 'unite de peche  n ayant pas effectuee de sortie  pour une raison d environnement climatique', '210');
INSERT INTO art_type_activite VALUES ('pluie', 'unite de peche  n ayant pas effectuee de sortie dans la journee  pour des raisons de pluie', '211');
INSERT INTO art_type_activite VALUES ('vent violent', 'unite de peche  n ayant pas effectuee de sortie dans la journee pour cause de vent violent', '212');
INSERT INTO art_type_activite VALUES ('maree defavorable', 'unite de peche  n ayant pas effectuee de sortie dans la journee en raison de maree defavorable', '213');
INSERT INTO art_type_activite VALUES ('brouillard intense', 'unite de peche  n ayant pas effectuee de sortie dans la journee en raison de brouillard intense', '214');
INSERT INTO art_type_activite VALUES ('retard par rapport a la maree', 'unite de peche  n ayant pas effectuee de sortie dans la journee en retard par rapport a la maree', '215');
INSERT INTO art_type_activite VALUES ('casse du mat�riel', 'UP non sortie du fait d une casse du mat�riel', '220');
INSERT INTO art_type_activite VALUES ('moteur en panne', 'moteur en panne', '221');
INSERT INTO art_type_activite VALUES ('pirogue en reparation', 'pirogue en reparation', '222');
INSERT INTO art_type_activite VALUES ('filet ou ligne en reparation', 'filet ou ligne en reparation', '223');
INSERT INTO art_type_activite VALUES ('pas d essence', 'pas d essence', '224');
INSERT INTO art_type_activite VALUES ('manque ou recherche d appats', 'manque ou recherche d appats', '225');
INSERT INTO art_type_activite VALUES ('glaciere en reparation', 'glaciere en reparation', '226');
INSERT INTO art_type_activite VALUES ('pas de glace', 'pas de glace', '227');
INSERT INTO art_type_activite VALUES ('probleme equipage', 'UP non sortie en raison de probleme d equipage', '230');
INSERT INTO art_type_activite VALUES ('equipage incomplet ou malade', 'equipage incomplet ou malade', '231');
INSERT INTO art_type_activite VALUES ('equipage en travaux', 'equipage effectuant des travaux autre que la peche', '232');
INSERT INTO art_type_activite VALUES ('equipage en travaux champetres', 'equipage effectuant des travaux champetres', '233');
INSERT INTO art_type_activite VALUES ('equipage au repos', 'equipage au repos', '234');
INSERT INTO art_type_activite VALUES ('peche importante la veille', 'le resultat de la veille justifie la non sortie _ peche importante', '235');
INSERT INTO art_type_activite VALUES ('peche faible la veille', 'le resultat de la veille justifie la non sortie _ peche faible', '236');
INSERT INTO art_type_activite VALUES ('pas d argent', 'pas d argent pour financer la sortie', '237');
INSERT INTO art_type_activite VALUES ('contraintes villageoises', 'UP non sortie en raison de contraintes villageoises', '240');
INSERT INTO art_type_activite VALUES ('fetes civiles', 'fetes civiles', '241');
INSERT INTO art_type_activite VALUES ('fetes religieuses', 'fetes religieuses', '242');
INSERT INTO art_type_activite VALUES ('interdits sociaux', 'interdits sociaux', '243');
INSERT INTO art_type_activite VALUES ('reunions administratives', 'organisations de reunions administratives', '244');
INSERT INTO art_type_activite VALUES ('UP neuve non operationnelle', 'UP neuve, mais non encore operationnelle', '250');
INSERT INTO art_type_activite VALUES ('pret elements UP', 'elements de l unite de peche prete a une autre unite de peche', '251');
INSERT INTO art_type_activite VALUES ('commerce', 'unite de peche non sortie pour raison commerciale', '260');
INSERT INTO art_type_activite VALUES ('vente du poisson', 'vente du poisson au cours de la journee', '261');
INSERT INTO art_type_activite VALUES ('mode commercialisation captures absent', 'non sortie car absence de mode de commercialisation des captures', '262');
INSERT INTO art_type_activite VALUES ('UP absente pour raison inconnue', 'UP absente du lieu de debarquement pour une raison inconnue', '300');
INSERT INTO art_type_activite VALUES ('UP en sortie de plusieurs jours', 'UP absente car effectuant une sortie de peche de plusieurs jours', '310');
INSERT INTO art_type_activite VALUES ('UP en deplacement', 'UP absente car en deplacement sur un autre lieu de debarquement _ migration', '320');
INSERT INTO art_type_activite VALUES ('UP vendue', 'UP absente car vendue', '330');
INSERT INTO art_type_activite VALUES ('embarcation chez un charpentier', 'UP absente car embarcation en reparation chez un charpentier', '340');
INSERT INTO art_type_activite VALUES ('barque brisee', 'UP absente car barque brisee', '350');
INSERT INTO art_type_activite VALUES ('enqueteur present mais pas d information', 'enqueteur present sur le lieu de debarquement n ayant pas d information sur l unite de peche', '900');
INSERT INTO art_type_activite VALUES ('enqueteur absent', 'enqueteur absent du lieu de debarquement', '910');

ALTER TABLE art_type_activite ENABLE TRIGGER ALL;


--
-- Data for Name: art_type_agglomeration; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_agglomeration DISABLE TRIGGER ALL;

INSERT INTO art_type_agglomeration VALUES (0, 'Inconnu');
INSERT INTO art_type_agglomeration VALUES (1, 'Village');
INSERT INTO art_type_agglomeration VALUES (2, 'Campement permanent');
INSERT INTO art_type_agglomeration VALUES (3, 'Campement temporaire');
INSERT INTO art_type_agglomeration VALUES (4, 'Ville');


SELECT pg_catalog.setval('art_type_agglomeration_id_seq',4,true);

ALTER TABLE art_type_agglomeration ENABLE TRIGGER ALL;


--
-- Data for Name: art_type_engin; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_engin DISABLE TRIGGER ALL;

INSERT INTO art_type_engin VALUES ('BALANCE', 'BALAN', 'Balance a crabes');
INSERT INTO art_type_engin VALUES ('BAMBOUS', 'BAMBO', 'Bambous');
INSERT INTO art_type_engin VALUES ('BARRAGE', 'PIEGE', 'Barrage de filets');
INSERT INTO art_type_engin VALUES ('CARMENT', 'NASSE', 'Durankono geant (decrue)');
INSERT INTO art_type_engin VALUES ('CASIER', 'NASSE', 'Casier � poulpes');
INSERT INTO art_type_engin VALUES ('CHA_CRE', 'FCREV', 'Chalut � crevettes');
INSERT INTO art_type_engin VALUES ('COQUILL', 'DIVER', 'Ramassage de coquillages');
INSERT INTO art_type_engin VALUES ('CORICOR', 'DIVER', 'Cori cori');
INSERT INTO art_type_engin VALUES ('DIENE', 'NASSE', 'Grande nasse Diene');
INSERT INTO art_type_engin VALUES ('DIVER', 'DIVER', 'Divers-Inconnu');
INSERT INTO art_type_engin VALUES ('DIVERS', 'DIVER', 'Divers-Inconnu');
INSERT INTO art_type_engin VALUES ('DURANKO', 'NASSE', 'Petite nasse Durankoro');
INSERT INTO art_type_engin VALUES ('EGLE', 'BALAN', 'Balance a crabes');
INSERT INTO art_type_engin VALUES ('EP', 'EPERV', 'Epervier');
INSERT INTO art_type_engin VALUES ('EP10', 'EPERV', 'Epervier maille 10');
INSERT INTO art_type_engin VALUES ('EP15', 'EPERV', 'Epervier maille 15');
INSERT INTO art_type_engin VALUES ('EP18', 'EPERV', 'Epervier maille 18');
INSERT INTO art_type_engin VALUES ('EP20', 'EPERV', 'Epervier maille 20');
INSERT INTO art_type_engin VALUES ('EP25', 'EPERV', 'Epervier maille 25');
INSERT INTO art_type_engin VALUES ('EP28', 'EPERV', 'Epervier maille 28');
INSERT INTO art_type_engin VALUES ('EP30', 'EPERV', 'Epervier maille 30');
INSERT INTO art_type_engin VALUES ('EP35', 'EPERV', 'Epervier maille 35');
INSERT INTO art_type_engin VALUES ('EP36', 'EPERV', 'Epervier maille 36');
INSERT INTO art_type_engin VALUES ('EP40', 'EPERV', 'Epervier maille 40');
INSERT INTO art_type_engin VALUES ('EP45', 'EPERV', 'Epervier maille 45');
INSERT INTO art_type_engin VALUES ('EP50', 'EPERV', 'Epervier maille 50');
INSERT INTO art_type_engin VALUES ('EP55', 'EPERV', 'Epervier maille 55');
INSERT INTO art_type_engin VALUES ('EP60', 'EPERV', 'Epervier maille 60');
INSERT INTO art_type_engin VALUES ('EP65', 'EPERV', 'Epervier maille 65');
INSERT INTO art_type_engin VALUES ('EP75', 'EPERV', 'Epervier maille 75');
INSERT INTO art_type_engin VALUES ('EP95', 'EPERV', 'Epervier maille 95');
INSERT INTO art_type_engin VALUES ('F2MAINS', 'GA/SW', 'Filet a deux mains');
INSERT INTO art_type_engin VALUES ('FCREV', 'FCREV', 'Filet a l`etalage crevette');
INSERT INTO art_type_engin VALUES ('FCREV10', 'FCREV', 'Filet a l`etalage crevette maille 10');
INSERT INTO art_type_engin VALUES ('FCREV20', 'FCREV', 'Filet a etalage crevette maille 20');
INSERT INTO art_type_engin VALUES ('FCREV30', 'FCREV', 'Filet a etalage crevette maille 30');
INSERT INTO art_type_engin VALUES ('FCREV45', 'FCREV', 'Filet a l`etalage crevette maille 45');
INSERT INTO art_type_engin VALUES ('FCREV55', 'FCREV', 'Filet a etalage crevette maille 55');
INSERT INTO art_type_engin VALUES ('FELEPOI', 'FMDEm', 'Fele Fele � poissons');
INSERT INTO art_type_engin VALUES ('FFIXCRE', 'FCREV', 'Filets fixes � crevettes');
INSERT INTO art_type_engin VALUES ('FM22.5', 'FMDOp', 'Filet Maillant Dormant maille 22.5');
INSERT INTO art_type_engin VALUES ('FMCL', 'FMCL', 'Filet Maillant a clochettes');
INSERT INTO art_type_engin VALUES ('FMCL105', 'FMCLg', 'Filet Maillant a clochettes maille 105');
INSERT INTO art_type_engin VALUES ('FMCL20', 'FMCLp', 'Filet Maillant a clochettes maille 20');
INSERT INTO art_type_engin VALUES ('FMCL35', 'FMCLm', 'Filet Maillant a clochettes maille 35');
INSERT INTO art_type_engin VALUES ('FMCL40', 'FMCLm', 'Filet Maillant a clochette maille 40');
INSERT INTO art_type_engin VALUES ('FMCL45', 'FMCLm', 'Filet Maillant a clochettes maille 45');
INSERT INTO art_type_engin VALUES ('FMCL50', 'FMCLg', 'Filet Maillant a clochettes maille 50');
INSERT INTO art_type_engin VALUES ('FMCL55', 'FMCLg', 'Filet Maillant a clochettes maille 55');
INSERT INTO art_type_engin VALUES ('FMCL60', 'FMCLg', 'Filet Maillant a clochettes maille 60');
INSERT INTO art_type_engin VALUES ('FMCL65', 'FMCLg', 'Filet Maillant a clochettes maille 65');
INSERT INTO art_type_engin VALUES ('FMCL75', 'FMCLg', 'Filet Maillant a clochettes maille 75');
INSERT INTO art_type_engin VALUES ('FMCL80', 'FMCLg', 'Filet Maillant a clochettes maille 80');
INSERT INTO art_type_engin VALUES ('FMCL85', 'FMCLg', 'Filet Maillant a clochettes maille 85');
INSERT INTO art_type_engin VALUES ('FMCL95', 'FMCLg', 'Filet Maillant a clochettes maille 95');
INSERT INTO art_type_engin VALUES ('FMDE', 'FMDE', 'Filet Maillant Derivant');
INSERT INTO art_type_engin VALUES ('FMDE10', 'FMDEp', 'Filet Maillant Derivant maille 10');
INSERT INTO art_type_engin VALUES ('FMDE100', 'FMDEg', 'Filet Maillant Derivant maille 100');
INSERT INTO art_type_engin VALUES ('FMDE115', 'FMDEg', 'Filet Maillant Derivant maille 115');
INSERT INTO art_type_engin VALUES ('FMDE120', 'FMDEg', 'Filet Maillant DErivant maille 120');
INSERT INTO art_type_engin VALUES ('FMDE20', 'FMDEp', 'Filet Maillant Derivant maille 20');
INSERT INTO art_type_engin VALUES ('FMDE25', 'FMDEp', 'Filet Maillant d�rivant 25mm');
INSERT INTO art_type_engin VALUES ('FMDE28', 'FMDEp', 'Filet Maillant DErivant maille 28');
INSERT INTO art_type_engin VALUES ('FMDE30', 'FMDEp', 'Filet Maillant Derivant maille 30');
INSERT INTO art_type_engin VALUES ('FMDE35', 'FMDEm', 'Filet Maillant Derivant maille 35');
INSERT INTO art_type_engin VALUES ('FMDE36', 'FMDEm', 'Filet Maillant d�rivant 36mm');
INSERT INTO art_type_engin VALUES ('FMDE40', 'FMDEm', 'Filet Maillant Derivant maille 40');
INSERT INTO art_type_engin VALUES ('FMDE44', 'FMDEm', 'Filet Maillant Derivant maille 44');
INSERT INTO art_type_engin VALUES ('FMDE45', 'FMDEm', 'Filet Maillant Derivant maille 45');
INSERT INTO art_type_engin VALUES ('FMDE50', 'FMDEm', 'Filet Maillant Derivant maille 50');
INSERT INTO art_type_engin VALUES ('FMDE55', 'FMDEg', 'Filet Maillant Derivant maille 55');
INSERT INTO art_type_engin VALUES ('FMDE60', 'FMDEg', 'Filet Maillant Derivant maille 60');
INSERT INTO art_type_engin VALUES ('FMDE65', 'FMDEg', 'Filet Maillant Derivant maille 65');
INSERT INTO art_type_engin VALUES ('FMDE75', 'FMDEg', 'Filet Maillant Derivant maille 75');
INSERT INTO art_type_engin VALUES ('FMDE80', 'FMDEg', 'Filet Maillant DErivant maille 80');
INSERT INTO art_type_engin VALUES ('FMDE85', 'FMDEg', 'Filet Maillant Derivant maille 85');
INSERT INTO art_type_engin VALUES ('FMDE90', 'FMDEg', 'Filet Maillant DErivant maille 90');
INSERT INTO art_type_engin VALUES ('FMDE95', 'FMDEg', 'Filet Maillant Derivant maille 95');
INSERT INTO art_type_engin VALUES ('FMDEfo', 'FMDEm', 'Filet maillant derivant de fond (Yolal)');
INSERT INTO art_type_engin VALUES ('FMDO', 'FMDO', 'Filet Maillant Dormant');
INSERT INTO art_type_engin VALUES ('FMDO10', 'FMDOp', 'Filet Maillant Dormant maille 10');
INSERT INTO art_type_engin VALUES ('FMDO100', 'FMDOg', 'Filet Maillant Dormant maille 100');
INSERT INTO art_type_engin VALUES ('FMDO105', 'FMDOg', 'Filet Maillant Dormant maille 105');
INSERT INTO art_type_engin VALUES ('FMDO115', 'FMDOg', 'Filet Maillant Dormant maille 115');
INSERT INTO art_type_engin VALUES ('FMDO125', 'FMDOg', 'Filet Maillant Dormant maille 125');
INSERT INTO art_type_engin VALUES ('FMDO135', 'FMDOg', 'Filet Maillant Dormant maille 135');
INSERT INTO art_type_engin VALUES ('FMDO15', 'FMDOp', 'Filet Maillant Dormant maille 15');
INSERT INTO art_type_engin VALUES ('FMDO15S', 'FMDOp', 'Filet Maillant Dormant maille 15');
INSERT INTO art_type_engin VALUES ('FMDO18S', 'FMDOp', 'Filet Maillant Dormant maille 18');
INSERT INTO art_type_engin VALUES ('FMDO20', 'FMDOp', 'Filet Maillant Dormant maille 20');
INSERT INTO art_type_engin VALUES ('FMDO20S', 'FMDOp', 'Filet Maillant Dormant maille 20');
INSERT INTO art_type_engin VALUES ('FMDO22', 'FMDOp', 'Filet Maillant Dormant maille 22');
INSERT INTO art_type_engin VALUES ('FMDO25', 'FMDOp', 'Filet Maillant Dormant maille 25');
INSERT INTO art_type_engin VALUES ('FMDO25S', 'FMDOp', 'Filet Maillant Dormant maille 25');
INSERT INTO art_type_engin VALUES ('FMDO30', 'FMDOp', 'Filet Maillant Dormant maille 30');
INSERT INTO art_type_engin VALUES ('FMDO30S', 'FMDOp', 'Filet Maillant Dormant maille 30');
INSERT INTO art_type_engin VALUES ('FMDO35', 'FMDOm', 'Filet Maillant Dormant maille 35');
INSERT INTO art_type_engin VALUES ('FMDO40', 'FMDOm', 'Filet Maillant Dormant maille 40');
INSERT INTO art_type_engin VALUES ('FMDO45', 'FMDOm', 'Filet Maillant Dormant maille 45');
INSERT INTO art_type_engin VALUES ('FMDO50', 'FMDOm', 'Filet Maillant Dormant maille 50');
INSERT INTO art_type_engin VALUES ('FMDO55', 'FMDOg', 'Filet Maillant Dormant maille 55');
INSERT INTO art_type_engin VALUES ('FMDO60', 'FMDOg', 'Filet Maillant Dormant maille 60');
INSERT INTO art_type_engin VALUES ('FMDO65', 'FMDOg', 'Filet Maillant Dormant maille 65');
INSERT INTO art_type_engin VALUES ('FMDO70', 'FMDOg', 'Filet Maillant Dormant maille 70');
INSERT INTO art_type_engin VALUES ('FMDO75', 'FMDOg', 'Filet Maillant Dormant maille 75');
INSERT INTO art_type_engin VALUES ('FMDO80', 'FMDOg', 'Filet Maillant Dormant maille 80');
INSERT INTO art_type_engin VALUES ('FMDO85', 'FMDOg', 'Filet Maillant Dormant maille 85');
INSERT INTO art_type_engin VALUES ('FMDO90', 'FMDOg', 'Filet Maillant Dormant maille 90');
INSERT INTO art_type_engin VALUES ('FMDO95', 'FMDOg', 'Filet Maillant Dormant maille 95');
INSERT INTO art_type_engin VALUES ('FMDOefi', 'FMDO', 'Filet maillant dormant � ethmaloses');
INSERT INTO art_type_engin VALUES ('FMDOg', 'FMDOg', 'Filet Maillant Dormant grandes mailles');
INSERT INTO art_type_engin VALUES ('FMDOlan', 'FMDO', 'Filet maillant dormant � langoustes');
INSERT INTO art_type_engin VALUES ('FMDOm', 'FMDOm', 'Filet Maillant Dormant moyennes mailles');
INSERT INTO art_type_engin VALUES ('FMDOp', 'FMDOp', 'Filet Maillant Dormant petites mailles');
INSERT INTO art_type_engin VALUES ('FMDOpoi', 'FMDOm', 'Filet maillant dormant � poissons');
INSERT INTO art_type_engin VALUES ('FMDOs', 'FMDOg', 'Filet Maillant Dormant a soles');
INSERT INTO art_type_engin VALUES ('FMDOsol', 'FMDOm', 'Filet maillant dormant � soles');
INSERT INTO art_type_engin VALUES ('FMDOsur', 'FMDOm', 'Filet maillant dormant');
INSERT INTO art_type_engin VALUES ('FMDOyee', 'FMDOg', 'Filet maillant dormant � yeet');
INSERT INTO art_type_engin VALUES ('FMEN', 'FMEN', 'Filet Maillant Encerclant');
INSERT INTO art_type_engin VALUES ('FMEN30', 'FMENp', 'Filet Maillant Encerclant maille 30');
INSERT INTO art_type_engin VALUES ('FMEN35', 'FMENm', 'Filet Maillant Encerclant maille 35');
INSERT INTO art_type_engin VALUES ('FMEN40', 'FMENm', 'Filet maillant encerclant Saima');
INSERT INTO art_type_engin VALUES ('FMEN45', 'FMENm', 'Filet Maillant Encerclant maille 45');
INSERT INTO art_type_engin VALUES ('FMEN55', 'FMENg', 'Filet Maillant Encerclant maille 55');
INSERT INTO art_type_engin VALUES ('FMEN65', 'FMENg', 'Filet Maillant Encerclant maille 65');
INSERT INTO art_type_engin VALUES ('FMEN75', 'FMENg', 'Filet Maillant Encerclant maille 75');
INSERT INTO art_type_engin VALUES ('FMMO', 'FMMO', 'Filet Maillant Monofilament');
INSERT INTO art_type_engin VALUES ('FMMO10', 'FMMOp', 'Filet Maillant Monofilament maille 10');
INSERT INTO art_type_engin VALUES ('FMMO100', 'FMMOg', 'Filet Maillant Monofilament maille 100');
INSERT INTO art_type_engin VALUES ('FMMO15', 'FMMOp', 'Filet Maillant Monofilament maille 15');
INSERT INTO art_type_engin VALUES ('FMMO20', 'FMMOp', 'Filet Maillant Monofilament maille 20');
INSERT INTO art_type_engin VALUES ('FMMO25', 'FMMOp', 'Filet Maillant Monofilamant maille 25');
INSERT INTO art_type_engin VALUES ('FMMO30', 'FMMOp', 'Filet Maillant Monofilament maille 30');
INSERT INTO art_type_engin VALUES ('FMMO35', 'FMMOm', 'Filet Maillant Monofilament maille 35');
INSERT INTO art_type_engin VALUES ('FMMO40', 'FMMOm', 'Filet Maillant Monofilament maille 40');
INSERT INTO art_type_engin VALUES ('FMMO45', 'FMMOm', 'Filet Maillant Monofilament maille 45');
INSERT INTO art_type_engin VALUES ('FMMO50', 'FMMOm', 'Filet maillant monofilament maille 50');
INSERT INTO art_type_engin VALUES ('FMMO55', 'FMMOg', 'Filet maillant monofilament maille 55');
INSERT INTO art_type_engin VALUES ('FMMO60', 'FMMOg', 'Filet maillant monofilament maille 60');
INSERT INTO art_type_engin VALUES ('FMMO65', 'FMMOg', 'Filet maillant monofilament maille 65');
INSERT INTO art_type_engin VALUES ('FMMO70', 'FMMOg', 'Filet maillant monofilament maille 70');
INSERT INTO art_type_engin VALUES ('FMMO75', 'FMMOg', 'Filet maillant monofilament maille 75');
INSERT INTO art_type_engin VALUES ('FMMO80', 'FMMOg', 'Filet maillant monofilament maille 80');
INSERT INTO art_type_engin VALUES ('FMMO85', 'FMMOg', 'Filet maillant monofilament maille 85');
INSERT INTO art_type_engin VALUES ('FMMO95', 'FMMOg', 'Filet maillant monofilament maille 95');
INSERT INTO art_type_engin VALUES ('FUSIL', 'LANCE', 'Fusil de p�che');
INSERT INTO art_type_engin VALUES ('GANGA', 'GA/SW', 'Ganga');
INSERT INTO art_type_engin VALUES ('GOLF', 'DIVER', 'Filet golf');
INSERT INTO art_type_engin VALUES ('GOLF40', 'DIVER', 'Filet golf');
INSERT INTO art_type_engin VALUES ('HA', 'DIVER', 'Engin inconnu');
INSERT INTO art_type_engin VALUES ('HARPON', 'LANCE', 'Harpons');
INSERT INTO art_type_engin VALUES ('HLHOC', 'DIVER', 'Engin inconnu');
INSERT INTO art_type_engin VALUES ('INCONNU', 'DIVER', 'Engin Inconnu');
INSERT INTO art_type_engin VALUES ('KA', 'DIVER', 'Engin Inconnu');
INSERT INTO art_type_engin VALUES ('KILLI', 'FCREV', 'Filet � crevettes Killi');
INSERT INTO art_type_engin VALUES ('LIGNE', 'LIGNE', 'Ligne a main');
INSERT INTO art_type_engin VALUES ('LIGNEC', 'LIGNE', 'Ligne avec canne');
INSERT INTO art_type_engin VALUES ('LIGNEM', 'LIGNE', 'Ligne a main');
INSERT INTO art_type_engin VALUES ('LIGNM', 'LIGNE', 'Ligne a main');
INSERT INTO art_type_engin VALUES ('MAINS', 'DIVER', 'Peche a la main');
INSERT INTO art_type_engin VALUES ('NASSE', 'NASSE', 'Nasse a poisson');
INSERT INTO art_type_engin VALUES ('NUL', 'DIVER', 'Engin Inconnu');
INSERT INTO art_type_engin VALUES ('PANIER', 'GA/SW', 'Panier');
INSERT INTO art_type_engin VALUES ('PAPOLO', 'NASSE', 'Nasse Papolo');
INSERT INTO art_type_engin VALUES ('PIEGE', 'PIEGE', 'Piege');
INSERT INTO art_type_engin VALUES ('PLA', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA10', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA11', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA12', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA13', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA14', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA15', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA16', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA2', 'PALAN', 'Palangre appatee ');
INSERT INTO art_type_engin VALUES ('PLA3', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA4', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA5', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA6', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA7', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA8', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLA9', 'PALAN', 'Palangre appatee');
INSERT INTO art_type_engin VALUES ('PLNA', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA10', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA11', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA12', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA13', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA14', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA15', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA16', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA17', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA2', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA3', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA4', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA5', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA6', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA7', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA8', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('PLNA9', 'PALAN', 'Palangre non appatee');
INSERT INTO art_type_engin VALUES ('SE_dig', 'SE_PL', 'Senne de plage Diguel');
INSERT INTO art_type_engin VALUES ('SE_opa', 'SE_PL', 'Senne de plage opane');
INSERT INTO art_type_engin VALUES ('SE_PL', 'SE_PL', 'Senne de plage');
INSERT INTO art_type_engin VALUES ('SE_PLAG', 'SE_PL', 'Senne de plage');
INSERT INTO art_type_engin VALUES ('SE_TOUR', 'SE_TO', 'Senne tournante ghaneene');
INSERT INTO art_type_engin VALUES ('SENNE', 'SE_PL', 'Senne de plage');
INSERT INTO art_type_engin VALUES ('SENNE T', 'SE_PL', 'Senne de plage');
INSERT INTO art_type_engin VALUES ('SENNE10', 'SE_PL', 'Senne de plage maille 10');
INSERT INTO art_type_engin VALUES ('SENNE14', 'SE_PL', 'Senne de plage maille 14');
INSERT INTO art_type_engin VALUES ('SENNE20', 'SE_PL', 'Senne de plage maille 20');
INSERT INTO art_type_engin VALUES ('SENNE25', 'SE_PL', 'Senne de plage maille 25');
INSERT INTO art_type_engin VALUES ('SENNE30', 'SE_PL', 'Senne de plage maille 30');
INSERT INTO art_type_engin VALUES ('SENNE35', 'SE_PL', 'Senne de plage maille 35');
INSERT INTO art_type_engin VALUES ('SENNE40', 'SE_PL', 'Senne de plage maille 40');
INSERT INTO art_type_engin VALUES ('SENNE45', 'SE_PL', 'Senne de plage maille 45');
INSERT INTO art_type_engin VALUES ('SENNE50', 'SE_PL', 'Senne de plage maille 50');
INSERT INTO art_type_engin VALUES ('SENNE55', 'SE_PL', 'Senne de plage maille 55');
INSERT INTO art_type_engin VALUES ('SENNE60', 'SE_PL', 'Senne de plage multi mailles');
INSERT INTO art_type_engin VALUES ('SENNE65', 'SE_PL', 'Senne de plage maille 65');
INSERT INTO art_type_engin VALUES ('SENNE85', 'SE_PL', 'Senne de plage maille 85');
INSERT INTO art_type_engin VALUES ('SENNEte', 'SE_PL', 'Senne de terre');
INSERT INTO art_type_engin VALUES ('SEPI', 'DIVER', 'Engin inconnu');
INSERT INTO art_type_engin VALUES ('SWANYA', 'GA/SW', 'Swanya');
INSERT INTO art_type_engin VALUES ('SYNDICA', 'SE_SY', 'Senne syndicat');
INSERT INTO art_type_engin VALUES ('XUBI10', 'SE_PL', 'Xubiseu petite senne maille 10');
INSERT INTO art_type_engin VALUES ('XUBI20', 'SE_PL', 'Xubiseu petite senne maille 20');
INSERT INTO art_type_engin VALUES ('XUBI30', 'SE_PL', 'Xubiseu petite senne maille 30');
INSERT INTO art_type_engin VALUES ('XUBI35', 'SE_PL', 'Xubiseu petite senne maille 35');
INSERT INTO art_type_engin VALUES ('XUBI40', 'SE_PL', 'Xubiseu petite senne maille 40');
INSERT INTO art_type_engin VALUES ('XUBI45', 'SE_PL', 'Xubiseu petite senne maille 45');
INSERT INTO art_type_engin VALUES ('XUBISEU', 'SE_PL', 'Xubiseu petite senne');

ALTER TABLE art_type_engin ENABLE TRIGGER ALL;


--
-- Data for Name: art_type_sortie; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_type_sortie DISABLE TRIGGER ALL;

INSERT INTO art_type_sortie VALUES (0, 'Non informe');
INSERT INTO art_type_sortie VALUES (1, 'Sortie effectuee a pied');
INSERT INTO art_type_sortie VALUES (2, 'Sortie effectuee dans une embarcation non motorisee');
INSERT INTO art_type_sortie VALUES (3, 'Sortie effectuee dans une embarcation motorisee');

SELECT pg_catalog.setval('art_type_sortie_id_seq',3,true);

ALTER TABLE art_type_sortie ENABLE TRIGGER ALL;


--
-- Data for Name: art_vent; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE art_vent DISABLE TRIGGER ALL;

INSERT INTO art_vent VALUES (0, 'Inconnu');
INSERT INTO art_vent VALUES (1, 'Absence de vent');
INSERT INTO art_vent VALUES (2, 'Vent l�ger');
INSERT INTO art_vent VALUES (3, 'Vent fort');
INSERT INTO art_vent VALUES (4, 'Vent tr�s fort');

SELECT pg_catalog.setval('art_vent_id_seq',4,true);

ALTER TABLE art_vent ENABLE TRIGGER ALL;


--
-- Data for Name: exp_contenu; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_contenu DISABLE TRIGGER ALL;

INSERT INTO exp_contenu VALUES (1, '1- poisson');
INSERT INTO exp_contenu VALUES (2, '2- crabe');
INSERT INTO exp_contenu VALUES (3, '3- crevette ind. ou non p�n�ide');
INSERT INTO exp_contenu VALUES (4, '4- plancton');
INSERT INTO exp_contenu VALUES (5, '5- insecte a�rien');
INSERT INTO exp_contenu VALUES (6, '6- oeufs (de poisson)');
INSERT INTO exp_contenu VALUES (7, '7- �chinoderme');
INSERT INTO exp_contenu VALUES (8, '8- mollusque ind�termin�');
INSERT INTO exp_contenu VALUES (9, '9- d�bris v�g�taux');
INSERT INTO exp_contenu VALUES (10, '10- autre');
INSERT INTO exp_contenu VALUES (11, '11- crevette p�n�ide');
INSERT INTO exp_contenu VALUES (12, '12- mysidac�');
INSERT INTO exp_contenu VALUES (13, '13- bivalve');
INSERT INTO exp_contenu VALUES (14, '14 - gast�ropode');
INSERT INTO exp_contenu VALUES (15, '15- c�phalopode');
INSERT INTO exp_contenu VALUES (16, '16- bouillie');
INSERT INTO exp_contenu VALUES (17, '17- amphipodes');
INSERT INTO exp_contenu VALUES (18, '18- crustac�s divers');
INSERT INTO exp_contenu VALUES (19, '19- vers');

ALTER TABLE exp_contenu ENABLE TRIGGER ALL;


--
-- Data for Name: exp_debris; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_debris DISABLE TRIGGER ALL;

INSERT INTO exp_debris VALUES ('a', 'a- absence de d�bris');
INSERT INTO exp_debris VALUES ('c', 'c- d�bris coquilliers');
INSERT INTO exp_debris VALUES ('e', 'e- �chinodermes');
INSERT INTO exp_debris VALUES ('m', 'm- d�bris min�raux');
INSERT INTO exp_debris VALUES ('o', 'o- d�bris organiques');

ALTER TABLE exp_debris ENABLE TRIGGER ALL;


--
-- Data for Name: exp_engin; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_engin DISABLE TRIGGER ALL;

INSERT INTO exp_engin VALUES ('1', '1- senne tournante CRO', 300, 18, 14, 'Ebri� : Senne tournante du CRO > remplac� par code "t" le 17/03/06 pour homog�n�iser');
INSERT INTO exp_engin VALUES ('3', '3- chalut (1er)', NULL, NULL, 20, 'Ebri� : Premier chalut (panneaux en bois) - ouverture verticale 1.5m, corde � dos 10m');
INSERT INTO exp_engin VALUES ('4', '4- chalut (2eme)', NULL, NULL, 20, 'Ebri� : Second chalut (panneaux en plastique) - ouverture verticale 1.5m, corde � dos 10m');
INSERT INTO exp_engin VALUES ('f1', 'f1- FMV06', 6, 2, NULL, 'Mali - filet maillant vertical 6m de haut');
INSERT INTO exp_engin VALUES ('f2', 'f2- FMV12', 12, 2, NULL, 'Mali - filet maillant vertical 12m de haut');
INSERT INTO exp_engin VALUES ('f3', 'f3- FMV24', 24, 2, NULL, 'Mali - filet maillant vertical 24m de haut');
INSERT INTO exp_engin VALUES ('f4', 'f4- FMV48', 48, 2, NULL, 'Mali - filet maillant vertical 48m de haut');
INSERT INTO exp_engin VALUES ('f5', 'f5- FMH2', 2, 12, NULL, 'Mali - filet maillant horizontal 2m de haut');
INSERT INTO exp_engin VALUES ('f6', 'f6- FMH1.2', 1, 25, NULL, 'Mali - filet maillant horizontal 1.2m de haut');
INSERT INTO exp_engin VALUES ('f7', 'f7- FMH3', 3, 25, NULL, 'Mali - filet maillant horizontal 3m de haut');
INSERT INTO exp_engin VALUES ('fm', 'fm- filet maillant Guin�e', 25, 2, NULL, 'Guin�e : Filet maillant utilis� par E. Baran');
INSERT INTO exp_engin VALUES ('l', 'l- senne de plage coupee', NULL, 7, 25, 'Saloum 1990 : engin test PSD');
INSERT INTO exp_engin VALUES ('n', 'n- nasse juv�niles', NULL, NULL, NULL, 'Saloum Programme Juv�niles VDG');
INSERT INTO exp_engin VALUES ('n1', 'n1- NasseGM', NULL, NULL, 15, ' Mali - Nasse Grandes Mailles');
INSERT INTO exp_engin VALUES ('n2', 'n2- NassePM', NULL, NULL, 10, ' Mali - Nasse Petites Mailles');
INSERT INTO exp_engin VALUES ('o', 'o- senne tournante 100m', 100, 20, 14, 'Saloum 1990 : engin test PSD');
INSERT INTO exp_engin VALUES ('p', 'p- senne de plage longue', NULL, 7, 25, 'Saloum 1990 : engin test PSD');
INSERT INTO exp_engin VALUES ('t', 't- senne tournante 250m', 250, 18, 14, 'Senne tournante Saloum > r�f�rence pour tous syst�mes');

ALTER TABLE exp_engin ENABLE TRIGGER ALL;


--
-- Data for Name: exp_force_courant; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_force_courant DISABLE TRIGGER ALL;

INSERT INTO exp_force_courant VALUES (0, '0- �tale');
INSERT INTO exp_force_courant VALUES (1, '1- courant l�ger');
INSERT INTO exp_force_courant VALUES (2, '2- notable');
INSERT INTO exp_force_courant VALUES (3, '3- fort');

ALTER TABLE exp_force_courant ENABLE TRIGGER ALL;


--
-- Data for Name: exp_position; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_position DISABLE TRIGGER ALL;

INSERT INTO exp_position VALUES (1, '1- milieu (chenal)');
INSERT INTO exp_position VALUES (2, '2- rive (bordure)');
INSERT INTO exp_position VALUES (3, '3- bolon');
INSERT INTO exp_position VALUES (4, '4- confluent');
INSERT INTO exp_position VALUES (5, '5- AV (vers aval)');
INSERT INTO exp_position VALUES (6, '6- RV (perpendiculai');
INSERT INTO exp_position VALUES (7, '7- AM (vers amont)');


ALTER TABLE exp_position ENABLE TRIGGER ALL;


--
-- Data for Name: exp_qualite; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_qualite DISABLE TRIGGER ALL;

INSERT INTO exp_qualite VALUES (1, '1- coup r�ussi');
INSERT INTO exp_qualite VALUES (2, '2- coup perdu');
INSERT INTO exp_qualite VALUES (3, '3- coup imparfait mais r�cup�r�');
INSERT INTO exp_qualite VALUES (4, '4- coup dont les r�sultats ont paru douteux lors du d�pouillement');
INSERT INTO exp_qualite VALUES (5, '5- coup rejou�');



ALTER TABLE exp_qualite ENABLE TRIGGER ALL;


--
-- Data for Name: exp_remplissage; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_remplissage DISABLE TRIGGER ALL;

INSERT INTO exp_remplissage VALUES ('d', 'd- Demi');
INSERT INTO exp_remplissage VALUES ('p', 'p- Plein');
INSERT INTO exp_remplissage VALUES ('v', 'v- Vide');
INSERT INTO exp_remplissage VALUES ('x', 'x- Pr�sence aliments');

ALTER TABLE exp_remplissage ENABLE TRIGGER ALL;


--
-- Data for Name: exp_sediment; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_sediment DISABLE TRIGGER ALL;

INSERT INTO exp_sediment VALUES ('al', 'al-alloides');
INSERT INTO exp_sediment VALUES ('pc', 'pc-petits cailloux');
INSERT INTO exp_sediment VALUES ('rh', 'rh- roches et huitres');
INSERT INTO exp_sediment VALUES ('ro', 'ro- roches');
INSERT INTO exp_sediment VALUES ('sa', 'sa- sable');
INSERT INTO exp_sediment VALUES ('sv', 'sv- sable vaseux');
INSERT INTO exp_sediment VALUES ('va', 'va- vase anoxyque');
INSERT INTO exp_sediment VALUES ('vd', 'vd- vase dure');
INSERT INTO exp_sediment VALUES ('vm', 'vm- vase molle');
INSERT INTO exp_sediment VALUES ('vr', 'vr- vase + roches');
INSERT INTO exp_sediment VALUES ('vs', 'vs- vase sableuse');
INSERT INTO exp_sediment VALUES ('vv', 'vv- vase');

ALTER TABLE exp_sediment ENABLE TRIGGER ALL;


--
-- Data for Name: exp_sens_courant; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_sens_courant DISABLE TRIGGER ALL;

INSERT INTO exp_sens_courant VALUES (1, '1- mar�e montante');
INSERT INTO exp_sens_courant VALUES (2, '2- mar�e descendante');
INSERT INTO exp_sens_courant VALUES (3, '3- �tale de haute mer');
INSERT INTO exp_sens_courant VALUES (4, '4- �tale de basse mer');
INSERT INTO exp_sens_courant VALUES (5, '5- �tale');


ALTER TABLE exp_sens_courant ENABLE TRIGGER ALL;


--
-- Data for Name: exp_sexe; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_sexe DISABLE TRIGGER ALL;

INSERT INTO exp_sexe VALUES ('f', 'f- femelle');
INSERT INTO exp_sexe VALUES ('i', 'i- immature / gonades tr�s r�duites / indiscernable');
INSERT INTO exp_sexe VALUES ('m', 'm- m�le');

ALTER TABLE exp_sexe ENABLE TRIGGER ALL;


--
-- Data for Name: exp_stade; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_stade DISABLE TRIGGER ALL;

INSERT INTO exp_stade VALUES (0, '0- immature / indiscernable');
INSERT INTO exp_stade VALUES (1, '1- repos sexuel');
INSERT INTO exp_stade VALUES (2, '2- d�but de la maturit� sexuelle');
INSERT INTO exp_stade VALUES (3, '3- en maturation');
INSERT INTO exp_stade VALUES (4, '4- m�r');
INSERT INTO exp_stade VALUES (5, '5- ponte');
INSERT INTO exp_stade VALUES (6, '6- post-ponte');
INSERT INTO exp_stade VALUES (7, '7- (6-2) post-ponte et retour au stade 2');
INSERT INTO exp_stade VALUES (8, '8- (6-3) post-ponte et retour au stade 3');
INSERT INTO exp_stade VALUES (9, '9- (6-4) post-ponte et retour au stade 4');


ALTER TABLE exp_stade ENABLE TRIGGER ALL;


--
-- Data for Name: exp_station; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_station DISABLE TRIGGER ALL;

INSERT INTO exp_station VALUES ('G1BJ1', 'Banjul 1', 'BJ1', '+ 13:25:17', '- 016:34:69', 'Lower Estuary (Banjul)', 23, 2, NULL, 'e', 'vv', NULL);
INSERT INTO exp_station VALUES ('G2SUP', 'Suara Point', 'SUP', '+ 13:26:92', '- 016:08:20', 'Amont Suara Creek', 24, 1, NULL, NULL, 'vm', 69);
INSERT INTO exp_station VALUES ('G2MUT', 'Muta Point', 'MUT', '+ 13:26:11', '- 016:08:63', 'Amont Muta Point (Kemoto)', 24, 2, NULL, NULL, 'vm', 69);
INSERT INTO exp_station VALUES ('G2SU1', 'Suara Island 1', 'SU1', '+ 13:25:24', '- 016:05:38', 'Rive gauche en face de Suara Island', 24, 2, NULL, NULL, 'vm', 74);
INSERT INTO exp_station VALUES ('G2SU2', 'Suara Island 2', 'SU2', '+ 13:25:25', '- 016:04:28', 'Chenal devant Suara Island', 24, 1, NULL, NULL, 'vm', 76);
INSERT INTO exp_station VALUES ('G2SLP', 'Selekini Point', 'SLP', '+ 13:26:64', '- 016:01:16', 'Amont Selekini Point (butte)', 24, 2, NULL, 'o', 'vm', 82);
INSERT INTO exp_station VALUES ('G2SLC', 'Selekini Creek', 'SLC', '+ 13:27:65', '- 015:59:86', 'Bolon : Selekini Creek � la 1ere confluence - Fond: vase molle, feuilles, d�bris v�g�taux', 24, 4, NULL, 'o', 'vm', NULL);
INSERT INTO exp_station VALUES ('G2TKL', 'Tankular Amont', 'TKL', '+ 13:25:59', '- 015:59:93', 'Chenal proche bolon Selekini (vers rive gauche)', 24, 1, NULL, NULL, 'vm', 84);
INSERT INTO exp_station VALUES ('G2JAL', 'Jali Point', 'JAL', '+ 13:26:80', '- 015:56:61', 'Rive droite en face Jali Point - Fond : vase dure, branches et troncs', 24, 2, NULL, 'o', 'vd', 91);
INSERT INTO exp_station VALUES ('G2JRP', 'Jarin Creek', 'JRP', '+ 13:24:78', '- 015:55:91', 'Fond propre, coquilles, bois', 24, NULL, NULL, 'c', 'ro', NULL);
INSERT INTO exp_station VALUES ('G2JRE', 'Jarin Embouchure', 'JRE', '+ 13:25:23', '- 015:56:17', 'Bolon � �viter pour la senne en raison de la nature du fond', 24, 3, NULL, NULL, 'rh', NULL);
INSERT INTO exp_station VALUES ('G1BJ2', 'Banjul 2', 'BJ2', '+ 13:25:50', '- 016:34:31', 'Lower Estuary, Chenal', 23, 1, NULL, 'e', 'vd', 4);
INSERT INTO exp_station VALUES ('G2TEN', 'Tendaba', 'TEN', '+ 13:27:16', '- 015:47:95', 'Tendaba rive droite - Fond : Feuilles', 24, 2, NULL, 'o', 'vv', 106);
INSERT INTO exp_station VALUES ('G2TSI', 'Tendaba silos', 'TSI', '+ 13:26:52', '- 015:47:60', 'Tendaba rive gauche en amont des silos d''arachides - Fond : Vase molle, plus feuille', 24, 2, NULL, 'o', 'vm', 107);
INSERT INTO exp_station VALUES ('G2KAT', 'Katchiang', 'KAT', '+ 13:27:57', '- 015:44:92', 'Devant Katchiang creek, rive droite', 24, 2, NULL, NULL, 'vm', 112);
INSERT INTO exp_station VALUES ('G2BBK', 'Bambako', 'BBK', '+ 13:27:00', '- 015:44:59', 'Rive gauche face Katchiang creek, tr�s proche du bord (acorre)', 24, 2, NULL, 'o', 'vd', 112);
INSERT INTO exp_station VALUES ('G2KUN', 'Kunda', 'KUN', '+ 13:28:63', '- 015:42:08', 'Chenal central entre Marmabere creek et Kunda creek', 24, 1, NULL, NULL, 'vd', 117);
INSERT INTO exp_station VALUES ('G2KRU', 'Krule Point', 'KRU', '+ 13:29:87', '- 015:40:44', 'Amont Jurong creek, rive droite - Fond : branches et huitres', 24, 2, NULL, 'c', 'vm', 121);
INSERT INTO exp_station VALUES ('G2JUR', 'Jurong Creek', 'JUR', '+ 13:30:30', '- 015:41:13', 'Fond : Bois et feuilles', 24, 3, NULL, 'o', 'vm', NULL);
INSERT INTO exp_station VALUES ('G3DEV', 'Devils Point', 'DEV', '+ 13:27:95', '- 015:36:41', 'Fond dur', 25, 1, NULL, NULL, 'vd', 129);
INSERT INTO exp_station VALUES ('G3BGO', 'Balingo', 'BGO', '+ 13:28:97', '- 015:35:76', 'Face Balingo', 25, 1, NULL, NULL, 'vd', 131);
INSERT INTO exp_station VALUES ('G3BBT', 'Bambatenda', 'BBT', '+ 13:30:93', '- 015:32:46', 'Rive gauche, en amont du bac de Farafeni - Mangroves abondantes et hautes,', 25, 2, NULL, 'o', 'vd', 140);
INSERT INTO exp_station VALUES ('G1SIK', 'Sika', 'SIK', '+ 13:19:89', '- 016:19:45', 'Lower Estuary (entre Sika et Albreda) - fond sablo-vaseux (vase dure).', 23, 2, NULL, NULL, 'sv', 40);
INSERT INTO exp_station VALUES ('G3TBK', 'Tambakoto', 'TBK', '+ 13:30:41', '- 015:31:58', 'En face de Tambakoto creek, rive gauche - Fond : Branches et feuilles,', 25, 2, NULL, 'o', 'vd', 141);
INSERT INTO exp_station VALUES ('G3SOF', 'Sofanyama', 'SOF', '+ 13:30:51', '- 015:18:43', 'Rive gauche pr�s de Sofanyama Creek - Fond : vase et feuilles  - Roseaux en bordure - Proximit� d''une fosse>20m', 25, 2, NULL, 'o', 'vv', 172);
INSERT INTO exp_station VALUES ('G3SAM', 'Samba', 'SAM', '+ 13:30:54', '- 015:19:19', 'Rive droite � l''oppos� de Samba Creek - Fond : vase et bcp feuilles', 25, 2, NULL, 'o', 'vv', 173);
INSERT INTO exp_station VALUES ('G3BBL', 'Bambali Amont', 'BBL', '+ 13:28:35', '- 015:19:16', 'Amont de Bambali rive gauche - Bras droit du fleuve, quart sup�rieur de Elephant Island - Fond dur, cailloux, feuilles', 25, 2, NULL, 'o', 'vd', 168);
INSERT INTO exp_station VALUES ('G3ELJ', 'Elephant Jassang', 'ELJ', '+ 13:26:51', '- 015:20:27', 'Rive droite - Bras gauche du fleuve, tiers inf�rieur de Elephant Island', 25, 2, NULL, NULL, 'vd', 165);
INSERT INTO exp_station VALUES ('G3ELP', 'Elephant Pointe', 'ELP', '+ 13:26:51', '- 015:21:72', 'Pointe sud de Elephant Island - Fond : branches et feuilles', 25, 2, NULL, 'o', 'vd', 162);
INSERT INTO exp_station VALUES ('G3ELV', 'Elephant Aval', 'ELV', '+ 13:26:57', '- 015:22:18', 'Aval Elephant Island - Rive droite - Bordure', 25, 2, NULL, 'o', 'vd', 161);
INSERT INTO exp_station VALUES ('G3TUD', 'Tudenda', 'TUD', '+ 13:27:87', '- 015:25:20', 'Amont Tudenda Creek - Rive droite - Bordure - NB : Position du Diassanga, GPS HS sur la pirogue de p�che', 25, 2, NULL, 'o', 'vm', 155);
INSERT INTO exp_station VALUES ('G3WAL', 'Wale', 'WAL', '+ 13:29:80', '- 015:28:72', 'Rive droite bordure, amont de Wale Creek', 25, 2, NULL, NULL, 'vm', 146);
INSERT INTO exp_station VALUES ('G3SNK', 'Sankuia', 'SNK', '+ 13:29:86', '- 015:30:49', 'Rive droite bordure l�g�rement en aval de Sankuia creek', 25, 2, NULL, NULL, 'vm', 144);
INSERT INTO exp_station VALUES ('G1LMP', 'Lamine Point', 'LMP', '+ 13:19:45', '- 016:24:24', 'Lower Estuary - Lamine Point - Bordure - Fond : branches et fond � oursin', 23, 2, NULL, 'e', 'vd', 31);
INSERT INTO exp_station VALUES ('G1CHI', 'Chilabong bolon', 'CHI', '+ 13:26:42', '- 016:36:08', 'Face � Chilabong Bolon, Bordure', 23, 2, NULL, 'e', 'vm', 6);
INSERT INTO exp_station VALUES ('G1DOG', 'Dog Island', 'DOG', '+ 13:24:45', '- 016:33:19', 'Large de Dog Island, Chenal Central, Fond dur', 23, 1, NULL, NULL, 'sa', 8);
INSERT INTO exp_station VALUES ('G1MAR', 'Maredina bolon', 'MAR', '+ 13:20:08', '- 016:32:92', 'Large de Maredina bolon, assez central', 23, 1, NULL, 'e', 'sa', 15);
INSERT INTO exp_station VALUES ('G2MAN', 'Mandori Creek', 'MAN', '+ 13:27:09', '- 015:53:88', 'Embouchure, confluent', 24, 4, NULL, NULL, 'vd', 96);
INSERT INTO exp_station VALUES ('G3SHM', 'Sea horse I. Amont', 'SHM', '+ 13:36:17', '- 015:22:28', 'Station en amont de l ile', 25, 1, NULL, NULL, 'sa', 187);
INSERT INTO exp_station VALUES ('G3SHV', 'Sea horse I. Aval', 'SHV', '+ 13:32:42', '- 015:21:96', 'Station en aval de l ile, Fond: Vase molle et branches', 25, 1, NULL, 'o', 'vm', 181);
INSERT INTO exp_station VALUES ('G3BGA', 'Balangar', 'BGA', '+ 13:39:64', '- 015:21:55', 'Bordure rive gauche, GPS p�che faux, not� GPS bord', 25, 2, NULL, NULL, 'vm', 193);
INSERT INTO exp_station VALUES ('G3BAN', 'Bantanta creek', 'BAN', '+ 13:40:90', '- 015:17:42', 'Bantanta Creek, Bordure rive droite, Mangrove basse avec qqs discontinuit�s et roseaux', 25, 2, 'd', NULL, 'vd', 202);
INSERT INTO exp_station VALUES ('G3PAP', 'Milieu Papa Island', 'PAP', '+ 13:38:65', '- 015:14:13', 'Bordure rive droite, Mangrove basse, �parse sur r. dr., continue sur r. g.,  roseaux dans trou�es de mangrove', 25, 2, 'd', NULL, 'sa', 209);
INSERT INTO exp_station VALUES ('MBA04', 'Q 04 Manan. Barrage', 'B04', '+ 13:11:40', '- 010:21:65', NULL, 14, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G3CAR', 'Carrol s wharf', 'CAR', '+ 13:40:58', '- 015:09:70', 'Bordure rive droite, Qqs bouquets de mangrove, petits, Baobabs, Palmiers', 25, 2, 'd', NULL, 'vm', 218);
INSERT INTO exp_station VALUES ('G1LMV', 'Lamine Village', 'LMV', '+ 13:19:65', '- 016:25:95', 'Lower Estuary - Lamine Village - Bordure - Fond : oursins et coquilles d''hu�tres', 23, 2, NULL, 'e', 'sv', 28);
INSERT INTO exp_station VALUES ('G3DEE', 'Deer Islands', 'DEE', '+ 13:41:53', '- 015:08:11', 'Bordure, Aval petit bolon, R. dr., Palmiers+petite mangrove en bordure+v�g�tation basse a l arriere avec palmiers a huile', 25, 2, NULL, NULL, 'sa', 221);
INSERT INTO exp_station VALUES ('G3BTD', 'Bai Tenda', 'BTD', '+ 13:28:42', '- 015:25:53', 'VDG', 25, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G3BKD', 'Barokunda', 'BKD', '+ 13:29:43', '- 015:17:29', 'VDG', 25, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G1BIN', 'Bintang', 'BIN', '+ 13:13:83', '- 016:11:53', 'VDG', 23, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G2KTC', 'Katchiang', 'KTC', '+ 13:28:94', '- 015:45:98', 'VDG', 24, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G1MDN', 'Mandinari Flats', 'MDN', '+ 13:23:91', '- 016:36:26', 'VDG', 23, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G1SAS', 'Sassankoto', 'SAS', '+ 13:15:68', '- 016:26:33', 'VDG', 23, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G2SEL', 'Selekini', 'SEL', '+ 13:27:73', '- 015:58:28', 'VDG', 24, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G2TDB', 'Tendaba', 'TDB', '+ 13:27:54', '- 015:45:68', 'VDG', 24, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G3WAC', 'Wale Creek', 'WAC', '+ 13:28:91', '- 015:28:35', 'VDG', 25, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('G1BIP', 'Bintang Point', 'BIP', '+ 13:19:75', '- 016:13:75', 'Entr�e du Bolon Bintang', 23, 3, NULL, NULL, 'vm', 50);
INSERT INTO exp_station VALUES ('G1BIA', 'Bintang Amont', 'BIA', '+ 13:15:42', '- 016:11:32', 'Bolon Bintang en amont du village Bintang', 23, 3, NULL, NULL, 'vd', 59);
INSERT INTO exp_station VALUES ('G1BIV', 'Bintang Village', 'BIV', '+ 13:16:94', '- 016:12:32', 'Bolon Bintang, en aval du village de Bintang - Mangrove rive droite - Fond : Vase dure+cailloux, Mangrove sur rive droite', 23, 3, 'd', 'm', 'vd', NULL);
INSERT INTO exp_station VALUES ('G1TAB', 'Tabirere Creek', 'TAB', '+ 13:26:22', '- 016:10:63', 'Amont Tabirere Creek', 23, 2, NULL, NULL, 'vm', 63);
INSERT INTO exp_station VALUES ('BIJ01', 'Rio Bruce', 'B01', '+ 11:13:36', '- 015:50:53', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ10', 'Rio de Acapa', 'B10', NULL, NULL, NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ11', 'Rio de Acapa 2', 'B11', '+ 11:06:47', '- 016:11:41', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ12', 'Canal Diego Gomez', 'B12', '+ 11:09:08', '- 016:11:32', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ13', 'Sud Uno', 'B13', '+ 11:13:35', '- 016:17:05', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ14', 'CanalAlvaroFernandes', 'B14', NULL, NULL, NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ15', 'Urtrocano', 'B15', '+ 11:19:29', '- 016:25:56', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ16', 'Canal Pedro deCintra', 'B16', '+ 11:25:14', '- 016:24:49', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ17', 'Baie de Escaramaca', 'B17', '+ 11:34:01', '- 016:20:49', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ18', 'Canal Ninguin', 'B18', '+ 11:32:56', '- 016:13:04', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ19', 'Porco', 'B19', '+ 11:23:56', '- 016:17:49', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ02', 'Bubaque hotel', 'B02', '+ 11:19:10', '- 015:50:57', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ20', 'Canal de Uno', 'B20', '+ 11:17:35', '- 016:08:16', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ21', 'Angaruma', 'B21', '+ 11:17:09', '- 015:58:05', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ22', 'Banco de Bubaque', 'B22', '+ 11:18:11', '- 015:50:37', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ23', 'Banco de Formosa', 'B23', '+ 11:28:29', '- 015:53:37', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ24', 'Banco Formosa Nord', 'B24', '+ 11:32:38', '- 015:53:10', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ25', 'Maio hotel', 'B25', '+ 11:36:23', '- 015:56:43', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ26', 'Maio Nord', 'B26', '+ 11:34:43', '- 016:00:31', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ27', 'Bol Nago/Formosa ent', 'B27', '+ 11:30:33', '- 016:00:16', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ28', 'Bol Formosa/Nago', 'B28', '+ 11:30:57', '- 015:58:46', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ29', 'Bol Maio/Formosa', 'B29', '+ 11:31:20', '- 015:55:52', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ03', 'Rio Anubudugui', 'B03', '+ 11:09:52', '- 015:58:47', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ30', 'Sud-Ouest Canhabaque', 'B30', '+ 11:10:41', '- 015:46:45', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ31', 'Sud Canhabaque', 'B31', '+ 11:08:27', '- 015:45:30', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ32', 'Joao Vieira', 'B32', '+ 11:05:20', '- 015:38:35', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ33', 'Meio Ouest', 'B33', '+ 11:59:38', '- 015:41:03', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ34', 'Meio Est', 'B34', '+ 11:58:40', '- 015:39:37', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ35', 'Maju de Inorei', 'B35', '+ 11:17:54', '- 015:38:33', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ36', 'Galinhas', 'B36', '+ 11:26:24', '- 015:39:29', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ04', 'Rio Amuja', 'B04', '+ 11:04:53', '- 015:57:20', NULL, 52, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ05', 'Rio Amuja 2', 'B05', '+ 11:05:38', '- 015:56:00', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ06', 'Rio Ancabenga Riname', 'B06', '+ 11:03:38', '- 016:04:48', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ07', 'Sud Adanga', 'B07', '+ 11:00:59', '- 016:02:01', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ08', 'Ancopado', 'B08', '+ 11:01:12', '- 016:08:00', NULL, 52, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BIJ09', 'Ponta Amudo', 'B09', '+ 11:04:02', '- 016:14:17', NULL, 52, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('DAN35', 'Dangara embouchure', 'D00', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, 0);
INSERT INTO exp_station VALUES ('DAN36', 'Dangara Sud bordure', 'D04', NULL, NULL, NULL, 42, 2, NULL, NULL, NULL, 4);
INSERT INTO exp_station VALUES ('DAN37', 'Dangara Sud', 'D04', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, 4);
INSERT INTO exp_station VALUES ('DAN40', 'Dangara Centre bord', 'D11', NULL, NULL, NULL, 42, 2, NULL, NULL, NULL, 11);
INSERT INTO exp_station VALUES ('DAN41', 'Dangara Centre', 'D11', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, 11);
INSERT INTO exp_station VALUES ('DAN43', 'Dangara 43', 'D14', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, 14);
INSERT INTO exp_station VALUES ('DAN44', 'Dangara Nord bordure', 'D18', NULL, NULL, NULL, 42, 2, NULL, NULL, NULL, 18);
INSERT INTO exp_station VALUES ('DAN45', 'Dangara Nord', 'D18', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, 18);
INSERT INTO exp_station VALUES ('DAN46', 'Dangara en mer', 'D-4', NULL, NULL, NULL, 42, 1, NULL, NULL, NULL, -4);
INSERT INTO exp_station VALUES ('EB101', 'Mbato Abouk�', '101', '+ 05:21:44', '- 003:47:24', 'Bordure', 2, 2, NULL, NULL, 'vd', 47);
INSERT INTO exp_station VALUES ('EB102', 'Potou', '102', '+ 05:22:17', '- 003:46:32', 'Bordure, Nord Ouest du Bac Abouak�', 2, 2, NULL, NULL, 'sa', 47);
INSERT INTO exp_station VALUES ('EB103', 'Potou Sud', '103', '+ 05:20:32', '- 003:45:92', 'Bordure, D�bouch� venant du Secteur II', 2, 2, NULL, NULL, 'vm', 45);
INSERT INTO exp_station VALUES ('EB104', 'Potou Nord', '104', '+ 05:21:89', '- 003:48:17', 'Chenal, D�bouch� venant d Aghien', 2, 1, NULL, NULL, NULL, 48);
INSERT INTO exp_station VALUES ('EB111', 'Aghien Sud-Ouest', '111', '+ 05:23:99', '- 003:50:22', 'Chenal', 2, 1, NULL, NULL, 'sa', 53);
INSERT INTO exp_station VALUES ('EB112', 'Aghien Bac', '112', '+ 05:24:81', '- 003:51:55', 'Bordure c�t� Nord du bac d Aghien', 2, 2, NULL, NULL, 'sa', 55);
INSERT INTO exp_station VALUES ('EB113', 'Baie Akandj�', '113', '+ 05:24:79', '- 003:53:28', 'Bordure fond de baie', 2, 2, NULL, 'o', 'vv', 58);
INSERT INTO exp_station VALUES ('EB114', 'Face Akandj�', '114', '+ 05:25:23', '- 003:53:30', 'Chenal', 2, 1, NULL, NULL, 'sa', 58);
INSERT INTO exp_station VALUES ('EB115', 'Aghien Nord Ouest', '115', '+ 05:25:93', '- 003:54:86', 'Chenal', 2, 1, NULL, NULL, 'vm', 60);
INSERT INTO exp_station VALUES ('EB116', 'Aghien Nord', '116', '+ 05:25:86', '- 003:54:38', 'Bordure', 2, 2, NULL, NULL, 'sa', 60);
INSERT INTO exp_station VALUES ('EB117', 'Baie Aghien', '117', '+ 05:23:71', '- 003:51:53', 'Bordure fond de baie', 2, 2, NULL, NULL, 'vm', 55);
INSERT INTO exp_station VALUES ('EB118', 'Aghien Centre', '118', '+ 05:24:31', '- 003:51:35', NULL, 2, 1, NULL, NULL, NULL, 55);
INSERT INTO exp_station VALUES ('EB201', 'Ile D�sir�e', '201', '+ 05:19:14', '- 003:54:93', 'Bordure', 3, 2, NULL, NULL, 'vm', 21);
INSERT INTO exp_station VALUES ('EB202', 'Centre Secteur II', '202', '+ 05:18:27', '- 003:54:49', 'Chenal central Est Ile D�sir�e', 3, 1, NULL, NULL, NULL, 22);
INSERT INTO exp_station VALUES ('EB203', 'Passe Abou Abou', '203', '+ 05:17:34', '- 003:54:24', 'Bordure', 3, 2, NULL, 'o', 'vv', 22);
INSERT INTO exp_station VALUES ('EB204', 'Baie Abou Abou', '204', '+ 05:17:07', '- 003:53:04', 'Bordure Fond de baie', 3, 2, NULL, 'o', 'vm', 25);
INSERT INTO exp_station VALUES ('EB205', 'Baie Bingerville', '205', '+ 05:20:62', '- 003:54:21', 'Bordure, Deuxi�me petite baie � gauche en entrant', 3, 2, NULL, 'o', 'sa', 25);
INSERT INTO exp_station VALUES ('EB206', 'Agban', '206', '+ 05:18:05', '- 003:51:61', 'Chenal face � la baie d Agban', 3, 1, NULL, NULL, 'sa', 25);
INSERT INTO exp_station VALUES ('EB207', 'Baie Agban', '207', '+ 05:19:38', '- 003:51:22', 'Bordure fond de baie', 3, 2, NULL, 'o', 'vv', 27);
INSERT INTO exp_station VALUES ('EB208', 'Bregbo', '208', '+ 05:17:92', '- 003:48:92', 'Entr�e de baie', 3, 2, NULL, NULL, 'sa', 30);
INSERT INTO exp_station VALUES ('EB210', 'Baie Vitr�', '210', '+ 05:16:35', '- 003:47:78', 'Bordure fond de baie, rive sud de Vitr�', 3, 2, NULL, 'c', 'vv', 32);
INSERT INTO exp_station VALUES ('EB211', 'Station 211', '211', '+ 05:16:56', '- 003:43:96', NULL, 3, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB212', 'Ile Vitr�', '212', '+ 05:14:80', '- 003:43:49', 'Bordure Est Ile Vitr�', 3, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB213', 'Bas Como�', '213', '+ 05:13:20', '- 003:42:79', 'Chenal dans le fleuve sous fils �lectriques', 3, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB214', 'Nord Ile Vitr�', '214', '+ 05:16:52', '- 003:46:59', 'Chenal', 3, 1, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('EB215', 'Baie Sud Face Bregbo', '215', '+ 05:17:06', '- 003:49:19', 'Bordure rive sud', 3, 2, NULL, NULL, 'vm', 29);
INSERT INTO exp_station VALUES ('EB216', 'Station 216', '216', '+ 05:16:79', '- 003:51:74', NULL, 3, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB217', 'Bordure Rive Sud', '217', '+ 05:16:86', '- 003:51:42', 'Bordure Fond de baie rive Sud', 3, 2, NULL, 'o', 'vd', 28);
INSERT INTO exp_station VALUES ('EB251', 'Station 251', '251', NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB252', 'Station 252', '252', NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB301', 'Vridi', '301', '+ 05:16:11', '- 004:01:93', 'Chenal face au campement', 4, 1, NULL, 'a', 'sa', 6);
INSERT INTO exp_station VALUES ('EB302', 'Cimeti�re bateaux', '302', '+ 05:16:14', '- 004:03:67', 'Chenal', 4, 1, NULL, NULL, 'sa', 8);
INSERT INTO exp_station VALUES ('EB303', 'Baie milliardaires', '303', '+ 05:15:97', '- 004:04:55', 'Bordure', 4, 2, NULL, NULL, 'va', 11);
INSERT INTO exp_station VALUES ('EB304', 'Ile Boulay Sud', '304', '+ 05:16:27', '- 004:05:28', 'Bordure', 4, 2, NULL, NULL, 'vv', 11);
INSERT INTO exp_station VALUES ('EB305', 'Station 305', '305', '+ 05:16:11', '- 004:05:92', 'Bordure 400m apr�s arriv�e sud du bac', 4, 2, NULL, NULL, 'vm', 12);
INSERT INTO exp_station VALUES ('EB311', 'Ile Boulay Nord', '311', '+ 05:17:49', '- 004:01:74', 'Bordure 2eme baie au nord de l ile', 4, 2, NULL, NULL, NULL, 9);
INSERT INTO exp_station VALUES ('EB312', 'Yopougon', '312', '+ 05:18:26', '- 004:03:86', 'Bordure baie de Yopongon Kantao', 4, 2, NULL, NULL, NULL, 12);
INSERT INTO exp_station VALUES ('EB313', 'Ile aux Serpents', '313', '+ 05:17:42', '- 004:06:68', 'Chenal face baie Adiopodoum�', 4, 1, NULL, NULL, NULL, 16);
INSERT INTO exp_station VALUES ('EB314', 'Adiopodoum�', '314', '+ 05:19:08', '- 004:07:32', 'Chenal centre de baie', 4, 1, NULL, NULL, NULL, 19);
INSERT INTO exp_station VALUES ('EB315', 'Bimbresso', '315', '+ 05:18:48', '- 004:09:51', 'Chenal centre de baie', 4, 1, NULL, 'c', 'vv', 21);
INSERT INTO exp_station VALUES ('EB351', 'Cocody Shell', '351', '+ 05:19:22', '- 004:00:48', 'Bordure', 4, 2, NULL, NULL, 'vd', NULL);
INSERT INTO exp_station VALUES ('EB352', 'Cocody Stade', '352', '+ 05:19:67', '- 004:00:83', 'Bordure', 4, 2, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB354', 'Cocody Bidet', '354', '+ 05:19:87', '- 004:00:68', 'Bordure', 4, 2, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB355', 'Cocody Le Relais', '355', '+ 05:19:53', '- 004:00:44', 'Bordure', 4, 2, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB356', 'Cocody Centre', '356', '+ 05:19:56', '- 004:00:65', 'Chenal', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB371', 'Sud Boulay 371', '371', NULL, NULL, 'Chalut Sud Boulay Station 371 Pointe Sud Boulay', 4, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB372', 'Sud Boulay 372', '372', NULL, NULL, 'Chalut Sud Boulay Station 372 Pointe Boulay apr�s le cimeti�re de bateaux', 4, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB373', 'Sud Boulay 373', '373', NULL, NULL, 'Chalut Sud Boulay Station 373 dans la baie', 4, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB374', 'Sud Boulay 374', '374', NULL, NULL, 'Chalut Sud Boulay Station 374 Ile de Boulay au SW (apr�s 4�05''W)', 4, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB375', 'Sud Boulay 375', '375', NULL, NULL, 'Chalut Sud Boulay Station 375', 4, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB391', 'Nord Boulay 391', '391', NULL, NULL, 'Chalut Nord Boulay Station 391', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB392', 'Nord Boulay 392', '392', NULL, NULL, 'Chalut Nord Boulay Station 392', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB393', 'Nord Boulay 393', '393', NULL, NULL, 'Chalut Nord Boulay Station 393', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB394', 'Nord Boulay 394', '394', NULL, NULL, 'Chalut Nord Boulay Station 394', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB395', 'Nord Boulay 395', '395', NULL, NULL, 'Chalut Nord Boulay Station 395', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB396', 'Nord Boulay 396', '396', NULL, NULL, 'Chalut Nord Boulay Station 396', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB397', 'Nord Boulay 397', '397', NULL, NULL, 'Chalut Nord Boulay Station 397', 4, 1, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('EB401', 'Layo', '401', '+ 05:19:23', '- 004:18:93', 'Bordure face aux enclos', 5, 2, NULL, NULL, 'al', 47);
INSERT INTO exp_station VALUES ('EB402', 'Agneby', '402', '+ 05:17:37', '- 004:19:60', 'Bordure face embouchure pr�s ile Leydet', 5, 2, NULL, NULL, 'sa', 48);
INSERT INTO exp_station VALUES ('EB403', 'Est Agneby', '403', '+ 05:17:85', '- 004:19:84', 'Bordure', 5, 2, NULL, NULL, 'vv', 47);
INSERT INTO exp_station VALUES ('EB404', 'Ouest Agneby', '404', '+ 05:16:92', '- 004:20:41', 'Bordure pointe Assaba', 5, 2, NULL, NULL, 'vm', 49);
INSERT INTO exp_station VALUES ('EB405', 'Ile Leydet', '405', '+ 05:16:90', '- 004:19:06', 'Bordure Sud-Est Ile', 5, 2, NULL, 'c', 'pc', 47);
INSERT INTO exp_station VALUES ('EB406', 'Avagou Tabot', '406', '+ 05:15:36', '- 004:20:48', 'Bordure Baie � l ouest du village', 5, 2, NULL, 'c', 'vm', 49);
INSERT INTO exp_station VALUES ('EB407', 'Centre Secteur IV', '407', '+ 05:17:26', '- 004:17:32', 'Chenal', 5, 1, NULL, 'c', 'vd', 42);
INSERT INTO exp_station VALUES ('EB408', 'Sud Secteur IV', '408', '+ 05:15:53', '- 004:16:70', 'Bordure', 5, 2, NULL, 'c', 'vd', 41);
INSERT INTO exp_station VALUES ('EB409', 'Songon Agban', '409', '+ 05:18:29', '- 004:15:14', 'Bordure', 5, 2, NULL, NULL, 'vd', 41);
INSERT INTO exp_station VALUES ('EB410', 'Ouest Jacqueville', '410', '+ 05:17:06', '- 004:14:42', 'Ouest digue', 5, 2, NULL, 'c', 'vm', 38);
INSERT INTO exp_station VALUES ('EB411', 'Est Jacqueville', '411', '+ 05:17:17', '- 004:13:99', 'Bordure Est digue', 5, 2, NULL, NULL, 'vv', 35);
INSERT INTO exp_station VALUES ('EB412', 'Godoum�', '412', '+ 05:18:28', '- 004:10:79', 'Bordure � 300m du village', 5, 2, NULL, NULL, 'sa', 29);
INSERT INTO exp_station VALUES ('EB501', 'Baie Dabou', '501', '+ 05:17:76', '- 004:23:52', NULL, 6, 1, NULL, NULL, 'al', 59);
INSERT INTO exp_station VALUES ('EB502', 'Mopoyem', '502', '+ 05:16:44', '- 004:27:06', 'Bordure, 1ere baie a gauche en entrant', 6, 2, NULL, NULL, NULL, 56);
INSERT INTO exp_station VALUES ('EB503', 'Baie Atoutou', '503', '+ 05:11:94', '- 004:33:75', 'Bordure', 6, 2, NULL, NULL, 'vm', 65);
INSERT INTO exp_station VALUES ('EB504', 'Nigui Assoko', '504', '+ 05:14:88', '- 004:34:75', 'Chenal', 6, 1, NULL, NULL, 'pc', 62);
INSERT INTO exp_station VALUES ('EB505', 'Nigui Nanon', '505', '+ 05:15:42', '- 004:35:71', 'Bordure Fond de Baie', 6, 2, NULL, NULL, 'vm', 63);
INSERT INTO exp_station VALUES ('EB601', 'Tefredji', '601', '+ 05:12:56', '- 004:41:28', 'Bordure', 7, 2, NULL, NULL, NULL, 68);
INSERT INTO exp_station VALUES ('EB602', 'Tiagba', '602', '+ 05:15:42', '- 004:43:52', 'Chenal face Tiagba', 7, 1, NULL, 'c', 'sa', 71);
INSERT INTO exp_station VALUES ('EB603', 'Baie Tiagba', '603', '+ 05:15:81', '- 004:40:43', 'Bordure Milieu de baie', 7, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('EB604', 'Centre Secteur VI', '604', '+ 05:13:42', '- 004:42:47', 'Chenal', 7, 1, NULL, NULL, 'vm', 69);
INSERT INTO exp_station VALUES ('EB605', 'Taboutou Sud', '605', '+ 05:11:71', '- 004:44:84', 'Bordure', 7, 2, NULL, NULL, 'al', 72);
INSERT INTO exp_station VALUES ('EB606', 'Face Taboutou', '606', '+ 05:11:87', '- 004:45:59', 'Chenal', 7, 1, NULL, NULL, 'vv', 72);
INSERT INTO exp_station VALUES ('F4001', 'Station 1', 'F52', NULL, NULL, NULL, 46, 2, NULL, NULL, NULL, 52);
INSERT INTO exp_station VALUES ('F3SAM', 'Samasir�', 'F39', NULL, NULL, NULL, 45, 1, NULL, NULL, NULL, 39);
INSERT INTO exp_station VALUES ('F3011', 'Station 11', 'F36', NULL, NULL, NULL, 45, 2, NULL, NULL, NULL, 36);
INSERT INTO exp_station VALUES ('F3012', 'Station 12', 'F36', NULL, NULL, NULL, 45, 1, NULL, NULL, NULL, 36);
INSERT INTO exp_station VALUES ('F3KTB', 'Kamatambaya bordure', 'F33', NULL, NULL, NULL, 45, 2, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F3KTM', 'Kamatambaya', 'F33', NULL, NULL, NULL, 45, 1, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F3016', 'Station 16', 'F29', NULL, NULL, NULL, 45, 1, NULL, NULL, NULL, 29);
INSERT INTO exp_station VALUES ('F3MRB', 'M�rou bordure', 'F24', NULL, NULL, NULL, 45, 2, NULL, NULL, NULL, 24);
INSERT INTO exp_station VALUES ('F3MRM', 'M�rou', 'F24', NULL, NULL, NULL, 45, 1, NULL, NULL, NULL, 24);
INSERT INTO exp_station VALUES ('F4002', 'Station 2', 'F52', NULL, NULL, NULL, 46, 1, NULL, NULL, NULL, 52);
INSERT INTO exp_station VALUES ('F2020', 'Station 20', 'F20', NULL, NULL, NULL, 44, 1, NULL, NULL, NULL, 20);
INSERT INTO exp_station VALUES ('F2BFB', 'Boffa bordure', 'F17', NULL, NULL, NULL, 44, 2, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F2BFM', 'Boffa', 'F17', NULL, NULL, NULL, 44, 1, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F2024', 'Station 24', 'F13', NULL, NULL, NULL, 44, 1, NULL, NULL, NULL, 13);
INSERT INTO exp_station VALUES ('F2IFB', 'Ile Fatala Nord bord', 'F10', NULL, NULL, NULL, 44, 2, NULL, NULL, NULL, 10);
INSERT INTO exp_station VALUES ('F2IFM', 'Ile Fatala Nord', 'F10', NULL, NULL, NULL, 44, 1, NULL, NULL, NULL, 10);
INSERT INTO exp_station VALUES ('F1028', 'Station 28', 'F06', NULL, NULL, NULL, 43, 1, NULL, NULL, NULL, 6);
INSERT INTO exp_station VALUES ('F1CNB', 'Conakrydi bordure', 'F03', NULL, NULL, NULL, 43, 2, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F1CNM', 'Conakrydi', 'F03', NULL, NULL, NULL, 43, 1, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F1EMB', 'Marara embouchure', 'F00', NULL, NULL, NULL, 43, 1, NULL, NULL, NULL, 0);
INSERT INTO exp_station VALUES ('F1MER', 'En mer', 'F-4', NULL, NULL, NULL, 43, 1, NULL, NULL, NULL, -4);
INSERT INTO exp_station VALUES ('F4004', 'Station 4', 'F49', NULL, NULL, NULL, 46, 1, NULL, NULL, NULL, 49);
INSERT INTO exp_station VALUES ('F4TA1', 'Tahir� FM 1', 'F46', NULL, NULL, 'Tahir� premi�re batterie de filets', 46, 2, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F4TA2', 'Tahir� FM 2', 'F46', NULL, NULL, 'Tahir� deuxi�me batterie de filets', 46, 2, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F4TA3', 'Tahir� FM 3', 'F46', NULL, NULL, 'Tahir� troisi�me batterie de filets', 46, 2, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F4TA4', 'Tahir� FM 4', 'F46', NULL, NULL, 'Tahir� quatri�me batterie de filets', 46, 2, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F4TAB', 'Tahir� bordure', 'F46', NULL, NULL, NULL, 46, 2, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F3KT1', 'Kamatambaya FM 1', 'F33', NULL, NULL, 'Kamatambaya premi�re batterie de filets', 45, 2, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F3KT2', 'Kamatambaya FM 2', 'F33', NULL, NULL, 'Kamatambaya deuxi�me batterie de filets', 45, 2, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F3KT3', 'Kamatambaya FM 3', 'F33', NULL, NULL, 'Kamatambaya troisi�me batterie de filets', 45, 2, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F3KT4', 'Kamatambaya FM 4', 'F33', NULL, NULL, 'Kamatambaya quatri�me batterie de filets', 45, 2, NULL, NULL, NULL, 33);
INSERT INTO exp_station VALUES ('F4TAM', 'Tahir�', 'F46', NULL, NULL, NULL, 46, 1, NULL, NULL, NULL, 46);
INSERT INTO exp_station VALUES ('F2BF1', 'Boffa FM 1', 'F17', NULL, NULL, 'Boffa premi�re batterie de filets', 44, 2, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F2BF2', 'Boffa FM 2', 'F17', NULL, NULL, 'Boffa deuxi�me batterie de filets', 44, 2, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F2BF3', 'Boffa FM 3', 'F17', NULL, NULL, 'Boffa troisi�me batterie de filets', 44, 2, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F2BF4', 'Boffa FM 4', 'F17', NULL, NULL, 'Boffa quatri�me batterie de filets', 44, 2, NULL, NULL, NULL, 17);
INSERT INTO exp_station VALUES ('F4007', 'Station 7', 'F43', NULL, NULL, NULL, 46, 2, NULL, NULL, NULL, 43);
INSERT INTO exp_station VALUES ('F1CN1', 'Conakrydi FM 1', 'F03', NULL, NULL, 'Conakrydi premi�re batterie de filets', 43, 2, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F1CN2', 'Conakrydi FM 2', 'F03', NULL, NULL, 'Conakrydi deuxi�me batterie de filets', 43, 2, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F1CN3', 'Conakrydi FM 3', 'F03', NULL, NULL, 'Conakrydi troisi�me batterie de filets', 43, 2, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F1CN4', 'Conakrydi FM 4', 'F03', NULL, NULL, 'Conakrydi quatri�me batterie de filets', 43, 2, NULL, NULL, NULL, 3);
INSERT INTO exp_station VALUES ('F4008', 'Station 8', 'F43', NULL, NULL, NULL, 46, 1, NULL, NULL, NULL, 43);
INSERT INTO exp_station VALUES ('F3SAB', 'Samasir� bordure', 'F39', NULL, NULL, NULL, 45, 2, NULL, NULL, NULL, 39);
INSERT INTO exp_station VALUES ('RB001', 'Rio Djindica', 'R01', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB010', 'Rio Fulacunda', 'R10', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB101', 'Rio Djindica bordure', 'R01', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB011', 'Rio Lenguete', 'R11', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB110', 'Rio Fulacunda bord.', 'R10', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB012', 'Buduco/Fulacunda', 'R12', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB013', 'Ponta Camputo', 'R13', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB014', 'Rio Tarna', 'R14', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB015', 'Rio Debala', 'R15', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB016', 'Canal de Buba', 'R16', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB017', 'Rio Canchaua', 'R17', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB018', 'Rio Ga-Maior', 'R18', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB019', 'Rio Colonia', 'R19', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB002', 'Rio Jassonca', 'R02', NULL, NULL, NULL, 53, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB020', 'Rio Bambaia', 'R20', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB021', 'Ponta Cangenia', 'R21', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB210', 'Rio Fulacunda bolon', 'R10', NULL, NULL, NULL, 53, 3, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB003', 'Dumbali', 'R03', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB004', 'Dumbali 2', 'R04', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB005', 'Rio Cambufula', 'R05', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB006', 'Rio Faracunda entr�e', 'R06', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB007', 'Rio Faracunda', 'R07', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB008', 'Rio Bissesse', 'R08', NULL, NULL, NULL, 53, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('RB009', 'Rio Buduco', 'R09', NULL, NULL, NULL, 53, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S1BNM', 'Bandiala', 'S01', '+ 13:39:08', '- 016:34:29', 'Embouchure Bandiala - Relev� GPS JJA Dec 2001- sable coquillier - oursins', 26, 1, 'd', 'c', 'sa', 3);
INSERT INTO exp_station VALUES ('S4NDO', 'Ndoffane', 'S10', NULL, NULL, NULL, 29, 1, 'r', NULL, NULL, 4);
INSERT INTO exp_station VALUES ('S1BNB', 'Bandiala bordure', 'S01', '+ 13:39:20', '- 016:34:45', 'Relev� GPS JJA Dec 2001 - Sable vaseux + coquilles + oursins', 26, 2, 'd', 'c', 'sv', 3);
INSERT INTO exp_station VALUES ('S1CAB', 'Cathior bordure', 'S02', NULL, NULL, NULL, 26, 2, 'd', NULL, NULL, 9);
INSERT INTO exp_station VALUES ('S2TBB', 'Toubakouta bordure', 'S04', '+ 13:45:85', '- 016:29:57', NULL, 27, 2, 'd', 'e', 'sa', 22);
INSERT INTO exp_station VALUES ('S2SKB', 'Soukouta bordure', 'S05', NULL, NULL, NULL, 27, 2, 'd', NULL, NULL, 27);
INSERT INTO exp_station VALUES ('S3NDB', 'Ndioundiou bordure', 'S06', '+ 13:50:57', '- 016:29:45', 'Relev� GPS JJA Dec 2001', 28, 2, 'd', 'c', 'sa', 30);
INSERT INTO exp_station VALUES ('S4DMB', 'Diama bordure', 'S07', NULL, NULL, NULL, 29, 2, 'd', NULL, NULL, 18);
INSERT INTO exp_station VALUES ('S4NRB', 'Ndiorene bordure', 'S08', NULL, NULL, NULL, 29, 2, 'd', NULL, NULL, 12);
INSERT INTO exp_station VALUES ('S4BAB', 'Babandiane bordure', 'S09', '+ 13:48:41', '- 016:36:28', 'Relev� GPS JJA Dec 2001', 29, 2, 'r', NULL, 'sa', 8);
INSERT INTO exp_station VALUES ('S7FAM', 'Fadoum', 'S11', '+ 14:09:04', '- 016:26:68', 'Fadoum - proche bou�e rouge n�40', 32, 1, 'a', NULL, 'sa', 48);
INSERT INTO exp_station VALUES ('S7FAB', 'Fadoum bordure', 'S11', '+ 14:09:29', '- 016:26:99', NULL, 32, 2, 'a', NULL, 'sv', 48);
INSERT INTO exp_station VALUES ('S7FDR', 'Foundiougne bordure', 'S12', NULL, NULL, NULL, 32, 2, 'a', NULL, NULL, 45);
INSERT INTO exp_station VALUES ('S7SOB', 'Soum bordure', 'S13', NULL, NULL, NULL, 32, 2, 'r', NULL, NULL, 39);
INSERT INTO exp_station VALUES ('S6NSB', 'Ndimsiro bordure', 'S15', '+ 14:01:14', '- 016:35:56', NULL, 31, 2, 'r', 'c', 'sa', 29);
INSERT INTO exp_station VALUES ('S6AMB', 'A mboye bordure', 'S16', '+ 13:59:55', '- 016:40:16', 'Fond poto-poto', 31, 2, 'd', 'c', 'vm', 19);
INSERT INTO exp_station VALUES ('S5FFB', 'Fafanda bordure', 'S17', NULL, NULL, NULL, 30, 2, 'd', NULL, NULL, 14);
INSERT INTO exp_station VALUES ('S5DHB', 'Djanhanor bordure', 'S18', NULL, NULL, NULL, 30, 2, 'a', NULL, NULL, 6);
INSERT INTO exp_station VALUES ('S5DJB', 'Djifere bordure', 'S19', '+ 13:57:01', '- 016:45:64', NULL, 30, 2, 'a', 'c', 'sa', 1);
INSERT INTO exp_station VALUES ('S7FDM', 'Foundiougne', 'S12', '+ 14:08:16', '- 016:28:98', 'Position GPS Saloum 34 JDD Mars 2004', 32, 1, 'a', NULL, NULL, 45);
INSERT INTO exp_station VALUES ('S8BEB', 'Bane bordure', 'S26', NULL, NULL, NULL, 33, 2, 'a', NULL, NULL, 85);
INSERT INTO exp_station VALUES ('S8GAB', 'Gagu� bordure', 'S27', '+ 14:08:58', '- 016:23:67', 'S31 -Gagu� bordure (Proche bou�e 53 bis)', 33, 2, 'a', NULL, 'sv', 54);
INSERT INTO exp_station VALUES ('S8SAB', 'Sassara bordure', 'S28', '+ 14:10:45', '- 016:16:65', NULL, 33, 2, 'a', NULL, 'sv', 75);
INSERT INTO exp_station VALUES ('S8LYB', 'Lyndiane bordure', 'S29', '+ 14:09:72', '- 016:09:50', 'S31 - Le chenal passe en bordure. Station plus "p�lagique" que Lyndiane milieu (LYM)', 33, 2, 'a', NULL, 'sa', 98);
INSERT INTO exp_station VALUES ('S7SOM', 'Soum', 'S13', '+ 14:05:38', '- 016:30:30', NULL, 32, 1, 'r', NULL, NULL, 39);
INSERT INTO exp_station VALUES ('S8KLB', 'Kaolack bordure', 'S30', NULL, NULL, NULL, 33, 2, 'a', NULL, NULL, 110);
INSERT INTO exp_station VALUES ('S8BRB', 'Ben Rone bordure', 'S31', '+ 14:07:00', '- 016:06:66', NULL, 33, 2, 'a', NULL, 'sa', 107);
INSERT INTO exp_station VALUES ('S7BBA', 'Baout', 'S14', NULL, NULL, NULL, 32, 1, 'r', NULL, NULL, 35);
INSERT INTO exp_station VALUES ('S6NSM', 'Ndimsiro', 'S15', '+ 14:01:16', '- 016:35:68', NULL, 31, 1, 'r', NULL, 'sv', 29);
INSERT INTO exp_station VALUES ('S6AMM', 'A mboye Kh. Samb', 'S16', '+ 13:59:72', '- 016:40:01', NULL, 31, 1, 'd', NULL, 'sa', 19);
INSERT INTO exp_station VALUES ('S7GUB', 'Guifoda bordure', 'S65', '+ 14:07:16', '- 016:30:12', 'S31 - Nouvelle station', 32, 2, 'r', 'c', 'sa', 39);
INSERT INTO exp_station VALUES ('S5FFM', 'Fafanda', 'S17', NULL, NULL, NULL, 30, 1, 'd', NULL, NULL, 14);
INSERT INTO exp_station VALUES ('S5DHM', 'Djahanor', 'S18', NULL, NULL, NULL, 30, 1, 'a', NULL, NULL, 6);
INSERT INTO exp_station VALUES ('S5DJM', 'Djifere', 'S19', '+ 13:56:69', '- 016:45:36', 'Erreur sur position GPS S31(meme position que station 16). On a pris la position du Diassanga', 30, 1, 'a', 'c', 'sa', 1);
INSERT INTO exp_station VALUES ('S1CAM', 'Cathior', 'S02', NULL, NULL, NULL, 26, 1, 'd', NULL, NULL, 9);
INSERT INTO exp_station VALUES ('S8FND', 'Fayako Niamdiarokh', 'S20', NULL, NULL, NULL, 33, 1, 'a', NULL, NULL, 55);
INSERT INTO exp_station VALUES ('S4GUL', 'Guilor', 'S21', NULL, NULL, NULL, 29, 1, 'd', NULL, NULL, 28);
INSERT INTO exp_station VALUES ('S2KK2', 'Kalalen kolale ent.', 'S22', NULL, NULL, NULL, 27, 1, 'd', NULL, NULL, 15);
INSERT INTO exp_station VALUES ('S2KK1', 'Kalalen kolale car.', 'S23', NULL, NULL, NULL, 27, 1, 'd', NULL, NULL, 15);
INSERT INTO exp_station VALUES ('S2BDI', 'Bolon dioto', 'S24', '+ 13:43:80', '- 016:30:23', NULL, 27, 1, 'd', 'e', 'sa', 15);
INSERT INTO exp_station VALUES ('S2KKF', 'Kalalen kolale f.mal', 'S25', NULL, NULL, NULL, 27, 1, 'd', NULL, NULL, 15);
INSERT INTO exp_station VALUES ('S8BST', 'Bane soutouta', 'S26', NULL, NULL, NULL, 33, 1, 'a', NULL, NULL, 85);
INSERT INTO exp_station VALUES ('S8SAM', 'Sassara', 'S28', '+ 14:10:39', '- 016:16:66', 'Proche bou�e n�83 verte', 33, 1, 'a', NULL, 'sv', 75);
INSERT INTO exp_station VALUES ('S8LYM', 'Lyndiane', 'S29', '+ 14:09:63', '- 016:09:57', 'S31 - Le chenal passe en bordure', 33, 1, 'a', NULL, 'sa', 98);
INSERT INTO exp_station VALUES ('S1NGF', 'Ndangane fali', 'S03', NULL, NULL, NULL, 26, 1, 'd', NULL, NULL, 15);
INSERT INTO exp_station VALUES ('S8KLM', 'Kaolack', 'S30', '+ 14:07:10', '- 016:06:25', NULL, 33, 1, 'a', NULL, NULL, 110);
INSERT INTO exp_station VALUES ('S8BRM', 'Ben Rone', 'S31', '+ 14:07:11', '- 016:06:55', NULL, 33, 1, 'a', NULL, 'sa', 107);
INSERT INTO exp_station VALUES ('S1BK2', 'Bolon Djinakh 2', 'S32', '+ 13:38:28', '- 016:31:10', 'mi-bolon', 26, 3, 'd', NULL, NULL, 8);
INSERT INTO exp_station VALUES ('S1BK3', 'Bolon Djinakh 3', 'S33', '+ 13:37:05', '- 016:31:14', 'amont', 26, 3, 'd', NULL, NULL, 5);
INSERT INTO exp_station VALUES ('S2BDA', 'Bolon Dioto Amont', 'S34', '+ 13:43:81', '- 016:30:24', 'position confirm�e par relev� GPS JJA Dec 2001 - bolon pour essais et d�mos senne - se jette dans le Bandiala', 27, 3, 'd', 'o', 'vs', 15);
INSERT INTO exp_station VALUES ('S2PD3', 'Bolon Place Dioto 3', 'S35', '+ 13:43:91', '- 016:30:61', NULL, 27, 3, 'd', NULL, NULL, 16);
INSERT INTO exp_station VALUES ('S3BM2', 'B. Madina Sangako 2', 'S36', '+ 13:50:29', '- 016:28:32', NULL, 28, 3, 'd', NULL, NULL, 31);
INSERT INTO exp_station VALUES ('S3BM3', 'B. Madina Sangako 3', 'S37', '+ 13:50:15', '- 016:27:38', NULL, 28, 3, 'd', NULL, NULL, 33);
INSERT INTO exp_station VALUES ('S4BD2', 'Bolon Diogane 2', 'S38', '+ 13:51:91', '- 016:38:62', NULL, 29, 3, 'd', NULL, NULL, 14);
INSERT INTO exp_station VALUES ('S4BD3', 'Bolon Diogane 3', 'S39', '+ 13:54:13', '- 016:37:25', NULL, 29, 3, 'd', NULL, NULL, 18);
INSERT INTO exp_station VALUES ('MBA05', 'Q 05 Manan. Barrage', 'B05', NULL, NULL, NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S2TBM', 'Toubakouta', 'S04', '+ 13:46:07', '- 016:29:67', 'Relev� GPS JJA Dec 2001', 27, 1, 'd', 'e', 'sa', 22);
INSERT INTO exp_station VALUES ('S6BR2', 'Bolon Djirnda 2', 'S40', '+ 13:57:71', '- 016:37:88', NULL, 31, 3, 'd', NULL, NULL, 27);
INSERT INTO exp_station VALUES ('S6BR3', 'Bolon Djirnda 3', 'S41', '+ 13:57:78', '- 016:38:47', NULL, 31, 3, 'd', NULL, NULL, 29);
INSERT INTO exp_station VALUES ('S5BI2', 'Bolon Itaf 2', 'S42', '+ 13:58:34', '- 016:43:02', NULL, 30, 3, 'd', NULL, NULL, 15);
INSERT INTO exp_station VALUES ('S5BI3', 'Bolon Itaf 3', 'S43', '+ 13:57:94', '- 016:43:06', NULL, 30, 3, 'd', NULL, NULL, 18);
INSERT INTO exp_station VALUES ('S5BN2', 'Bolon Ndangane 2', 'S44', '+ 14:04:33', '- 016:41:78', NULL, 30, 3, 'a', NULL, NULL, 23);
INSERT INTO exp_station VALUES ('S5BN3', 'Bolon Ndangane 3', 'S45', '+ 14:05:70', '- 016:39:29', NULL, 30, 3, 'a', NULL, NULL, 30);
INSERT INTO exp_station VALUES ('S7BB2', 'Bolon Bol 2', 'S46', '+ 14:04:95', '- 016:32:99', NULL, 32, 3, 'r', NULL, NULL, 36);
INSERT INTO exp_station VALUES ('S7BB3', 'Bolon Bol 3', 'S47', '+ 14:05:65', '- 016:32:03', NULL, 32, 3, 'r', NULL, NULL, 37);
INSERT INTO exp_station VALUES ('S7BS2', 'Bolon Sin 2', 'S48', '+ 14:12:44', '- 016:24:92', NULL, 32, 3, 'a', NULL, NULL, 56);
INSERT INTO exp_station VALUES ('S7BS3', 'Bolon Sin 3', 'S49', '+ 14:14:19', '- 016:23:88', NULL, 32, 3, 'a', NULL, NULL, 63);
INSERT INTO exp_station VALUES ('S2SKM', 'Soukouta', 'S05', NULL, NULL, NULL, 27, 1, 'd', NULL, NULL, 27);
INSERT INTO exp_station VALUES ('S7BG2', 'Bolon Guifoda 2', 'S50', '+ 14:07:66', '- 016:31:03', NULL, 32, 3, 'a', NULL, NULL, 46);
INSERT INTO exp_station VALUES ('S7BG3', 'Bolon Guifoda 3', 'S51', '+ 14:08:77', '- 016:32:00', NULL, 32, 3, 'a', NULL, NULL, 50);
INSERT INTO exp_station VALUES ('S5BN4', 'Bolon Ndangane 4', 'S52', '+ 14:07:79', '- 016:38:60', NULL, 30, 3, 'a', NULL, NULL, 40);
INSERT INTO exp_station VALUES ('S3NDM', 'Ndioundiou', 'S06', '+ 13:50:71', '- 016:29:47', 'Confluent Bandiala-Diomboss - Relev� GPS JJA Dec 2001', 28, 1, 'd', 'c', 'sa', 30);
INSERT INTO exp_station VALUES ('S8API', 'Amont Pont Intermed.', 'S61', '+ 14:06:25', '- 016:00:32', 'S31 - Amont Pont Interm�diaire - Petit baobab isol� en rive droite', 33, 1, 'a', NULL, 'sa', 128);
INSERT INTO exp_station VALUES ('S8APE', 'Amont Pont Extr�me', 'S62', '+ 14:06:07', '- 015:58:92', 'S31 - Amont Pont Extr�me', 33, 1, 'a', NULL, 'sa', 131);
INSERT INTO exp_station VALUES ('S8APP', 'Amont Pont Proximal', 'S63', '+ 14:05:61', '- 016:01:61', 'S31 - Amont Pont Proximal - Baobab tordu en rive gauche + cabane', 33, 1, 'a', NULL, 'sa', 125);
INSERT INTO exp_station VALUES ('S8KKE', 'Keur Kekoita', 'S64', '+ 14:09:76', '- 016:15:42', 'S31 - Keur Kekoita - Proche bou�e n�76 rouge', 33, 1, 'a', NULL, 'sv', 78);
INSERT INTO exp_station VALUES ('S4DMM', 'Diama', 'S07', NULL, NULL, NULL, 29, 1, 'd', NULL, NULL, 18);
INSERT INTO exp_station VALUES ('S3B01', 'Bapindo 01', 'S71', '+ 13:50:99', '- 016:29:70', '= Ndioundiou bordure', 28, 2, NULL, 'e', 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B02', 'Bapindo 02', 'S71', '+ 13:50:31', '- 016:29:59', '= Ndioundiou milieu', 28, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B03', 'Bapindo 03', 'S71', '+ 13:50:11', '- 016:28:05', 'Bapindo Amont', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B04', 'Bapindo 04', 'S71', '+ 13:50:86', '- 016:28:44', 'Bapindo interm�diaire', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B05', 'Bapindo 05', 'S71', '+ 13:50:71', '- 016:29:52', 'Confluent Diomboss Bandiala', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B06', 'Bapindo 06', 'S71', '+ 13:50:76', '- 016:29:45', 'Confluent Diomboss Bandiala', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B07', 'Bapindo 07', 'S71', '+ 13:49:36', '- 016:29:76', 'Amont Soukouta', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B08', 'Bapindo 08', 'S71', '+ 13:50:12', '- 016:29:66', 'Amont Soukouta', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B09', 'Bapindo 09', 'S71', '+ 13:49:14', '- 016:28:49', 'Bolon Sangako', 28, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KV1', 'Kaolack Aval 01', 'S72', '+ 14:09:85', '- 016:09:55', '= Lyndiane bordure', 33, 2, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KV2', 'Kaolack Aval 02', 'S72', '+ 14:09:57', '- 016:09:56', '= Lyndiane milieu', 33, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KV3', 'Kaolack Aval 03', 'S72', '+ 14:07:10', '- 016:06:65', '= Ben Rone milieu', 33, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KV4', 'Kaolack Aval 04', 'S72', '+ 14:07:01', '- 016:06:41', '= Ben Rone bordure', 33, 2, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KV5', 'Kaolack Aval 05', 'S72', '+ 14:07:12', '- 016:07:55', 'Mosquee crayon', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KM1', 'Kaolack Amont 01', 'S73', '+ 14:06:25', '- 015:58:80', '= Amont Pont Extreme', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KM2', 'Kaolack Amont 02', 'S73', '+ 14:05:63', '- 016:01:56', '= Amont Pont Intermediaire', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8KM3', 'Kaolack Amont 03', 'S73', '+ 14:06:71', '- 016:03:44', '= Amont Pont Proximal', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8BD1', 'Bada�e 01', 'S74', '+ 14:08:88', '- 016:23:00', '= Gague milieu', 33, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8BD2', 'Bada�e 02', 'S74', '+ 14:08:89', '- 016:23:28', '= Gague bordure', 33, 2, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8BD3', 'Bada�e 03', 'S74', '+ 14:09:88', '- 016:25:21', 'Bada�e face embouchure', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8BD4', 'Bada�e 04', 'S74', '+ 14:10:05', '- 016:25:36', 'Bada�e embouchure', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S8BD5', 'Bada�e 05', 'S74', '+ 14:10:19', '- 016:25:69', 'Bada�e aval embouchure', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7ND1', 'Ndiamniadio 01', 'S75', '+ 14:06:17', '- 016:33:79', 'Faoye Aval', 32, NULL, NULL, 'm', 'sa', NULL);
INSERT INTO exp_station VALUES ('S7ND2', 'Ndiamniadio 02', 'S75', '+ 14:06:54', '- 016:33:68', 'Faoye intermediaire', 32, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7ND3', 'Ndiamniadio 03', 'S75', '+ 14:07:32', '- 016:33:38', 'Faoye Amont', 32, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7ND4', 'Ndiamniadio 04', 'S75', '+ 14:03:18', '- 016:33:94', 'Saloum face Ndiamniadio', 32, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7ND5', 'Ndiamniadio 05', 'S75', '+ 14:04:31', '- 016:33:11', 'Bolon Bourgui (Station Ndiamniadio pour Guy Vidy)', 32, NULL, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('S7ND6', 'Ndiamniadio 06', 'S75', '+ 14:05:23', '- 016:32:84', 'Bolon Bourgui Amont', 32, NULL, NULL, NULL, 'vm', NULL);
INSERT INTO exp_station VALUES ('S7ND7', 'Ndiamniadio 07', 'S75', '+ 14:01:67', '- 016:35:59', '=Ndimsiro bordure (attention : zone 6 et non 7)', 32, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN1', 'Djirnda 01', 'S76', '+ 13:58:31', '- 016:37:57', 'Djirnda Bordure', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN2', 'Djirnda 02', 'S76', '+ 13:57:91', '- 016:37:95', 'Djirnda Milieu', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN3', 'Djirnda 03', 'S76', '+ 13:57:42', '- 016:37:30', 'Bolon oublie Aval', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN4', 'Djirnda 04', 'S76', '+ 13:57:55', '- 016:37:07', 'Bolon oublie Amont', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN5', 'Djirnda 05', 'S76', '+ 13:57:66', '- 016:37:73', 'Bolon Djirnda face bolon de Mounde', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN6', 'Djirnda 06', 'S76', '+ 13:56:87', '- 016:37:94', 'Bolon Tialane "Bus Stop"', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN7', 'Djirnda 07', 'S76', '+ 13:56:47', '- 016:37:34', 'Bolon Djirnda distal', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN8', 'Djirnda 08', 'S76', '+ 13:56:71', '- 016:35:81', 'Bolon de Gadior', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S6DN9', 'Djirnda 09', 'S76', '+ 13:57:06', '- 016:35:36', 'Bolon de Gadior Amont', 31, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S3B10', 'Bapindo 10', 'S71', '+ 13:50:40', '- 016:27:72', 'Mai 2007 - Bapindo pour comparaison Bamboung', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3B11', 'Bapindo 11', 'S71', '+ 13:50:19', '- 016:27:97', 'Mai 2007 - Bapindo pour comparaison Bamboung', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3B12', 'Bapindo 12', 'S71', '+ 13:50:30', '- 016:28:10', 'Mai 2007 - Bapindo pour comparaison Bamboung', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3B13', 'Bapindo 13', 'S71', '+ 13:50:54', '- 016:27:66', 'Mai 2007 - Bapindo pour comparaison Bamboung', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3B14', 'Bapindo 14', 'S71', '+ 13:50:17', '- 016:27:39', 'Mai 2007 - Bapindo pour comparaison Bamboung', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BS1', 'Bolon Sangako 01', 'S78', '+ 13:51:37', '- 016:27:13', 'Oct 2007 : Bolon Sangako pour comparaison BBG', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BS2', 'Bolon Sangako 02', 'S78', '+ 13:51:35', '- 016:26:02', 'Oct 2007 : Bolon Sangako pour comparaison BBG', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BS3', 'Bolon Sangako 03', 'S78', '+ 13:50:87', '- 016:25:47', 'Oct 2007 : Bolon Sangako pour comparaison BBG', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BS4', 'Bolon Sangako 04', 'S78', '+ 13:50:02', '- 016:24:85', 'Oct 2007 : Bolon Sangako pour comparaison BBG', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BS5', 'Bolon Sangako 05', 'S78', '+ 13:50:08', '- 016:24:25', 'Oct 2007 : Bolon Sangako pour comparaison BBG', 28, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S4NRM', 'Ndiorene', 'S08', NULL, NULL, NULL, 29, 1, 'd', NULL, NULL, 12);
INSERT INTO exp_station VALUES ('S6VGA', 'Velingara', 'S81', '+ 14:03:20', '- 016:34:01', 'Station JDD Saloum 34 - coup 2', 31, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S7FA1', 'Foundiougne Aval 1', 'S82', '+ 14:07:62', '- 016:29:57', 'Station JDD Saloum 34 - coup 3', 32, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S8SIB', 'Sibassor', 'S83', '+ 14:08:71', '- 016:11:74', 'Station JDD Saloum 34 - coup 5', 33, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7FA2', 'Foundiougne Aval 2', 'S84', '+ 14:07:72', '- 016:29:02', 'Station JDD Saloum 34 - coup 7', 32, NULL, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('S7FEL', 'Felir', 'S85', '+ 14:09:30', '- 016:29:69', 'Station JDD Saloum 34 - coups 8 et 9', 32, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S2BDE', 'Bolon Dioto entr�e', 'S86', '+ 13:43:14', '- 016:30:38', 'Station JDD Saloum 34 - coups 11 et 12', 27, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S1MIS', 'Missirah', 'S87', '+ 13:40:98', '- 016:31:81', 'Station JDD Saloum 35', 26, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S4BAM', 'Babandiane', 'S09', '+ 13:48:10', '- 016:35:96', 'Relev� GPS JJA Dec 2001', 29, 1, 'r', NULL, 'sa', 8);
INSERT INTO exp_station VALUES ('S7BAO', 'Baout', 'BAO', '+ 14:02:43', '- 016:31:56', 'VDG - Baout', 32, 2, 'd', NULL, 'vv', 35);
INSERT INTO exp_station VALUES ('S2DTO', 'Dioto', 'DTO', '+ 13:43:49', '- 016:30:20', 'VDG - Place Dioto', 27, 2, 'd', NULL, 'vv', 23);
INSERT INTO exp_station VALUES ('S4GUI', 'Guilor', 'GUI', '+ 13:57:78', '- 016:27:86', 'VDG - Guilor', 29, 2, 'd', NULL, 'vv', 35);
INSERT INTO exp_station VALUES ('S4JV1', 'Juv1', 'JV1', '+ 13:50:58', '- 016:40:25', 'VDG - Juv1', 29, 2, 'd', NULL, 'vv', 12);
INSERT INTO exp_station VALUES ('S7JV2', 'Juv2', 'JV2', '+ 14:01:70', '- 016:30:65', 'VDG - Juv2 - coord Google Earth', 32, 2, 'd', NULL, 'vv', 35);
INSERT INTO exp_station VALUES ('S7JV3', 'Juv3', 'JV3', '+ 14:00:36', '- 016:32:31', 'VDG - Juv3', 32, 2, 'd', NULL, 'vs', 35);
INSERT INTO exp_station VALUES ('S6LIK', 'Likitt', 'LIK', '+ 13:58:49', '- 016:40:31', 'VDG - Likitt', 31, 2, 'd', NULL, 'vm', 18);
INSERT INTO exp_station VALUES ('S6MOU', 'Mounde', 'MOU', '+ 13:57:58', '- 016:38:05', 'VDG - Mounde - coord Google Earth', 31, 2, 'd', NULL, 'sa', 27);
INSERT INTO exp_station VALUES ('S7NDI', 'Ndiamniadio', 'NDI', '+ 14:05:36', '- 016:32:41', 'VDG - Ndiamniadio', 32, 2, 'd', NULL, 'sv', 38);
INSERT INTO exp_station VALUES ('S8BAD', 'Bada�e', 'BAD', '+ 14:10:67', '- 016:24:93', 'VDG - Badaie - coord GPS', 33, 2, 'r', NULL, 'vs', 61);
INSERT INTO exp_station VALUES ('S8KLK', 'Kaolack', 'KLK', '+ 14:07:83', '- 016:04:53', 'VDG - Kaolack - coord Google Earth', 33, 2, 'a', NULL, 'sv', 109);
INSERT INTO exp_station VALUES ('LSC02', 'Q02 S�l. Sank Centre', 'C02', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('S3BAP', 'Bapindo', 'BAP', '+ 13:50:12', '- 016:28:07', 'VDG - Bapindo - coord GPS', 28, 2, 'd', NULL, 'vv', 40);
INSERT INTO exp_station VALUES ('AMP01', 'Bamboung01', 'B01', '+ 13:46:30', '- 016:31:27', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP10', 'Bamboung10', 'B10', '+ 13:49:94', '- 016:32:95', NULL, 51, 1, 'r', 'o', 'sv', NULL);
INSERT INTO exp_station VALUES ('AMP11', 'Bamboung11', 'B11', '+ 13:50:15', '- 016:33:51', NULL, 51, 1, NULL, 'o', 'sv', NULL);
INSERT INTO exp_station VALUES ('AMP12', 'Bamboung12', 'B12', '+ 13:50:22', '- 016:33:93', NULL, 51, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP02', 'Bamboung02', 'B02', '+ 13:47:31', '- 016:30:95', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('SAN01', 'Sangako01', 'S01', '+ 13:51:10', '- 016:27:59', 'Bolon Sangako aval', 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('SAN02', 'Sangako02', 'S02', '+ 13:51:20', '- 016:26:54', NULL, 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('SAN03', 'Sangako03', 'S03', '+ 13:51:18', '- 016:26:20', NULL, 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('SAN04', 'Sangako04', 'S04', '+ 13:50:55', '- 016:25:20', NULL, 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('SAN05', 'Sangako05', 'S05', '+ 13:50:25', '- 016:25:07', NULL, 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('SAN06', 'Sangako06', 'S06', '+ 13:50:06', '- 016:24:48', 'Bolon Sangako amont', 54, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('AMP03', 'Bamboung03', 'B03', '+ 13:47:87', '- 016:30:29', NULL, 51, 1, NULL, NULL, 'sa', NULL);
INSERT INTO exp_station VALUES ('DBS01', 'Diomboss01', 'D01', '+ 13:50:56', '- 016:33:15', NULL, 55, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('DBS02', 'Diomboss02', 'D02', '+ 13:50:37', '- 016:32:00', NULL, 55, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('DBS03', 'Diomboss03', 'D03', '+ 13:51:17', '- 016:31:24', NULL, 55, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('DBS04', 'Diomboss04', 'D04', '+ 13:51:34', '- 016:29:40', NULL, 55, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('AMP04', 'Bamboung04', 'B04', '+ 13:48:85', '- 016:30:59', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP05', 'Bamboung05', 'B05', '+ 13:48:95', '- 016:30:79', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP06', 'Bamboung06', 'B06', '+ 13:49:16', '- 016:30:80', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP07', 'Bamboung07', 'B07', '+ 13:49:64', '- 016:31:16', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP08', 'Bamboung08', 'B08', '+ 13:49:12', '- 016:31:70', NULL, 51, 1, NULL, 'o', 'sa', NULL);
INSERT INTO exp_station VALUES ('AMP09', 'Bamboung09', 'B09', '+ 13:49:62', '- 016:32:11', NULL, 51, 1, NULL, 'o', 'sv', NULL);
INSERT INTO exp_station VALUES ('MBA01', 'Q 01 Manan. Barrage', 'B01', NULL, NULL, NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA02', 'Q 02 Manan. Barrage', 'B02', '+ 13:11:55', '- 010:23:70', NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA03', 'Q 03 Manan. Barrage', 'B03', NULL, NULL, NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA06', 'Q 06 Manan. Barrage', 'B06', '+ 13:11:40', '- 010:19:00', NULL, 14, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA07', 'Q 07 Manan. Barrage', 'B07', NULL, NULL, NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA08', 'Q 08 Manan. Barrage', 'B08', NULL, NULL, NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA09', 'Q 09 Manan. Barrage', 'B09', '+ 13:11:50', '- 010:22:80', NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA10', 'Q 10 Manan. Barrage', 'B10', '+ 13:12:30', '- 010:21:75', NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA11', 'Q 11 Manan. Barrage', 'B11', NULL, NULL, NULL, 14, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MBA12', 'Q 12 Manan. Barrage', 'B12', '+ 13:12:50', '- 010:19:25', NULL, 14, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE01', 'Q 01 Manan. Centre', 'C01', '+ 13:07:90', '- 010:22:80', NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE02', 'Q 02 Manan. Centre', 'C02', NULL, NULL, NULL, 15, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE03', 'Q 03 Manan. Centre', 'C03', NULL, NULL, NULL, 15, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE04', 'Q 04 Manan. Centre', 'C04', NULL, NULL, NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE05', 'Q 05 Manan. Centre', 'C05', '+ 13:07:40', '- 010:18:00', NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE06', 'Q 06 Manan. Centre', 'C06', '+ 13:06:39', '- 010:17:32', NULL, 15, 2, NULL, NULL, 'ro', NULL);
INSERT INTO exp_station VALUES ('MCE07', 'Q 07 Manan. Centre', 'C07', '+ 13:06:72', '- 010:22:01', NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE08', 'Q 08 Manan. Centre', 'C08', NULL, NULL, NULL, 15, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE09', 'Q 09 Manan. Centre', 'C09', '+ 13:06:70', '- 010:20:80', NULL, 15, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE10', 'Q 10 Manan. Centre', 'C10', '+ 13:07:80', '- 010:19:30', NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE11', 'Q 11 Manan. Centre', 'C11', NULL, NULL, NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('MCE12', 'Q 12 Manan. Centre', 'C12', NULL, NULL, NULL, 15, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA01', 'Q01 S�l. Barrage', 'B01', NULL, NULL, NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA02', 'Q02 S�l. Barrage', 'B02', '+ 11:37:20', '- 008:13:00', NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA03', 'Q03 S�l. Barrage', 'B03', NULL, NULL, NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA04', 'Q04 S�l. Barrage', 'B04', NULL, NULL, NULL, 18, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA05', 'Q05 S�l. Barrage', 'B05', '+ 11:35:19', '- 008:09:83', 'position GPS  corrig�e le 23/06/05', 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA06', 'Q06 S�l. Barrage', 'B06', '+ 11:34:42', '- 008:09:74', NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA07', 'Q07 S�l. Barrage', 'B07', NULL, NULL, NULL, 18, 1, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA08', 'Q08 S�l. Barrage', 'B08', NULL, NULL, NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA09', 'Q09 S�l. Barrage', 'B09', '+ 11:36:03', '- 008:11:50', NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA10', 'Q10 S�l. Barrage', 'B10', '+ 11:37:50', '- 008:12:30', NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA11', 'Q11 S�l. Barrage', 'B11', NULL, NULL, NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LBA12', 'Q12 S�l. Barrage', 'B12', '+ 11:36:60', '- 008:12:50', NULL, 18, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC01', 'Q01 S�l. Sank Centre', 'C01', '+ 11:31:70', '- 008:16:00', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC03', 'Q03 S�l. Sank Centre', 'C03', '+ 11:29:50', '- 008:17:10', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC04', 'Q04 S�l. Sank Centre', 'C04', '+ 11:28:60', '- 008:16:10', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC05', 'Q05 S�l. Sank Centre', 'C05', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC06', 'Q06 S�l. Sank Centre', 'C06', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC07', 'Q07 S�l. Sank Centre', 'C07', '+ 11:27:40', '- 008:17:00', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC08', 'Q08 S�l. Sank Centre', 'C08', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC09', 'Q09 S�l. Sank Centre', 'C09', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC10', 'Q10 S�l. Sank Centre', 'C10', '+ 11:29:82', '- 008:15:83', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC11', 'Q11 S�l. Sank Centre', 'C11', NULL, NULL, NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('LSC12', 'Q12 S�l. Sank Centre', 'C12', '+ 11:30:70', '- 008:15:50', NULL, 49, 2, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA201', 'BancArguin01', 'A01', '+ 20:54:25', '- 016:52:30', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA110', 'BancArguin10', 'A10', '+ 20:26:38', '- 016:34:33', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA111', 'BancArguin11', 'A11', '+ 20:25:58', '- 016:23:13', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA212', 'BancArguin12', 'A12', '+ 20:11:12', '- 016:57:92', 'barguin 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA213', 'BancArguin13', 'A13', '+ 20:09:12', '- 016:45:27', 'barguin 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA114', 'BancArguin14', 'A14', '+ 20:11:67', '- 016:30:07', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA115', 'BancArguin15', 'A15', '+ 20:13:92', '- 016:24:68', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA116', 'BancArguin16', 'A16', '+ 20:11:97', '- 016:12:75', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA217', 'BancArguin17', 'A17', '+ 20:04:14', '- 017:02:29', 'barguin 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA218', 'BancArguin18', 'A18', '+ 20:03:83', '- 016:45:98', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA119', 'BancArguin19', 'A19', '+ 20:06:60', '- 016:40:63', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA202', 'BancArguin02', 'A02', '+ 20:45:33', '- 016:52:12', 'position modifi�e lors de la campagne Barguin 02 (trop proche de la 01), 26/10/08', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA120', 'BancArguin20', 'A20', '+ 20:05:03', '- 016:24:90', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA121', 'BancArguin21', 'A21', '+ 20:06:95', '- 016:16:50', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA222', 'BancArguin22', 'A22', '+ 19:52:00', '- 017:02:00', 'non �chantillonn�e camp 01 et 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA223', 'BancArguin23', 'A23', '+ 19:53:07', '- 016:49:92', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA124', 'BancArguin24', 'A24', '+ 19:52:70', '- 016:39:78', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA225', 'BancArguin25', 'A25', '+ 19:43:00', '- 016:58:00', 'non �chantillonn�e camp 01 et 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA226', 'BancArguin26', 'A26', '+ 19:26:25', '- 016:44:60', 'barguin 02', 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA227', 'BancArguin27', 'A27', '+ 19:43:75', '- 016:49:82', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA128', 'BancArguin28', 'A28', '+ 19:43:98', '- 016:38:93', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA129', 'BancArguin29', 'A29', '+ 19:32:10', '- 016:44:85', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA203', 'BancArguin03', 'A03', '+ 20:45:00', '- 016:58:23', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA130', 'BancArguin30', 'A30', '+ 19:32:43', '- 016:36:08', 'non �chantillonn�e camp 02', 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA131', 'BancArguin31', 'A31', '+ 19:26:00', '- 016:36:00', 'non �chantillonn�e camp 01 et 02', 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA132', 'BancArguin32', 'A32', '+ 19:30:00', '- 016:32:00', 'non �chantillonn�e camp 01 et 02', 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA133', 'BancArguin33', 'A33', '+ 19:34:00', '- 016:34:00', 'non �chantillonn�e camp 01 et 02', 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA134', 'BancArguin34', 'A34', '+ 19:54:00', '- 016:20:00', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA204', 'BancArguin04', 'A04', '+ 20:44:12', '- 016:47:18', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA205', 'BancArguin05', 'A05', '+ 20:34:95', '- 016:55:95', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA206', 'BancArguin06', 'A06', '+ 20:35:33', '- 016:45:48', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA107', 'BancArguin07', 'A07', '+ 20:34:27', '- 016:41:33', NULL, 56, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA208', 'BancArguin08', 'A08', '+ 20:24:97', '- 016:56:18', NULL, 57, NULL, NULL, NULL, NULL, NULL);
INSERT INTO exp_station VALUES ('BA209', 'BancArguin09', 'A09', '+ 20:24:85', '- 016:47:13', NULL, 57, NULL, NULL, NULL, NULL, NULL);

ALTER TABLE exp_station ENABLE TRIGGER ALL;

--
-- Data for Name: exp_vegetation; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE exp_vegetation DISABLE TRIGGER ALL;

INSERT INTO exp_vegetation VALUES ('a', 'a- absence de v�g�tation');
INSERT INTO exp_vegetation VALUES ('d', 'd- mangrove d�velopp�e');
INSERT INTO exp_vegetation VALUES ('r', 'r- mangrove r�siduelle');

ALTER TABLE exp_vegetation ENABLE TRIGGER ALL;


--
-- Data for Name: ref_categorie_ecologique; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_categorie_ecologique DISABLE TRIGGER ALL;

INSERT INTO ref_categorie_ecologique VALUES ('C', 'Continentale');
INSERT INTO ref_categorie_ecologique VALUES ('Ce', 'Continentale � affinit� estuarienne');
INSERT INTO ref_categorie_ecologique VALUES ('Co', 'Continentale occasionnelle');
INSERT INTO ref_categorie_ecologique VALUES ('Ec', 'Estuarienne d''origine continentale');
INSERT INTO ref_categorie_ecologique VALUES ('Em', 'Estuarienne d''origine marine');
INSERT INTO ref_categorie_ecologique VALUES ('Es', 'Estuarienne stricte');
INSERT INTO ref_categorie_ecologique VALUES ('M', 'Marine');
INSERT INTO ref_categorie_ecologique VALUES ('Ma', 'Marine accessoire');
INSERT INTO ref_categorie_ecologique VALUES ('ME', 'Marine-estuarienne');
INSERT INTO ref_categorie_ecologique VALUES ('Mo', 'Marine occasionnelle');

ALTER TABLE ref_categorie_ecologique ENABLE TRIGGER ALL;


--
-- Data for Name: ref_categorie_trophique; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_categorie_trophique DISABLE TRIGGER ALL;

INSERT INTO ref_categorie_trophique VALUES ('he-de', 'Herbivore d�tritivore ou brouteur');
INSERT INTO ref_categorie_trophique VALUES ('he-ph', 'Herbivore � pr�dominance phytoplanctonophage ou microphytophage');
INSERT INTO ref_categorie_trophique VALUES ('om-ge', 'Omnivore g�n�raliste');
INSERT INTO ref_categorie_trophique VALUES ('om-in', 'Omnivore � pr�dominance insectivore');
INSERT INTO ref_categorie_trophique VALUES ('p1-bt', 'Pr�dateur de premier niveau � pr�dominance benthophage (mollusques,vers)');
INSERT INTO ref_categorie_trophique VALUES ('p1-in', 'Pr�dateur de premier niveau � pr�dominance insectivore');
INSERT INTO ref_categorie_trophique VALUES ('p1-mc', 'Pr�dateur de premier niveau g�n�raliste (crustac�s,insectes)');
INSERT INTO ref_categorie_trophique VALUES ('p1-zo', 'Zooplanctonophagie dominante');
INSERT INTO ref_categorie_trophique VALUES ('p2-ge', 'Pr�dateur de deuxi�me niveau g�n�raliste (poisson et autres proies)');
INSERT INTO ref_categorie_trophique VALUES ('p2-pi', 'Pr�dateur de deuxi�me niveau � pr�dominance piscivore');

ALTER TABLE ref_categorie_trophique ENABLE TRIGGER ALL;


--
-- Data for Name: ref_espece; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_espece DISABLE TRIGGER ALL;

INSERT INTO ref_espece VALUES ('AAM', 'Hemicaranx bicolor', NULL, 15, 'Mo', 'p2-ge', 1.4933, 3.0334001, 9, NULL);
INSERT INTO ref_espece VALUES ('ABA', 'Alestes baremoze', NULL, 3, 'Co', 'om-ge', 2.6429999, 2.868, 2, NULL);
INSERT INTO ref_espece VALUES ('ABI', 'Auchenoglanis biscutatus', NULL, 10, 'C', NULL, 5.0900002, 2.885, 2, NULL);
INSERT INTO ref_espece VALUES ('ACI', 'Alectis ciliaris', NULL, 15, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ACS', 'Porogobius schlegelii', NULL, 41, 'Es', 'om-ge', 2.5190001, 2.743, 1, NULL);
INSERT INTO ref_espece VALUES ('ADE', 'Alestes dentex', NULL, 3, 'C', NULL, 2.6229999, 2.8959999, 2, NULL);
INSERT INTO ref_espece VALUES ('AGA', 'Arius latiscutatus', NULL, 9, 'ME', 'p2-ge', 1.3200001, 2.994, 4, NULL);
INSERT INTO ref_espece VALUES ('AGI', 'Arius gigas', NULL, 9, 'C', NULL, 1, 3, 0, NULL);
INSERT INTO ref_espece VALUES ('AGU', 'Engraulis encrasicolus', NULL, 35, 'Ma', 'p1-zo', 0, 0, 0, NULL);
INSERT INTO ref_espece VALUES ('AHE', 'Arius heudelotii', NULL, 9, 'ME', 'p2-ge', 0.67510003, 3.1124001, 9, NULL);
INSERT INTO ref_espece VALUES ('AHI', 'Ablennes hians', NULL, 13, 'Mo', 'p2-pi', 0.0040000002, 3.322, 8, NULL);
INSERT INTO ref_espece VALUES ('ALE', 'Alestes spp.', 'JME 15/05/03', 3, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ALH', 'Aluterus heudelotii', 'GIBAO', 58, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ALI', 'Brycinus imberi', NULL, 3, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ALO', 'Brycinus longipinnis', NULL, 3, 'Ce', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('AMA', 'Brycinus macrolepidotus', NULL, 3, 'Ce', 'om-ge', 2.6429999, 2.868, 2, NULL);
INSERT INTO ref_espece VALUES ('AMI', 'Apogon imberbis', NULL, 8, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('AMO', 'Acanthurus monroviae', NULL, 1, 'Mo', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ANM', 'Aplocheilichthys normani', 'GIBAO', 27, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ANO', 'Antennarius striatus', NULL, 5, 'Ma', 'p2-ge', 3.03, 3.036, 4, NULL);
INSERT INTO ref_espece VALUES ('ANU', 'Brycinus nurse', NULL, 3, 'Co', 'om-ge', 2.3099999, 2.9590001, 7, NULL);
INSERT INTO ref_espece VALUES ('AOC', 'Auchenoglanis occidentalis', NULL, 10, 'C', NULL, 3.553, 2.9230001, 2, NULL);
INSERT INTO ref_espece VALUES ('APA', 'Antennarius pardalis', NULL, 5, 'Mo', 'p2-ge', 0, 0, 0, 'ANO');
INSERT INTO ref_espece VALUES ('APR', 'Atherina presbyter', 'GIBAO', 109, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('APY', 'Aplysia spp.', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ARA', 'Aplocheilichthys rancureli', NULL, 27, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ARE', 'Argyrosomus regius', NULL, 86, 'Mo', 'p2-pi', 0.99800003, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('ARI', 'Arius spp.', NULL, 9, NULL, NULL, 1.36, 2.9909999, 4, NULL);
INSERT INTO ref_espece VALUES ('ARP', 'Arius parkii', NULL, 9, 'ME', 'p2-ge', 0.50650001, 3.1677999, 9, NULL);
INSERT INTO ref_espece VALUES ('ASE', 'Arca senilis', 'Demande JME 21/06/04', 108, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ASH', 'Aluterus schoepfii', 'Arguin 02 (oct 2008)', 58, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ASO', 'Acanthocybium solandri', 'GIBAO', 87, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ASP', 'Aplocheilichthys spilauchen', NULL, 27, 'Es', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ATH', 'Auxis thazard', NULL, 87, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ATL', 'Atherina lopeziana', 'GIBAO', 109, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ATR', 'Atherina sp.', 'VDG - Pgm Saloum', 109, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('AUC', 'Auchenoglanis spp.', 'JME 15/05/03', 10, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('AVU', 'Albula vulpes', NULL, 2, 'Mo', 'p1-bt', 1.176, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('BAB', 'Barbus ablabes', NULL, 26, 'Co', NULL, 0, 0, 0, 'BOC');
INSERT INTO ref_espece VALUES ('BAG', 'Bagrus spp.', 'JME 15/05/03', 10, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BAL', 'Balistes spp.', NULL, 11, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BAR', 'Barbus spp.', NULL, 26, 'C', NULL, 0, 0, 0, 'BDS');
INSERT INTO ref_espece VALUES ('BAU', 'Brachydeuterus auritus', NULL, 45, 'ME', 'p1-mc', 0.89999998, 3.1159999, 4, NULL);
INSERT INTO ref_espece VALUES ('BBA', 'Synodontis batensoda', NULL, 57, 'Co', 'om-ge', 4.8070002, 2.925, 2, NULL);
INSERT INTO ref_espece VALUES ('BBO', 'Boops boops', NULL, 92, 'Mo', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BDM', 'Bagrus bajad', NULL, 10, 'C', NULL, 0.57099998, 3.132, 2, NULL);
INSERT INTO ref_espece VALUES ('BDO', 'Bagrus docmak', NULL, 10, 'C', NULL, 1.04, 3.076, 8, NULL);
INSERT INTO ref_espece VALUES ('BDS', 'Barbus perince', NULL, 26, 'C', NULL, 0.21699999, 3.5, 2, NULL);
INSERT INTO ref_espece VALUES ('BEL', 'Belonidae', 'BancArguin mai08', 13, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BES', 'Strongylura senegalensis', NULL, 13, 'Em', 'p2-pi', 4.2410002, 2.4130001, 1, NULL);
INSERT INTO ref_espece VALUES ('BFI', 'Bagrus filamentosus', NULL, 10, 'C', NULL, 0, 0, 0, 'BDO');
INSERT INTO ref_espece VALUES ('BKO', 'Butis koilomatodon', 'VDG - Pgm Saloum', 33, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BLB', 'Batanga lebretonis', NULL, 33, 'Es', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BLE', 'Parablennius goreensis', 'Paro... corrige en Para... le 27/03/6 (LT)', 14, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BLI', 'Batrachoides liberiensis', NULL, 12, 'Ma', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BLN', 'Blennius sp.', 'VDG - Pgm Saloum', 14, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BMA', 'Barbus macrops', NULL, 26, 'C', NULL, 0, 0, 0, 'BDS');
INSERT INTO ref_espece VALUES ('BOC', 'Barbus bynni occidentalis', 'V�rifi� dans L�v�que, Paugy, Teugels, 90 (7/02/02)', 26, 'C', NULL, 0.21699999, 3.5, 2, NULL);
INSERT INTO ref_espece VALUES ('BPO', 'Bothus podas', 'GIBAO', 115, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BRB', 'Brotula barbata', 'GIBAO', 116, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BRL', 'Brycinus leuciscus', NULL, 3, 'C', NULL, 3, 3, 2, NULL);
INSERT INTO ref_espece VALUES ('BRY', 'Brycinus spp.', 'JME 15/05/03', 3, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BSE', 'Branchiostegus semifasciatus', 'GIBAO', 117, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BSO', 'Bathygobius soporator', NULL, 41, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BSP', 'Bodianus speciosus', 'GIBAO', 113, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('BWA', 'Barbus bynni waldroni', 'V�rifi� dans L�v�que, Paugy, Teugels, 90 (7/02/02)', 26, 'Co', NULL, 0, 0, 0, 'BOC');
INSERT INTO ref_espece VALUES ('CAA', 'Callinectes amnicola', NULL, 78, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CAL', 'Callinectes sp.', NULL, 78, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CAR', 'Caranx spp.', NULL, 15, NULL, NULL, 1.67, 3.036, 4, NULL);
INSERT INTO ref_espece VALUES ('CAS', 'Caranx senegallus', 'corrig� le 26/02/02', 15, 'ME', 'p2-ge', 6.4400001, 2.7190001, 4, NULL);
INSERT INTO ref_espece VALUES ('CBR', 'Carcharhinus brevipinna', 'GIBAO', 16, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CCE', 'Dalophis cephalopeltis', NULL, 70, 'Es', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CCH', 'Carcharhinus spp.', NULL, 16, NULL, NULL, 0, 0, 0, 'RCE');
INSERT INTO ref_espece VALUES ('CCI', 'Citharinus citharus', NULL, 21, 'C', NULL, 2.168, 3.0610001, 2, NULL);
INSERT INTO ref_espece VALUES ('CCR', 'Caranx crysos', 'demande JME 03/03', 15, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CCY', 'Cymbium cymbium', 'BancArguin mai08', 102, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CFI', 'Chrysichthys auratus', NULL, 118, 'Ec', 'p1-bt', 1.6, 2.8800001, 8, NULL);
INSERT INTO ref_espece VALUES ('CGA', 'Clarias gariepinus', NULL, 22, 'Co', NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('CGL', 'Callinectes pallidus', NULL, 78, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CGP', 'Crevette Grosse Pince', 'VDG Saloum 2001-2003', 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CGY', 'Campogramma glaycos', 'GIBAO', 15, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CHG', 'Ephippus goreensis', 'anc. Chaetodipterus - MS (23/07/09)', 36, 'Mo', 'p1-mc', 0, 0, 0, 'CLI');
INSERT INTO ref_espece VALUES ('CHI', 'Caranx hippos', NULL, 15, 'ME', 'p2-ge', 1.37, 3.0829999, 10, NULL);
INSERT INTO ref_espece VALUES ('CHL', 'Chloroscombrus chrysurus', NULL, 15, 'ME', 'p1-mc', 1.17, 3.053, 4, NULL);
INSERT INTO ref_espece VALUES ('CHM', 'Chromis chromis', NULL, 77, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CHO', 'Chaetodon hoefleri', NULL, 18, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CHY', 'Chrysichthys spp.', NULL, 118, NULL, NULL, 2.73, 2.8829999, 4, NULL);
INSERT INTO ref_espece VALUES ('CIL', 'Citharinus latus', NULL, 21, 'C', NULL, 0, 0, 3, 'CCI');
INSERT INTO ref_espece VALUES ('CIT', 'Citharinus spp.', '18/11/02', 21, 'C', NULL, 0, 0, 0, 'CCI');
INSERT INTO ref_espece VALUES ('CJO', 'Chrysichthys johnelsi', NULL, 118, 'Ce', 'p1-bt', 0, 0, 0, 'CHY');
INSERT INTO ref_espece VALUES ('CJU', 'Coris julis', 'GIBAO - il doit s`agir de C. atlantica. � v�rifier', 113, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CKI', 'Ctenopoma kingsleyae', NULL, 4, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CLA', 'Clarotes laticeps', NULL, 118, 'Co', NULL, 1.531, 3.01, 2, NULL);
INSERT INTO ref_espece VALUES ('CLB', 'Chelon labrosus', 'GIBAO', 62, 'ME', 'he-de', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CLC', 'Carcharhinus leucas', 'Bamboung 09', 16, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CLE', 'Clarias ebriensis', NULL, 22, 'Es', NULL, 0, 0, 0, NULL);
INSERT INTO ref_espece VALUES ('CLI', 'Chaetodipterus lippei', NULL, 36, 'Ma', 'p1-mc', 3.4319999, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('CLL', 'Clarias laeviceps', NULL, 22, 'C', NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('CLM', 'Carcharhinus limbatus', 'JME 25/02/05', 16, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CLP', 'Clarias spp.', NULL, 22, NULL, NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('CLR', 'Clarioides spp.', NULL, 22, NULL, NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('CLS', 'Clarias anguillaris', NULL, 22, 'Co', 'om-ge', 0.93400002, 3.0339999, 2, NULL);
INSERT INTO ref_espece VALUES ('CMB', 'Cymbium sp.', NULL, 102, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CNI', 'Chrysichthys nigrodigitatus', NULL, 118, 'Ec', 'p1-bt', 2.1300001, 2.9170001, 4, NULL);
INSERT INTO ref_espece VALUES ('COB', 'Carcharhinus obscurus', 'GIBAO', 16, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('COR', 'Corvina spp.', NULL, 86, NULL, NULL, 0, 0, 0, 'PSS');
INSERT INTO ref_espece VALUES ('CPE', 'Cymbium pepo', 'BancArguin mai08', 102, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CPL', 'Carcharhinus plumbeus', 'GIBAO', 16, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CRA', 'Crabe non Callinectes', 'VDG Saloum 2001-2003', 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CRE', 'Chilomycterus reticulatus', 'GIBAO', 30, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CRG', 'Crassostrea gasar', 'Demande JME 21/06/04', 107, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CRH', 'Caranx rhonchus', NULL, 15, 'Mo', 'p1-bt', 0, 0, 0, 'CAR');
INSERT INTO ref_espece VALUES ('CRV', 'Crevette non p�n�ide', 'VDG Saloum 2001-2003', 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CSP', 'Chilomycterus spinosus', 'GIBAO', 30, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CST', 'Citharichthys stampflii', NULL, 72, 'Em', 'p2-ge', 0.23, 3.1470001, 4, NULL);
INSERT INTO ref_espece VALUES ('CTA', 'Campylomormyrus tamandua', NULL, 60, 'C', NULL, 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('CTL', 'Ctenogobius lepturus', 'Baran (21/12/01)', 41, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CTR', 'Scomberomorus tritor', NULL, 87, 'Ma', 'p2-pi', 1.08, 2.9849999, 4, NULL);
INSERT INTO ref_espece VALUES ('CVO', 'Dactylopterus volitans', 'corrig� le 26/02/02', 28, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CWA', 'Chrysichthys maurus', NULL, 118, 'Ec', 'p1-bt', 1.77, 2.983, 4, NULL);
INSERT INTO ref_espece VALUES ('CYB', 'Cynoglossus browni', 'GIBAO', 25, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CYG', 'Cymbium glans', 'Amphore - Nov 2009 - Demande O. Sadio', 102, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('CYM', 'Cynoglossus monodi', NULL, 25, 'Mo', 'p1-bt', 0, 0, 0, 'CYS');
INSERT INTO ref_espece VALUES ('CYN', 'Cynoglossus spp.', NULL, 25, NULL, NULL, 0, 0, 0, 'CYS');
INSERT INTO ref_espece VALUES ('CYS', 'Cynoglossus senegalensis', NULL, 25, 'Em', 'p1-bt', 0.31999999, 2.9860001, 4, NULL);
INSERT INTO ref_espece VALUES ('DAF', 'Drepane africana', NULL, 31, 'ME', 'p1-mc', 0.69, 3.2720001, 4, NULL);
INSERT INTO ref_espece VALUES ('DAM', 'Dasyatis margaritella', NULL, 29, 'Em', 'p1-bt', 0, 0, 0, 'DMA');
INSERT INTO ref_espece VALUES ('DAN', 'Dentex angolensis', 'GIBAO', 92, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DAS', 'Dasyatis spp.', NULL, 29, NULL, NULL, 4.039, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('DBE', 'Diplodus bellottii', NULL, 92, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DBR', 'Distichodus brevipinnis', NULL, 21, 'C', NULL, 0, 0, 0, 'DRO');
INSERT INTO ref_espece VALUES ('DCA', 'Dentex canariensis', NULL, 92, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DCE', 'Dasyatis centroura', 'GIBAO', 29, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DCU', 'Dicologlossa cuneata', 'GIBAO', 91, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DEN', 'Distichodus engrycephalus', NULL, 21, 'C', NULL, 0, 0, 0, 'DRO');
INSERT INTO ref_espece VALUES ('DEP', 'Decapterus punctatus', NULL, 15, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DGI', 'Dentex gibbosus', 'GIBAO', 92, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DIC', 'Diplodus cervinus', 'GIBAO', 92, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DIM', 'Diodon holocanthus', NULL, 30, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DIS', 'Distichodus spp.', 'JME 15/05/03', 21, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DIV', 'Divers-Melange', NULL, 49, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DMA', 'Dasyatis margarita', NULL, 29, 'Em', 'p1-bt', 16.83, 2.7479999, 6, NULL);
INSERT INTO ref_espece VALUES ('DMC', 'Dentex macrophthalmus', 'GIBAO', 92, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DMM', 'Dasyatis marmorata', 'GIBAO', 29, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DPA', 'Dasyatis pastinaca', 'GIBAO', 29, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DPU', 'Dicentrarchus punctatus', NULL, 61, 'Mo', 'p2-ge', 1.245, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('DPZ', 'Diplodus puntazzo', 'GIBAO', 92, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DRO', 'Distichodus rostratus', NULL, 21, 'Co', NULL, 1.99, 3, 7, NULL);
INSERT INTO ref_espece VALUES ('DSA', 'Diplodus sargus', 'ss-esp. cadenati seule pr�sente sur zone (7/02/02)', 92, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DUK', 'Urogymnus ukpam', 'anc. Dasyatis - MS (23/07/09)', 29, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('DVU', 'Diplodus vulgaris', NULL, 92, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('EAE', 'Epinephelus aeneus', NULL, 90, 'ME', 'p2-pi', 1.33, 2.9760001, 4, NULL);
INSERT INTO ref_espece VALUES ('EAL', 'Euthynnus alletteratus', NULL, 87, 'Ma', NULL, 0, 0, 0, NULL);
INSERT INTO ref_espece VALUES ('EAX', 'Epinephelus fasciatus', 'GIBAO - E. alexandrinus - r�partition Pacifique ?', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('EBI', 'Epiplatys bifasciatus', 'GIBAO', 119, 'Ce', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ECA', 'Epinephelus caninus', 'GIBAO', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ECH', 'Epiplatys chaperi', NULL, 119, 'Ce', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('EDA', 'Eleotris daganensis', NULL, 33, 'Es', NULL, 0, 0, 0, 'ELE');
INSERT INTO ref_espece VALUES ('EES', 'Epinephelus esonue', NULL, 90, 'Mo', NULL, 0, 0, 0, 'EAE');
INSERT INTO ref_espece VALUES ('EFI', 'Ethmalosa fimbriata', NULL, 23, 'Em', 'he-ph', 0.75, 3.1719999, 4, NULL);
INSERT INTO ref_espece VALUES ('EGU', 'Ephippion guttifer', NULL, 98, 'ME', 'p1-bt', 5.6399999, 2.8239999, 4, NULL);
INSERT INTO ref_espece VALUES ('ELA', 'Elops lacerta', NULL, 34, 'ME', 'p2-pi', 0.93000001, 2.9890001, 4, NULL);
INSERT INTO ref_espece VALUES ('ELE', 'Eleotris spp.', NULL, 33, NULL, NULL, 0.83999997, 3.075, 4, NULL);
INSERT INTO ref_espece VALUES ('ELS', 'Elops senegalensis', NULL, 34, 'Ma', 'p2-pi', 0, 0, 0, 'ELA');
INSERT INTO ref_espece VALUES ('EME', 'Schilbe mandibularis', NULL, 85, 'Ce', 'p1-mc', 0.34599999, 3.187, 1, NULL);
INSERT INTO ref_espece VALUES ('ENA', 'Echeneis naucrates', NULL, 32, 'Mo', 'p1-zo', 0.23800001, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('ENI', 'Schilbe niloticus', NULL, 85, 'C', NULL, 0.59200001, 3.154, 2, NULL);
INSERT INTO ref_espece VALUES ('EPG', 'Epinephelus guaza', 'syst�matique d`apres Seret et Leveque - remplace code EGA de VDG', 90, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('EPI', 'Epinephelus spp.', 'GIBAO - Arguin', 90, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ESE', 'Eleotris senegalensis', NULL, 33, 'Es', 'p1-mc', 8.2290001, 2.6170001, 1, NULL);
INSERT INTO ref_espece VALUES ('ESP', 'Epiplatys spilargyreius', 'GIBAO', 119, 'Ce', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('EVI', 'Eleotris vittata', NULL, 33, 'Es', 'p1-mc', 3.79, 2.7909999, 5, NULL);
INSERT INTO ref_espece VALUES ('FAC', 'Fodiator acutus', NULL, 37, 'Ma', 'p1-mc', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('FTA', 'Fistularia tabacaria', NULL, 38, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('FVI', 'Fistularia petimba', NULL, 38, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GAL', 'Galathea spp.', NULL, 39, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GAN', 'Gobioides sagitta', NULL, 41, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GCI', 'Ginglymostoma cirratum', 'GIBAO', 120, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GDE', 'Galeoides decadactylus', NULL, 75, 'ME', 'p2-ge', 0.89999998, 3.1459999, 4, NULL);
INSERT INTO ref_espece VALUES ('GER', 'Gerres spp.', NULL, 40, NULL, NULL, 1.09, 3.135, 4, NULL);
INSERT INTO ref_espece VALUES ('GGU', 'Awaous lateristriga', 'anc. Chonophorus - MS (23/07/09)', 41, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GME', 'Eucinostomus melanopterus', NULL, 40, 'ME', 'p1-mc', 0.5, 3.29, 4, NULL);
INSERT INTO ref_espece VALUES ('GMI', 'Gymnura micrura', NULL, 44, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GNE', 'Breinomyrus niger', NULL, 60, 'C', NULL, 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('GNI', 'Gerres nigri', NULL, 40, 'Es', 'p1-mc', 1.52, 3.056, 5, NULL);
INSERT INTO ref_espece VALUES ('GOB', 'Gobiidae', NULL, 41, NULL, NULL, 0, 0, 0, 'ACS');
INSERT INTO ref_espece VALUES ('GON', 'Gorogobius nigricinctus', 'GIBAO', 41, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GRU', 'Gobius rubropunctatus', 'Baran (21/12/01)', 41, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GSE', 'Marcusenius senegalensis', NULL, 60, 'C', NULL, 0, 0, 0, 'MRU');
INSERT INTO ref_espece VALUES ('GTH', 'Yongeichthys thomasi', NULL, 41, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GYA', 'Gymnura altavela', NULL, 44, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('GYM', 'Gymnarchus spp.', NULL, 43, NULL, NULL, 0, 0, 0, 'GYN');
INSERT INTO ref_espece VALUES ('GYN', 'Gymnarchus niloticus', NULL, 43, 'C', NULL, 0.38999999, 2.9779999, 0, NULL);
INSERT INTO ref_espece VALUES ('HAF', 'Bostrychus africanus', NULL, 33, 'Es', 'p1-mc', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HBA', 'Hemiramphus balao', NULL, 46, 'Em', 'p2-ge', 0, 0, 0, 'HPI');
INSERT INTO ref_espece VALUES ('HBI', 'Hemichromis bimaculatus', NULL, 20, 'Co', NULL, 7.3330002, 2.8770001, 3, NULL);
INSERT INTO ref_espece VALUES ('HBL', 'Haplochromis bloyeti', NULL, 20, 'C', NULL, 1.378, 0.30599999, 3, NULL);
INSERT INTO ref_espece VALUES ('HBO', 'Hyperopisus bebe', 'ss-esp. occidentalis supprim�e le 7/02/02', 60, 'Co', 'p1-bt', 0.34999999, 3.151, 2, NULL);
INSERT INTO ref_espece VALUES ('HBR', 'Hemiramphus brasiliensis', NULL, 46, 'Em', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HDI', 'Halobatrachus didactylus', 'GIBAO', 12, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HEB', 'Heterobranchus bidorsalis', NULL, 22, 'Co', NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('HET', 'Heterobranchus spp.', '18/11/02', 22, 'C', NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('HFA', 'Hemichromis fasciatus', NULL, 20, 'Ec', 'p2-ge', 0.33000001, 3.3340001, 4, NULL);
INSERT INTO ref_espece VALUES ('HFO', 'Hydrocynus forskahlii', 'LT 19/04/07 (ajout du h)', 3, 'Co', 'p2-pi', 0.78899997, 3.098, 2, NULL);
INSERT INTO ref_espece VALUES ('HHA', 'Hippopotamyrus harringtoni', NULL, 60, 'C', NULL, 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('HIN', 'Rhabdalestes septentrionalis', NULL, 3, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HIP', 'Hippopotamyrus pictus', 'Mali 03', 60, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HIS', 'Heterobranchus isopterus', NULL, 22, 'Ce', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HLA', 'Hypleurochilus langi', 'Bamboung 09', 14, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HLO', 'Heterobranchus longifilis', NULL, 22, 'Ce', NULL, 0, 0, 0, 'CLS');
INSERT INTO ref_espece VALUES ('HME', 'Synodontis membranaceus', NULL, 57, 'Co', NULL, 1.46, 3.119, 8, NULL);
INSERT INTO ref_espece VALUES ('HNI', 'Heterotis niloticus', 'Pechart Gambie - sans doute une erreur d`identification', 134, 'C', NULL, 3.0150001, 2.865, 2, NULL);
INSERT INTO ref_espece VALUES ('HOD', 'Hepsetus odoe', NULL, 47, 'Co', 'p2-pi', 0, 0, 0, 'HYB');
INSERT INTO ref_espece VALUES ('HPA', 'Hippopotamyrus paugyi', 'GIBAO', 60, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HPI', 'Hyporhamphus picarti', NULL, 46, 'Ma', 'p2-ge', 3.5190001, 2.6029999, 1, NULL);
INSERT INTO ref_espece VALUES ('HPU', 'Hippocampus algiricus', NULL, 96, 'Ma', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('HYB', 'Hydrocynus brevis', NULL, 3, 'Co', 'p2-pi', 0.55000001, 3.201, 3, NULL);
INSERT INTO ref_espece VALUES ('HYD', 'Hydrocynus spp.', 'JME 15/05/03', 3, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('IAF', 'Ilisha africana', NULL, 135, 'Em', 'p1-zo', 2.73, 2.7909999, 4, NULL);
INSERT INTO ref_espece VALUES ('ICO', 'Illex coindetii', 'GIBAO - Arguin', 132, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('INC', 'Inconnu', NULL, 49, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('IND', 'Ind�termin�', 'VDG - Pgm Saloum', 49, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('KKR', 'Kribia kribensis', 'Baran (21/12/01)', 33, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LAB', 'Labeo spp.', 'JME 15/05/03', 26, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LAD', 'Laeviscutella dekimpei', NULL, 23, 'Es', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LAF', 'Gymnothorax afer', NULL, 64, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LAG', 'Lutjanus agennes', 'Baran (21/12/01)', 53, 'Mo', NULL, 0, 0, 0, 'LGO');
INSERT INTO ref_espece VALUES ('LAT', 'Lethrinus atlanticus', NULL, 50, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LAU', 'Liza aurata', 'GIBAO', 62, 'ME', 'p1-zo', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LBA', 'Liza bandialensis', 'JME - 16/12/05 - espece nouvelle pgme Saloum - cf these PSD; 96', 62, NULL, NULL, 0, 0, 0, 'LGR');
INSERT INTO ref_espece VALUES ('LCO', 'Labeo coubie', NULL, 26, 'Co', NULL, 3.3469999, 2.9679999, 2, NULL);
INSERT INTO ref_espece VALUES ('LDU', 'Liza dumerili', NULL, 62, 'Em', 'he-de', 3.9100001, 2.7750001, 6, NULL);
INSERT INTO ref_espece VALUES ('LED', 'Lepidotrigla cadmani', NULL, 101, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LEN', 'Lutjanus endecacanthus', 'Baran (21/12/01)', 53, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LEP', 'Leptoc�phale', 'VDG - Pgm Saloum', 49, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LFA', 'Liza falcipinnis', NULL, 62, 'Em', 'he-de', 1.1900001, 2.987, 4, NULL);
INSERT INTO ref_espece VALUES ('LFU', 'Lutjanus fulgens', 'GIBAO', 53, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LGL', 'Trachinotus ovatus', NULL, 15, 'Ma', 'p2-ge', 6.0320001, 2.6960001, 1, NULL);
INSERT INTO ref_espece VALUES ('LGO', 'Lutjanus goreensis', NULL, 53, 'Ma', 'p2-pi', 2.95, 2.8829999, 4, NULL);
INSERT INTO ref_espece VALUES ('LGR', 'Liza grandisquamis', NULL, 62, 'Em', 'he-de', 1.5700001, 2.9590001, 4, NULL);
INSERT INTO ref_espece VALUES ('LIA', 'Lichia amia', NULL, 15, 'Ma', 'p2-ge', 0, 0, 0, 'LGL');
INSERT INTO ref_espece VALUES ('LIZ', 'Liza spp', 'Demande JME 21/06/04', 62, NULL, NULL, 0, 0, 0, 'LFA');
INSERT INTO ref_espece VALUES ('LKE', 'Lophiodes kempi', 'GIBAO', 121, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LLA', 'Lagocephalus laevigatus', NULL, 98, 'Ma', 'p2-ge', 0, 0, 0, NULL);
INSERT INTO ref_espece VALUES ('LMO', 'Lithognathus mormyrus', NULL, 92, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LNI', 'Lates niloticus', NULL, 17, 'Co', 'p2-pi', 0.77399999, 3.089, 7, NULL);
INSERT INTO ref_espece VALUES ('LPA', 'Labeo parvus', 'Mali 03', 26, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LRA', 'Liza ramado', 'GIBAO', 62, 'ME', 'p1-zo', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LSE', 'Labeo senegalensis', NULL, 26, 'C', NULL, 2.3840001, 2.9949999, 2, NULL);
INSERT INTO ref_espece VALUES ('LSM', 'Leptocharias smithii', 'GIBAO', 122, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('LSU', 'Lobotes surinamensis', NULL, 51, 'Mo', 'p2-ge', 0.042800002, 2.8399999, 8, NULL);
INSERT INTO ref_espece VALUES ('LUD', 'Lutjanus dentatus', NULL, 53, 'Mo', 'p2-pi', 0, 0, 0, 'LGO');
INSERT INTO ref_espece VALUES ('LUT', 'Lutjanus spp.', NULL, 53, NULL, NULL, 0, 0, 0, 'LGO');
INSERT INTO ref_espece VALUES ('LVU', 'Loligo vulgaris', 'BancArguin mai08', 52, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MAN', 'Mormyrops anguilloides', NULL, 60, 'Co', 'p2-ge', 0, 0, 0, 'MDE');
INSERT INTO ref_espece VALUES ('MAQ', 'Myliobatis aquila', 'GIBAO', 67, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MAR', 'Marcusenius spp.', 'JME 15/05/03', 60, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MBA', 'Mugil bananensis', NULL, 62, 'ME', 'he-de', 0.86000001, 3.0969999, 6, NULL);
INSERT INTO ref_espece VALUES ('MBO', 'Microchirus boscanion', 'GIBAO', 91, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MBR', 'Marcusenius ussheri', 'anc. M. bruyerei (corr. 19/04/07 MS)', 60, 'Co', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MCA', 'Mugil capurrii', 'GIBAO', 62, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MCE', 'Mugil cephalus', NULL, 62, 'ME', 'he-de', 0, 0, 0, 'MUG');
INSERT INTO ref_espece VALUES ('MCO', 'Murex cornutus', 'BancArguin mai08', 65, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MCU', 'Mugil curema', NULL, 62, 'Em', 'he-de', 1.02, 3.0650001, 4, NULL);
INSERT INTO ref_espece VALUES ('MCY', 'Marcusenius cyprinoides', NULL, 60, 'C', NULL, 0, 0, 0, 'MRU');
INSERT INTO ref_espece VALUES ('MDE', 'Mormyrops deliciosus', NULL, 60, 'C', NULL, 1.5, 2.8699999, 7, NULL);
INSERT INTO ref_espece VALUES ('MDU', 'Murex duplex', 'BBG17', 65, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MED', 'Meduse', NULL, 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MEL', 'Malapterurus electricus', NULL, 55, 'Co', NULL, 1.08, 3.069, 7, NULL);
INSERT INTO ref_espece VALUES ('MER', 'Merlucciidae', 'GIBAO - Nov 2008', 124, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MFU', 'Marcusenius furcidens', NULL, 60, 'Co', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MHA', 'Mormyrus hasselquistii', NULL, 60, 'Co', NULL, 1.6900001, 2.7449999, 8, NULL);
INSERT INTO ref_espece VALUES ('MHE', 'Muraena helena', 'GIBAO', 64, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MHI', 'Monochirus hispidus', 'GIBAO', 91, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MIB', 'Microphis brachyurus', NULL, 96, 'Es', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MIC', 'Micralestes spp.', 'JME - 18/12/02', 3, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MIE', 'Micralestes elongatus', 'JME - 18/12/02', 3, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MIT', 'Microchirus theophila', 'GIBAO - Arguin', 91, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MMA', 'Mormyrus macrophthalmus', NULL, 60, 'C', NULL, 1.288, 2.9330001, 2, NULL);
INSERT INTO ref_espece VALUES ('MME', 'Marcusenius mento', 'GIBAO', 60, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MMU', 'Mustelus mustelus', 'GIBAO', 123, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MOO', 'Mormyrops spp.', 'JME 15/05/03', 60, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MOR', 'Mormyrus spp.', 'JME 15/05/03', 60, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MOU', 'Mormyrops oudoti', NULL, 60, 'C', NULL, 0, 0, 0, 'MDE');
INSERT INTO ref_espece VALUES ('MPL', 'Myrophis plumbeus', NULL, 70, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MPO', 'Merluccius polli', 'GIBAO', 124, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MRB', 'Mycteroperca rubra', 'GIBAO', 90, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MRU', 'Mormyrus rume', NULL, 60, 'Co', NULL, 1.288, 2.9330001, 2, NULL);
INSERT INTO ref_espece VALUES ('MTH', 'Marcusenius thomasi', NULL, 60, 'Co', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MUG', 'Mugilidae', NULL, 62, NULL, NULL, 1.45, 2.9749999, 4, NULL);
INSERT INTO ref_espece VALUES ('MUL', 'Mugil spp', 'Demande JME 21/06/04', 62, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MUR', 'Murex sp.', NULL, 65, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('MVO', 'Macrobrachius volenvoli', NULL, 66, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('NBR', 'Negaprion brevirostris', 'GIBAO', 16, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('NHA', 'Nematopalaemon hastatus', '18/11/02', 105, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('NMA', 'Nematogobius maindroni', NULL, 41, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('NUD', 'Nudibranche', 'demande JME 03/03', 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('NUS', 'Nicholsina usta', 'GIBAO', 84, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('OAU', 'Oreochromis aureus', NULL, 20, 'C', NULL, 0, 0, 0, 'TNI');
INSERT INTO ref_espece VALUES ('OLI', 'Calamar', NULL, 52, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('OOC', 'Gobionellus occidentalis', NULL, 41, 'Es', 'p1-bt', 118.5, 1.841, 1, NULL);
INSERT INTO ref_espece VALUES ('OUN', 'Orcynopsis unicolor', NULL, 87, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('OVU', 'Octopus vulgaris', NULL, 69, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAA', 'Papyrocranus afer', 'corrig� le 16/05/08 (enlev� le h) - cf Fishbase et le Paugy', 68, 'Co', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAB', 'Pagellus bellottii', 'Demande JME 21/06/04 -ajout "t"- MS (23/07/09)', 92, 'M', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAC', 'Pagellus acarne', 'GIBAO', 92, 'M', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAF', 'Panopeus africanus', NULL, 103, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAN', 'Protopterus annectens', NULL, 80, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAT', 'Parapenaeopsis atlantica', 'corrig� le 18/11/02 (anc. Parapeneus atlanticus ??)', 73, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PAU', 'Pagrus auriga', 'GIBAO', 92, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PBA', 'Petrocephalus bane', NULL, 60, 'C', NULL, 0.76899999, 3.1760001, 2, NULL);
INSERT INTO ref_espece VALUES ('PBE', 'Psettodes belcheri', NULL, 81, 'Mo', 'p2-ge', 0.0057000001, 3.2218001, 8, NULL);
INSERT INTO ref_espece VALUES ('PBI', 'Polypterus bichir', NULL, 76, 'C', NULL, 0, 0, 0, 'PEN');
INSERT INTO ref_espece VALUES ('PBN', 'Psettodes bennettii', 'GIBAO', 81, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PBO', 'Petrocephalus bovei', NULL, 60, 'Co', 'p1-mc', 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('PBR', 'Pseudotolithus senegallus', 'anc. P. brachygnathus - MS (23/07/09)', 86, 'ME', 'p2-ge', 0.36000001, 3.135, 4, NULL);
INSERT INTO ref_espece VALUES ('PDU', 'Penaeus notialis', NULL, 73, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PEA', 'Petrocephalus ansorgii', 'LTDM - Mali 01', 60, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PEF', 'Pellonula leonensis', NULL, 23, 'Ec', 'p1-mc', 2.02, 2.855, 4, NULL);
INSERT INTO ref_espece VALUES ('PEH', 'Pagrus caeruleostictus', NULL, 92, 'Ma', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PEL', 'Pseudotolithus elongatus', NULL, 86, 'Em', 'p2-ge', 0.27000001, 3.1930001, 4, NULL);
INSERT INTO ref_espece VALUES ('PEM', 'Penaeus monodon', 'Gambie 02', 73, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PEN', 'Polypterus endlicheri', NULL, 76, 'Co', 'p2-ge', 0.84799999, 3.0150001, 8, NULL);
INSERT INTO ref_espece VALUES ('PEP', 'Pseudotolithus epipercus', NULL, 86, 'Mo', 'p2-ge', 0, 0, 0, 'PSS');
INSERT INTO ref_espece VALUES ('PER', 'Thysia ansorgii', NULL, 20, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PET', 'Petrocephalus spp.', 'demande JME 03/03', 60, 'C', NULL, 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('PGU', 'Chromidotilapia guentheri', NULL, 20, 'Co', 'p1-mc', 2.1329999, 2.9860001, 3, NULL);
INSERT INTO ref_espece VALUES ('PHP', 'Parailia pellucida', NULL, 85, 'Ce', 'p1-bt', 0.43000001, 3.3710001, 8, NULL);
INSERT INTO ref_espece VALUES ('PIN', 'Pomadasys incisus', NULL, 45, 'Ma', 'p1-bt', 0, 0, 0, 'POM');
INSERT INTO ref_espece VALUES ('PIS', 'Pisodonophis semicinctus', 'Pisonodophis corrige en Pisodonophis le 27/03/06 (LT)', 70, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PJU', 'Pomadasys jubelini', NULL, 45, 'Em', 'p1-bt', 1.23, 3.043, 4, NULL);
INSERT INTO ref_espece VALUES ('PKE', 'Penaeus kerathurus', NULL, 73, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PLA', 'Pegusa lascaris', 'Casamance - demande JME 05/07', 91, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PLG', 'Solitas gruveli', NULL, 74, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PLM', 'Plectorhinchus macrolepis', NULL, 45, 'Em', 'p2-ge', 3.1400001, 2.915, 4, NULL);
INSERT INTO ref_espece VALUES ('PLO', 'Parapenaeus longirostris', '18/11/02', 73, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PMA', 'Parakuhlia macrophthalmus', 'Saloum 34 - Mars 04 - JDD', 45, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PME', 'Plectorhinchus mediterraneus', 'JME 25/02/05 -corrig� le 25/10/07 (2r)', 45, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PMO', 'Pseudotolithus moorii', NULL, 86, 'Ma', 'p1-bt', 0, 0, 0, 'PSS');
INSERT INTO ref_espece VALUES ('PNI', 'Cephalopholis nigri', NULL, 90, 'Mo', NULL, 0, 0, 0, 'EAE');
INSERT INTO ref_espece VALUES ('POB', 'Parachanna obscura', NULL, 19, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('POC', 'Parapristipoma octolineatum', 'GIBAO', 45, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('POL', 'Polypterus spp.', NULL, 76, NULL, NULL, 0, 0, 0, 'PEN');
INSERT INTO ref_espece VALUES ('POM', 'Pomadasys spp.', NULL, 45, NULL, NULL, 3.1700001, 2.868, 4, NULL);
INSERT INTO ref_espece VALUES ('POQ', 'Polydactylus quadrifilis', NULL, 75, 'ME', 'p2-pi', 0.76999998, 3.0929999, 4, NULL);
INSERT INTO ref_espece VALUES ('PPA', 'Periophthalmus barbarus', 'corrig� le 16/05/08 (ajout� le second h) - cf Fishbase et le Paugy', 41, 'Es', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PPC', 'Paragaleus pectoralis', 'GIBAO', 125, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PPE', 'Pomadasys perotaei', NULL, 45, 'Em', 'p1-bt', 8.71, 2.677, 6, NULL);
INSERT INTO ref_espece VALUES ('PPR', 'Pseudupeneus prayensis', NULL, 63, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PQQ', 'Pentanemus quinquarius', NULL, 75, 'Ma', 'p2-ge', 0.1851, 3.3912001, 9, NULL);
INSERT INTO ref_espece VALUES ('PRA', 'Priacanthus arenatus', NULL, 79, 'Mo', 'p1-mc', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PRO', 'Pomadasys rogerii', NULL, 45, 'Mo', 'p1-bt', 0, 0, 0, 'POM');
INSERT INTO ref_espece VALUES ('PRP', 'Pristis pristis', 'GIBAO', 126, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PSA', 'Pomatomus saltatrix', 'GIBAO', 127, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PSB', 'Monodactylus sebae', NULL, 59, 'Es', 'p2-ge', 10.28, 2.7479999, 4, NULL);
INSERT INTO ref_espece VALUES ('PSE', 'Polypterus senegalus', 'ss-esp.  Senegalus supprim�e le 7/02/02', 76, 'C', NULL, 0, 0, 0, 'PEN');
INSERT INTO ref_espece VALUES ('PSN', 'Pseudotolithus senegalensis', NULL, 86, 'Ma', 'p2-ge', 0.30000001, 3.1819999, 4, NULL);
INSERT INTO ref_espece VALUES ('PSO', 'Petrocephalus soudanensis', 'LTDM - Mali 01', 60, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PSS', 'Pseudotolithus spp.', NULL, 86, NULL, NULL, 0.56, 3.0550001, 4, NULL);
INSERT INTO ref_espece VALUES ('PST', 'Psettodidae', 'GIBAO - Nov 2008', 81, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PSU', 'Pomadasys suillus', NULL, 45, 'Mo', NULL, 0, 0, 0, 'POM');
INSERT INTO ref_espece VALUES ('PTB', 'Pteromylaeus bovinus', 'BBG 10 (27/03/06)', 67, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PTE', 'Petrocephalus tenuicauda', NULL, 60, 'Co', 'p1-mc', 0, 0, 0, 'PBA');
INSERT INTO ref_espece VALUES ('PTP', 'Pteroscion peli', NULL, 86, 'ME', 'p1-mc', 6.1500001, 2.6949999, 5, NULL);
INSERT INTO ref_espece VALUES ('PTR', 'Pegusa triophthalma', 'LT 19/04/07 (ajout du 2nd h)', 91, 'Ma', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PTY', 'Pseudotolithus typus', NULL, 86, 'ME', 'p2-ge', 0.2613, 3.1503, 9, NULL);
INSERT INTO ref_espece VALUES ('PUM', 'Pugilina morio', 'BBG17', 133, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PVA', 'Portunus validus', 'Bamboung 6', 78, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('PYM', 'Pythonichthys macrurus', NULL, 48, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RAC', 'Rhizoprionodon acutus', NULL, 16, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RAJ', 'Raja spp.', 'GIBAO - Arguin', 128, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RAL', 'Rhinobatos albomaculatus', NULL, 83, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RBO', 'Rhinoptera bonasus', NULL, 67, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RCA', 'Rachycentron canadum', NULL, 82, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RCE', 'Rhinobatos cemiculus', NULL, 83, 'Ma', 'p2-ge', 9.1400003, 2.4579999, 6, NULL);
INSERT INTO ref_espece VALUES ('RHI', 'Rhinobatos spp.', NULL, 83, NULL, NULL, 0, 0, 0, 'RCE');
INSERT INTO ref_espece VALUES ('RLU', 'Rhynchobatus luebberti', 'GIBAO', 83, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RMA', 'Rhinoptera marginata', 'GIBAO', 67, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RMI', 'Raja miraletus', 'GIBAO', 128, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RNI', 'Raiamas nigeriensis', NULL, 26, 'C', NULL, 0, 0, 0, 'BDS');
INSERT INTO ref_espece VALUES ('RRE', 'Remora remora', 'GIBAO', 32, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RRH', 'Rhinobatos rhinobatos', 'GIBAO', 83, NULL, 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RSA', 'Rypticus saponaceus', 'GIBAO', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RSE', 'Raiamas senegalensis', NULL, 26, 'C', NULL, 0, 0, 0, 'BDS');
INSERT INTO ref_espece VALUES ('RST', 'Raja straeleni', 'GIBAO', 128, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('RUN', 'Raja undulata', 'GIBAO', 128, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SAC', 'Sarotherodon caudomarginatus', 'Baran (21/12/01)', 20, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SAL', 'Alectis alexandrinus', NULL, 15, 'Mo', 'p1-mc', 1.826, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('SAN', 'Scorpaena angolensis', NULL, 88, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SAR', 'Sardinella spp.', NULL, 23, NULL, NULL, 0, 0, 0, 'SEB');
INSERT INTO ref_espece VALUES ('SAU', 'Sardinella aurita', NULL, 23, 'Ma', 'p1-zo', 0, 0, 0, 'SEB');
INSERT INTO ref_espece VALUES ('SBA', 'Synodontis bastiani', NULL, 57, 'Co', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SBE', 'Sepia bertheloti', 'BancArguin mai08', 89, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SBU', 'Synodontis budgetti', NULL, 57, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCA', 'Synaptura cadenati', NULL, 91, 'Mo', 'p1-bt', 0.63700002, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('SCH', 'Schilbe spp.', '18/11/02', 85, 'C', NULL, 0, 0, 0, 'SMY');
INSERT INTO ref_espece VALUES ('SCL', 'Synodontis clarias', NULL, 57, 'C', NULL, 2.2490001, 3.0699999, 2, NULL);
INSERT INTO ref_espece VALUES ('SCM', 'Scorpaena maderensis', 'corrig� le 16/05/08 (madurensis en maderensis) - cf Fishbase', 88, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCN', 'Spondyliosoma cantharus', 'GIBAO', 92, 'M', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCO', 'Synodontys courteti', NULL, 57, 'C', NULL, 0, 0, 0, 'SYO');
INSERT INTO ref_espece VALUES ('SCP', 'Schedophilus pemarco', 'GIBAO', 129, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCR', 'Selar crumenophtalmus', NULL, 15, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCS', 'Scorpaena scrofa', NULL, 88, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SCU', 'Sarmatum curvatum', NULL, 111, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SEA', 'Serranus accraensis', 'GIBAO', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SEB', 'Sardinella maderensis', NULL, 23, 'ME', 'p1-zo', 1.61, 2.9779999, 4, NULL);
INSERT INTO ref_espece VALUES ('SEC', 'Serranus cabrilla', 'BancArguin mai08', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SEP', 'Sepia sp.', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SES', 'Serranus scriba', 'GIBAO', 90, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SEU', 'Synodontis eupterus', NULL, 57, 'C', NULL, 9.1739998, 2.779, 2, NULL);
INSERT INTO ref_espece VALUES ('SFI', 'Stromateus fiatola', NULL, 95, 'Mo', 'p2-ge', 1.9299999, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('SGA', 'Sarotherodon galilaeus', NULL, 20, 'C', NULL, 1.778, 3.04, 11, NULL);
INSERT INTO ref_espece VALUES ('SGE', 'Scriptaphyosemion geryi', 'GIBAO', 119, 'Co', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SGU', 'Sphyraena guachancho', NULL, 93, 'ME', 'p2-pi', 0, 0, 0, 'SPI');
INSERT INTO ref_espece VALUES ('SHI', 'Stephanolepis hispidus', NULL, 58, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SHO', 'Scarus hoefleri', NULL, 84, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SHU', 'Sesarma (chiromantes) huzardi', NULL, 42, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SIA', 'Siluranodon auritus', NULL, 85, 'C', NULL, 3, 3, 2, NULL);
INSERT INTO ref_espece VALUES ('SIN', 'Schilbe intermedius', NULL, 85, 'Ce', 'p1-mc', 0.2701, 3.2593, 9, NULL);
INSERT INTO ref_espece VALUES ('SIO', 'Sepiola spp.', 'BancArguin mai08', 114, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SJA', 'Scomber japonicus', 'GIBAO - Scomber colias Gmelin', 87, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SKA', 'Enneacampus kaupi', NULL, 96, 'Es', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SLE', 'Sierrathrissa leonensis', NULL, 23, 'C', NULL, 0, 0, 0, 'PEF');
INSERT INTO ref_espece VALUES ('SLU', 'Synaptura lusitanica', NULL, 91, 'Ma', 'p1-bt', 0.63700002, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('SLW', 'Sphyrna lewini', 'GIBAO', 112, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SMC', 'Syacium micrurum', 'GIBAO', 72, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SMI', 'Schilbe micropogon', NULL, 85, 'Co', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SMK', 'Sphyrna mokarran', 'GIBAO', 112, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SMY', 'Schilbe mystus', NULL, 85, 'Ce', NULL, 6.447, 2.5680001, 1, NULL);
INSERT INTO ref_espece VALUES ('SNE', 'Synodontis nigrita', NULL, 57, 'C', NULL, 7.3940001, 2.8429999, 2, NULL);
INSERT INTO ref_espece VALUES ('SNO', 'Scorpaena normani', 'GIBAO', 88, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SOC', 'Synodontis ocellifer', NULL, 57, 'C', NULL, 2.28, 2.9920001, 7, NULL);
INSERT INTO ref_espece VALUES ('SOL', 'Solea spp.', NULL, 91, NULL, NULL , NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SOS', 'Solea solea', 'GIBAO', 91, NULL, 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPA', 'Saurida brasiliensis', NULL, 97, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPB', 'Sphyraena barracuda', 'GIBAO', 93, 'ME', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPC', 'Sphyrna couardi', 'GIBAO', 112, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPE', 'Syngnathus pelagicus', 'Bamboung 02', 96, 'Ma', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPG', 'Pagrus pagrus', 'GIBAO', 92, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPH', 'Sphyraena spp.', 'JME 15/05/03', 93, NULL, NULL, 0, 0, 0, 'SPI');
INSERT INTO ref_espece VALUES ('SPI', 'Sphyraena afra', NULL, 93, 'ME', 'p2-pi', 1.84, 2.8010001, 4, NULL);
INSERT INTO ref_espece VALUES ('SPL', 'Sardina pilchardus', 'GIBAO', 23, NULL, 'p1-zo', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPR', 'Sparus aurata', 'GIBAO', 92, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPS', 'Sphyraena sphyraena', 'GIBAO', 93, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SPT', 'Spicara alta', 'GIBAO', 130, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SQM', 'Squilla mantis', NULL, 94, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SRO', 'Sardinella rouxi', 'GIBAO', 23, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SRU', 'Sparisoma rubripinne', 'GIBAO', 84, 'M', 'he-de', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SSA', 'Sarda sarda', 'GIBAO', 87, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SSC', 'Synodontis schall', NULL, 57, 'Co', 'om-ge', 3.9860001, 2.951, 2, NULL);
INSERT INTO ref_espece VALUES ('SSE', 'Solea senegalensis', 'GIBAO', 91, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SSF', 'Stephanolepis setifer', 'GIBAO - il s`agit en fait de SHI', 58, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SSL', 'Sarpa salpa', 'GIBAO', 92, NULL, 'he-de', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SSO', 'Synodontis sorex', NULL, 57, 'C', NULL, 2.0239999, 3.0369999, 2, NULL);
INSERT INTO ref_espece VALUES ('SSP', 'Sphoeroides spengleri', NULL, 98, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SST', 'Scorpaena stephanica', '25/11/02 (Saloum 31)', 88, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('STE', 'Stenorynchus', NULL, 54, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SUM', 'Sciaena umbra', 'GIBAO', 86, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SY1', 'Synodontis sp. 1', 'Mali 03', 57, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SYA', 'Syngnathus acus', 'GIBAO', 96, 'Ma', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SYB', 'Symphodus bailloni', 'BancArguin mai08', 113, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SYF', 'Synodontis filamentosus', NULL, 57, 'C', NULL, 3, 3, 2, NULL);
INSERT INTO ref_espece VALUES ('SYG', 'Synodontis gambiensis', NULL, 57, 'Ce', 'om-ge', 0.14910001, 3.4765, 9, NULL);
INSERT INTO ref_espece VALUES ('SYN', 'Syngnathidae', NULL, 96, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('SYO', 'Synodontis spp.', NULL, 57, NULL, NULL, 0.389, 3.29, 11, NULL);
INSERT INTO ref_espece VALUES ('SZY', 'Sphyrna zygaena', 'Casamance - demande JME 05/07', 112, 'M', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TAB', 'Thunnus albacares', 'GIBAO', 87, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TAL', 'Thunnus alalunga ', 'GIBAO', 87, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TAT', 'Megalops atlanticus', NULL, 56, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TBR', 'Tilapia brevimanus', NULL, 20, 'Co', 'om-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TBU', 'Tilapia buttikoferi', 'Baran (21/12/01)', 20, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TCR', 'Tylosurus crocodilus', NULL, 13, 'Mo', 'p2-pi', 0, 0, 0, 'HPI');
INSERT INTO ref_espece VALUES ('TDA', 'Tilapia dageti', NULL, 20, 'C', NULL, 0, 0, 0, 'TIL');
INSERT INTO ref_espece VALUES ('TET', 'Tetraodon sp.', NULL, 98, NULL, 'p2-ge', 0, 0, 0, 'LLA');
INSERT INTO ref_espece VALUES ('TFA', 'Trachinotus teraia', NULL, 15, 'Em', 'p1-bt', 1.84, 3.049, 4, NULL);
INSERT INTO ref_espece VALUES ('TGO', 'Trachinotus goreensis', 'Demande JME 21/06/04', 15, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TGU', 'Tilapia guineensis', NULL, 20, 'Es', 'he-de', 3.6900001, 2.898, 4, NULL);
INSERT INTO ref_espece VALUES ('THE', 'Sarotherodon melanotheron', NULL, 20, 'Es', 'he-ph', 6.7800002, 2.7780001, 4, NULL);
INSERT INTO ref_espece VALUES ('TIL', 'Tilapia spp.', NULL, 20, NULL, NULL, 4.5799999, 2.8559999, 4, NULL);
INSERT INTO ref_espece VALUES ('TIN', 'Tylochromis intermedius', NULL, 20, 'Co', 'he-de', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TJE', 'Tylochromis jentinki', NULL, 20, 'Es', 'p1-bt', 1.378, 3.0580001, 1, NULL);
INSERT INTO ref_espece VALUES ('TLE', 'Trichiurus lepturus', NULL, 100, 'ME', 'p2-pi', 0.003, 3.473, 4, NULL);
INSERT INTO ref_espece VALUES ('TLI', 'Tetraodon lineatus', NULL, 98, 'C', NULL, 3.1800001, 3, 8, NULL);
INSERT INTO ref_espece VALUES ('TMA', 'Tilapia mariae', NULL, 20, 'Ec', 'om-ge', 0, 0, 0, 'TIL');
INSERT INTO ref_espece VALUES ('TMY', 'Trachinocephalus myops', 'Bamboung 8', 97, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TNI', 'Oreochromis niloticus', NULL, 20, 'Co', NULL, 2.1329999, 2.9860001, 2, NULL);
INSERT INTO ref_espece VALUES ('TOB', 'Thunnus obesus ', 'GIBAO', 87, 'M', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TOM', 'Torpedo marmorata', NULL, 99, 'Mo', 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TOR', 'Torpedo sp.', NULL, 99, NULL, 'p2-ge', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TRA', 'Tylosurus acus rafale', NULL, 13, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TRM', 'Trachinotus maxillosus', 'Demande JME 21/06/04', 15, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TRT', 'Trachurus trachurus', 'GIBAO', 15, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TSU', 'Tylochromis sudanensis', 'DCN (4/02/02)', 20, 'C', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TTO', 'Torpedo torpedo', 'GIBAO', 99, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TTR', 'Trachurus trecae', NULL, 15, 'Mo', 'p1-zo', 0, 0, 0, 'CAR');
INSERT INTO ref_espece VALUES ('TYL', 'Tylochromis leonensis', NULL, 20, 'Co', 'he-de', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('TZI', 'Tilapia zillii', NULL, 20, 'C', NULL, 2.138, 2.96, 11, NULL);
INSERT INTO ref_espece VALUES ('UAF', 'Urogymnus asperrimus', NULL, 29, 'Mo', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('UCA', 'Umbrina canariensis', NULL, 86, 'M', 'p1-bt', 1.1, 3, 12, NULL);
INSERT INTO ref_espece VALUES ('ULE', 'Uroconger lepturus', NULL, 24, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('URO', 'Umbrina ronchus', 'Gambie 02', 86, 'Mo', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('USE', 'Uraspis secunda', 'GIBAO', 15, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('UTA', 'Uca tangeri', '18/11/02', 106, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('VSE', 'Selene dorsalis', NULL, 15, 'ME', 'p2-ge', 6.0300002, 2.7190001, 4, NULL);
INSERT INTO ref_espece VALUES ('XGL', 'Xiphias gladius', 'GIBAO', 131, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('XNO', 'Xyrichthys novacula', 'GIBAO', 113, 'M', NULL, NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ZSC', 'Zanobatus schoenleinii', 'GIBAO (Arguin)', 83, 'M', 'p1-bt', NULL, NULL, NULL, NULL);
INSERT INTO ref_espece VALUES ('ZFA', 'Zeus faber', NULL, 104, 'Mo', 'p2-pi', NULL, NULL, NULL, NULL);

ALTER TABLE ref_espece ENABLE TRIGGER ALL;

--
-- Data for Name: ref_famille; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_famille DISABLE TRIGGER ALL;

INSERT INTO ref_famille VALUES (1, 'Acanthuridae', 23, 0);
INSERT INTO ref_famille VALUES (2, 'Albulidae', 1, 0);
INSERT INTO ref_famille VALUES (3, 'Alestiidae', 9, 0);
INSERT INTO ref_famille VALUES (4, 'Anabantidae', 23, 0);
INSERT INTO ref_famille VALUES (5, 'Antennariidae', 17, 0);
INSERT INTO ref_famille VALUES (7, 'Aplysiidae', 21, 1);
INSERT INTO ref_famille VALUES (8, 'Apogonidae', 23, 0);
INSERT INTO ref_famille VALUES (9, 'Ariidae', 29, 0);
INSERT INTO ref_famille VALUES (10, 'Bagridae', 29, 0);
INSERT INTO ref_famille VALUES (11, 'Balistidae', 32, 0);
INSERT INTO ref_famille VALUES (12, 'Batrachoididae', 5, 0);
INSERT INTO ref_famille VALUES (13, 'Belonidae', 6, 0);
INSERT INTO ref_famille VALUES (14, 'Blenniidae', 23, 0);
INSERT INTO ref_famille VALUES (15, 'Carangidae', 23, 0);
INSERT INTO ref_famille VALUES (16, 'Carcharhinidae', 8, 0);
INSERT INTO ref_famille VALUES (17, 'Centropomidae', 23, 0);
INSERT INTO ref_famille VALUES (18, 'Chaetodontidae', 23, 0);
INSERT INTO ref_famille VALUES (19, 'Channidae', 23, 0);
INSERT INTO ref_famille VALUES (20, 'Cichlidae', 23, 0);
INSERT INTO ref_famille VALUES (21, 'Citharinidae', 9, 0);
INSERT INTO ref_famille VALUES (22, 'Clariidae', 29, 0);
INSERT INTO ref_famille VALUES (23, 'Clupeidae', 10, 0);
INSERT INTO ref_famille VALUES (24, 'Congridae', 2, 0);
INSERT INTO ref_famille VALUES (25, 'Cynoglossidae', 24, 0);
INSERT INTO ref_famille VALUES (26, 'Cyprinidae', 11, 0);
INSERT INTO ref_famille VALUES (27, 'Cyprinodontidae', 12, 0);
INSERT INTO ref_famille VALUES (28, 'Dactylopteridae', 27, 0);
INSERT INTO ref_famille VALUES (29, 'Dasyatidae', 26, 0);
INSERT INTO ref_famille VALUES (30, 'Diodontidae', 32, 0);
INSERT INTO ref_famille VALUES (31, 'Drepaneidae', 23, 0);
INSERT INTO ref_famille VALUES (32, 'Echeneidae', 23, 0);
INSERT INTO ref_famille VALUES (33, 'Eleotridae', 23, 0);
INSERT INTO ref_famille VALUES (34, 'Elopidae', 14, 0);
INSERT INTO ref_famille VALUES (35, 'Engraulidae', 10, 0);
INSERT INTO ref_famille VALUES (36, 'Ephippidae', 23, 0);
INSERT INTO ref_famille VALUES (37, 'Exocoetidae', 6, 0);
INSERT INTO ref_famille VALUES (38, 'Fistulariidae', 31, 0);
INSERT INTO ref_famille VALUES (39, 'Galatheidae', 13, 1);
INSERT INTO ref_famille VALUES (40, 'Gerreidae', 23, 0);
INSERT INTO ref_famille VALUES (41, 'Gobiidae', 23, 0);
INSERT INTO ref_famille VALUES (42, 'Grapsidae', 13, 1);
INSERT INTO ref_famille VALUES (43, 'Gymnarchidae', 22, 0);
INSERT INTO ref_famille VALUES (44, 'Gymnuridae', 26, 0);
INSERT INTO ref_famille VALUES (45, 'Haemulidae', 23, 0);
INSERT INTO ref_famille VALUES (46, 'Hemiramphidae', 6, 0);
INSERT INTO ref_famille VALUES (47, 'Hepsetidae', 9, 0);
INSERT INTO ref_famille VALUES (48, 'Heterenchelyidae', 2, 0);
INSERT INTO ref_famille VALUES (49, 'Inconnu Poisson', 15, 0);
INSERT INTO ref_famille VALUES (50, 'Lethrinidae', 23, 0);
INSERT INTO ref_famille VALUES (51, 'Lobotidae', 23, 0);
INSERT INTO ref_famille VALUES (52, 'Loliginidae', 33, 1);
INSERT INTO ref_famille VALUES (53, 'Lutjanidae', 23, 0);
INSERT INTO ref_famille VALUES (54, 'Magidae', 13, 1);
INSERT INTO ref_famille VALUES (55, 'Malapterudidae', 29, 0);
INSERT INTO ref_famille VALUES (56, 'Megalopidae', 14, 0);
INSERT INTO ref_famille VALUES (57, 'Mochokidae', 29, 0);
INSERT INTO ref_famille VALUES (58, 'Monacanthidae', 32, 0);
INSERT INTO ref_famille VALUES (59, 'Monodactylidae', 23, 0);
INSERT INTO ref_famille VALUES (60, 'Mormyridae', 22, 0);
INSERT INTO ref_famille VALUES (61, 'Moronidae', 23, 0);
INSERT INTO ref_famille VALUES (62, 'Mugilidae', 23, 0);
INSERT INTO ref_famille VALUES (63, 'Mullidae', 23, 0);
INSERT INTO ref_famille VALUES (64, 'Muraenidae', 2, 0);
INSERT INTO ref_famille VALUES (65, 'Muricidae', 19, 1);
INSERT INTO ref_famille VALUES (66, 'Mycetophilidae', 18, 1);
INSERT INTO ref_famille VALUES (67, 'Myliobatidae', 26, 0);
INSERT INTO ref_famille VALUES (68, 'Notopteridae', 22, 0);
INSERT INTO ref_famille VALUES (69, 'Octopodidae', 20, 1);
INSERT INTO ref_famille VALUES (70, 'Ophichthidae', 2, 0);
INSERT INTO ref_famille VALUES (72, 'Paralichthyidae', 24, 0);
INSERT INTO ref_famille VALUES (73, 'Penaeidae', 13, 1);
INSERT INTO ref_famille VALUES (74, 'Platycephalidae', 27, 0);
INSERT INTO ref_famille VALUES (75, 'Polynemidae', 23, 0);
INSERT INTO ref_famille VALUES (76, 'Polypteridae', 25, 0);
INSERT INTO ref_famille VALUES (77, 'Pomacentridae', 23, 0);
INSERT INTO ref_famille VALUES (78, 'Portunidae', 13, 1);
INSERT INTO ref_famille VALUES (79, 'Priacanthidae', 23, 0);
INSERT INTO ref_famille VALUES (80, 'Protopteridae', 16, 0);
INSERT INTO ref_famille VALUES (81, 'Psettodidae', 24, 0);
INSERT INTO ref_famille VALUES (82, 'Rhachycentridae', 23, 0);
INSERT INTO ref_famille VALUES (83, 'Rhinobatidae', 26, 0);
INSERT INTO ref_famille VALUES (84, 'Scaridae', 23, 0);
INSERT INTO ref_famille VALUES (85, 'Schilbeidae', 29, 0);
INSERT INTO ref_famille VALUES (86, 'Sciaenidae', 23, 0);
INSERT INTO ref_famille VALUES (87, 'Scombridae', 23, 0);
INSERT INTO ref_famille VALUES (88, 'Scorpaenidae', 27, 0);
INSERT INTO ref_famille VALUES (89, 'Sepiidae', 28, 1);
INSERT INTO ref_famille VALUES (90, 'Serranidae', 23, 0);
INSERT INTO ref_famille VALUES (91, 'Soleidae', 24, 0);
INSERT INTO ref_famille VALUES (92, 'Sparidae', 23, 0);
INSERT INTO ref_famille VALUES (93, 'Sphyraenidae', 23, 0);
INSERT INTO ref_famille VALUES (94, 'Squillidae', 30, 1);
INSERT INTO ref_famille VALUES (95, 'Stromateidae', 23, 0);
INSERT INTO ref_famille VALUES (96, 'Syngnathidae', 31, 0);
INSERT INTO ref_famille VALUES (97, 'Synodontidae', 4, 0);
INSERT INTO ref_famille VALUES (98, 'Tetraodontidae', 32, 0);
INSERT INTO ref_famille VALUES (99, 'Torpedinidae', 34, 0);
INSERT INTO ref_famille VALUES (100, 'Trichiuridae', 23, 0);
INSERT INTO ref_famille VALUES (101, 'Triglidae', 27, 0);
INSERT INTO ref_famille VALUES (102, 'Volutidae', 19, 1);
INSERT INTO ref_famille VALUES (103, 'Xanthidae', 13, 1);
INSERT INTO ref_famille VALUES (104, 'Zeidae', 35, 0);
INSERT INTO ref_famille VALUES (105, 'Palaemonidae', 13, 1);
INSERT INTO ref_famille VALUES (106, 'Ocypodidae', 13, 1);
INSERT INTO ref_famille VALUES (107, 'Ostreidae', 36, 1);
INSERT INTO ref_famille VALUES (108, 'Arcidae', 37, 1);
INSERT INTO ref_famille VALUES (109, 'Atherinidae', 38, 0);
INSERT INTO ref_famille VALUES (111, 'Inconnu non poisson', 39, 1);
INSERT INTO ref_famille VALUES (112, 'Sphyrnidae', 8, 0);
INSERT INTO ref_famille VALUES (113, 'Labridae', 23, 0);
INSERT INTO ref_famille VALUES (114, 'Sepiolidae', 40, 1);
INSERT INTO ref_famille VALUES (115, 'Bothidae', 24, 0);
INSERT INTO ref_famille VALUES (116, 'Ophidiidae', 41, 0);
INSERT INTO ref_famille VALUES (117, 'Malacanthidae', 23, 0);
INSERT INTO ref_famille VALUES (118, 'Claroteidae', 29, 0);
INSERT INTO ref_famille VALUES (119, 'Nothobranchiidae', 12, 0);
INSERT INTO ref_famille VALUES (120, 'Ginglymostomatidae', 42, 0);
INSERT INTO ref_famille VALUES (121, 'Lophiidae', 17, 0);
INSERT INTO ref_famille VALUES (122, 'Leptochariidae', 8, 0);
INSERT INTO ref_famille VALUES (123, 'Triakidae', 8, 0);
INSERT INTO ref_famille VALUES (124, 'Merlucciidae', 43, 0);
INSERT INTO ref_famille VALUES (125, 'Hemigaleidae', 8, 0);
INSERT INTO ref_famille VALUES (126, 'Pristidae', 44, 0);
INSERT INTO ref_famille VALUES (127, 'Pomatomidae', 23, 0);
INSERT INTO ref_famille VALUES (128, 'Rajidae', 26, 0);
INSERT INTO ref_famille VALUES (129, 'Centrolophidae', 23, 0);
INSERT INTO ref_famille VALUES (130, 'Centrachantidae', 23, 0);
INSERT INTO ref_famille VALUES (131, 'Xiphiidae', 23, 0);
INSERT INTO ref_famille VALUES (132, 'Ommastrephidae', 33, 1);
INSERT INTO ref_famille VALUES (133, 'Melongenidae', 19, 1);
INSERT INTO ref_famille VALUES (134, 'Arapaimidae', 22, 0);
INSERT INTO ref_famille VALUES (135, 'Pristigasteridae', 10, 0);

SELECT pg_catalog.setval('ref_famille_id_seq',135,true);

ALTER TABLE ref_famille ENABLE TRIGGER ALL;


--
-- Data for Name: ref_ordre; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_ordre DISABLE TRIGGER ALL;

INSERT INTO ref_ordre VALUES (1, 'Albuliformes');
INSERT INTO ref_ordre VALUES (2, 'Anguilliformes');
INSERT INTO ref_ordre VALUES (4, 'Aulopiformes');
INSERT INTO ref_ordre VALUES (5, 'Batrachoidiformes');
INSERT INTO ref_ordre VALUES (6, 'Beloniformes');
INSERT INTO ref_ordre VALUES (7, 'Caenogast�ropodes');
INSERT INTO ref_ordre VALUES (8, 'Carcharhiniformes');
INSERT INTO ref_ordre VALUES (9, 'Characiformes');
INSERT INTO ref_ordre VALUES (10, 'Clup�iformes');
INSERT INTO ref_ordre VALUES (11, 'Cypriniformes');
INSERT INTO ref_ordre VALUES (12, 'Cyprinodontiformes');
INSERT INTO ref_ordre VALUES (13, 'D�capodes');
INSERT INTO ref_ordre VALUES (14, 'Elopiformes');
INSERT INTO ref_ordre VALUES (15, 'Inconnu Poisson');
INSERT INTO ref_ordre VALUES (16, 'Lepidosir�niformes');
INSERT INTO ref_ordre VALUES (17, 'Lophiiformes');
INSERT INTO ref_ordre VALUES (18, 'Nematoc�res');
INSERT INTO ref_ordre VALUES (19, 'Neogast�ropodes');
INSERT INTO ref_ordre VALUES (20, 'Octopodes');
INSERT INTO ref_ordre VALUES (21, 'Opisthobranches');
INSERT INTO ref_ordre VALUES (22, 'Ost�oglossiformes');
INSERT INTO ref_ordre VALUES (23, 'Perciformes');
INSERT INTO ref_ordre VALUES (24, 'Pleuronectiformes');
INSERT INTO ref_ordre VALUES (25, 'Polypt�riformes');
INSERT INTO ref_ordre VALUES (26, 'Rajiformes');
INSERT INTO ref_ordre VALUES (27, 'Scorpaeniformes');
INSERT INTO ref_ordre VALUES (28, 'Sepiida');
INSERT INTO ref_ordre VALUES (29, 'Siluriformes');
INSERT INTO ref_ordre VALUES (30, 'Stomatopodes');
INSERT INTO ref_ordre VALUES (31, 'Syngnathiformes');
INSERT INTO ref_ordre VALUES (32, 'T�traodontiformes');
INSERT INTO ref_ordre VALUES (33, 'Teuthida');
INSERT INTO ref_ordre VALUES (34, 'Torp�diniformes');
INSERT INTO ref_ordre VALUES (35, 'Zeiformes');
INSERT INTO ref_ordre VALUES (36, 'Filibranches');
INSERT INTO ref_ordre VALUES (37, 'Arcoida');
INSERT INTO ref_ordre VALUES (38, 'Atheriniformes');
INSERT INTO ref_ordre VALUES (39, 'Inconnu non poisson');
INSERT INTO ref_ordre VALUES (40, 'Sepiolida');
INSERT INTO ref_ordre VALUES (41, 'Ophidiiformes');
INSERT INTO ref_ordre VALUES (42, 'Orectolobiformes');
INSERT INTO ref_ordre VALUES (43, 'Gadiformes');
INSERT INTO ref_ordre VALUES (44, 'Pristiformes');

SELECT pg_catalog.setval('ref_ordre_id_seq',44,true);

ALTER TABLE ref_ordre ENABLE TRIGGER ALL;

--
-- Data for Name: ref_origine_kb; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_origine_kb DISABLE TRIGGER ALL;

INSERT INTO ref_origine_kb VALUES (0, 'Inconnu');
INSERT INTO ref_origine_kb VALUES (1, 'Bert & Ecoutin, 1982');
INSERT INTO ref_origine_kb VALUES (2, 'Delta Central du Niger');
INSERT INTO ref_origine_kb VALUES (3, 'Lacs Maliens');
INSERT INTO ref_origine_kb VALUES (4, 'Ecoutin & Albaret, 2003');
INSERT INTO ref_origine_kb VALUES (5, 'Lagune Ebri� (Ecoutin & Albaret, 2003)');
INSERT INTO ref_origine_kb VALUES (6, 'Sin� Saloum (Ecoutin & Albaret, 2003)');
INSERT INTO ref_origine_kb VALUES (7, 'Fishbase (mediane)');
INSERT INTO ref_origine_kb VALUES (8, 'Fishbase (article)');
INSERT INTO ref_origine_kb VALUES (9, 'Estuaire de la Gambie');
INSERT INTO ref_origine_kb VALUES (10, 'Toutes donnees MEL');
INSERT INTO ref_origine_kb VALUES (11, 'Peuplements Lacs Maliens');
INSERT INTO ref_origine_kb VALUES (12, 'Moyenne b=3');

SELECT pg_catalog.setval('ref_origine_kb_id_seq',12,true);

ALTER TABLE ref_origine_kb ENABLE TRIGGER ALL;


--
-- Data for Name: ref_pays; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_pays DISABLE TRIGGER ALL;

INSERT INTO ref_pays VALUES ('0', 'aucun');
INSERT INTO ref_pays VALUES ('BN', 'Benin');
INSERT INTO ref_pays VALUES ('GA', 'Gambia, The');
INSERT INTO ref_pays VALUES ('GH', 'Ghana');
INSERT INTO ref_pays VALUES ('GV', 'Guinee');
INSERT INTO ref_pays VALUES ('IN', 'Inconnu');
INSERT INTO ref_pays VALUES ('IV', 'Cote d''Ivoire');
INSERT INTO ref_pays VALUES ('ML', 'Mali');
INSERT INTO ref_pays VALUES ('MR', 'Mauritanie');
INSERT INTO ref_pays VALUES ('NG', 'Niger');
INSERT INTO ref_pays VALUES ('PU', 'Guinee Bissau');
INSERT INTO ref_pays VALUES ('SG', 'Senegal');
INSERT INTO ref_pays VALUES ('SL', 'Sierra Leone');
INSERT INTO ref_pays VALUES ('TO', 'Togo');
INSERT INTO ref_pays VALUES ('UV', 'Burkina Faso');

ALTER TABLE ref_pays ENABLE TRIGGER ALL;


--
-- Data for Name: ref_secteur; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_secteur DISABLE TRIGGER ALL;

INSERT INTO ref_secteur VALUES (0, 0, 'aucun', 0, 0);
INSERT INTO ref_secteur VALUES (1, 1, 'Inconnu', 0, 1);
INSERT INTO ref_secteur VALUES (2, 1, 'Ebrie Secteur I', 22, 3);
INSERT INTO ref_secteur VALUES (3, 2, 'Ebrie Secteur II', 62, 3);
INSERT INTO ref_secteur VALUES (4, 3, 'Ebrie Secteur III', 71, 3);
INSERT INTO ref_secteur VALUES (5, 4, 'Ebrie Secteur IV', 86, 3);
INSERT INTO ref_secteur VALUES (6, 5, 'Ebrie Secteur V', 170, 3);
INSERT INTO ref_secteur VALUES (7, 6, 'Ebrie Secteur VI', 135, 3);
INSERT INTO ref_secteur VALUES (8, 1, 'DCN Niger Amont', 0, 4);
INSERT INTO ref_secteur VALUES (9, 2, 'DCN Djenneri', 0, 4);
INSERT INTO ref_secteur VALUES (10, 3, 'DCN Diaka', 0, 4);
INSERT INTO ref_secteur VALUES (11, 4, 'DCN Lacs', 2240, 4);
INSERT INTO ref_secteur VALUES (12, 5, 'DCN Nord Dunaire', 0, 4);
INSERT INTO ref_secteur VALUES (13, 6, 'DCN Niger Aval', 0, 4);
INSERT INTO ref_secteur VALUES (14, 1, 'Manantali Barrage', 0, 5);
INSERT INTO ref_secteur VALUES (15, 2, 'Manantali Centre', 0, 5);
INSERT INTO ref_secteur VALUES (16, 3, 'Manantali Amont', 0, 5);
INSERT INTO ref_secteur VALUES (18, 1, 'Selingue Barrage', 0, 6);
INSERT INTO ref_secteur VALUES (19, 2, 'Selingue Sankarani', 0, 6);
INSERT INTO ref_secteur VALUES (20, 3, 'Selingue Bale', 0, 6);
INSERT INTO ref_secteur VALUES (21, 1, 'Togo Secteur I', 0, 7);
INSERT INTO ref_secteur VALUES (22, 2, 'Togo Secteur II', 0, 7);
INSERT INTO ref_secteur VALUES (23, 1, 'Gambie Aval', 0, 10);
INSERT INTO ref_secteur VALUES (24, 2, 'Gambie Centre', 0, 10);
INSERT INTO ref_secteur VALUES (25, 3, 'Gambie Amont', 0, 10);
INSERT INTO ref_secteur VALUES (26, 1, 'Bandiala Aval', 0, 8);
INSERT INTO ref_secteur VALUES (27, 2, 'Bandiala Centre', 0, 8);
INSERT INTO ref_secteur VALUES (28, 3, 'Bandiala Amont', 0, 8);
INSERT INTO ref_secteur VALUES (29, 4, 'Diomboss', 0, 8);
INSERT INTO ref_secteur VALUES (30, 5, 'Saloum aval', 0, 8);
INSERT INTO ref_secteur VALUES (31, 6, 'Saloum zone 6', 0, 8);
INSERT INTO ref_secteur VALUES (32, 7, 'Saloum zone 7', 0, 8);
INSERT INTO ref_secteur VALUES (33, 8, 'Saloum amont', 0, 8);
INSERT INTO ref_secteur VALUES (34, 1, 'Aby Nord', 0, 2);
INSERT INTO ref_secteur VALUES (35, 2, 'Aby Sud', 0, 2);
INSERT INTO ref_secteur VALUES (36, 3, 'Aby Tendo', 0, 2);
INSERT INTO ref_secteur VALUES (37, 4, 'Aby Ehy', 0, 2);
INSERT INTO ref_secteur VALUES (38, 9, 'Mer devant Saloum', 0, 8);
INSERT INTO ref_secteur VALUES (39, 1, 'Casamance Aval', 0, 12);
INSERT INTO ref_secteur VALUES (40, 2, 'Casamance Centre', 0, 12);
INSERT INTO ref_secteur VALUES (41, 3, 'Casamance Amont', 0, 12);
INSERT INTO ref_secteur VALUES (42, 1, 'Dangara', 0, 13);
INSERT INTO ref_secteur VALUES (43, 1, 'Fatala Embouchure', 0, 14);
INSERT INTO ref_secteur VALUES (44, 2, 'Fatala Aval', 0, 14);
INSERT INTO ref_secteur VALUES (45, 3, 'Fatala Centre', 0, 14);
INSERT INTO ref_secteur VALUES (46, 4, 'Fatala Amont', 0, 14);
INSERT INTO ref_secteur VALUES (47, 4, 'Selingue Bale Centre', 0, 6);
INSERT INTO ref_secteur VALUES (48, 5, 'Selingue Bale Amont', 0, 6);
INSERT INTO ref_secteur VALUES (49, 6, 'Selingue Sankarani Centre', 0, 6);
INSERT INTO ref_secteur VALUES (50, 7, 'Selingue Sankarani Amont', 0, 6);
INSERT INTO ref_secteur VALUES (51, 1, 'AMP Bamboung', 0, 17);
INSERT INTO ref_secteur VALUES (52, 1, 'Bijagos', 0, 15);
INSERT INTO ref_secteur VALUES (53, 1, 'Rio Buba', 285, 16);
INSERT INTO ref_secteur VALUES (54, 2, 'AMP Sangako', 0, 17);
INSERT INTO ref_secteur VALUES (55, 3, 'AMP Diomboss', 0, 17);
INSERT INTO ref_secteur VALUES (56, 1, 'Arguin dans Parc', 0, 18);
INSERT INTO ref_secteur VALUES (57, 2, 'Arguin hors Parc', 0, 18);

SELECT pg_catalog.setval('ref_secteur_id_seq',57,true);

ALTER TABLE ref_secteur ENABLE TRIGGER ALL;


--
-- Data for Name: ref_systeme; Type: TABLE DATA; Schema: public; Owner: devppeao
--

ALTER TABLE ref_systeme DISABLE TRIGGER ALL;

INSERT INTO ref_systeme VALUES (0, 'aucun', '0', '0');
INSERT INTO ref_systeme VALUES (1, 'Inconnu', 'IN', 0);
INSERT INTO ref_systeme VALUES (2, 'Lagune Aby', 'IV', 424);
INSERT INTO ref_systeme VALUES (3, 'Lagune Ebrie', 'IV', 566);
INSERT INTO ref_systeme VALUES (4, 'Delta Central du Niger', 'ML', 27986);
INSERT INTO ref_systeme VALUES (5, 'Lac de Manantali', 'ML', 485);
INSERT INTO ref_systeme VALUES (6, 'Lac de Selingue', 'ML', 409);
INSERT INTO ref_systeme VALUES (7, 'Lac Togo', 'TO', 64);
INSERT INTO ref_systeme VALUES (8, 'Sine Saloum', 'SG', 848);
INSERT INTO ref_systeme VALUES (9, 'Lac de Korientze', 'ML', 0);
INSERT INTO ref_systeme VALUES (10, 'Estuaire de la Gambie', 'GA', 719);
INSERT INTO ref_systeme VALUES (11, 'Lagune de Grand Lahou', 'IV', 190);
INSERT INTO ref_systeme VALUES (12, 'Casamance', 'SG', 0);
INSERT INTO ref_systeme VALUES (13, 'Dangara', 'GV', 0);
INSERT INTO ref_systeme VALUES (14, 'Estuaire de la Fatala', 'GV', 56.299999);
INSERT INTO ref_systeme VALUES (15, 'Archipel des Bijagos', 'PU', 0);
INSERT INTO ref_systeme VALUES (16, 'Rio Buba', 'PU', 285);
INSERT INTO ref_systeme VALUES (17, 'Bolong Bamboung', 'SG', 0.68000001);
INSERT INTO ref_systeme VALUES (18, 'Banc d''Arguin', 'MR', 6000);

SELECT pg_catalog.setval('ref_systeme_id_seq',18,true);

ALTER TABLE ref_systeme ENABLE TRIGGER ALL;

