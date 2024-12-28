-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 28, 2024 at 07:54 PM
-- Server version: 5.7.34
-- PHP Version: 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `NoteFlix`
--

-- --------------------------------------------------------

--
-- Table structure for table `block_list`
--

CREATE TABLE `block_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blocked_user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `note_id`, `user_id`, `feedback`, `created_at`) VALUES
(1, 3, 2, 'Hermione, your theory is brilliant, but honestly, a bit overcomplicated. Sometimes I wish magic were just about waving a wand!', '2024-12-27 04:45:51'),
(2, 5, 2, 'As much as I hate to admit it, Snape, your potions skills are on point. But I still won’t trust your dungeon!', '2024-12-27 04:47:43'),
(3, 2, 3, 'Nice work, Harry. But defense isn’t just about reflexes—it’s about strategy too. Something to think about for next time!', '2024-12-27 04:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `follow_list`
--

CREATE TABLE `follow_list` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `follow_list`
--

INSERT INTO `follow_list` (`id`, `follower_id`, `user_id`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 3, 1),
(14, 5, 3),
(7, 2, 3),
(17, 5, 1),
(8, 2, 6),
(9, 3, 2),
(10, 3, 5),
(11, 6, 2),
(12, 6, 3),
(13, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `read_status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `msg`, `read_status`, `created_at`) VALUES
(1, 2, 3, 'Hey Hermione, I’ve been trying out your theories on magical charms and... I think I’m getting the hang of it. Could use some help with the incantation, though. You’re a lifesaver, as always!', 1, '2024-12-27 04:53:44'),
(2, 3, 5, 'I don’t agree with most of your views, Draco, but I have to admit, your notes on the Dark Arts are well-structured. Still, I think you should consider the consequences before diving too deep into them.', 1, '2024-12-27 04:55:34'),
(3, 6, 2, 'Potter, you’ve got a lot to learn about subtlety. Your approach to defense magic is brash, not strategic. When you get over your impulse to charge into everything, then we’ll talk.', 1, '2024-12-27 04:56:54'),
(7, 2, 6, 'Snape, I know you think I’m reckless, but I know what I’m doing. I’m not just charging in blindly. I’ve survived a lot because I think on my feet.', 1, '2024-12-27 07:22:43'),
(8, 3, 6, 'Professor, I was reading your notes on potion-making again. Have you ever thought of including more on potion variation in different magical communities? It could be a valuable addition to your teachings.', 1, '2024-12-27 07:25:40'),
(9, 6, 3, 'Granger, I appreciate your suggestions, but some things in potion-making can’t be diluted by academic curiosity. There’s no time for extensive studies when we need to master the craft first.', 0, '2024-12-27 07:30:05'),
(11, 5, 3, 'Granger, your lectures on ethics are as boring as ever, but I can’t deny the points you make. Just remember, power is everything in the end.', 0, '2024-12-27 07:34:39'),
(10, 5, 6, 'Professor Snape, I was thinking about your potion-making techniques. I want to perfect the Draught of Living Death. Any advice on getting the consistency just right?', 1, '2024-12-27 07:33:42');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_img` text NOT NULL,
  `note_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `note_img`, `note_text`, `created_at`) VALUES
