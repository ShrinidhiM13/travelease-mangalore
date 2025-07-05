-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 08:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelease_mangaluru`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(3, 'Exploring the Rich Culture of Tulunadu', 'Tulunadu, a region steeped in tradition, offers a vibrant cultural experience. From the mesmerizing Yakshagana performances to age-old rituals and cuisine, every aspect is a window into its glorious past...', 'uploads/blogs/blog_682d69f8d6ba39.94524280.jfif', '2025-05-21 05:51:52'),
(4, 'Top 5 Must-Visit Temples in Mangaluru', 'Mangaluru is home to some of the most spiritually enriching temples. This blog lists the top five temples you must visit to soak in peace, history, and stunning architecture — starting with Kudroli and Kateel...', 'uploads/blogs/blog_682d6a19094075.20251799.jfif', '2025-05-21 05:52:25'),
(5, 'St. Aloysius Chapel – Art on Sacred Walls', 'The chapel’s interiors are a visual feast. Painted over a century ago, the walls tell biblical stories in vivid colors and detail. A must-visit for art lovers and history buffs alike...', 'uploads/blogs/blog_682d6a2d8524e8.89052740.jpg', '2025-05-21 05:52:45');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `pickup_location`, `booking_date`) VALUES
(3, 2, 2, 'Mangalore', '2025-05-21 06:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gmap_embed` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `description`, `image`, `gmap_embed`) VALUES
(3, 'Kudroli Gokarnanatha Temple', 'A prominent temple in Mangalore dedicated to Lord Shiva, known for its Dravidian-style architecture and grand Dasara celebrations. The temple\'s vibrant ambiance and cultural importance make it a major spiritual center for locals and tourists alike.', 'uploads/destinations/dest_682d681d9812b0.86993429.jfif', '<iframe src=\"https://www.google.com/maps/embed?pb=!4v1747806221516!6m8!1m7!1sCAoSFkNJSE0wb2dLRUlDQWdJRGU1WnJNSUE.!2m2!1d12.87617032132521!2d74.8321646849076!3f332.1834!4f0!5f0.7820865974627469\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>'),
(4, 'Panambur Beach', 'One of the most popular beaches in Mangalore, known for its clean shores, sunset views, and fun activities like camel rides, jet skiing, and food stalls. It\'s also the venue for the annual International Kite Festival.', 'uploads/destinations/dest_682d688e4e07e9.73259993.jpg', '<iframe src=\"https://www.google.com/maps/embed?pb=!4v1747806299521!6m8!1m7!1sCAoSFkNJSE0wb2dLRUlDQWdJREVfSWVuRlE.!2m2!1d12.90227860379042!2d74.82498360382252!3f329.97128!4f0!5f0.7820865974627469\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>'),
(5, 'St. Aloysius Chapel', 'Located on the campus of St. Aloysius College, this chapel is renowned for its breathtaking frescoes and paintings by Italian artist Antonio Moscheni. It’s a heritage site showcasing Mangalore’s colonial history and Christian art.', 'uploads/destinations/dest_682d68db33b408.01424805.jpg', '<iframe src=\"https://www.google.com/maps/embed?pb=!4v1747806389737!6m8!1m7!1sCAoSFkNJSE0wb2dLRUlDQWdJQ0hoUEstQXc.!2m2!1d12.87384506225891!2d74.8453426817232!3f25.271767!4f0!5f0.7820865974627469\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `youtube_url`, `image1`, `image2`, `image3`, `image4`, `image5`) VALUES
(3, 'Karavali Utsav', 'A grand cultural festival celebrating the coastal traditions, music, dance, and food of Tulunadu. Expect folk performances, Yakshagana, and local crafts.', '2025-05-08', 'https://youtu.be/nkGWfIkUapE?si=ZbNUMGEQr5-yx_qe', 'image1_682d6ba95d773.jpg', NULL, NULL, NULL, NULL),
(4, 'Yakshagana Night', 'A traditional Yakshagana performance showcasing a mythological story through vibrant costumes, music, and storytelling.', '2025-05-29', 'https://youtu.be/hY4Ek-fEffE?si=MdIV2np48sKuBYKO', 'image1_682d6bfb5bcc7.jpg', 'image2_682d6bfb5be60.jpg', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `name`, `contact`, `experience`, `photo`) VALUES
(2, 'Anish', '6363658451', '2 years as a local travel guide in Mangaluru. Specialized in cultural heritage tours, coastal food walks, and religious site guiding.', 'uploads/guides/guide_682d665f32c9f5.37275226.jpg'),
(3, 'Ravi', '9876543210', '3.5 years working with tourism agencies. Expertise in backwater trips, Yakshagana storytelling, and temple rituals.', 'uploads/guides/guide_682d667e395126.00966588.jpg'),
(4, 'Sneha', '9163658451', '1 year conducting city walks and local art workshops in Mangaluru.', 'uploads/guides/guide_682d6695cfa373.23910412.jpg'),
(5, 'Faizal', '7676543210', '4 years offering language-assisted guided tours for foreign tourists around Udupi and Mangalore.', 'uploads/guides/guide_682d66b72d2ed9.55932055.jpg'),
(6, 'Asha', '965245555', '2.5 years organizing cultural exchange programs and homestay coordination in Tulunadu.', 'uploads/guides/guide_682d66d17a0fa0.09811930.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `destination_id`, `details`, `price`, `days`, `image`) VALUES
(2, 'Temple Package', 3, 'Explore the grand Kudroli Gokarnanatha Temple, known for its Dravidian architecture and vibrant Dasara celebrations. The package includes temple visit, guide assistance, pooja arrangements, and local refreshments.', 1299.00, 1, 'uploads/packages/package_682d6957eb2204.18431466.jfif'),
(3, 'Cultural Heritage Tour', 5, 'Visit the stunning St. Aloysius Chapel with beautiful frescoes painted by Italian artist Antonio Moscheni. Includes guided tour, historical insights, and time for reflection and photography.', 999.00, 1, 'uploads/packages/package_682d69743e8e68.55962885.jpg'),
(4, 'Coastal Leisure Escape', 4, 'Enjoy a serene beach day at Panambur with beach games, camel rides, and snacks. The package includes transportation, beach mats, life jackets, and a tour guide to ensure a safe and fun experience.', 799.00, 1, 'uploads/packages/package_682d698f498615.28801742.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_package` (`package_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_user` (`user_id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_destination` (`destination_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `fk_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
