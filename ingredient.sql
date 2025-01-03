-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 08 nov. 2024 à 09:44
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `planification_repas`
--

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

CREATE TABLE `ingredient` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantite_defaut` double DEFAULT NULL,
  `unite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`id`, `nom`, `quantite_defaut`, `unite`) VALUES
(1, 'Ail en poudre', 1, 'g'),
(2, 'Abondance', 1, 'piece'),
(3, 'Abricot', 1, 'piece'),
(4, 'Abricot au sirop', 1, 'piece'),
(5, 'Abricot dénoyauté cru', 1, 'piece'),
(6, 'Abricot sec', 1, 'g'),
(7, 'Accra de poisson', 1, 'g'),
(8, 'Agar-agar', 1, 'g'),
(9, 'Agneau', 1, 'g'),
(10, 'Agneau épaule', 1, 'g'),
(11, 'Agneau gigot', 1, 'g'),
(12, 'Ail cru', 1, 'g'),
(13, 'ail frais', 1, 'g'),
(14, 'Ail poudre', 1, 'g'),
(15, 'Alcool pur', 1, 'g'),
(16, 'Allumettes de Bacon', 1, 'g'),
(17, 'Allumettes de jambon', 1, 'g'),
(18, 'Amande', 1, 'g'),
(19, 'Amande grillée salée', 1, 'g'),
(20, 'Amandes éffilées', 1, 'g'),
(21, 'Amarante crue', 1, 'g'),
(22, 'Amidon de maïs ou fécule de maïs', 1, 'g'),
(23, 'Amidon de riz', 1, 'g'),
(24, 'Ananas', 1, 'g'),
(25, 'Ananas', 1, 'g'),
(26, 'Anchois au sel', 1, 'g'),
(27, 'Anchois commun cru', 1, 'g'),
(28, 'Andouille', 1, 'g'),
(29, 'Andouille de Guéméné', 1, 'g'),
(30, 'Andouille de Vire', 1, 'g'),
(31, 'Andouillette crue', 1, 'g'),
(32, 'Andouillette de Troyes crue', 1, 'g'),
(33, 'Aneth frais', 1, 'g'),
(34, 'Anguille', 1, 'g'),
(35, 'Ao-nori (Enteromorpha sp.)', 1, 'g'),
(36, 'Aôme vanille', 1, 'g'),
(37, 'Artichaut', 1, 'g'),
(38, 'Artichaut coeur', 1, 'g'),
(39, 'Artichaut fond', 1, 'g'),
(40, 'Asiago', 1, 'g'),
(41, 'Asperge', 1, 'g'),
(42, 'Asperge blanche', 1, 'g'),
(43, 'Asperge verte', 1, 'g'),
(44, 'Asperge violette', 1, 'g'),
(45, 'Aubergine', 1, 'g'),
(46, 'Autruche', 1, 'g'),
(47, 'Avocat', 1, 'g'),
(48, 'Son d\'avoine', 1, 'g'),
(49, 'Bacon', 1, 'g'),
(50, 'Bagel', 1, 'g'),
(51, 'Baie de goji', 1, 'g'),
(52, 'Bambou pousses', 1, 'g'),
(53, 'Banane', 1, 'g'),
(54, 'Banane plantain crue', 1, 'g'),
(55, 'Bar commun', 1, 'g'),
(56, 'Barre à la noix de coco enrobée de chocolat', 1, 'g'),
(57, 'Barre céréales aux amandes ou noisettes', 1, 'g'),
(58, 'Barre céréales aux fruits', 1, 'g'),
(59, 'Barre céréales chocolat', 1, 'g'),
(60, 'Barre chocolat au lait avec nougat', 1, 'g'),
(61, 'Barre chocolat aux fruits secs', 1, 'g'),
(62, 'Basilic', 1, 'g'),
(63, 'Basilic deshydraté', 1, 'g'),
(64, 'Basilic frais', 1, 'g'),
(65, 'Beaufort', 1, 'g'),
(66, 'Beignet à la confiture', 1, 'g'),
(67, 'Beignet de crevette', 1, 'g'),
(68, 'Beignet de légumes', 1, 'g'),
(69, 'Beignet de viande volaille ou poisson fait maison', 1, 'g'),
(70, 'Bette ou blette', 1, 'g'),
(71, 'Betterave rouge', 1, 'g'),
(72, 'Beurre', 1, 'g'),
(73, 'Beurre de cacahuète', 1, 'g'),
(74, 'Beurre de cacao', 1, 'g'),
(75, 'Bicarbonate de soude', 1, 'g'),
(76, 'Bicarbonate de soude alimentaire', 1, 'g'),
(77, 'Biére', 1, 'g'),
(78, 'Biscotte', 1, 'g'),
(79, 'Biscotte compléte ou riche en fibres', 1, 'g'),
(80, 'Biscuit apéritif', 1, 'g'),
(81, 'Biscuit de Savoie', 1, 'g'),
(82, 'Biscuit sec', 1, 'g'),
(83, 'Biscuit sec ', 1, 'g'),
(84, 'Biscuit sec aux fruits', 1, 'g'),
(85, 'Biscuit sec avec nappage chocolat', 1, 'g'),
(86, 'Biscuit sec avec tablette de chocolat', 1, 'g'),
(87, 'Biscuit sec croquant au chocolat', 1, 'g'),
(88, 'Biscuit sec nature', 1, 'g'),
(89, 'Biscuit sec ou tuile aux amandes', 1, 'g'),
(90, 'Biscuit sec pauvre en glucides', 1, 'g'),
(91, 'Biscuit sec petit beurre', 1, 'g'),
(92, 'Biscuit sec petit beurre au chocolat', 1, 'g'),
(93, 'Biscuit sec petits fours en assortiment', 1, 'g'),
(94, 'Biscuit sec pour petit déjeuner', 1, 'g'),
(95, 'Biscuit sec pour petit déjeuner au chocolat', 1, 'g'),
(96, 'Biscuit sec type langue de chat ou cigarette russe', 1, 'g'),
(97, 'Biscuit sec type tuile aux fruits', 1, 'g'),
(98, 'blanc d\'oeuf', 1, 'g'),
(99, 'Blanc de Dinde', 1, 'g'),
(100, 'Blanc de poulet', 1, 'g'),
(101, 'Blanquette de veau', 1, 'g'),
(102, 'Blé de Khorasan cru', 1, 'g'),
(103, 'Blé dur entier cru', 1, 'g'),
(104, 'Blé dur précuit cuisiné en sachet micro-ondable', 1, 'g'),
(105, 'Blé dur précuit entier cru', 1, 'g'),
(106, 'Blé dur précuit grains entiers cuisiné', 1, 'g'),
(108, 'Blé germé', 1, 'g'),
(109, 'Bleu fromage', 1, 'g'),
(110, 'Blinis', 1, 'g'),
(111, 'Boeuf bavette d\'aloyau', 1, 'g'),
(112, 'Boeuf boulettes surgelés', 1, 'g'),
(113, 'Boeuf bourguignon', 1, 'g'),
(114, 'Boeuf entrecôte', 1, 'g'),
(115, 'Boeuf faux-filet cru', 1, 'g'),
(116, 'Boeuf hampe crue', 1, 'g'),
(117, 'Boeuf jarret', 1, 'g'),
(118, 'Boeuf jarret cru', 1, 'g'),
(119, 'Boeuf onglet cru', 1, 'g'),
(120, 'Boeuf onglet grillé', 1, 'g'),
(121, 'Boeuf paleron', 1, 'g'),
(122, 'Boeuf paleron cru', 1, 'g'),
(123, 'Boeuf rosbif', 1, 'g'),
(124, 'Boeuf rumsteck cru', 1, 'g'),
(125, 'Boeuf steak haché 20% MG cuit', 1, 'g'),
(126, 'Boeuf steak haché 5% MG cuit', 1, 'g'),
(127, 'Boeuf steak ou bifteck', 1, 'g'),
(128, 'Boeuf tende de tranche', 1, 'g'),
(129, 'Boisson au soja nature', 1, 'g'),
(130, 'Boisson gazeuse aux fruits', 1, 'g'),
(131, 'Boisson gazeuse nature', 1, 'g'),
(132, 'Bonbon', 1, 'g'),
(133, 'Bonbon dur et sucette', 1, 'g'),
(134, 'Bonbons tout type', 1, 'g'),
(135, 'Boudin blanc', 1, 'g'),
(136, 'Boudin noir', 1, 'g'),
(137, 'Bouillon de boeuf déshydraté', 1, 'g'),
(138, 'Bouillon KUB OR', 1, 'g'),
(139, 'Boulgour de blé', 1, 'g'),
(140, 'Bouquet garni', 1, 'g'),
(141, 'Bresaola', 1, 'g'),
(142, 'Brick au boeuf', 1, 'g'),
(143, 'Brie de Meaux', 1, 'g'),
(144, 'Brie de Melun', 1, 'g'),
(145, 'Brioche aux pépites de chocolat', 1, 'g'),
(146, 'Brioche fourrée au chocolat', 1, 'g'),
(147, 'Brioche pur beurre', 1, 'g'),
(148, 'Brochet cru', 1, 'g'),
(149, 'Brochette d\'agneau', 1, 'g'),
(150, 'Brochette de boeuf', 1, 'g'),
(151, 'Brochette de crevettes', 1, 'g'),
(152, 'Brochette de poisson', 1, 'g'),
(153, 'Brochette de porc crue', 1, 'g'),
(154, 'Brochette de volaille', 1, 'g'),
(155, 'Brochette mixte de viande', 1, 'g'),
(156, 'Brocoli cru', 1, 'g'),
(157, 'Brocoli cuit', 1, 'g'),
(158, 'Brocoli surgeés', 1, 'g'),
(159, 'Brownie au chocolat', 1, 'g'),
(160, 'Brugnon', 1, 'g'),
(161, 'Brugnon', 1, 'g'),
(162, 'Bulot', 1, 'g'),
(163, 'Burger au poisson', 1, 'g'),
(164, 'Burger au poulet', 1, 'g'),
(165, 'Burritos', 1, 'g'),
(166, 'Cabillaud', 1, 'g'),
(167, 'Cabillaud cru', 1, 'g'),
(168, 'Cacahuète', 1, 'g'),
(169, 'Cacao non sucré poudre soluble', 1, 'g'),
(170, 'Café en grains', 1, 'g'),
(171, 'Cake aux fruits', 1, 'g'),
(172, 'Calamar', 1, 'g'),
(173, 'Calmar ou encornet', 1, 'g'),
(174, 'Camembert au lait cru', 1, 'g'),
(175, 'Canard cuisse', 1, 'g'),
(176, 'Canard magret cru', 1, 'g'),
(177, 'Cancoillotte', 1, 'g'),
(178, 'Canneberge ou cranberry crue', 1, 'g'),
(179, 'Cannelle', 1, 'g'),
(180, 'Cannelle poudre', 1, 'g'),
(181, 'Cannelloni', 1, 'g'),
(182, 'Cantal entre-deux', 1, 'g'),
(183, 'Capre', 1, 'g'),
(184, 'Cardine', 1, 'g'),
(185, 'Carotte', 1, 'g'),
(186, 'Carotte surgelées', 1, 'g'),
(187, 'Carpaccio', 1, 'g'),
(188, 'Carpe', 1, 'g'),
(189, 'Cassis', 1, 'g'),
(190, 'Cassoulet', 1, 'g'),
(191, 'Caviar de tomates', 1, 'g'),
(192, 'Céleri branche', 1, 'g'),
(193, 'Céréales Sacha', 1, 'g'),
(194, 'Céréales Zoé', 1, 'g'),
(195, 'Cerfeuil frais', 1, 'g'),
(196, 'Cerise', 1, 'g'),
(197, 'Cervelas', 1, 'g'),
(198, 'Cervelas obernois', 1, 'g'),
(199, 'Cervelle agneau crue', 1, 'g'),
(200, 'Chabichou (fromage de chèvre)', 1, 'g'),
(201, 'Chair à saucisse crue', 1, 'g'),
(202, 'Champagne', 1, 'g'),
(203, 'Champignon ', 1, 'g'),
(204, 'Champignon de Paris', 1, 'g'),
(205, 'Champignon en boites', 1, 'g'),
(206, 'Champignon frais', 1, 'g'),
(207, 'Champignon Surgelés', 1, 'g'),
(208, 'Champignon tout type cru', 1, 'g'),
(209, 'Chaource', 1, 'g'),
(210, 'Chapelure', 1, 'g'),
(211, 'Chapon viande et peau cru', 1, 'g'),
(212, 'Charcuterie', 1, 'g'),
(213, 'Charlotte aux fruits', 1, 'g'),
(214, 'Chausson aux pommes', 1, 'g'),
(215, 'Cheddar', 1, 'g'),
(216, 'Cheeseburger', 1, 'g'),
(217, 'Chewing-gum sans sucre', 1, 'g'),
(218, 'Chia graine', 1, 'g'),
(219, 'Chili con carne', 1, 'g'),
(220, 'Chinchard cru', 1, 'g'),
(221, 'Chipolata crue', 1, 'g'),
(222, 'Chips de crevette', 1, 'g'),
(223, 'Chips de légumes', 1, 'g'),
(224, 'Chips de pommes de terre', 1, 'g'),
(225, 'Chips de pommes de terre petit paquet', 1, 'g'),
(226, 'chocolat 78%', 1, 'g'),
(227, 'Chocolat noir 85%', 1, 'g'),
(228, 'chocolat noir 90%', 1, 'g'),
(229, 'Chocolat noir 70% cacao', 1, 'g'),
(230, 'Chocolat noir aux fruits (orange framboise poire) tablette', 1, 'g'),
(231, 'Chocolat noir aux fruits secs (noisettes amandes raisins praline) tablette', 1, 'g'),
(232, 'Chorizo', 1, 'g'),
(233, 'Chou blanc cru', 1, 'g'),
(234, 'Chou brocoli', 1, 'g'),
(235, 'Chou chinois', 1, 'g'),
(236, 'Chou de Bruxelles', 1, 'g'),
(237, 'Chou farci', 1, 'g'),
(238, 'Chou romanesco', 1, 'g'),
(239, 'Chou rouge', 1, 'g'),
(240, 'Chou vert', 1, 'g'),
(241, 'Chou-fleur', 1, 'g'),
(242, 'Chou-fleur surgelé', 1, 'g'),
(244, 'Chou-rave', 1, 'g'),
(245, 'Choucroute garnie', 1, 'g'),
(246, 'Chouquette', 1, 'g'),
(247, 'Choux fleur', 1, 'g'),
(248, 'Ciboulette', 1, 'g'),
(249, 'Cidre brut', 1, 'g'),
(250, 'Cidre doux', 1, 'g'),
(251, 'Cidre traditionnel', 1, 'g'),
(252, 'Citron', 1, 'g'),
(253, 'Citron vert ou Lime pulpe cru', 1, 'g'),
(254, 'Citrouille', 1, 'g'),
(255, 'Clémentine', 1, 'g'),
(256, 'Clou de girofle', 1, 'g'),
(257, 'Coca Cola', 1, 'g'),
(258, 'Cocktail type punch 16% alcool', 1, 'g'),
(259, 'Coeur de palmier', 1, 'g'),
(260, 'Coing cru', 1, 'g'),
(261, 'Compote de pomme', 1, 'g'),
(262, 'Compote de pomme (SSA)', 1, 'g'),
(263, 'Compote tout type de fruits', 1, 'g'),
(264, 'Comté', 1, 'g'),
(265, 'Concentré de tomate', 1, 'g'),
(266, 'Concombre', 1, 'g'),
(267, 'Confit de canard', 1, 'g'),
(268, 'Congre', 1, 'g'),
(269, 'Coppa', 1, 'g'),
(270, 'Coquille Saint-Jacques noix', 1, 'g'),
(271, 'Cordon bleu (maitre coq)', 1, 'g'),
(272, 'Coriandre', 1, 'g'),
(273, 'Cornichon', 1, 'g'),
(274, 'Coulis de tomate', 1, 'g'),
(275, 'Coulommiers', 1, 'g'),
(276, 'Courge', 1, 'g'),
(277, 'Courge butternut', 1, 'g'),
(278, 'Courgette', 1, 'g'),
(279, 'Court-bouillon', 1, 'g'),
(280, 'Couscous (semoule de blé dur)', 1, 'g'),
(281, 'Crabe cru', 1, 'g'),
(282, 'Crème chantilly', 1, 'g'),
(283, 'Crème de cassis', 1, 'g'),
(284, 'Crème de coco', 1, 'g'),
(285, 'Crème de marrons', 1, 'g'),
(286, 'Crème de soja', 1, 'g'),
(287, 'Crème liquide', 1, 'g'),
(288, 'Crèpe dentelle', 1, 'g'),
(289, 'Crevette', 1, 'g'),
(290, 'Crevette rose', 1, 'g'),
(291, 'Croissant au beurre artisanal', 1, 'g'),
(292, 'Croque-madame', 1, 'g'),
(293, 'Croque-monsieur', 1, 'g'),
(294, 'Crosne', 1, 'g'),
(295, 'Crottin de chèvre', 1, 'g'),
(296, 'Croutons', 1, 'g'),
(297, 'Crumble aux pommes', 1, 'g'),
(298, 'Cucurbitacées', 1, 'g'),
(299, 'Cuisse de poulet', 1, 'g'),
(300, 'Cumin graine', 1, 'g'),
(301, 'Curcuma poudre', 1, 'g'),
(302, 'Curcurma', 1, 'g'),
(303, 'Curry', 1, 'g'),
(304, 'Curry poudre', 1, 'g'),
(305, 'Datte pulpe et peau séche', 1, 'g'),
(306, 'Dés de jambon', 1, 'g'),
(307, 'Dinde', 1, 'g'),
(308, 'Dinde cuisse', 1, 'g'),
(309, 'Dinde escalope', 1, 'g'),
(310, 'Diot', 1, 'g'),
(311, 'Dorade', 1, 'g'),
(312, 'Dulse (Palmaria palmata)', 1, 'g'),
(313, 'Echalote crue', 1, 'g'),
(314, 'Echalotte', 1, 'piece'),
(315, 'Edam', 1, 'g'),
(316, 'Eglefin', 1, 'g'),
(317, 'Emmental ou emmenthal', 1, 'g'),
(318, 'Empereur filet', 1, 'g'),
(319, 'Endive crue', 1, 'g'),
(320, 'Endives', 1, 'g'),
(321, 'Epeautre', 1, 'g'),
(322, 'Epices mexicain', 1, 'g'),
(323, 'Epinard surgelé', 1, 'g'),
(324, 'Epinards a la creme', 1, 'g'),
(325, 'Epinards frais', 1, 'g'),
(326, 'Epinards Surgelés', 1, 'g'),
(327, 'Epoisses', 1, 'g'),
(328, 'Escargot', 1, 'g'),
(329, 'Espadon cru', 1, 'g'),
(330, 'Estragon', 1, 'g'),
(331, 'Estragon frais', 1, 'g'),
(332, 'Esturgeon', 1, 'g'),
(333, 'Faisan', 1, 'g'),
(334, 'Faisselle', 1, 'g'),
(335, 'Fajitas', 1, 'g'),
(336, 'farine', 1, 'g'),
(337, 'Farine d\'épeautre', 1, 'g'),
(338, 'Farine d\'orge', 1, 'g'),
(339, 'Farine de blè tendre', 1, 'g'),
(340, 'Farine de chataigne', 1, 'g'),
(341, 'Farine de maïs', 1, 'g'),
(342, 'Farine de millet', 1, 'g'),
(343, 'Farine de pois chiche', 1, 'g'),
(344, 'Farine de riz', 1, 'g'),
(345, 'Farine de sarrasin', 1, 'g'),
(346, 'Farine de seigle', 1, 'g'),
(347, 'Farine de soja', 1, 'g'),
(348, 'Fécule de pomme de terre', 1, 'g'),
(349, 'Fenouil cru', 1, 'g'),
(350, 'Feta salakis', 1, 'g'),
(351, 'Feuille de brick', 1, 'g'),
(352, 'Ficelle picarde', 1, 'g'),
(353, 'Figue crue', 1, 'g'),
(354, 'Figue séche', 1, 'g'),
(355, 'Filet de bacon', 1, 'g'),
(356, 'Filet de dinde', 1, 'g'),
(357, 'filet de sardine', 1, 'g'),
(358, 'Filet de saumon', 1, 'g'),
(359, 'Flageolet', 1, 'g'),
(360, 'Flan de légumes', 1, 'g'),
(361, 'Flétan', 1, 'g'),
(362, 'Fleur de sel', 1, 'g'),
(363, 'Flocon d\'avoine', 1, 'g'),
(364, 'Foie agneau', 1, 'g'),
(365, 'Foie canard', 1, 'g'),
(366, 'Foie de lotte', 1, 'g'),
(367, 'Foie de morue', 1, 'g'),
(368, 'Foie dinde cru', 1, 'g'),
(369, 'Foie gras canard bloc', 1, 'g'),
(370, 'Foie lapin', 1, 'g'),
(371, 'Foie oie', 1, 'g'),
(372, 'Foie porc', 1, 'g'),
(373, 'Foie porc ', 1, 'g'),
(374, 'Foie poulet', 1, 'g'),
(375, 'Foie poulet ', 1, 'g'),
(376, 'Foie veau', 1, 'g'),
(377, 'Foie veau ', 1, 'g'),
(378, 'Foie volaille', 1, 'g'),
(379, 'fond brun', 1, 'g'),
(380, 'fond de veau ', 1, 'g'),
(381, 'Fondue de poireau', 1, 'g'),
(382, 'Fougasse garnie', 1, 'g'),
(383, 'Fourme d\'Ambert', 1, 'g'),
(384, 'Fourme de Montbrison', 1, 'g'),
(385, 'Fraise', 1, 'g'),
(386, 'Fraisier ou framboisier', 1, 'g'),
(387, 'Framboise', 1, 'g'),
(388, 'Framboise surgelée', 1, 'g'),
(389, 'Fromage blanc 0%', 1, 'g'),
(390, 'Fromage bleu d\'Auvergne', 1, 'g'),
(391, 'Fromage bleu de Bresse', 1, 'g'),
(392, 'Fromage de brebis', 1, 'g'),
(393, 'Fromage de chèvre buche', 1, 'g'),
(394, 'Fromage fondu en tranchettes', 1, 'g'),
(395, 'Fromage tomme de brebis', 1, 'g'),
(396, 'Fromage type feta au lait de vache', 1, 'g'),
(397, 'Fruit confit', 1, 'g'),
(398, 'Fruit de la passion', 1, 'g'),
(399, 'Fruits de mer', 1, 'g'),
(400, 'Fruits de mer cuits surgelés', 1, 'g'),
(401, 'Fruits rouges (framboises fraises groseilles cassis)', 1, 'g'),
(402, 'galette', 1, 'g'),
(403, 'Galette de pomme de terre', 1, 'g'),
(404, 'Galette de riz soufflé complet', 1, 'g'),
(405, 'gélatine', 1, 'g'),
(406, 'Génoise', 1, 'g'),
(407, 'Germe de blé', 1, 'g'),
(408, 'Gésier canard', 1, 'g'),
(409, 'Gésier poulet cru', 1, 'g'),
(410, 'Gin', 1, 'g'),
(411, 'Gingembre', 1, 'g'),
(412, 'Gingembre poudre', 1, 'g'),
(413, 'Gingembre racine crue', 1, 'g'),
(414, 'Gnocchi', 1, 'g'),
(415, 'Gomme de guar', 1, 'g'),
(416, 'Gorgonzola', 1, 'g'),
(417, 'Gouda', 1, 'g'),
(418, 'Gousse d\'ail', 1, 'g'),
(419, 'Goyave', 1, 'g'),
(420, 'Graines de lin', 1, 'g'),
(421, 'Graisse d\'oie', 1, 'g'),
(422, 'Graisse de canard', 1, 'g'),
(423, 'Graisse de dinde', 1, 'g'),
(424, 'Graisse de poulet', 1, 'g'),
(425, 'Grenade', 1, 'g'),
(426, 'Grenouille cuisse', 1, 'g'),
(427, 'Gressins', 1, 'g'),
(428, 'Griotte', 1, 'g'),
(429, 'Grondin', 1, 'g'),
(430, 'gros sel', 1, 'g'),
(431, 'Groseille', 1, 'g'),
(432, 'Groseille à maquereau', 1, 'g'),
(433, 'Gruyère', 1, 'g'),
(434, 'Guimauve', 1, 'g'),
(435, 'Haddock', 1, 'g'),
(436, 'Hamburger surgelés', 1, 'g'),
(437, 'Hareng', 1, 'g'),
(438, 'Haricot beurre', 1, 'g'),
(439, 'Haricot blanc', 1, 'g'),
(440, 'Haricot de mer', 1, 'g'),
(441, 'Haricot mungo', 1, 'g'),
(442, 'Haricot plat', 1, 'g'),
(443, 'haricot rouge', 1, 'g'),
(444, 'Haricot vert en boite', 1, 'g'),
(445, 'Haricot vert stérilisés', 1, 'g'),
(446, 'Haricot vert surgelés', 1, 'g'),
(447, 'Haricots blancs', 1, 'g'),
(448, 'Harissa (sauce condimentaire)', 1, 'g'),
(449, 'Herbes aromatiques', 1, 'g'),
(450, 'Herbes de provence', 1, 'g'),
(451, 'Hoki', 1, 'g'),
(452, 'Homard', 1, 'g'),
(453, 'Hot-dog', 1, 'g'),
(454, 'Houmous', 1, 'g'),
(455, 'huile', 1, 'g'),
(456, 'Huile d\'amande', 1, 'g'),
(457, 'Huile d\'amandes d\'abricot', 1, 'g'),
(458, 'Huile d\'arachide', 1, 'g'),
(459, 'Huile d\'argan ou d\'argane', 1, 'g'),
(460, 'Huile d\'avocat', 1, 'g'),
(461, 'Huile d\'olive', 1, 'g'),
(462, 'Huile d\'olive vierge extra', 1, 'g'),
(463, 'Huile de carthame', 1, 'g'),
(464, 'Huile de coco', 1, 'g'),
(465, 'Huile de colza', 1, 'g'),
(466, 'Huile de coton', 1, 'g'),
(467, 'Huile de foie de morue', 1, 'g'),
(468, 'Huile de germe de blé', 1, 'g'),
(469, 'Huile de hareng', 1, 'g'),
(470, 'Huile de lin', 1, 'g'),
(471, 'Huile de maïs', 1, 'g'),
(472, 'Huile de noisette', 1, 'g'),
(473, 'Huile de noix', 1, 'g'),
(474, 'Huile de palme', 1, 'g'),
(475, 'Huile de paraffine', 1, 'g'),
(476, 'Huile de pépins de raisin', 1, 'g'),
(477, 'Huile de sardine', 1, 'g'),
(478, 'Huile de saumon', 1, 'g'),
(479, 'Huile de sésame', 1, 'g'),
(480, 'Huile de soja', 1, 'g'),
(481, 'Huile de tournesol', 1, 'g'),
(482, 'Huile isio 4', 1, 'g'),
(483, 'Huile pour friture', 1, 'g'),
(484, 'Huile végétale', 1, 'g'),
(485, 'Huitre creuse', 1, 'g'),
(486, 'Huitre plate', 1, 'g'),
(487, 'Jambon', 1, 'g'),
(488, 'Jambon blanc', 1, 'g'),
(489, 'Jambon cru', 1, 'g'),
(490, 'Jambon cru fumé', 1, 'g'),
(491, 'Jambon cuit', 1, 'g'),
(492, 'Jambon cuit fumé', 1, 'g'),
(493, 'Jambon de Bayonne', 1, 'g'),
(494, 'Jambon de dinde ou Blanc de dinde en tranche', 1, 'g'),
(495, 'Jambon de poulet ou Blanc de poulet en tranche', 1, 'g'),
(496, 'Jambon persillé en gelée', 1, 'g'),
(497, 'Jambon sec', 1, 'g'),
(498, 'Jambon sec de Parme', 1, 'g'),
(499, 'Jambon sec Serrano', 1, 'g'),
(500, 'Jambonneau cuit', 1, 'g'),
(501, 'Julienne ou brunoise de légumes surgelée', 1, 'g'),
(502, 'Julienne ou Lingue', 1, 'g'),
(503, 'Jus d\'ananas', 1, 'g'),
(504, 'Jus d\'orange', 1, 'g'),
(505, 'Jus de carotte', 1, 'g'),
(506, 'Jus de citron', 1, 'g'),
(507, 'Jus de citron vert', 1, 'g'),
(508, 'Jus de fruit', 1, 'g'),
(509, 'Jus de fruit de la passion', 1, 'g'),
(510, 'Jus de grenade frais', 1, 'g'),
(511, 'Jus de grenade pur jus', 1, 'g'),
(512, 'Jus de mangue frais', 1, 'g'),
(513, 'Jus de pamplemousse', 1, 'g'),
(514, 'Jus de pomme', 1, 'g'),
(515, 'Jus de pruneau', 1, 'g'),
(516, 'Jus de raisin', 1, 'g'),
(517, 'Jus de tomate', 1, 'g'),
(518, 'Jus multifruit', 1, 'g'),
(519, 'Kaki', 1, 'g'),
(520, 'Ketchup', 1, 'g'),
(521, 'kiwi', 1, 'g'),
(522, 'Kombu breton (Laminaria digitata)', 1, 'g'),
(523, 'Kombu royal (Saccharina latissima)', 1, 'g'),
(524, 'Kouign Amann', 1, 'g'),
(525, 'Lait concentré non sucré', 1, 'g'),
(526, 'Lait concentré sucré', 1, 'g'),
(527, 'Lait d\'amande', 1, 'g'),
(528, 'Lait de brebis', 1, 'g'),
(529, 'Lait de chèvre', 1, 'g'),
(530, 'Lait de coco', 1, 'g'),
(531, 'Lait de Soja', 1, 'g'),
(532, 'Lait Demi écrémé', 1, 'g'),
(533, 'Lait écrémé', 1, 'g'),
(534, 'Lait entier UHT', 1, 'g'),
(535, 'Laitue', 1, 'g'),
(536, 'Laitue de mer (Ulva sp.)', 1, 'g'),
(537, 'Laitue iceberg', 1, 'g'),
(538, 'Laitue romaine', 1, 'g'),
(539, 'Langouste', 1, 'g'),
(540, 'Langoustine', 1, 'g'),
(541, 'Langres', 1, 'g'),
(542, 'Langue boeuf', 1, 'g'),
(543, 'Langue porc', 1, 'g'),
(544, 'Langue veau', 1, 'g'),
(545, 'Lapin', 1, 'g'),
(546, 'Lardon fumé', 1, 'g'),
(547, 'Lardon nature', 1, 'g'),
(548, 'Lasagnes', 1, 'g'),
(549, 'Laurier feuille', 1, 'g'),
(550, 'Légumes aux choix', 1, 'g'),
(551, 'Légumes surgelés', 1, 'g'),
(552, 'Lentilles corails', 1, 'g'),
(553, 'Lentilles vertes', 1, 'g'),
(554, 'Levure', 1, 'g'),
(555, 'Levure chimique', 1, 'g'),
(556, 'Levure de boulanger', 1, 'g'),
(557, 'Liégeois', 1, 'g'),
(558, 'Lieu jaune ou colin', 1, 'g'),
(559, 'Lieu noir', 1, 'g'),
(560, 'Limande crue', 1, 'g'),
(561, 'Limande-sole', 1, 'g'),
(562, 'Lin brun graine', 1, 'g'),
(563, 'Lin graine', 1, 'g'),
(564, 'Lingue bleue ou Lingue', 1, 'g'),
(565, 'Litchi', 1, 'g'),
(566, 'Livarot', 1, 'g'),
(567, 'Lotte', 1, 'g'),
(568, 'Lupin graine', 1, 'g'),
(569, 'Luzerne graine', 1, 'g'),
(570, 'Madeleine gateaux', 1, 'g'),
(571, 'Maïs', 1, 'g'),
(572, 'Maïs doux boite', 1, 'g'),
(573, 'Maïs doux surgelé', 1, 'g'),
(574, 'maizzena', 1, 'g'),
(575, 'Mandarine', 1, 'g'),
(576, 'Mangue', 1, 'g'),
(577, 'Manioc racine', 1, 'g'),
(578, 'Maquereau au naturel', 1, 'g'),
(579, 'Marmelade d\'orange', 1, 'g'),
(580, 'maroilles', 1, 'g'),
(581, 'Marron glacé', 1, 'g'),
(582, 'Marsala aux oeufs', 1, 'g'),
(583, 'Marshmallow', 1, 'g'),
(584, 'Mascarpone', 1, 'g'),
(585, 'Mayonnaise', 1, 'g'),
(586, 'Melon', 1, 'g'),
(587, 'Merguez boeuf mouton', 1, 'g'),
(588, 'Meringue', 1, 'g'),
(589, 'Merlan cru', 1, 'g'),
(590, 'Merlu', 1, 'g'),
(591, 'Merlu filet surgelé', 1, 'g'),
(592, 'Miel', 1, 'g'),
(593, 'Mille-feuille', 1, 'g'),
(594, 'Mimolette demi-vieille', 1, 'g'),
(595, 'Mimolette extra-vieille', 1, 'g'),
(596, 'Mimolette jeune', 1, 'g'),
(597, 'Miso', 1, 'g'),
(598, 'Mix de graines variées', 1, 'g'),
(599, 'Mont d\'or', 1, 'g'),
(600, 'Morbier', 1, 'g'),
(601, 'Mortadelle', 1, 'g'),
(602, 'Morue salée', 1, 'g'),
(603, 'Moule', 1, 'g'),
(604, 'Moussaka', 1, 'g'),
(605, 'Mousse au chocolat', 1, 'g'),
(606, 'Mousse de canard', 1, 'g'),
(607, 'Mousse de foie de porc', 1, 'g'),
(608, 'Moutarde', 1, 'g'),
(609, 'Moutarde à l\'ancienne', 1, 'g'),
(610, 'Mozzarella', 1, 'g'),
(611, 'Muesli', 1, 'g'),
(612, 'Mulet cru', 1, 'g'),
(613, 'Munster', 1, 'g'),
(614, 'Mûres', 1, 'g'),
(615, 'Museau de boeuf', 1, 'g'),
(616, 'Myrtille', 1, 'g'),
(617, 'Myrtille surgelée', 1, 'g'),
(618, 'Navarin d\'agneau', 1, 'g'),
(619, 'Navet', 1, 'g'),
(620, 'Nectar d\'abricot', 1, 'g'),
(621, 'Nectar d\'orange', 1, 'g'),
(622, 'Nectar de banane', 1, 'g'),
(623, 'Nectar de fruit de la passion ou maracuja', 1, 'g'),
(624, 'Nectar de goyave', 1, 'g'),
(625, 'Nectar de mangue', 1, 'g'),
(626, 'Nectar de papaye', 1, 'g'),
(627, 'Nectar de peche', 1, 'g'),
(628, 'Nectar de poire', 1, 'g'),
(629, 'Nectar multifruit multivitaminé', 1, 'g'),
(630, 'Nectarine', 1, 'g'),
(631, 'Nem au porc', 1, 'g'),
(632, 'Nem au poulet', 1, 'g'),
(633, 'Nem aux crevettes et/ou au crabe', 1, 'g'),
(634, 'Neufchatel', 1, 'g'),
(635, 'Noisettes', 1, 'g'),
(636, 'Noix', 1, 'g'),
(637, 'Noix', 1, 'g'),
(638, 'Noix de cajou', 1, 'g'),
(639, 'Noix de coco', 1, 'g'),
(640, 'Noix de coco rapée', 1, 'g'),
(641, 'Noix de macadamia', 1, 'g'),
(642, 'Noix de muscade', 1, 'g'),
(643, 'Noix de pécan', 1, 'g'),
(644, 'Noix du Brésil', 1, 'g'),
(645, 'Nori (Porphyra sp.)', 1, 'g'),
(646, 'Nougat glacé', 1, 'g'),
(647, 'Nouilles asiatiques', 1, 'g'),
(648, 'Oeuf d\'oie', 1, 'g'),
(649, 'Oeuf de caille', 1, 'g'),
(650, 'Oeuf de cane', 1, 'g'),
(651, 'Oeuf de dinde', 1, 'g'),
(652, 'Oeufs', 1, 'piece'),
(653, 'Oignon', 1, 'g'),
(654, 'Oignon rouge', 1, 'g'),
(655, 'Oléagineux', 1, 'g'),
(656, 'Omelette norvégienne', 1, 'g'),
(657, 'Orange', 1, 'g'),
(658, 'Oreille de porc', 1, 'g'),
(659, 'Origan', 1, 'g'),
(660, 'Ormeau', 1, 'g'),
(661, 'Oseille', 1, 'g'),
(662, 'Osso buco', 1, 'g'),
(663, 'Pain au son', 1, 'g'),
(664, 'pain aux graines', 1, 'g'),
(665, 'Pain baguette tradition', 1, 'g'),
(666, 'Pain burger au blé complet', 1, 'g'),
(667, 'Pain d\'épices', 1, 'g'),
(668, 'Pain de campagne', 1, 'g'),
(669, 'Pain de mie', 1, 'g'),
(670, 'Pain de mie complet', 1, 'g'),
(671, 'Pain de mie multicéréale', 1, 'g'),
(672, 'Pain de seigle et froment', 1, 'g'),
(674, 'Pain pita', 1, 'g'),
(675, 'Pain pour hamburger', 1, 'g'),
(676, 'palet breton', 1, 'g'),
(677, 'Palet ou galette de légumes surgelé', 1, 'g'),
(678, 'Pamplemousse', 1, 'g'),
(679, 'Panais', 1, 'g'),
(680, 'Panna cotta', 1, 'g'),
(681, 'panure', 1, 'g'),
(682, 'Papaye', 1, 'g'),
(683, 'Paprika', 1, 'g'),
(684, 'Parmesan', 1, 'g'),
(686, 'Pastèque', 1, 'g'),
(687, 'Pastis', 1, 'g'),
(688, 'Patate douce', 1, 'g'),
(689, 'Patates nouvelles', 1, 'g'),
(690, 'Pâte de fruits', 1, 'g'),
(691, 'Pâte feuilletée', 1, 'g'),
(692, 'Pâte sablée', 1, 'g'),
(693, 'Pâtes complètes', 1, 'g'),
(694, 'Paupiette de veau', 1, 'g'),
(695, 'Paupiette de volaille', 1, 'g'),
(696, 'Pavot graine', 1, 'g'),
(697, 'Perche filet', 1, 'g'),
(698, 'Persil', 1, 'g'),
(699, 'Persil séché', 1, 'g'),
(700, 'pesto', 1, 'g'),
(701, 'Petit pois en boites', 1, 'g'),
(702, 'Petit pois surgelés', 1, 'g'),
(703, 'Petit salé', 1, 'g'),
(704, 'Petit suisse', 1, 'g'),
(705, 'Petits pains grillés blé complet', 1, 'g'),
(706, 'Petits pois ', 1, 'g'),
(707, 'Petits pois et carottes', 1, 'g'),
(708, 'Petits pois surgelés', 1, 'g'),
(709, 'pickles', 1, 'g'),
(710, 'Pied de porc demi-sel', 1, 'g'),
(711, 'Pigeon', 1, 'g'),
(712, 'Pignon de pin', 1, 'g'),
(713, 'Piment cru', 1, 'g'),
(714, 'Piment doux', 1, 'g'),
(715, 'Piment en poudre', 1, 'g'),
(716, 'Pintade', 1, 'g'),
(717, 'Pintade cuisse', 1, 'g'),
(718, 'Pistache', 1, 'g'),
(719, 'Pizza (aliment moyen)', 1, 'g'),
(720, 'Pizza 4 fromages', 1, 'g'),
(721, 'Pizza au chèvre et lardons', 1, 'g'),
(722, 'Pizza au chorizo ou salami', 1, 'g'),
(723, 'Pizza au fromage ou Pizza margherita', 1, 'g'),
(724, 'Pizza au poulet', 1, 'g'),
(725, 'Pizza au saumon', 1, 'g'),
(726, 'Pizza au speck ou jambon cru', 1, 'g'),
(727, 'Pizza au thon', 1, 'g'),
(728, 'Pizza aux fruits de mer', 1, 'g'),
(729, 'Pizza aux lardons oignons et fromage', 1, 'g'),
(730, 'Pizza champignons fromage', 1, 'g'),
(731, 'Pizza jambon fromage', 1, 'g'),
(732, 'Pizza jambon fromage champignons ou pizza royale reine ou regina', 1, 'g'),
(733, 'Pizza kebab', 1, 'g'),
(734, 'Pizza sauce garniture pour', 1, 'g'),
(735, 'Pizza type raclette ou tartiflette', 1, 'g'),
(736, 'Poelée de légumes Asiatiques', 1, 'g'),
(737, 'Poelée de légumes provencale', 1, 'g'),
(738, 'Poire', 1, 'g'),
(739, 'Poire au sirop', 1, 'g'),
(740, 'Poireau', 1, 'g'),
(741, 'Poireau cru', 1, 'g'),
(742, 'Poireau surgelé', 1, 'g'),
(743, 'Pois cassé', 1, 'g'),
(744, 'pois chiche', 1, 'g'),
(745, 'Pois mange-tout', 1, 'g'),
(746, 'Poisson blanc', 1, 'g'),
(747, 'Poisson pané', 1, 'g'),
(748, 'Poitrine de porc', 1, 'g'),
(749, 'Poivre', 1, 'g'),
(750, 'Poivre blanc poudre', 1, 'g'),
(751, 'Poivre de Cayenne ou piment de Cayenne', 1, 'g'),
(752, 'Poivre gris poudre', 1, 'g'),
(753, 'Poivre noir poudre', 1, 'g'),
(754, 'Poivron jaune', 1, 'g'),
(755, 'Poivron rouge', 1, 'g'),
(756, 'Poivron vert', 1, 'g'),
(757, 'Poivron vert cuit', 1, 'g'),
(758, 'Polenta ou semoule de maïs', 1, 'g'),
(759, 'Pomelo (dit Pamplemousse)', 1, 'g'),
(760, 'Pomme', 1, 'g'),
(761, 'Pomme Canada', 1, 'g'),
(762, 'Pommes de terre', 1, 'g'),
(763, 'Pont l\'évéque', 1, 'g'),
(764, 'Potimarron', 1, 'g'),
(765, 'Poudre d\'amande', 1, 'g'),
(766, 'Poulet', 1, 'g'),
(767, 'Poulet cuisse', 1, 'g'),
(768, 'Poulet escalope', 1, 'g'),
(769, 'Poulet fermier viande', 1, 'g'),
(770, 'Poulet filet', 1, 'g'),
(771, 'Poulet pilon', 1, 'g'),
(772, 'Poulpe cru', 1, 'g'),
(773, 'Protéine de soja déshydratée', 1, 'g'),
(774, 'Prune', 1, 'g'),
(775, 'Prune Reine-Claude', 1, 'g'),
(776, 'Pruneau sec', 1, 'g'),
(777, 'Pruneaux d\'Agen', 1, 'g'),
(778, 'Quatre épices', 1, 'g'),
(779, 'Quenelle de poisson', 1, 'g'),
(780, 'Quenelle de veau ', 1, 'g'),
(781, 'Quenelle de volaille', 1, 'g'),
(782, 'Quenelle nature', 1, 'g'),
(783, 'Quiche lorraine', 1, 'g'),
(784, 'Quinoa cru', 1, 'g'),
(785, 'Radis ', 1, 'g'),
(786, 'Radis noir', 1, 'g'),
(787, 'Raie ailes', 1, 'g'),
(788, 'Raifort cru', 1, 'g'),
(789, 'Raisin', 1, 'g'),
(790, 'Raisin sec', 1, 'g'),
(791, 'Ras el hanout', 1, 'g'),
(792, 'Rascasse', 1, 'g'),
(793, 'Ratatouille en boite', 1, 'g'),
(794, 'Ratatouille surgelé', 1, 'g'),
(795, 'Reblochon', 1, 'g'),
(796, 'Rhum', 1, 'g'),
(797, 'Ricotta', 1, 'g'),
(798, 'Rillettes d\'oie', 1, 'g'),
(799, 'Rillettes de canard', 1, 'g'),
(800, 'Rillettes de crabe', 1, 'g'),
(801, 'Rillettes de maquereau', 1, 'g'),
(802, 'Rillettes de poisson', 1, 'g'),
(803, 'Rillettes de poulet', 1, 'g'),
(804, 'Rillettes de saumon', 1, 'g'),
(805, 'Rillettes de thon', 1, 'g'),
(806, 'Rillettes de Tours', 1, 'g'),
(807, 'Rillettes du Mans', 1, 'g'),
(808, 'Rillettes pur oie', 1, 'g'),
(809, 'Rillettes pur porc', 1, 'g'),
(810, 'Rillettes traditionnelles de porc', 1, 'g'),
(811, 'Ris agneau cru', 1, 'g'),
(812, 'Ris agneau cuit', 1, 'g'),
(813, 'Riz à risotto', 1, 'g'),
(814, 'Riz arborio', 1, 'g'),
(815, 'Riz basmati', 1, 'g'),
(816, 'Riz blanc', 1, 'g'),
(817, 'Riz cantonais', 1, 'g'),
(818, 'Riz complet', 1, 'g'),
(819, 'Riz rouge', 1, 'g'),
(820, 'Riz sauvage', 1, 'g'),
(821, 'Riz thaï', 1, 'g'),
(822, 'Rocamadour', 1, 'g'),
(823, 'Rognon', 1, 'g'),
(824, 'Romarin', 1, 'g'),
(825, 'Roquefort', 1, 'g'),
(826, 'Roquette', 1, 'g'),
(827, 'Rouget-barbet', 1, 'g'),
(828, 'Rouleau de printemps', 1, 'g'),
(829, 'Roussette', 1, 'g'),
(830, 'Sabre cru', 1, 'g'),
(831, 'Sachet de levure', 1, 'g'),
(832, 'Safran', 1, 'g'),
(833, 'Saindoux', 1, 'g'),
(834, 'Saint-Marcellin', 1, 'g'),
(835, 'Saint-Nectaire', 1, 'g'),
(836, 'Saint-Paulin', 1, 'g'),
(837, 'Saint-Pierre cru', 1, 'g'),
(838, 'Sainte-Maure de Touraine', 1, 'g'),
(839, 'Salade de fruits', 1, 'g'),
(840, 'Salade verte', 1, 'g'),
(841, 'Salami', 1, 'g'),
(842, 'Salers', 1, 'g'),
(843, 'Salicorne (Salicornia sp.)', 1, 'g'),
(844, 'Salsifis', 1, 'g'),
(845, 'Salsifis surgelé', 1, 'g'),
(846, 'Samossas ou Samoussas', 1, 'g'),
(847, 'Sandre', 1, 'g'),
(848, 'Sanglier', 1, 'g'),
(849, 'Sardine', 1, 'g'),
(850, 'Sauce Nuoc', 1, 'g'),
(851, 'Sauce soja', 1, 'g'),
(852, 'Sauce tomate', 1, 'g'),
(853, 'Saucisse aux herbes', 1, 'g'),
(854, 'Saucisse cocktail', 1, 'g'),
(855, 'Saucisse de foie', 1, 'g'),
(856, 'Saucisse de Francfort', 1, 'g'),
(857, 'Saucisse de jambon pur porc', 1, 'g'),
(858, 'Saucisse de Montbéliard', 1, 'g'),
(859, 'Saucisse de Morteau', 1, 'g'),
(860, 'Saucisse de Strasbourg ou Knack', 1, 'g'),
(861, 'Saucisse de Toulouse', 1, 'g'),
(862, 'Saucisse fumé', 1, 'g'),
(863, 'Saucisse nature', 1, 'g'),
(864, 'Saucisson à l\'ail', 1, 'g'),
(865, 'Saucisson au noix', 1, 'g'),
(866, 'Saucisson sec', 1, 'g'),
(867, 'Saumon', 1, 'g'),
(868, 'Saumon fumé', 1, 'g'),
(869, 'Saumonette', 1, 'g'),
(870, 'Scarole crue', 1, 'g'),
(871, 'Sel', 1, 'g'),
(872, 'Semoule', 1, 'g'),
(873, 'Sirop d\'agave', 1, 'g'),
(874, 'Sirop d\'érable', 1, 'g'),
(875, 'Skyr', 1, 'g'),
(876, 'Smoothie', 1, 'g'),
(877, 'Sole filet', 1, 'g'),
(878, 'Son d\'avoine', 1, 'g'),
(879, 'Sorbet en bac', 1, 'g'),
(880, 'Soupe de faineant', 1, 'g'),
(881, 'Spéculoos', 1, 'g'),
(882, 'Spiruline (Spirulina sp.)', 1, 'g'),
(883, 'steak', 1, 'g'),
(884, 'Steak de boeuf', 1, 'g'),
(885, 'Steak de soja', 1, 'g'),
(886, 'Steak haché *10', 1, 'g'),
(887, 'Steak haché 5%', 1, 'g'),
(888, 'sucre', 1, 'g'),
(889, 'Sucre blanc', 1, 'g'),
(890, 'Sucre roux', 1, 'g'),
(891, 'Sucre stevia', 1, 'g'),
(892, 'Sucre vanillé', 1, 'g'),
(893, 'Surimi', 1, 'g'),
(894, 'tabasco', 1, 'g'),
(895, 'Tacaud cru', 1, 'g'),
(896, 'tandoori', 1, 'g'),
(897, 'Tapenade', 1, 'g'),
(898, 'Tapioca ou Perles du Japon', 1, 'g'),
(899, 'Tempeh', 1, 'g'),
(900, 'Terrine de canard', 1, 'g'),
(901, 'Terrine de fruits de mer', 1, 'g'),
(902, 'Terrine de lapin', 1, 'g'),
(903, 'Terrine de poisson', 1, 'g'),
(904, 'Thon', 1, 'g'),
(905, 'Thon au naturel', 1, 'g'),
(906, 'Thon rouge', 1, 'g'),
(907, 'Thym frais', 1, 'g'),
(908, 'Tilapia cru', 1, 'g'),
(909, 'Tofu nature', 1, 'g'),
(910, 'Tomate', 1, 'g'),
(911, 'Tomate double concentré', 1, 'g'),
(912, 'Tomate pulpe', 1, 'g'),
(913, 'Tomate séchées', 1, 'g'),
(914, 'Tomates cerises', 1, 'g'),
(915, 'Tomates pelées', 1, 'g'),
(916, 'tomme savoie', 1, 'g'),
(917, 'Topinambour', 1, 'g'),
(918, 'Tortillas', 1, 'g'),
(919, 'Tournesol graine', 1, 'g'),
(920, 'Truite', 1, 'g'),
(921, 'Turbot', 1, 'g'),
(922, 'vanille', 1, 'g'),
(923, 'Vanille extrait', 1, 'g'),
(924, 'Vermicelle', 1, 'g'),
(925, 'Vermicelle de riz', 1, 'g'),
(926, 'Viande des Grisons', 1, 'g'),
(927, 'Vin blanc mousseux', 1, 'g'),
(928, 'Vin blanc sec', 1, 'g'),
(929, 'Vin doux', 1, 'g'),
(930, 'Vin rosé', 1, 'g'),
(931, 'Vin rouge', 1, 'g'),
(932, 'Vin rouge merlot 3-5L', 1, 'g'),
(933, 'Vinaigre', 1, 'g'),
(934, 'Vinaigre balsamique', 1, 'g'),
(935, 'Vinaigre de cidre', 1, 'g'),
(936, 'Vodka', 1, 'g'),
(937, 'Wakamé (Undaria pinnatifida)', 1, 'g'),
(938, 'Whey', 1, 'g'),
(939, 'Whisky', 1, 'g'),
(940, 'Yaourt à la Grec', 1, 'piece'),
(942, 'Yaourt aux fruits', 1, 'g'),
(943, 'Yaourt nature', 1, 'g'),
(944, 'Zeste d\'orange confit', 1, 'g'),
(1001, 'After Eight menthe', 1, 'piece'),
(1002, 'Amandes décortiquées', 1, 'g'),
(1003, 'Amandes en poudre', 1, 'g'),
(1004, 'Asparthame', 1, 'g'),
(1005, 'Babybel', 1, 'piece'),
(1006, 'Beurre d\'arachides', 1, 'g'),
(1007, 'Bigorneaux', 1, 'g'),
(1008, 'Bretzel', 1, 'g'),
(1009, 'Brioche tressée', 1, 'piece'),
(1010, 'Cacao Van Houten', 1, 'g'),
(1011, 'Camembert', 1, 'g'),
(1012, 'Chèvre fromage', 1, 'g'),
(1013, 'Chou brocolis surgelé', 1, 'g'),
(1014, 'Confiture', 1, 'piece'),
(1015, 'Crevettes Grises', 1, 'g'),
(1016, 'Crozets', 1, 'g'),
(1017, 'Dos de colin', 1, 'g'),
(1018, 'Echines de porc', 1, 'g'),
(1019, 'Épinards hachés', 1, 'g'),
(1020, 'Epinard branches', 1, 'g'),
(1021, 'Fromage à raclette', 1, 'piece'),
(1022, 'Gesiers', 1, 'g'),
(1023, 'Jardinière de légumes', 1, 'g'),
(1024, 'kinder bueno', 1, 'piece'),
(1025, 'Knacki', 1, 'piece'),
(1026, 'Lait Amande', 1, 'ml'),
(1027, 'Leerdamer', 1, 'g'),
(1028, 'Mâche', 1, 'g'),
(1029, 'Magret de canard', 1, 'g'),
(1030, 'Noisettes en poudre', 1, 'g'),
(1031, 'Nutella', 1, 'g'),
(1032, 'Pâte brisée', 1, 'piece'),
(1033, 'Pâte feuilletée', 1, 'piece'),
(1034, 'Poélée à l\'Italienne', 1, 'g'),
(1035, 'Poëlée de légumes', 1, 'g'),
(1036, 'Poëlée méridionale', 1, 'g'),
(1037, 'Pulpe de Tomate', 1, 'g'),
(1038, 'Purée mousseline', 1, 'g'),
(1039, 'Ricoré', 1, 'g'),
(1040, 'Roti de porc', 1, 'g'),
(1041, 'saucisse coktail', 1, 'piece'),
(1042, 'Saumon en pavé', 1, 'g'),
(1043, 'Tomates pelées en conserve', 1, 'piece'),
(1044, 'Vache qui rit', 1, 'piece'),
(1046, 'pain complet', 1, 'piece'),
(1047, 'pain de blé', 1, 'piece'),
(1061, 'Farine complète', 1, 'g'),
(1062, 'eau', 1, 'ml');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1071;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