(1, 1, '1734888052CSE370 Lab_2.jpg', 'Intro to ER diagrams', '2024-12-22 17:20:52'),
(2, 2, '1735237514Harry-note1.jpg', 'Defeating dark wizards', '2024-12-26 18:25:14'),
(3, 3, '1735272740handwriting_20241227_101121_via_10015_io.jpg', 'Magical Theory', '2024-12-27 04:12:21'),
(4, 5, '1735273391handwriting_20241227_102233_via_10015_io.jpg', 'Ethics of the dark arts', '2024-12-27 04:23:11'),
(5, 6, '1735274346handwriting_20241227_103848_via_10015_io.jpg', '', '2024-12-27 04:39:06'),
(7, 7, '1735303933handwriting_20241227_103848_via_10015_io.jpg', 'Potion making ', '2024-12-27 12:52:13');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_user_id` int(11) NOT NULL,
  `read_status` int(11) NOT NULL DEFAULT '0',
  `note_id` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `to_user_id`, `message`, `created_at`, `from_user_id`, `read_status`, `note_id`) VALUES
(1, 2, 'pinned your note !', '2024-12-27 03:49:45', 1, 1, '2'),
(2, 2, 'unpinned your note !', '2024-12-27 03:49:46', 1, 1, '2'),
(3, 2, 'pinned your note !', '2024-12-27 03:49:53', 1, 1, '2'),
(4, 2, 'unpinned your note !', '2024-12-27 03:49:54', 1, 1, '2'),
(5, 2, 'pinned your note !', '2024-12-27 03:49:55', 1, 1, '2'),
(6, 2, 'unpinned your note !', '2024-12-27 03:49:55', 1, 1, '2'),
(7, 3, 'started following you !', '2024-12-27 03:52:26', 1, 1, '0'),
(8, 1, 'started following you !', '2024-12-27 03:56:39', 3, 1, '0'),
(9, 2, 'pinned your note !', '2024-12-27 03:58:45', 1, 1, '2'),
(10, 1, 'started following you !', '2024-12-27 04:15:53', 5, 1, '0'),
(11, 2, 'started following you !', '2024-12-27 04:15:54', 5, 1, '0'),
(12, 3, 'started following you !', '2024-12-27 04:15:55', 5, 1, '0'),
(13, 3, 'pinned your note !', '2024-12-27 04:16:08', 5, 1, '3'),
(14, 2, 'pinned your note !', '2024-12-27 04:16:10', 5, 1, '2'),
(15, 1, 'pinned your note !', '2024-12-27 04:16:12', 5, 1, '1'),
(16, 2, 'blocked you', '2024-12-27 04:25:25', 5, 1, '0'),
(17, 3, 'started following you !', '2024-12-27 04:45:39', 2, 1, '0'),
(18, 3, 'commented on your note', '2024-12-27 04:45:51', 2, 1, '3'),
(19, 6, 'started following you !', '2024-12-27 04:47:32', 2, 1, '0'),
(20, 6, 'commented on your note', '2024-12-27 04:47:43', 2, 1, '5'),
(21, 6, 'pinned your note !', '2024-12-27 04:47:57', 2, 1, '5'),
(22, 2, 'started following you !', '2024-12-27 04:50:19', 3, 1, '0'),
(23, 2, 'commented on your note', '2024-12-27 04:50:41', 3, 1, '2'),
(24, 5, 'started following you !', '2024-12-27 04:55:12', 3, 1, '0'),
(25, 2, 'started following you !', '2024-12-27 05:46:50', 6, 1, '0'),
(26, 3, 'started following you !', '2024-12-27 05:48:17', 6, 1, '0'),
(27, 5, 'started following you !', '2024-12-27 05:50:35', 6, 1, '0'),
(28, 3, 'Unfollowed you !', '2024-12-27 06:47:45', 5, 1, '0'),
(29, 2, 'Unblocked you !', '2024-12-27 06:48:15', 5, 1, '0'),
(30, 1, 'Unfollowed you !', '2024-12-27 06:48:47', 5, 1, '0'),
(31, 3, 'started following you !', '2024-12-27 06:49:02', 5, 1, '0'),
(32, 1, 'started following you !', '2024-12-27 06:55:22', 5, 1, '0'),
(33, 1, 'unpinned your note !', '2024-12-27 06:55:29', 5, 1, '1'),
(34, 1, 'Unfollowed you !', '2024-12-27 06:55:38', 5, 1, '0'),
(35, 1, 'started following you !', '2024-12-27 07:12:33', 5, 1, '0'),
(36, 1, 'pinned your note !', '2024-12-27 07:12:45', 5, 1, '1'),
(37, 1, 'Unfollowed you !', '2024-12-27 07:12:52', 5, 1, '0'),
(38, 1, 'started following you !', '2024-12-27 07:12:56', 5, 1, '0'),
(39, 6, 'started following you !', '2024-12-27 07:13:10', 5, 1, '0'),
(40, 6, 'Unfollowed you !', '2024-12-27 07:13:17', 5, 1, '0'),
(41, 6, 'started following you !', '2024-12-27 09:06:18', 1, 1, '0'),
(42, 6, 'Unfollowed you !', '2024-12-27 09:06:21', 1, 1, '0'),
(43, 1, 'started following you !', '2024-12-27 10:51:23', 2, 1, '0'),
(44, 1, 'Unfollowed you !', '2024-12-27 10:51:25', 2, 1, '0'),
(45, 3, 'pinned your note !', '2024-12-27 11:23:47', 1, 0, '3'),
(46, 3, 'unpinned your note !', '2024-12-27 11:23:48', 1, 0, '3'),
(47, 6, 'started following you !', '2024-12-27 14:47:05', 1, 0, '0'),
(48, 6, 'Unfollowed you !', '2024-12-27 14:47:19', 1, 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `pins`
--

CREATE TABLE `pins` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pins`
--

