
-- script de creation de la base a utiliser pour l'application PPEAO
-- choisir les valeurs desirees pour le nom de la base : CREATE DATABASE lenomchoisi
-- et le nom de l'utilisateur ayant acces a cette base : WITH OWNER = lutilisateur
-- ces informations doivent etre celles saisies ensuite dans le fichier /connect.inc de l'application Web

CREATE DATABASE bdppeao
  WITH OWNER = devppeao
       ENCODING = 'LATIN9';