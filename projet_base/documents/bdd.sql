#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: TYPE UTILISATEUR
#------------------------------------------------------------

CREATE TABLE TYPE_UTILISATEUR(
        CodeTypeUtilisateur Varchar (5) NOT NULL ,
        Libelle             Varchar (50) NOT NULL
	,CONSTRAINT TYPE_UTILISATEUR_PK PRIMARY KEY (CodeTypeUtilisateur)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: UTILISATEUR
#------------------------------------------------------------

CREATE TABLE UTILISATEUR(
        NumUtilisateur      Int  Auto_increment  NOT NULL ,
        Identifiant         Varchar (50) NOT NULL ,
        MotDePasse          Varchar (100) NOT NULL ,
        Sel                 Varchar (50) NOT NULL ,
        DateInscription     Date NOT NULL ,
        CodeTypeUtilisateur Varchar (5) NOT NULL
	,CONSTRAINT UTILISATEUR_PK PRIMARY KEY (NumUtilisateur)

	,CONSTRAINT UTILISATEUR_TYPE_UTILISATEUR_FK FOREIGN KEY (CodeTypeUtilisateur) REFERENCES TYPE_UTILISATEUR(CodeTypeUtilisateur)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: JETON
#------------------------------------------------------------

CREATE TABLE JETON(
        NumUtilisateur Int NOT NULL ,
        Valeur         Varchar (100) NOT NULL ,
        DateExpiration Date NOT NULL
	,CONSTRAINT JETON_PK PRIMARY KEY (NumUtilisateur)

	,CONSTRAINT JETON_UTILISATEUR_FK FOREIGN KEY (NumUtilisateur) REFERENCES UTILISATEUR(NumUtilisateur)
)ENGINE=InnoDB;

