-- MySQL dump 10.13  Distrib 8.3.0, for macos14.2 (x86_64)
--
-- Host: 127.0.0.1    Database: my_quizz
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie`
--

LOCK TABLES `categorie` WRITE;
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` VALUES (1,'Harry Potter'),(2,'\nSigles Français'),(3,'\nDéfinitions de mots'),(4,'\nLes spécialités culinaires'),(5,'\nSéries TV : les simpson - partie 1'),(6,'\nSéries TV : les simpson - partie 2'),(7,'\nSéries TV : Stargate SG1'),(8,'\nSéries TV : NCIS'),(9,'\nJeux de société'),(10,'\nProgrammation'),(11,'\nSigles informatiques');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20240513102707','2024-05-13 10:27:58',56),('DoctrineMigrations\\Version20240513205306','2024-05-13 20:53:57',92),('DoctrineMigrations\\Version20240513205909','2024-05-13 20:59:38',40);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_categorie` int NOT NULL,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,1,'Dans la partie déchec, Harry Potter prend la place de :'),(2,1,'Quel est le mot de passe du bureau de Dumbledore ?'),(3,1,'Quel chiffre est écrit à l\'avant du Poudlard Express ?'),(4,1,'Avec qui Harry est-il interdit de jouer à vie au Quidditch par Ombrage ?'),(5,1,'Sur quelle(s) main(s) Harry s\'écrit-il je ne dois pas dire de mensonge pendant ses retenues avec Ombrage ?'),(6,1,'Everard et Dilys sont :'),(7,1,'Quel est le prénom du professeur Gobe-Planche ?'),(8,1,'Quel est le nom de jeune fille de Molly Weasley ?'),(9,1,'Lequel de ces Mangemorts n\'était pas présent lors de l\'invasion au ministère ?'),(10,1,'En quelle année sont morts les parents de Harry Potter ?'),(11,2,'Que signifie AOC ?'),(12,2,'Que signifie CROUS ?'),(13,2,'Que signifie FAI ?'),(14,2,'Que signifie l\'INSEE ?'),(15,2,'Que signifie ADN ?'),(16,2,'Que signifie SAMU ?'),(17,2,'Que signifie SFR ?'),(18,2,'Que signifie FNAC ?'),(19,2,'Que signifie RATP ?'),(20,2,'Que signifie SMIC ?'),(21,3,'Que signifie le verbe Enrêner ?'),(22,3,'Qu\'est-ce qu\'un protocole ?'),(23,3,'Que fait une langue qui est protractile ?'),(24,3,'Qu\'est ce qu\'un Campanile ?'),(25,3,'Que signifie le mot « gentilé » ?'),(26,3,'Qu\'est ce qu\'un Pugilat ?'),(27,3,'Parmi ces définitions, laquelle n\'est pas une torpille ?'),(28,3,'Qu\'est ce que la déontologie ?'),(29,3,'Qu\'est ce qu\'un carcan ?'),(30,3,'Que signifie le terme univoque ?'),(31,4,'Quelle est la spécialité de Reims ?'),(32,4,'Parmi ces spécialités, laquelle ne fait pas partie du patrimoine gastronomique de la ville de Troyes ?'),(33,4,'Dans quelle département trouve-t-on les lentilles du Puy ?'),(34,4,'Dans quel département trouve-t-on la Teurgoule ?'),(35,4,'Quel fromage ne trouve-t-on pas en Normandie ?'),(36,4,'Parmi ces spécialités, laquelle ne vient pas de la région PACA ?'),(37,4,'Quelle est la spécialité de Tours ?'),(38,4,'Parmi ces biscuits lesquelles ne vient pas de Bretagne ?'),(39,4,'Quelle est le nom de cette recette: Lamproie à la ?'),(40,4,'Le Kouglof est une spécialité de :'),(41,5,'Comment s\'appelle le père d\'Homer Simpson ?'),(42,5,'Quel est le nom du dessin animé gore préféré de Bart et Lisa Simpson ?'),(43,5,'De quel instrument joue Lisa Simpson ?'),(44,5,'Comment s\'appelle le meilleur ami de Bart ?'),(45,5,'Quelle est la profession de Wiggum ?'),(46,5,'Qui en veut à la vie de Bart Simpson ?'),(47,5,'Qui est Smithers ?'),(48,5,'Quelle est la nationalité de Willy ?'),(49,5,'Quelle est la nourriture préférée d\'Homer ?'),(50,5,'Dans quelle ville habitent les Simpson ?'),(51,6,'Qui est le créateur des Simpson ?'),(52,6,'Quel est le nom de jeune fille de Marge Simpson ?'),(53,6,'Que faisait le chien des Simpson avant qu\'ils l\'adoptent ?'),(54,6,'Où Maud Flanders trouva t-elle la mort ?'),(55,6,'Quelle réplique prononce très souvent Homer Simpson ?'),(56,6,'Comment s\'appelle la bière préférée des habitat de Springfield ?'),(57,6,'Comment s\'appelle la mère d\'Homer ?'),(58,6,'Comment s\'appelle la ville voisine et ennemie de Springfrield ?'),(59,6,'Quelle est l\'une des particularités de Moe ?'),(60,7,'Où se trouve la base de commandement du SGC ?'),(61,7,'Comment s\'appelle les crabes métalliques qui se reproduisent rapidement en se nourrissant de métal ?'),(62,7,'Combien a y-t-il de saisons dans Stargate SG1 ?'),(63,7,'Dans l\'épisode « L\'histoire sans fin » que font Jack et Teal\'c d\'assez particulier ?'),(64,7,'Qui est le commandant suprême de la flotte Asgard ?'),(65,7,'De qui Jolinar était-elle la compagne ?'),(66,7,'Quel mot désigne les habitants de la planète Terre ?'),(67,7,'De qui Sha\'are devient-elle l\'hôte ?'),(68,7,'L\'alliance des quatre races est composée des Anciens, Des Asgards, des Furlings et..'),(69,7,'Comment meurt Daniel Jackson avant de faire son Ascension et d\'être ensuite remplacé par Jonas Quinn ?'),(70,8,'Quels sont les prénoms de Gibbs ?'),(71,8,'Comment est morte Kate à la fin de la deuxième saison ?'),(72,8,'Quelle est la boisson préférée d\'Abby ?'),(73,8,'Qui est en réalité Jeanne Benoit, la petite amie de Tony dans la Saison 4 ?'),(74,8,'De quelle grave maladie Tony DiNozzo est infectée dans la saison 2 ?'),(75,8,'A part les filles, quelle est la grande passion de Tony DiNozzo ?'),(76,8,'Ziva David est un ancien officier du  ?'),(77,8,'Lorsque Gibbs décide de démissionner à la fin de la Saison 3, quel personnage devient le chef de l\'équipe ?'),(78,8,'Avec quel agent Palmer a-t-il eu une liaison ?'),(79,8,'Comment Jenny Shepard trouve-t-elle la mort au court de la saison 5 ?'),(80,9,'Lequel de ces navires ne se retrouvent pas dans le « Toucher-couler » ?'),(81,9,'Laquelle de ces couleurs n\'existe pas au Trivial poursuite traditionnel ?'),(82,9,'Laquelle de ces lettres vaut 10 points au scrabble ?'),(83,9,'Quelle est la rue qui coute le moins cher au Monopoly français ?'),(84,9,'Dans le monopoly d\'origine combien gagnait-on en passant par la case départ ?'),(85,9,'Parmi ces pays, lequel n\'est pas présent sur le plateau du jeu « Risk » ?'),(86,9,'Combien y a-t-il de flèches au Backgammon ?'),(87,9,'Lequel de ces déplacement n\'existe pas aux échecs ?'),(88,9,'Au jeu du Cluedo qui est professeur ?'),(89,9,'Comment appelle-t-on le groupe de cartes au 1000 bornes qui comprend : As du volant, camion-citerne, increvable, prioritaire....'),(90,10,'Lequel de ces langages ne peut pas être exécuté côté serveur ?'),(91,10,'Lequel de ces langages a la vitesse d\'éxécution la plus rapide ?'),(92,10,'Quel est l\'animal qui représente habituellement le langage PHP ?'),(93,10,'Lequel de ces systèmes d\'exploitation est sous environnement UNIX ?'),(94,10,'Lequel de ces langages est reconnu pour sa grande portabilité et sa flexibilité ?'),(95,10,'Laquelle de ces propositions n\'est pas un langage de programmation ?'),(96,10,'Quelle commande permet de planifier l\'éxécution de tâches sous UNIX ?'),(97,10,'Quel est le composant principal d\'un ordinateur, sur lequel sont greffés les autres ?'),(98,10,'Quel port externe n\'existe pas sur un ordinateur ?'),(99,10,'Quel nom d\'attaque n\'existe pas dans le domaine de la sécurité ?'),(100,11,'Que signifie HTTP ?'),(101,11,'Que signifie SSL ?'),(102,11,'Que signifie FTP ?'),(103,11,'Laquelle de ces propositions n\'est pas un SGBDR ?'),(104,11,'Que signifie WWW ?'),(105,11,'Que signifie URI ?'),(106,11,'Que signifie IP ?'),(107,11,'Qu\'est-ce que peut évoquer REMOTE_ADDR ?'),(108,11,'Laquelle de ces propositions n\'est pas une IP correcte ?'),(109,11,'Laquelle de ces propositions n\'est pas une MAC correcte ?');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reponse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_question` int NOT NULL,
  `reponse` varchar(255) NOT NULL,
  `reponse_expected` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reponse`
