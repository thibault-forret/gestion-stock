-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jan 15, 2025 at 03:37 PM
-- Server version: 11.5.2-MariaDB-ubu2404
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion-stock-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_description`, `created_at`, `updated_at`) VALUES
(1, 'Jambons', 'Une variété de jambons allant du jambon blanc doux au jambon sec affiné, offrant des saveurs authentiques pour enrichir vos repas.', '2024-11-21 15:40:48', '2024-11-21 15:40:48'),
(2, 'Pâtes sèches', 'Des pâtes de haute qualité, parfaites pour des plats traditionnels ou créatifs, offrant une base idéale pour des repas savoureux.', '2024-11-21 15:41:35', '2024-11-21 15:41:35'),
(3, 'Pizzas', 'Une sélection de pizzas préparées avec des ingrédients frais et variés, adaptées à toutes les envies pour un moment de partage.', '2024-11-21 15:41:35', '2024-11-21 15:41:35'),
(4, 'Salades de pâtes', 'Des salades de pâtes fraîches et équilibrées, idéales pour un repas rapide et savoureux, parfaites pour une pause déjeuner.', '2024-11-21 15:44:25', '2024-11-21 15:44:25'),
(5, 'Produits laitiers', 'Une gamme de produits laitiers, alliant fraîcheur et qualité, pour compléter vos recettes ou déguster nature.', '2024-11-21 15:44:52', '2024-11-21 15:44:52'),
(6, 'Sandwichs', 'Une gamme de sandwichs allant du classique jambon-beurre au plus élaboré, avec des garnitures variées, pour satisfaire toutes vos envies de repas rapides et savoureux.', '2024-11-21 22:14:41', '2024-11-21 22:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `invoice_status` enum('PAID','UNPAID','PARTIALLY_PAID') NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supply_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_name` varchar(255) DEFAULT NULL,
  `warehouse_address` varchar(255) DEFAULT NULL,
  `warehouse_director` varchar(255) DEFAULT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `entity_address` varchar(255) DEFAULT NULL,
  `entity_director` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_11_14_085517_create_categories_table', 1),
