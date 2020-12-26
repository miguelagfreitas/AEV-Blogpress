-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 19, 2020 at 11:37 AM
-- Server version: 5.6.50
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`) VALUES
(1, 'Announcements');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  `moderated` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` datetime DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `created`, `modified`, `published`, `title`, `summary`, `content`) VALUES
(1, 1, '2020-11-19 01:31:44', '2020-11-19 01:31:44', '2020-11-19 01:31:44', 'Hello World!', 'Welcome to your new BlogPress!\r\n\r\nYou are about to embark on an amazing journey...', '<p>Welcome to your new BlogPress!</p>\r\n\r\n<p>You are about to embark on an amazing journey...</p>\r\nThe Aveiro lagoon (Ria de Aveiro) is a lagoon in Portugal. It is located on the Atlantic coast of Portugal, south of the municipality of Espinho and north of Mira (to the north of the Cape Mondego). Its average area covers approximately 75 square kilometres (29 sq mi). It is named after the city of Aveiro, which is the chief urban centre located near to the lagoon. Other urban centres near the Ria de Aveiro are Ílhavo, Gafanha da Nazaré, Estarreja, Ovar and Esmoriz. Some beaches nearby include those of Barra, Costa Nova, Torreira, Vagueira, Furadouro, Cortegaça and Praia de Mira. There are also beaches in the São Jacinto Peninsula.\r\n\r\nThe Aveiro Lagoon is beyond a mere geographical feature of Portugal. This 45 kilometres (28 mi) long lagoon stands as one of Europe\'s last remaining untouched coastal marshland. It is also a haven for numerous bird species. The locals call this rich lagoon Ria de Aveiro. Tourism and aquaculture are the mainstay of the Aveiro Lagoon region. It is also renowned for its artisan fishing and as a center for the collection of Flor de sal, an expensive salt variety.');

-- --------------------------------------------------------

--
-- Table structure for table `post_categories`
--

CREATE TABLE `post_categories` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_categories`
--

INSERT INTO `post_categories` (`id`, `post_id`, `category_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `setting` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `type`, `setting`, `value`, `title`) VALUES
(1, 'text', 'name', 'BlogPress', 'Blog Name'),
(2, 'text', 'front_title', 'Latest News', 'Front Page Title'),
(3, 'checkbox', 'comments', '1', 'Enable commenting on posts'),
(4, 'checkbox', 'moderate', '1', 'Enable comment moderation'),
(5, 'text', 'subtitle', 'Welcome to BlogPress', 'Site Strapline'),
(6, 'text', 'email', 'root@localhost', 'Administrator Email'),
(7, 'checkbox', 'debug', '0', 'Debug mode');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `displayname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `bio` text NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `displayname`, `email`, `password`, `level`, `created`, `bio`, `avatar`) VALUES
(1, 'admin', 'Administrator', 'root@localhost', 'admin', 2, '2000-01-01 00:00:00', '<p>I am Administrator!</p>\r\n', ''),
(2, 'test', 'Test', 'test@test.com', 'test', 2, '2020-11-19 01:31:46', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`blog_id`,`moderated`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`,`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`setting`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