INSERT INTO `pins` (`id`, `note_id`, `user_id`) VALUES
(33, 1, 1),
(34, 2, 1),
(35, 3, 5),
(36, 2, 5),
(39, 1, 5),
(38, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `profile_pic` varchar(255) NOT NULL DEFAULT 'default_profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ac_status` int(11) DEFAULT '1' COMMENT '0=not verified, 1=active, 2=blocked'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `gender`, `email`, `username`, `password`, `profile_pic`, `created_at`, `updated_at`, `ac_status`) VALUES
(1, 'Shafat', 'Mufdi', 1, 'shafatmufdi4807@gmail.com', 'meowdy', 'f30aa7a662c728b7407c54ae6bfd27d1', '1734888025FB_IMG_1734778816026.jpg', '2024-12-22 17:19:19', '2024-12-22 17:20:25', 1),
(2, 'Harry', 'Potter', 1, 'harry.potter@hogwarts.edu', 'TheBoyWhoNotes', 'f30aa7a662c728b7407c54ae6bfd27d1', '1735237449Harry-Potter-Characters-Harry-Potter.jpg', '2024-12-26 18:16:04', '2024-12-26 18:24:09', 1),
(3, 'Hermoine', 'Granger', 2, 'hermione.granger@hogwarts.edu', 'GrangerDanger', 'f30aa7a662c728b7407c54ae6bfd27d1', '1735238097IMG_20241227_003414.jpg', '2024-12-26 18:30:57', '2024-12-26 18:34:57', 1),
(5, 'Draco', 'Malfoy', 1, 'draco.malfoy@malfoy.com', 'SlytherinsBestBoy', 'f30aa7a662c728b7407c54ae6bfd27d1', '1735273110IMG_20241227_101747.jpg', '2024-12-27 04:15:34', '2024-12-27 04:18:30', 1),
(6, 'Severus', 'Snape', 1, 'severus.snape@hogwarts.edu', 'DarkHalfBlood', 'f30aa7a662c728b7407c54ae6bfd27d1', '1735273989IMG_20241227_103231.jpg', '2024-12-27 04:28:51', '2024-12-27 04:33:09', 1),
(7, 'Shafat', 'Mufdi', 1, 'shafatmufdi@gmail.com', 'shafat', 'f30aa7a662c728b7407c54ae6bfd27d1', 'default_profile.jpg', '2024-12-27 12:46:23', '2024-12-27 12:46:23', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `block_list`
--
ALTER TABLE `block_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_block_user_id` (`user_id`),
  ADD KEY `fk_block_blocked_user_id` (`blocked_user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_note_id` (`note_id`),
  ADD KEY `fk_feedback_user_id` (`user_id`);

--
-- Indexes for table `follow_list`
--
ALTER TABLE `follow_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_follow_follower_id` (`follower_id`),
  ADD KEY `fk_follow_user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_messages_from_user_id` (`from_user_id`),
  ADD KEY `fk_messages_to_user_id` (`to_user_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notes_user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_to_user_id` (`to_user_id`),
  ADD KEY `fk_notifications_from_user_id` (`from_user_id`);

--
-- Indexes for table `pins`
--
ALTER TABLE `pins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pins_note_id` (`note_id`),
  ADD KEY `fk_pins_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `block_list`
--
ALTER TABLE `block_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `follow_list`
--
ALTER TABLE `follow_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `pins`
--
ALTER TABLE `pins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