(2, '2024_11_14_085518_create_products_table', 1),
(3, '2024_11_14_085519_create_roles_table', 1),
(4, '2024_11_14_085520_create_suppliers_table', 1),
(5, '2024_11_14_085521_create_users_table', 1),
(6, '2024_11_14_085522_create_warehouses_table', 1),
(7, '2024_11_14_085523_create_user_warehouses_table', 1),
(8, '2024_11_14_085524_create_supplies_table', 1),
(9, '2024_11_14_085525_create_supply_lines_table', 1),
(10, '2024_11_14_085526_create_stocks_table', 1),
(11, '2024_11_14_085527_create_stock_movements_table', 1),
(12, '2024_11_14_085528_create_orders_table', 1),
(13, '2024_11_14_085529_create_order_lines_table', 1),
(14, '2024_11_14_085530_create_invoices_table', 1),
(15, '2024_11_19_085947_create_stores_table', 1),
(16, '2024_11_19_085948_create_user_stores_table', 1),
(17, '2024_11_20_215504_create_cache_table', 2),
(18, '2024_11_29_122733_create_product_categories_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` enum('IN PROGRESS','DELIVERED','PENDING','REFUSED') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `store_id`, `order_date`, `order_status`, `created_at`, `updated_at`) VALUES
(39, 11, 3, '2025-01-13 16:28:15', 'IN PROGRESS', '2025-01-13 16:28:15', '2025-01-13 16:28:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_lines`
--

CREATE TABLE `order_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_ordered` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Settings related to Designer';

--
-- Dumping data for table `pma__designer_settings`
--

INSERT INTO `pma__designer_settings` (`username`, `settings_data`) VALUES
('root', '{\"snap_to_grid\":\"off\",\"angular_direct\":\"direct\",\"relation_lines\":\"true\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"gestion-stock-db\",\"table\":\"pma__recent\"},{\"db\":\"gestion-stock-db\",\"table\":\"pma__column_info\"},{\"db\":\"gestion-stock-db\",\"table\":\"pma__bookmark\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='User preferences storage for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Users and their assignments to user groups';

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `image_url` text NOT NULL,
  `reference_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `image_url`, `reference_price`, `created_at`, `updated_at`) VALUES
(3038359012419, 'Sauce 4 fromages', 'https://images.openfoodfacts.org/images/products/303/835/901/2419/front_fr.22.400.jpg', 5.72, '2024-12-04 11:12:20', '2024-12-04 11:12:20'),
(3095756102013, 'Le Tranché Fin - Dégustation', 'https://images.openfoodfacts.org/images/products/309/575/610/2013/front_fr.1275.400.jpg', 1.28, '2025-01-13 10:28:14', '2025-01-13 10:28:14'),
(3095757903015, 'Le Supérieur - à l\'Etouffée - FILIERE FRANCAISE D\'ELEVEURS ENGAGES', 'https://images.openfoodfacts.org/images/products/309/575/790/3015/front_fr.678.400.jpg', 6.65, '2025-01-13 10:22:46', '2025-01-13 10:22:46'),
(3095758914010, 'Allumettes de Jambon', 'https://images.openfoodfacts.org/images/products/309/575/891/4010/front_fr.166.400.jpg', 11.77, '2025-01-13 10:24:00', '2025-01-13 10:24:00'),
(3154230809869, 'Le Bon Paris à l\'Étouffée', 'https://images.openfoodfacts.org/images/products/315/423/080/9869/front_fr.72.400.jpg', 13.75, '2024-12-02 13:12:59', '2024-12-02 13:12:59'),
(3154230809890, 'Le Bon Paris à l\'Etouffée', 'https://images.openfoodfacts.org/images/products/315/423/080/9890/front_fr.49.400.jpg', 10.85, '2024-12-02 15:50:33', '2024-12-02 15:50:33'),
(3154230810131, 'Jambon le bon paris', 'https://images.openfoodfacts.org/images/products/315/423/081/0131/front_fr.57.400.jpg', 12.86, '2024-12-02 15:49:13', '2024-12-02 15:49:13'),
(3242272890751, 'Suédois - Duo de saumon', 'https://images.openfoodfacts.org/images/products/324/227/289/0751/front_fr.61.400.jpg', 7.55, '2024-12-03 13:48:59', '2024-12-03 13:48:59'),
(3242272922056, 'Sandwich le méga baguette thon oeuf sauce cocktail', 'https://images.openfoodfacts.org/images/products/324/227/292/2056/front_fr.61.400.jpg', 8.56, '2025-01-12 22:40:42', '2025-01-12 22:40:42'),
(3242274000059, 'Salade & Compagnie - Manhattan - pâtes, crudités, œuf, poulet rôti, carottes et fromage.', 'https://images.openfoodfacts.org/images/products/324/227/400/0059/front_fr.200.400.jpg', 5.08, '2024-12-02 14:08:49', '2024-12-02 14:08:49'),
(3242274003050, 'Salade & Compagnie - Roma', 'https://images.openfoodfacts.org/images/products/324/227/400/3050/front_fr.126.400.jpg', 13.41, '2025-01-12 20:24:10', '2025-01-12 20:24:10'),
(3302740050237, 'Simplement BIO - Le supérieur - Conservation sans nitrite', 'https://images.openfoodfacts.org/images/products/330/274/005/0237/front_fr.38.400.jpg', 17.67, '2024-12-04 09:14:06', '2024-12-04 09:14:06'),
(8076809521581, 'Pâtes mini penne rigate piccolini 500g', 'https://images.openfoodfacts.org/images/products/807/680/952/1581/front_fr.69.400.jpg', 17.86, '2025-01-12 20:24:34', '2025-01-12 20:24:34'),
(8076809532709, 'coquillette', 'https://images.openfoodfacts.org/images/products/807/680/953/2709/front_fr.111.400.jpg', 14.29, '2025-01-12 20:24:20', '2025-01-12 20:24:20'),
(8076809545440, 'Spaghetti Senza Glutine', 'https://images.openfoodfacts.org/images/products/807/680/954/5440/front_en.245.400.jpg', 6.70, '2025-01-13 10:26:24', '2025-01-13 10:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(3095756102013, 1),
(3095757903015, 1),
(3095758914010, 1),
(3154230809869, 1),
(3154230809890, 1),
(3154230810131, 1),
(3302740050237, 1),
(8076809521581, 2),
(8076809532709, 2),
(8076809545440, 2),
(3242274000059, 4),
(3242274003050, 4),
(3038359012419, 5),
(3242272890751, 6),
(3242272922056, 6);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_description`, `created_at`, `updated_at`) VALUES
(1, 'Directeur d\'entrepôt', 'Le Directeur d\'entrepôt supervise l\'ensemble des opérations logistiques et de gestion de l\'entrepôt. Il est responsable de la planification, de l\'organisation et de la gestion des stocks, ainsi que de l\'optimisation des processus d\'entreposage. Ce rôle implique également la gestion d\'équipe, le respect des normes de sécurité, la gestion des relations avec les fournisseurs et le contrôle des inventaires. Il doit garantir l\'efficacité et la rentabilité de l\'entrepôt, tout en respectant les délais de livraison.', '2025-01-13 12:14:44', '2025-01-13 12:14:44'),
(2, 'Employé logistique', 'L\'Employé logistique est chargé des opérations quotidiennes liées au stockage, à la gestion des inventaires et à la préparation des commandes dans l\'entrepôt. Il s\'assure que les produits sont correctement réceptionnés, stockés et expédiés dans les délais. Il peut également être responsable de l\'organisation de l\'espace de stockage, de la manutention des produits, ainsi que de l\'étiquetage et du suivi des stocks. L\'employé logistique travaille en étroite collaboration avec le directeur d\'entrepôt et l\'équipe logistique pour assurer le bon déroulement des activités.', '2025-01-13 12:16:20', '2025-01-13 12:16:20'),
(3, ' Directeur de magasin', 'Le Directeur de magasin est responsable de la gestion complète d\'un ou plusieurs magasins. Il supervise les ventes, la gestion des stocks, l\'accueil des clients et la mise en place des stratégies commerciales. Il est chargé de la gestion de l\'équipe de vente, de la formation des employés et de l\'atteinte des objectifs commerciaux. Il doit également veiller à la rentabilité du magasin, au respect des normes de qualité et de service, ainsi qu\'à la mise en place de promotions ou d\'événements pour attirer et fidéliser la clientèle.', '2025-01-13 12:16:46', '2025-01-13 12:16:46'),
(4, 'Responsable de commande magasin', 'Le responsable de commande magasin gère l\'ensemble du processus de commande des produits destinés à l\'approvisionnement du magasin. Il/elle veille à ce que les commandes soient passées en fonction des besoins de stock, des tendances de consommation et des prévisions de vente. Il/elle travaille en étroite collaboration avec les fournisseurs pour garantir la disponibilité des produits en rayon, tout en respectant les budgets alloués. En plus de la gestion des commandes, il/elle supervise le suivi des livraisons, la gestion des stocks et l\'optimisation de l\'espace de stockage en magasin. Son rôle est essentiel pour assurer une bonne rotation des produits et éviter les ruptures de stock qui pourraient impacter l\'expérience client.', '2025-01-13 12:17:38', '2025-01-13 12:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qKJjANzZgeXfyOuGwa5zO6Ot9v6GkL2FvdvRT3zQ', 11, '172.21.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNGpEZm5XeUpNZUxaTFc4WFc3eUxIYTFUak16VjFhT2hDbWdKRVkzQSI7czo2OiJsb2NhbGUiO3M6MjoiZnIiO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vbG9jYWxob3N0OjcwOTkvc3RvcmUvb3JkZXIvMzkvcGxhY2UiO31zOjUyOiJsb2dpbl9zdG9yZV81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1736785695);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `alert_threshold` int(11) NOT NULL,
  `restock_threshold` int(11) NOT NULL,
  `auto_restock_quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `stocks`
--
DELIMITER $$
CREATE TRIGGER `auto_restock_trigger` AFTER UPDATE ON `stocks` FOR EACH ROW BEGIN
    IF NEW.quantity_available <= NEW.restock_threshold THEN
        INSERT INTO restock_orders (product_id, warehouse_id, quantity, created_at)
        VALUES (NEW.product_id, NEW.warehouse_id, NEW.auto_restock_quantity, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_stocks_insert_update` BEFORE INSERT ON `stocks` FOR EACH ROW BEGIN
    IF NEW.quantity_available < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'quantity_available must be greater than or equal to 0';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_alert_trigger` AFTER UPDATE ON `stocks` FOR EACH ROW BEGIN
    IF NEW.quantity_available <= NEW.alert_threshold THEN
        INSERT INTO stock_alerts (product_id, warehouse_id, alert_message, created_at)
        VALUES (NEW.product_id, NEW.warehouse_id, 'Stock bas, réapprovisionnement nécessaire', NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_stocks_timestamp` BEFORE UPDATE ON `stocks` FOR EACH ROW BEGIN
    SET NEW.updated_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_moved` int(11) NOT NULL,
  `movement_type` enum('IN','OUT') NOT NULL,
  `movement_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `movement_status` enum('IN_PROGRESS','COMPLETED','CANCELLED') NOT NULL,
  `movement_source` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(50) NOT NULL,
  `store_address` varchar(255) NOT NULL,
  `store_email` varchar(255) DEFAULT NULL,
  `store_phone` varchar(50) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_name`, `store_address`, `store_email`, `store_phone`, `capacity`, `warehouse_id`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Super Nova Montreuil', '10 Rue de la Fraternité, 93100 Montreuil, France', 'super-montreuil@nova-store.fr', '+33 1 48 70 15 20', 350, 1, 11, '2025-01-13 12:26:00', '2025-01-13 12:26:00'),
(4, 'Hyper Nova Chantilly', '15 Rue des Jardins, 60500 Chantilly, France\r\n\r\n', 'hyper-chantilly@nova-store.fr', '+33 3 44 57 12 34', 1040, 1, 13, '2025-01-13 12:26:00', '2025-01-13 12:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_address` varchar(255) NOT NULL,
  `supplier_phone` varchar(255) NOT NULL,
  `supplier_email` varchar(255) NOT NULL,
  `supplier_contact` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `supplier_address`, `supplier_phone`, `supplier_email`, `supplier_contact`, `created_at`, `updated_at`) VALUES
(1, 'Sodebo', 'ZI du Plessis, 85607 Montaigu, France', '+33 2 51 45 23 45', 'contact@sodebo.fr', 'Marie Dubois', '2024-11-21 15:49:40', '2024-11-21 15:49:40'),
(2, 'Herta', '25 rue du Maréchal Foch, 60765 Clermont, France', '+33 3 44 77 12 34', 'support@herta.fr', 'Pierre Leroy', '2024-11-21 15:49:40', '2024-11-21 15:49:40'),
(3, 'Fleury Michon', '4 rue de la Vallée, 85700 Pouzauges, France', '+33 2 51 57 10 20', 'serviceclient@fleurymichon.fr\r\n', 'Sophie Martin', '2024-11-21 15:53:15', '2024-11-21 15:53:15'),
(4, 'Panzani', '100 avenue Stalingrad, 69100 Villeurbanne, France', '+33 4 78 85 47 89', 'info@panzani.fr', 'Jean Moreau', '2024-11-21 15:53:15', '2024-11-21 15:53:15'),
(5, 'Barilla', 'Via Mantova 166, 43122 Parme, Italie', '+39 0521 26 51 11', 'contact@barilla.it', 'Laura Rossi', '2024-11-21 15:53:15', '2024-11-21 15:53:15'),
(6, 'Mix', '15 rue des Entrepreneurs, 75015 Paris, France', '+33 1 40 22 67 89', 'contact@mix.fr', 'Olivier Garnier', '2024-11-21 15:56:18', '2024-11-21 15:56:18'),
(7, 'Président', '63 boulevard de la République, 92100 Boulogne-Billancourt, France', '+33 1 41 35 10 00', 'contact@president.fr', 'Nathalie Dupont', '2024-11-21 15:56:18', '2024-11-21 15:56:18'),
(8, 'Marque Repère', 'Rue Nicolas Appert, 35230 Bourgbarré, France', '+33 2 99 57 42 30', 'support@marquerepere.fr', 'Julien Lemoine', '2024-11-21 15:57:13', '2024-11-21 15:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `supply_status` enum('IN PROGRESS','DELIVERED') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supply_lines`
--

CREATE TABLE `supply_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supply_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_supplied` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `last_name`, `first_name`, `username`, `email`, `password`, `role_id`, `created_at`, `updated_at`) VALUES
(9, 'Aubry', 'Léonidas', 'leonidas.aubry', 'leonidas.aubry@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 1, '2025-01-13 11:19:17', '2025-01-13 11:19:17'),
(10, 'Dufresne', 'Elara', 'elara.dufresne', 'elara.dufresne@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 2, '2025-01-13 11:19:17', '2025-01-13 11:19:17'),
(11, 'Leclerc', 'Zéphyr', 'zephyr.leclerc', 'zephyr.leclerc@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 3, '2025-01-13 11:19:17', '2025-01-13 11:19:17'),
(12, 'Moreau', 'Calypso', 'calypso.moreau', 'calypso.moreau@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 4, '2025-01-13 11:19:17', '2025-01-13 11:19:17'),
(13, 'Dupont', 'Claire', 'claire.dupont', 'claire.dupont@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 3, '2025-01-13 11:24:04', '2025-01-13 11:24:04'),
(14, 'Lefevre', 'Julien', 'julien.lefevre', 'julien.lefevre@nova.fr', '$2y$12$5WuZM0JPfMSAju5Rk7pYcefnPzjjcvXRHDovSnPC34ffrYeF1HjdO', 4, '2025-01-13 11:24:04', '2025-01-13 11:24:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_stores`
--

CREATE TABLE `user_stores` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `responsibility_start_date` date NOT NULL,
  `responsibility_end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_stores`
--

INSERT INTO `user_stores` (`user_id`, `store_id`, `responsibility_start_date`, `responsibility_end_date`, `created_at`, `updated_at`) VALUES
(11, 3, '2025-01-13', '2026-01-06', '2025-01-13 12:28:44', '2025-01-13 12:28:44'),
(12, 3, '2025-01-13', '2027-01-14', '2025-01-13 12:29:09', '2025-01-13 12:29:09'),
(13, 4, '2025-01-13', '2028-01-05', '2025-01-13 13:55:28', '2025-01-13 13:55:28'),
(14, 4, '2025-01-13', '2026-02-12', '2025-01-13 13:55:28', '2025-01-13 13:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_warehouses`
--

CREATE TABLE `user_warehouses` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `responsibility_start_date` date NOT NULL,
  `responsibility_end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_warehouses`
--

INSERT INTO `user_warehouses` (`user_id`, `warehouse_id`, `responsibility_start_date`, `responsibility_end_date`, `created_at`, `updated_at`) VALUES
(9, 1, '2025-01-13', '2027-01-12', '2025-01-13 12:20:05', '2025-01-13 12:20:05'),
(10, 1, '2025-01-13', '2026-01-13', '2025-01-13 12:20:30', '2025-01-13 12:20:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_cache`
-- (See below for the actual view)
--
CREATE TABLE `vue_cache` (
`clé` varchar(255)
,`valeur` mediumtext
,`expiration` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_cache_locks`
-- (See below for the actual view)
--
CREATE TABLE `vue_cache_locks` (
`clé` varchar(255)
,`proprietaire` varchar(255)
,`expiration` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_categories`
-- (See below for the actual view)
--
CREATE TABLE `vue_categories` (
`id` bigint(20) unsigned
,`nom_categorie` varchar(50)
,`description_categorie` text
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_invoices`
-- (See below for the actual view)
--
CREATE TABLE `vue_invoices` (
`id` bigint(20) unsigned
,`numero_facture` varchar(50)
,`date_facture` timestamp
,`statut_facture` enum('PAID','UNPAID','PARTIALLY_PAID')
,`id_commande` bigint(20) unsigned
,`id_fourniture` bigint(20) unsigned
,`nom_entrepot` varchar(255)
,`adresse_entrepot` varchar(255)
,`directeur_entrepot` varchar(255)
,`nom_entite` varchar(255)
,`adresse_entite` varchar(255)
,`directeur_entite` varchar(255)
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_migrations`
-- (See below for the actual view)
--
CREATE TABLE `vue_migrations` (
`id` int(10) unsigned
,`nom_migration` varchar(255)
,`lot` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_orders`
-- (See below for the actual view)
--
CREATE TABLE `vue_orders` (
`id` bigint(20) unsigned
,`id_utilisateur` bigint(20) unsigned
,`id_magasin` bigint(20) unsigned
,`date_commande` timestamp
,`statut_commande` enum('IN PROGRESS','DELIVERED','PENDING','REFUSED')
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_order_lines`
-- (See below for the actual view)
--
CREATE TABLE `vue_order_lines` (
`id` bigint(20) unsigned
,`id_commande` bigint(20) unsigned
,`id_produit` bigint(20) unsigned
,`quantite_commandee` int(11)
,`prix_unitaire` decimal(10,2)
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_products`
-- (See below for the actual view)
--
CREATE TABLE `vue_products` (
`id` bigint(20) unsigned
,`nom_produit` varchar(100)
,`url_image` text
,`prix_reference` decimal(10,2)
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_product_categories`
-- (See below for the actual view)
--
CREATE TABLE `vue_product_categories` (
`id_produit` bigint(20) unsigned
,`id_categorie` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_roles`
-- (See below for the actual view)
--
CREATE TABLE `vue_roles` (
`id` bigint(20) unsigned
,`nom_role` varchar(50)
,`description_role` text
,`date_creation` timestamp
,`date_modification` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_sessions`
-- (See below for the actual view)
--
CREATE TABLE `vue_sessions` (
`id` varchar(255)
,`id_utilisateur` bigint(20) unsigned
,`adresse_ip` varchar(45)
,`agent_utilisateur` text
,`charge_utile` longtext
,`derniere_activite` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_stocks`
-- (See below for the actual view)
--
CREATE TABLE `vue_stocks` (
`id` bigint(20) unsigned
,`id_produit` bigint(20) unsigned
,`id_entrepot` bigint(20) unsigned
,`quantite_disponible` int(11)
,`seuil_alerte` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_stock_movements`
-- (See below for the actual view)
--
CREATE TABLE `vue_stock_movements` (
`id` bigint(20) unsigned
,`id_produit` bigint(20) unsigned
,`id_entrepot` bigint(20) unsigned
,`id_utilisateur` bigint(20) unsigned
,`quantite_deplacee` int(11)
,`type_mouvement` enum('IN','OUT')
,`date_mouvement` timestamp
,`statut_mouvement` enum('IN_PROGRESS','COMPLETED','CANCELLED')
,`source_mouvement` varchar(255)
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_stores`
-- (See below for the actual view)
--
CREATE TABLE `vue_stores` (
`id` bigint(20) unsigned
,`nom_boutique` varchar(50)
,`adresse_boutique` varchar(255)
,`email_boutique` varchar(255)
,`telephone_boutique` varchar(50)
,`capacite` int(11)
,`id_entrepot` bigint(20) unsigned
,`id_utilisateur` bigint(20) unsigned
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_suppliers`
-- (See below for the actual view)
--
CREATE TABLE `vue_suppliers` (
`id` bigint(20) unsigned
,`nom_fournisseur` varchar(255)
,`adresse_fournisseur` varchar(255)
,`telephone_fournisseur` varchar(255)
,`email_fournisseur` varchar(255)
,`contact_fournisseur` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_supplies`
-- (See below for the actual view)
--
CREATE TABLE `vue_supplies` (
`id` bigint(20) unsigned
,`id_utilisateur` bigint(20) unsigned
,`id_fournisseur` bigint(20) unsigned
,`id_entrepot` bigint(20) unsigned
,`statut_approvisionnement` enum('IN PROGRESS','DELIVERED')
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_supply_lines`
-- (See below for the actual view)
--
CREATE TABLE `vue_supply_lines` (
`id` bigint(20) unsigned
,`id_approvisionnement` bigint(20) unsigned
,`id_produit` bigint(20) unsigned
,`quantite_fournie` int(11)
,`prix_unitaire` decimal(10,2)
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_users`
-- (See below for the actual view)
--
CREATE TABLE `vue_users` (
`id` bigint(20) unsigned
,`nom` varchar(50)
,`prenom` varchar(50)
,`nom_utilisateur` varchar(100)
,`email` varchar(255)
,`mot_de_passe` varchar(255)
,`id_role` bigint(20) unsigned
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_user_stores`
-- (See below for the actual view)
--
CREATE TABLE `vue_user_stores` (
`id_utilisateur` bigint(20) unsigned
,`id_boutique` bigint(20) unsigned
,`date_debut_responsabilite` date
,`date_fin_responsabilite` date
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_user_warehouses`
-- (See below for the actual view)
--
CREATE TABLE `vue_user_warehouses` (
`id_utilisateur` bigint(20) unsigned
,`id_entrepot` bigint(20) unsigned
,`date_debut_responsabilite` date
,`date_fin_responsabilite` date
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vue_warehouses`
-- (See below for the actual view)
--
CREATE TABLE `vue_warehouses` (
`id` bigint(20) unsigned
,`nom_entrepot` varchar(50)
,`adresse_entrepot` varchar(255)
,`email_entrepot` varchar(255)
,`telephone_entrepot` varchar(50)
,`capacite` int(11)
,`marge_globale` decimal(10,2)
,`id_utilisateur` bigint(20) unsigned
,`date_creation` timestamp
,`date_mise_a_jour` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_name` varchar(50) NOT NULL,
  `warehouse_address` varchar(255) NOT NULL,
  `warehouse_email` varchar(255) DEFAULT NULL,
  `warehouse_phone` varchar(50) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `global_margin` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `warehouse_name`, `warehouse_address`, `warehouse_email`, `warehouse_phone`, `capacity`, `global_margin`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Nova-LOG Paris', '15 Rue de la Tuilerie, 95310 Saint-Ouen-l\'Aumône, France', 'paris-warehouse@nova-warehouse.fr', '+33 3 88 76 54 32', 2000, 1.10, 9, '2025-01-13 11:05:54', '2025-01-13 11:05:56');

-- --------------------------------------------------------

--
-- Structure for view `vue_cache`
--
DROP TABLE IF EXISTS `vue_cache`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_cache`  AS SELECT `cache`.`key` AS `clé`, `cache`.`value` AS `valeur`, `cache`.`expiration` AS `expiration` FROM `cache` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_cache_locks`
--
DROP TABLE IF EXISTS `vue_cache_locks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_cache_locks`  AS SELECT `cache_locks`.`key` AS `clé`, `cache_locks`.`owner` AS `proprietaire`, `cache_locks`.`expiration` AS `expiration` FROM `cache_locks` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_categories`
--
DROP TABLE IF EXISTS `vue_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_categories`  AS SELECT `categories`.`id` AS `id`, `categories`.`category_name` AS `nom_categorie`, `categories`.`category_description` AS `description_categorie`, `categories`.`created_at` AS `date_creation`, `categories`.`updated_at` AS `date_modification` FROM `categories` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_invoices`
--
DROP TABLE IF EXISTS `vue_invoices`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_invoices`  AS SELECT `invoices`.`id` AS `id`, `invoices`.`invoice_number` AS `numero_facture`, `invoices`.`invoice_date` AS `date_facture`, `invoices`.`invoice_status` AS `statut_facture`, `invoices`.`order_id` AS `id_commande`, `invoices`.`supply_id` AS `id_fourniture`, `invoices`.`warehouse_name` AS `nom_entrepot`, `invoices`.`warehouse_address` AS `adresse_entrepot`, `invoices`.`warehouse_director` AS `directeur_entrepot`, `invoices`.`entity_name` AS `nom_entite`, `invoices`.`entity_address` AS `adresse_entite`, `invoices`.`entity_director` AS `directeur_entite`, `invoices`.`created_at` AS `date_creation`, `invoices`.`updated_at` AS `date_modification` FROM `invoices` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_migrations`
--
DROP TABLE IF EXISTS `vue_migrations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_migrations`  AS SELECT `migrations`.`id` AS `id`, `migrations`.`migration` AS `nom_migration`, `migrations`.`batch` AS `lot` FROM `migrations` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_orders`
--
DROP TABLE IF EXISTS `vue_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_orders`  AS SELECT `orders`.`id` AS `id`, `orders`.`user_id` AS `id_utilisateur`, `orders`.`store_id` AS `id_magasin`, `orders`.`order_date` AS `date_commande`, `orders`.`order_status` AS `statut_commande`, `orders`.`created_at` AS `date_creation`, `orders`.`updated_at` AS `date_modification` FROM `orders` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_order_lines`
--
DROP TABLE IF EXISTS `vue_order_lines`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_order_lines`  AS SELECT `order_lines`.`id` AS `id`, `order_lines`.`order_id` AS `id_commande`, `order_lines`.`product_id` AS `id_produit`, `order_lines`.`quantity_ordered` AS `quantite_commandee`, `order_lines`.`unit_price` AS `prix_unitaire`, `order_lines`.`created_at` AS `date_creation`, `order_lines`.`updated_at` AS `date_modification` FROM `order_lines` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_products`
--
DROP TABLE IF EXISTS `vue_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_products`  AS SELECT `products`.`id` AS `id`, `products`.`product_name` AS `nom_produit`, `products`.`image_url` AS `url_image`, `products`.`reference_price` AS `prix_reference`, `products`.`created_at` AS `date_creation`, `products`.`updated_at` AS `date_modification` FROM `products` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_product_categories`
--
DROP TABLE IF EXISTS `vue_product_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_product_categories`  AS SELECT `product_categories`.`product_id` AS `id_produit`, `product_categories`.`category_id` AS `id_categorie` FROM `product_categories` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_roles`
--
DROP TABLE IF EXISTS `vue_roles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_roles`  AS SELECT `roles`.`id` AS `id`, `roles`.`role_name` AS `nom_role`, `roles`.`role_description` AS `description_role`, `roles`.`created_at` AS `date_creation`, `roles`.`updated_at` AS `date_modification` FROM `roles` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_sessions`
--
DROP TABLE IF EXISTS `vue_sessions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_sessions`  AS SELECT `sessions`.`id` AS `id`, `sessions`.`user_id` AS `id_utilisateur`, `sessions`.`ip_address` AS `adresse_ip`, `sessions`.`user_agent` AS `agent_utilisateur`, `sessions`.`payload` AS `charge_utile`, `sessions`.`last_activity` AS `derniere_activite` FROM `sessions` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_stocks`
--
DROP TABLE IF EXISTS `vue_stocks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_stocks`  AS SELECT `stocks`.`id` AS `id`, `stocks`.`product_id` AS `id_produit`, `stocks`.`warehouse_id` AS `id_entrepot`, `stocks`.`quantity_available` AS `quantite_disponible`, `stocks`.`alert_threshold` AS `seuil_alerte` FROM `stocks` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_stock_movements`
--
DROP TABLE IF EXISTS `vue_stock_movements`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_stock_movements`  AS SELECT `stock_movements`.`id` AS `id`, `stock_movements`.`product_id` AS `id_produit`, `stock_movements`.`warehouse_id` AS `id_entrepot`, `stock_movements`.`user_id` AS `id_utilisateur`, `stock_movements`.`quantity_moved` AS `quantite_deplacee`, `stock_movements`.`movement_type` AS `type_mouvement`, `stock_movements`.`movement_date` AS `date_mouvement`, `stock_movements`.`movement_status` AS `statut_mouvement`, `stock_movements`.`movement_source` AS `source_mouvement`, `stock_movements`.`created_at` AS `date_creation`, `stock_movements`.`updated_at` AS `date_mise_a_jour` FROM `stock_movements` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_stores`
--
DROP TABLE IF EXISTS `vue_stores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_stores`  AS SELECT `stores`.`id` AS `id`, `stores`.`store_name` AS `nom_boutique`, `stores`.`store_address` AS `adresse_boutique`, `stores`.`store_email` AS `email_boutique`, `stores`.`store_phone` AS `telephone_boutique`, `stores`.`capacity` AS `capacite`, `stores`.`warehouse_id` AS `id_entrepot`, `stores`.`user_id` AS `id_utilisateur`, `stores`.`created_at` AS `date_creation`, `stores`.`updated_at` AS `date_mise_a_jour` FROM `stores` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_suppliers`
--
DROP TABLE IF EXISTS `vue_suppliers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_suppliers`  AS SELECT `suppliers`.`id` AS `id`, `suppliers`.`supplier_name` AS `nom_fournisseur`, `suppliers`.`supplier_address` AS `adresse_fournisseur`, `suppliers`.`supplier_phone` AS `telephone_fournisseur`, `suppliers`.`supplier_email` AS `email_fournisseur`, `suppliers`.`supplier_contact` AS `contact_fournisseur` FROM `suppliers` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_supplies`
--
DROP TABLE IF EXISTS `vue_supplies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_supplies`  AS SELECT `supplies`.`id` AS `id`, `supplies`.`user_id` AS `id_utilisateur`, `supplies`.`supplier_id` AS `id_fournisseur`, `supplies`.`warehouse_id` AS `id_entrepot`, `supplies`.`supply_status` AS `statut_approvisionnement`, `supplies`.`created_at` AS `date_creation`, `supplies`.`updated_at` AS `date_mise_a_jour` FROM `supplies` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_supply_lines`
--
DROP TABLE IF EXISTS `vue_supply_lines`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_supply_lines`  AS SELECT `supply_lines`.`id` AS `id`, `supply_lines`.`supply_id` AS `id_approvisionnement`, `supply_lines`.`product_id` AS `id_produit`, `supply_lines`.`quantity_supplied` AS `quantite_fournie`, `supply_lines`.`unit_price` AS `prix_unitaire`, `supply_lines`.`created_at` AS `date_creation`, `supply_lines`.`updated_at` AS `date_mise_a_jour` FROM `supply_lines` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_users`
--
DROP TABLE IF EXISTS `vue_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_users`  AS SELECT `users`.`id` AS `id`, `users`.`last_name` AS `nom`, `users`.`first_name` AS `prenom`, `users`.`username` AS `nom_utilisateur`, `users`.`email` AS `email`, `users`.`password` AS `mot_de_passe`, `users`.`role_id` AS `id_role`, `users`.`created_at` AS `date_creation`, `users`.`updated_at` AS `date_mise_a_jour` FROM `users` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_user_stores`
--
DROP TABLE IF EXISTS `vue_user_stores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_user_stores`  AS SELECT `user_stores`.`user_id` AS `id_utilisateur`, `user_stores`.`store_id` AS `id_boutique`, `user_stores`.`responsibility_start_date` AS `date_debut_responsabilite`, `user_stores`.`responsibility_end_date` AS `date_fin_responsabilite`, `user_stores`.`created_at` AS `date_creation`, `user_stores`.`updated_at` AS `date_mise_a_jour` FROM `user_stores` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_user_warehouses`
--
DROP TABLE IF EXISTS `vue_user_warehouses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_user_warehouses`  AS SELECT `user_warehouses`.`user_id` AS `id_utilisateur`, `user_warehouses`.`warehouse_id` AS `id_entrepot`, `user_warehouses`.`responsibility_start_date` AS `date_debut_responsabilite`, `user_warehouses`.`responsibility_end_date` AS `date_fin_responsabilite`, `user_warehouses`.`created_at` AS `date_creation`, `user_warehouses`.`updated_at` AS `date_mise_a_jour` FROM `user_warehouses` ;

-- --------------------------------------------------------

--
-- Structure for view `vue_warehouses`
--
DROP TABLE IF EXISTS `vue_warehouses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `vue_warehouses`  AS SELECT `warehouses`.`id` AS `id`, `warehouses`.`warehouse_name` AS `nom_entrepot`, `warehouses`.`warehouse_address` AS `adresse_entrepot`, `warehouses`.`warehouse_email` AS `email_entrepot`, `warehouses`.`warehouse_phone` AS `telephone_entrepot`, `warehouses`.`capacity` AS `capacite`, `warehouses`.`global_margin` AS `marge_globale`, `warehouses`.`user_id` AS `id_utilisateur`, `warehouses`.`created_at` AS `date_creation`, `warehouses`.`updated_at` AS `date_mise_a_jour` FROM `warehouses` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_supply_id_foreign` (`supply_id`),
  ADD KEY `idx_invoice_date` (`invoice_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_stores_id_foreign` (`store_id`),
  ADD KEY `idx_order_date` (`order_date`);

--
-- Indexes for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_lines_order_id_foreign` (`order_id`),
  ADD KEY `order_lines_product_id_foreign` (`product_id`);

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_name` (`product_name`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`),
  ADD KEY `stocks_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stores_user_id_foreign` (`user_id`),
  ADD KEY `stores_warehouses_id_foreign` (`warehouse_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplies_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplies_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `supplies_user_id_foreign` (`user_id`);

--
-- Indexes for table `supply_lines`
--
ALTER TABLE `supply_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supply_lines_product_id_foreign` (`product_id`),
  ADD KEY `supply_lines_supply_id_foreign` (`supply_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_stores`
--
ALTER TABLE `user_stores`
  ADD PRIMARY KEY (`user_id`,`store_id`),
  ADD KEY `user_stores_store_id_foreign` (`store_id`);

--
-- Indexes for table `user_warehouses`
--
ALTER TABLE `user_warehouses`
  ADD PRIMARY KEY (`user_id`,`warehouse_id`),
  ADD KEY `user_warehouses_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_lines`
--
ALTER TABLE `order_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `supply_lines`
--
ALTER TABLE `supply_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_supply_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_stores_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD CONSTRAINT `order_lines_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_movements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stores_warehouses_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplies`
--
ALTER TABLE `supplies`
  ADD CONSTRAINT `supplies_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `supplies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `supplies_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `supply_lines`
--
ALTER TABLE `supply_lines`
  ADD CONSTRAINT `supply_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `supply_lines_supply_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_stores`
--
ALTER TABLE `user_stores`
  ADD CONSTRAINT `user_stores_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_stores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_warehouses`
--
ALTER TABLE `user_warehouses`
  ADD CONSTRAINT `user_warehouses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
