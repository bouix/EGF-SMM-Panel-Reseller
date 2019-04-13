-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13/04/2019 às 15:00
-- Versão do servidor: 10.2.17-MariaDB
-- Versão do PHP: 7.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u372812883_child`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `apis`
--

CREATE TABLE `apis` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_end_point` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_success_response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_method` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_end_point` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_success_response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_method` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_counter_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remains_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process_all_order` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `apis`
--

INSERT INTO `apis` (`id`, `name`, `order_end_point`, `order_success_response`, `order_method`, `status_end_point`, `status_success_response`, `status_method`, `order_id_key`, `start_counter_key`, `status_key`, `remains_key`, `process_all_order`, `created_at`, `updated_at`) VALUES
(1, 'test', 'https://xxx.com/api/v2', '{\r\n}', 'POST', 'https://api.com/v2/api', '{}', 'POST', 'order', 'start_count', 'status', 'remains', 1, '2019-04-11 13:56:20', '2019-04-11 13:56:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `api_mappings`
--

CREATE TABLE `api_mappings` (
  `id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `api_package_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `api_request_params`
--

CREATE TABLE `api_request_params` (
  `id` int(10) UNSIGNED NOT NULL,
  `param_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `param_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `param_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `api_request_params`
--

INSERT INTO `api_request_params` (`id`, `param_key`, `param_value`, `param_type`, `api_type`, `api_id`, `created_at`, `updated_at`) VALUES
(1, 'test', 'link', 'table_column', 'order', 1, '2019-04-11 13:56:20', '2019-04-11 13:56:20'),
(2, 'test', 'id', 'table_column', 'status', 1, '2019-04-11 13:56:20', '2019-04-11 13:56:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `api_response_logs`
--

CREATE TABLE `api_response_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `api_id` int(10) UNSIGNED NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `configs`
--

CREATE TABLE `configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Easy Grow Fast - SMM Panel Child', NULL, NULL),
(2, 'currency_symbol', '$', NULL, NULL),
(3, 'currency_code', 'USD', NULL, NULL),
(4, 'logo', 'images/MxycgeaF5HkHa6GjICf0VUTzyCpihUEaxODNMPwE.png', NULL, NULL),
(5, 'date_format', 'd-m-Y', NULL, NULL),
(6, 'banner', 'images/99d575092d9e0fd3a1ad35b091660b3e.png', NULL, NULL),
(7, 'home_page_description', '⭐️Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', NULL, NULL),
(8, 'recaptcha_public_key', 'pub_abc123', NULL, NULL),
(9, 'recaptcha_private_key', 'prv_abc123', NULL, NULL),
(10, 'minimum_deposit_amount', '10', NULL, NULL),
(11, 'home_page_meta', '<meta name=\"description\" content=\"A description\">\r\n<meta name=\"keywords\" content=\"SMM Services\">', NULL, NULL),
(12, 'module_api_enabled', '1', NULL, NULL),
(13, 'module_support_enabled', '1', NULL, NULL),
(14, 'theme_color', '#4285f4', NULL, NULL),
(15, 'background_color', '#e9ebee', NULL, NULL),
(16, 'language', 'en', NULL, NULL),
(17, 'display_price_per', '1000', NULL, NULL),
(18, 'admin_note', 'Update me I am admin nhgote', NULL, NULL),
(19, 'admin_layout', 'container-fluid', NULL, NULL),
(20, 'user_layout', 'container', NULL, NULL),
(21, 'panel_theme', 'material', NULL, NULL),
(22, 'anonymizer', 'https://anonym.to/?', NULL, NULL),
(23, 'front_page', 'login', NULL, NULL),
(24, 'show_service_list_without_login', 'YES', NULL, NULL),
(25, 'notify_email', 'notify@example.com', NULL, NULL),
(26, 'currency_separator', '.', NULL, NULL),
(27, 'app_key', '$2y$10$Bpvj5bSA7mn9D83x/pjFqOhBfRG2dcnh52SjKhY0JytfMx5.1MSPK', NULL, NULL),
(28, 'app_code', '$2y$10$JoSs59ZA2LPqRp7Xs9Ed6.57zDdGqLNhr6UAAAy/8WffBxMGTOpz2', NULL, NULL),
(29, 'envato_username', '', NULL, NULL),
(30, 'envato_purchase_code', '', NULL, NULL),
(31, 'module_subscription_enabled', '1', NULL, NULL),
(32, 'timezone', 'America/Chicago', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_12_17_063842_create_payment_methods_table', 1),
(4, '2016_12_17_064048_create_transactions_table', 1),
(5, '2016_12_17_064634_create_services_table', 1),
(6, '2016_12_17_064818_create_packages_table', 1),
(7, '2016_12_17_065205_create_orders_table', 1),
(8, '2016_12_17_090102_create_tickets_table', 1),
(9, '2016_12_17_090321_create_ticket_messages_table', 1),
(10, '2016_12_17_090506_create_configs_table', 1),
(11, '2017_03_06_085212_create_payment_logs_table', 1),
(12, '2017_05_02_044021_create_pages_table', 1),
(13, '2017_05_23_023325_create_apis_table', 1),
(14, '2017_05_23_125309_create_api_mappings_table', 1),
(15, '2017_05_24_110239_create_api_response_logs_table', 1),
(16, '2017_05_25_111202_create_api_request_params_table', 1),
(17, '2017_06_16_062151_create_activity_log_table', 1),
(18, '2017_09_23_130959_create_user_package_prices_table', 1),
(19, '2017_11_09_090238_create_subscriptions_table', 1),
(20, '2017_11_09_161550_add_subscription_id_to_orders_table', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `source` enum('WEB','API') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WEB',
  `status` enum('COMPLETED','PROCESSING','INPROGRESS','PENDING','PARTIAL','CANCELLED','REFUNDED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `price` decimal(11,2) NOT NULL,
  `link` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_counter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remains` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `api_id` int(10) UNSIGNED DEFAULT NULL,
  `api_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_comments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subscription_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_item` decimal(11,5) NOT NULL,
  `minimum_quantity` int(10) UNSIGNED NOT NULL,
  `maximum_quantity` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `preferred_api_id` int(10) UNSIGNED DEFAULT NULL,
  `custom_comments` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pages`
--

INSERT INTO `pages` (`id`, `slug`, `content`, `meta_tags`, `created_at`, `updated_at`) VALUES
(1, 'faqs', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(2, 'terms-and-conditions', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(3, 'privacy-policy', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(4, 'about-us', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(5, 'contact-us', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(6, 'add-funds', '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(7, 'new-order', '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(8, 'mass-order', '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(9, 'login-page', '<h3><i>👌*** Important Information**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(10, 'register-page', '<h3><i>👌*** Important Information**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(11, 'new-subscription-order', '<h3><i>👌*** Important Subscription Info **👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(12, 'bank-account-page', '<h3><i>👌*** Bank Account Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(13, 'instamojo-page', '<h3><i>👌*** Instamojo Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(14, 'paytm-page', '<h3><i>👌*** Paytm Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">\n<meta name=\"keywords\" content=\"A Keyword\">', NULL, NULL),
(15, 'paywant-page', '<h3><i>👌*** Paywant Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>', '<meta name=\"description\" content=\"A description\">', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `is_disabled_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `slug`, `config_key`, `config_value`, `status`, `is_disabled_default`, `created_at`, `updated_at`) VALUES
(1, 'PayPal', 'paypal', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(2, 'Stripe', 'stripe', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(3, 'Bitcoin', 'bitcoin', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(4, 'Payza', 'payza', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(5, 'Bank/Other', 'bank-other', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(6, 'Instamojo', 'instamojo', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(7, 'Skrill', 'skrill', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(8, 'Paytm', 'paytm', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(9, 'Paywant', 'paywant', NULL, NULL, 'ACTIVE', 0, NULL, NULL),
(101, 'PayPal', 'paypal', 'paypal_mode', 'live', 'ACTIVE', 0, NULL, NULL),
(102, 'Stripe', 'stripe', 'stripe_secret', 'sc_abc123', 'ACTIVE', 0, NULL, NULL),
(103, 'Stripe', 'stripe', 'stripe_key', 'sc_abc123', 'ACTIVE', 0, NULL, NULL),
(104, 'Bitcoin', 'bitcoin', 'merchant_id', '123456', 'ACTIVE', 0, NULL, NULL),
(105, 'Bitcoin', 'bitcoin', 'secret_key', 'sec_key456', 'ACTIVE', 0, NULL, NULL),
(106, 'Payza', 'payza', 'ap_merchant', 'test@test.com', 'ACTIVE', 0, NULL, NULL),
(107, 'Payza', 'payza', 'payza_mode', 'live', 'ACTIVE', 0, NULL, NULL),
(108, 'Bank/Other', 'bank-other', 'bank_details', 'Update here bank accounts etc', 'ACTIVE', 0, NULL, NULL),
(109, 'Paypal', 'paypal', 'paypal_email', 'test@test.com', 'ACTIVE', 0, NULL, NULL),
(110, 'Instamojo', 'instamojo', 'instamojo_api_key', '123456', 'ACTIVE', 0, NULL, NULL),
(111, 'Instamojo', 'instamojo', 'instamojo_token', '123456', 'ACTIVE', 0, NULL, NULL),
(112, 'Instamojo', 'instamojo', 'instamojo_salt', '123456', 'ACTIVE', 0, NULL, NULL),
(113, 'Skrill', 'skrill', 'skrill_email', 'test@test.com', 'ACTIVE', 0, NULL, NULL),
(114, 'Skrill', 'skrill', 'skrill_secret', 'replace-me', 'ACTIVE', 0, NULL, NULL),
(115, 'Paytm', 'paytm', 'paytm_email_imap_address', '{imap.gmail.com:993/imap/ssl}INBOX', 'ACTIVE', 0, NULL, NULL),
(116, 'Paytm', 'paytm', 'paytm_email', 'your@email.com', 'ACTIVE', 0, NULL, NULL),
(117, 'Paytm', 'paytm', 'paytm_email_password', '123456', 'ACTIVE', 0, NULL, NULL),
(118, 'Paytm', 'paytm', 'paytm_indian_rupees_valued_1_usd', '66', 'ACTIVE', 0, NULL, NULL),
(119, 'Instamojo', 'instamojo', 'instamojo_indian_rupees_valued_1_usd', '66', 'ACTIVE', 0, NULL, NULL),
(120, 'Paywant', 'paywant', 'paywant_api_key', '123-123-123', 'ACTIVE', 0, NULL, NULL),
(121, 'Paywant', 'paywant', 'paywant_api_secret', '123-123-123', 'ACTIVE', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `is_subscription_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `posts` int(10) UNSIGNED NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `link` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('PENDING','ACTIVE','COMPLETED','STOPPED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('OPEN','CLOSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tickets`
--

INSERT INTO `tickets` (`id`, `subject`, `status`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'test', 'OPEN', 'test desc', 2, '2019-04-11 14:31:28', '2019-04-11 14:31:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `funds` decimal(11,2) NOT NULL DEFAULT 0.00,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','DEACTIVATED','DELETED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `role` enum('ADMIN','USER') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USER',
  `api_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled_payment_methods` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'America/Chicago',
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `funds`, `password`, `status`, `role`, `api_token`, `enabled_payment_methods`, `skype_id`, `timezone`, `last_login`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '999.00', '$2y$10$rLv68NtHTZZFn8TimVeaveuf/wtOx5kmzA7fShRzMyo99f/SR1eUq', 'ACTIVE', 'ADMIN', NULL, '', '', 'America/Chicago', '2019-04-13 14:16:05', 'EGwFgpvUpAe7zOMvUTbsRbVdI9UhR3dHPRT43h05K5MldMDMVZoAKtmdAmjL', NULL, '2019-04-13 14:16:05'),
(2, 'test user', 'test@user.com', '10.00', '$2y$10$Ty1yGph9.d.BN5Li6FAvPOtq10He5Qk7b/YPVdrwwGo6vrR/LmX96', 'ACTIVE', 'USER', NULL, '5,3,6,1,8,9,4,7,2', 'test', 'America/Chicago', '2019-04-11 13:57:38', NULL, '2019-04-11 13:57:23', '2019-04-11 13:57:38'),
(3, 'test01', 'test01@admin.com', '100000.00', '$2y$10$63SjZRoW.aDYUL/MtqJA5O.mxIF2BrHVl1vsuBVx8GSq7Z6WC0G2C', 'ACTIVE', 'ADMIN', NULL, '5', '', 'America/Chicago', '2019-04-12 13:32:07', NULL, '2019-04-12 13:29:58', '2019-04-12 13:32:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_package_prices`
--

CREATE TABLE `user_package_prices` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `price_per_item` decimal(11,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `api_mappings`
--
ALTER TABLE `api_mappings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_mappings_api_id_foreign` (`api_id`);

--
-- Índices de tabela `api_request_params`
--
ALTER TABLE `api_request_params`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_request_params_api_id_foreign` (`api_id`);

--
-- Índices de tabela `api_response_logs`
--
ALTER TABLE `api_response_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_response_logs_api_id_foreign` (`api_id`);

--
-- Índices de tabela `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_package_id_foreign` (`package_id`),
  ADD KEY `orders_subscription_id_foreign` (`subscription_id`);

--
-- Índices de tabela `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_service_id_foreign` (`service_id`);

--
-- Índices de tabela `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Índices de tabela `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_logs_user_id_foreign` (`user_id`),
  ADD KEY `payment_logs_payment_method_id_foreign` (`payment_method_id`);

--
-- Índices de tabela `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_package_id_foreign` (`package_id`);

--
-- Índices de tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`);

--
-- Índices de tabela `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_messages_user_id_foreign` (`user_id`),
  ADD KEY `ticket_messages_ticket_id_foreign` (`ticket_id`);

--
-- Índices de tabela `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_payment_method_id_foreign` (`payment_method_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- Índices de tabela `user_package_prices`
--
ALTER TABLE `user_package_prices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `apis`
--
ALTER TABLE `apis`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `api_mappings`
--
ALTER TABLE `api_mappings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `api_request_params`
--
ALTER TABLE `api_request_params`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `api_response_logs`
--
ALTER TABLE `api_response_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de tabela `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `user_package_prices`
--
ALTER TABLE `user_package_prices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `api_mappings`
--
ALTER TABLE `api_mappings`
  ADD CONSTRAINT `api_mappings_api_id_foreign` FOREIGN KEY (`api_id`) REFERENCES `apis` (`id`);

--
-- Restrições para tabelas `api_request_params`
--
ALTER TABLE `api_request_params`
  ADD CONSTRAINT `api_request_params_api_id_foreign` FOREIGN KEY (`api_id`) REFERENCES `apis` (`id`);

--
-- Restrições para tabelas `api_response_logs`
--
ALTER TABLE `api_response_logs`
  ADD CONSTRAINT `api_response_logs_api_id_foreign` FOREIGN KEY (`api_id`) REFERENCES `apis` (`id`);

--
-- Restrições para tabelas `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `orders_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Restrições para tabelas `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `payment_logs_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `payment_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
