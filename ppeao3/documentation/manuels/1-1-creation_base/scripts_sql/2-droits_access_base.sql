
-- script de creation de la base a utiliser pour l'application PPEAO
-- attribution des droits d'acces a l'utilisateur 
-- le nom de l'utilisateur doit etre celui utilise dans le script de creation de la base
-- note : ce script est séparé du script de creation de la base car il n'est pas possible de faire un CREATE DATABASE et un GRANT dans la meme instruction

GRANT ALL ON DATABASE bdppeao TO devppeao;
GRANT ALL ON DATABASE bdppeao TO postgres WITH GRANT OPTION;