--

LOCK TABLES `reponse` WRITE;
/*!40000 ALTER TABLE `reponse` DISABLE KEYS */;
INSERT INTO `reponse` VALUES (1,1,'Un fou',1),(2,1,'Une tour',0),(3,1,'Un pion',0),(4,2,'Sorbet Citron',1),(5,2,'Chocogrenouille',0),(6,2,'Dragées Surprise',0),(7,3,'5972',1),(8,3,'4732',0),(9,3,'6849',0),(10,4,'George Weasley',1),(11,4,'Fred Weasley',0),(12,4,'Drago Malefoy',0),(13,5,'La droite',1),(14,5,'La gauche',0),(15,5,'Les deux',0),(16,6,'Deux directeurs de Poudlard',1),(17,6,'Deux amants célèbres de Poudlard',0),(18,6,'Deux préfets en chef',0),(19,7,'Wilhelmina',1),(20,7,'Libellia',0),(21,7,'Carlotta',0),(22,8,'Prewett',1),(23,8,'Foist',0),(24,8,'Jugson',0),(25,9,'Rowle',1),(26,9,'Crabbe',0),(27,9,'Goyle',0),(28,10,'1981',1),(29,10,'1982',0),(30,10,'1983',0),(31,11,'Appellation d\'Origine Contrôlée',1),(32,11,'Aliment Original Contrôlé',0),(33,11,'Association des Obligations des Consommateurs',0),(34,12,'Centre Régional des Oeuvres Universitaires et Scolaires',1),(35,12,'Centre de Restauration et d\'Organisation Universitaire et Secondaire',0),(36,12,'Comité Régional pour l\'Organisation Universitaire et Scolaire',0),(37,13,'Fournisseur d\'Accès Internet',1),(38,13,'Fournisseur d\'Alimention et d\'Informatique',0),(39,13,'Fédération à l\'Accès Informatique',0),(40,14,'Institut National de la Statistique et des Études Économiques',1),(41,14,'Institut National de Service pour l\'Économie et l\'Enseignement',0),(42,14,'Institution Nationalisé pour les Statistiques des Établissements Économiques',0),(43,15,'Acide Desoxyriboucléique',1),(44,15,'Atome Desoxygénénucléique',0),(45,15,'Aspérité Desoxygéné et Nucléanbique',0),(46,16,'Service d\'Aide Médicale Urgente',1),(47,16,'Service d\'Ambulance et de Médecine d\'Urgence',0),(48,16,'Service Auxiliaire Mutualisé d\'Urgence',0),(49,17,'Société Française de Radiotéléphone',1),(50,17,'Société Francophone des Réseaux',0),(51,17,'Société Financière et Radio-téléphonique',0),(52,18,'Fédération Nationale d\'Achat des Cadres',1),(53,18,'Franchise Nationale d\'Art et de Culture',0),(54,18,'Firme Nationale d\'Achat Culturel',0),(55,19,'Régie autonome des transports parisiens',1),(56,19,'Reseaux automatisé des transports parisiens',0),(57,19,'Régie automatique des transports de Paris',0),(58,20,'Salaire Minimum Interprofessionnel de Croissance',1),(59,20,'Salaire Médian d\'Intérêt Communautaire',0),(60,20,'Salaire Moyen d\'Insertion de Croissance',0),(61,21,'Mettre des rênes',1),(62,21,'Etre dépendent de quelque chose',0),(63,21,'Etre à l\'origine d\'un fait',0),(64,22,'Un ensemble de règles établies',1),(65,22,'Le fait de savoir parler plusieurs langues',0),(66,22,'Une série de chiffre',0),(67,23,'Elle peut être étirée vers l\'avant',1),(68,23,'Elle peut se diviser en deux',0),(69,23,'Elle peut s\'enrouler sur elle même',0),(70,24,'Un cloché',1),(71,24,'Une maison de campagne',0),(72,24,'Une forteresse',0),(73,25,'C\'est le nom des habitants d\'un lieu',1),(74,25,'C\'est un synonyme du mot gentillesse',0),(75,25,'C\'est le nom du mouvement que l\'on fait avec un tournevis',0),(76,26,'Un combat au corps à corps',1),(77,26,'Une demande d\'audience',0),(78,26,'Une sorte de dague',0),(79,27,'Une espèce de calamar',1),(80,27,'Un poisson qui ressemble à une raie',0),(81,27,'Un engin automoteur sous-marin',0),(82,28,'Le code de conduite d\'une profession',1),(83,28,'Une partie de la médecine qui étudie la peau',0),(84,28,'L\'étude des facultés psychiques des dauphins',0),(85,29,'Une contrainte qui entrave la liberté',1),(86,29,'Une sorte de montre',0),(87,29,'Une pièce de tissu',0),(88,30,'Qui n\'a qu\'un sens',1),(89,30,'Qui a plusieurs sens',0),(90,30,'Qui est sans contrainte',0),(91,31,'Le biscuit rose',1),(92,31,'Le trou rémois',0),(93,31,'Le cidre rosé',0),(94,32,'La pâte de fruit à la mirabelle',1),(95,32,'Le chaource',0),(96,32,'L\'andouillette',0),(97,33,'Haute Loire',1),(98,33,'Allier',0),(99,33,'Cantal',0),(100,34,'Le Calvados',1),(101,34,'Le cantal',0),(102,34,'L\'ardèche',0),(103,35,'Saint Félicien',1),(104,35,'Livarot',0),(105,35,'Neufchâtel',0),(106,36,'Le cassoulet',1),(107,36,'La tapenade',0),(108,36,'Les calissons',0),(109,37,'Les rillons',1),(110,37,'Le confis',0),(111,37,'Le magret',0),(112,38,'Les madeleines',1),(113,38,'Les craquelins',0),(114,38,'Les gavottes',0),(115,39,'Bordelaise',1),(116,39,'Toulousaine',0),(117,39,'Marseillaise',0),(118,40,'L\'Alsace',1),(119,40,'La lorraine',0),(120,40,'La Franche comté',0),(121,41,'Abraham',1),(122,41,'Georges',0),(123,41,'Francis',0),(124,42,'Itchy et Scratchy show',1),(125,42,'Les tronçonneuses folles',0),(126,42,'Cat and dog',0),(127,43,'Du saxophone',1),(128,43,'De la trompette',0),(129,43,'De la clarinette',0),(130,44,'Milhouse',1),(131,44,'Martin',0),(132,44,'Ralph',0),(133,45,'C\'est le chef de la police',1),(134,45,'Il est vendeur de BD',0),(135,45,'C\'est le vrai nom de l\'homme Abeille',0),(136,46,'Tahiti Bob',1),(137,46,'Krusty le clown',0),(138,46,'L\'homme Abeille',0),(139,47,'L\'assistant du président de la centrale nucléaire',1),(140,47,'Un collègue d\'Homer Simpson',0),(141,47,'Le président de la centrale nucléaire où travaille Homer',0),(142,48,'Ecossais',1),(143,48,'Canadien',0),(144,48,'Australien',0),(145,49,'Les donuts',1),(146,49,'Les pizzas',0),(147,49,'Les hamburgers',0),(148,50,'Springfield',1),(149,50,'Sheffield',0),(150,50,'Shortfield',0),(151,51,'Matt Groening',1),(152,51,'Seth Mac Farlane',0),(153,51,'Glenn Eichler',0),(154,52,'Bouvier',1),(155,52,'Polsen',0),(156,52,'March',0),(157,53,'De la course de lévriers',1),(158,53,'C\'était un chien d\'aveugle',0),(159,53,'Il était chien policer',0),(160,54,'Dans les gradins d\'un stade',1),(161,54,'Elle disparaît en mer',0),(162,54,'Dans la maison des Simpson',0),(163,55,'Oh punaise!',1),(164,55,'Oh mon dieu!',0),(165,55,'Oh bravo!',0),(166,56,'La Duff',1),(167,56,'La Kronekein',0),(168,56,'La Spiner',0),(169,57,'Mona',1),(170,57,'Gina',0),(171,57,'Dina',0),(172,58,'Shelbyville',1),(173,58,'Summerville',0),(174,58,'Stringville',0),(175,59,'Il a des tendances suicidaires',1),(176,59,'Il est ventriloque',0),(177,59,'Il vole dans les supermarchés',0),(178,60,'Dans le Colorado',1),(179,60,'Dans l\'Arizona',0),(180,60,'Dans l\'Utah',0),(181,61,'Les réplicateurs',1),(182,61,'Les réplicants',0),(183,61,'Les répliqueurs',0),(184,62,'10 Saisons',1),(185,62,'8 Saisons',0),(186,62,'12 Saisons',0),(187,63,'Ils font du golf avec la porte des étoiles',1),(188,63,'Ils jouent au tennis dans les couloirs de la base',0),(189,63,'Ils font du camping dans la base',0),(190,64,'Thor',1),(191,64,'Loki',0),(192,64,'Penegal',0),(193,65,'Martouf',1),(194,65,'Selmak',0),(195,65,'Malek',0),(196,66,'Les Tau\'ri',1),(197,66,'Les Tok\'ra',0),(198,66,'Les Oris',0),(199,67,'Amonet',1),(200,67,'Amaterasu',0),(201,67,'Hathor',0),(202,68,'Des Nox',1),(203,68,'Des Ori',0),(204,68,'Des Unas',0),(205,69,'Il absorbe une dose de radiation',1),(206,69,'Il est tué par Apophis',0),(207,69,'Il tombe dans un ravin',0),(208,70,'Leroy Jethro',1),(209,70,'Jack Lenny',0),(210,70,'Lance Jimmy',0),(211,71,'D\'une balle dans la tête',1),(212,71,'Lors d\'une explosion',0),(213,71,'En tombant d\'un toit',0),(214,72,'Un soda caféine',1),(215,72,'Un diabolo menthe',0),(216,72,'Un thé glacé',0),(217,73,'La fille d\'un trafiquant d\'armes',1),(218,73,'Une espionne russe',0),(219,73,'Un agent double de la CIA',0),(220,74,'La peste',1),(221,74,'La tuberculose',0),(222,74,'Le cholera',0),(223,75,'Le cinéma',1),(224,75,'Le base-ball',0),(225,75,'Les voitures de courses',0),(226,76,'Mossad',1),(227,76,'KGB',0),(228,76,'NSA',0),(229,77,'Tony',1),(230,77,'Ziva',0),(231,77,'McGee',0),(232,78,'Lee',1),(233,78,'Ziva',0),(234,78,'Kate',0),(235,79,'Lors d\'une fusillade',1),(236,79,'Lors d\'un accident de voiture',0),(237,79,'Lors d\'une explosion',0),(238,80,'Un cuirassé',1),(239,80,'Un sous-marin',0),(240,80,'Un porte-avions',0),(241,81,'Rouge',1),(242,81,'Orange',0),(243,81,'Vert',0),(244,82,'K',1),(245,82,'J',0),(246,82,'Q',0),(247,83,'Boulevard de Belleville',1),(248,83,'Rue de Vaugirard',0),(249,83,'Rue Lecourbe',0),(250,84,'20 000 francs',1),(251,84,'2 000 francs',0),(252,84,'50 000 francs',0),(253,85,'Russie',1),(254,85,'Ukraine',0),(255,85,'Chine',0),(256,86,'24',1),(257,86,'12',0),(258,86,'32',0),(259,87,'Le pool',1),(260,87,'Le roque',0),(261,87,'En passant',0),(262,88,'Violet',1),(263,88,'Olive',0),(264,88,'Orange',0),(265,89,'Les bottes',1),(266,89,'Les parades',0),(267,89,'Les attaques',0),(268,90,'HTML',1),(269,90,'JavaScript',0),(270,90,'PHP',0),(271,91,'C',1),(272,91,'PHP',0),(273,91,'Python',0),(274,92,'Elephant',1),(275,92,'Serpent',0),(276,92,'Souris',0),(277,93,'Debian',1),(278,93,'Windows',0),(279,93,'Java',0),(280,94,'Java',1),(281,94,'Python',0),(282,94,'C++',0),(283,95,'Saphir',1),(284,95,'Ruby',0),(285,95,'Perl',0),(286,96,'crontab',1),(287,96,'task',0),(288,96,'run',0),(289,97,'Carte mère',1),(290,97,'Processeur',0),(291,97,'Carte graphique',0),(292,98,'VGE',1),(293,98,'HDMI',0),(294,98,'USB',0),(295,99,'MS-DOS 95',1),(296,99,'DDOS',0),(297,99,'Bruteforce',0),(298,100,'Hyper Text Transfer Protocol',1),(299,100,'Host Type Text Protocol',0),(300,100,'Host Trame Transfer Protocol',0),(301,101,'Secure Socket Layer',1),(302,101,'Socket Same Loundge',0),(303,101,'Security Socket Law',0),(304,102,'File Transfer Protocol',1),(305,102,'Film Transfert Processus',0),(306,102,'File Trame Pratical',0),(307,103,'CSV',1),(308,103,'MySQL',0),(309,103,'MongoDB',0),(310,104,'World Wide Web',1),(311,104,'Word Wild Web',0),(312,104,'Warp World Web',0),(313,105,'Uniform Resource Identifier',1),(314,105,'Ulimit Redirection Id',0),(315,105,'Unity Range Information',0),(316,106,'Internet Protocol',1),(317,106,'Internic Procedural',0),(318,106,'Internal Processus',0),(319,107,'Une Adresse IP',1),(320,107,'Une Adresse MAC',0),(321,107,'Une Prise de contrôle',0),(322,108,'128.256.0.1',1),(323,108,'127.0.0.1',0),(324,108,'255.255.0.0',0),(325,109,'EX:3F:7E:E6:2D:58',1),(326,109,'EA:9D:00:5B:CE:FF',0),(327,109,'AA:BB:CC:DD:EE:FF',0);
/*!40000 ALTER TABLE `reponse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(180) COLLATE utf8mb3_unicode_ci NOT NULL,
  `firstname` varchar(180) COLLATE utf8mb3_unicode_ci NOT NULL,
  `lastname` varchar(180) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT (now()),
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uuid` varchar(180) COLLATE utf8mb3_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (3,'judikaelbellance@icloud.com','jud3v','judikael','bellance','2024-05-14 08:10:27','2024-05-14 08:10:27','66431c733a80d','[]','$2y$13$B8DneKo.CWy3WOfX5TSQwuCYVwRXCwrcD27uza4QNLxh6.et.v6R2',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-14 14:55:28
