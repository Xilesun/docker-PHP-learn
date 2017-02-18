-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: 2017-02-18 02:31:50
-- 服务器版本： 5.7.17
-- PHP Version: 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web`
--

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE `comment` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `username`, `comment`, `date`) VALUES
(61, 'testaaaa', 'To test2017: hi', '2017/02/18'),
(59, 'test2017', 'test reply', '2017/02/18'),
(62, 'testaaaa', 'To test2017: test again', '2017/02/18');

-- --------------------------------------------------------

--
-- 表的结构 `msg`
--

CREATE TABLE `msg` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment_user` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `msg`
--

INSERT INTO `msg` (`id`, `username`, `comment_user`) VALUES
(2, 'test2017', 'testaaaa'),
(3, 'test2017', 'testaaaa');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(3) NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(38, 'test2017', '$2y$10$/ZFsue7.xmJwnKMLsGWu.ehCBNkeKb2tTrEAw28/dBT6gSIwMaSym', '2013xile@gmail.com'),
(39, 'testaaa', '$2y$10$rb5aTKFiyb/pbTsFHK0/0uVp58EFemYG31KV5huy9I5VZL4rjTAB6', '2013xile@gmail.com'),
(40, 'testaaaa', '$2y$10$9abY1prKnH6dkyQ/QtGIlO8n5/40/weds9kQayLloPI307R0i6Zee', '2013xile@gmail.com'),
(41, 'testaaaaa', '$2y$10$RE5vMiaWRju684AMoaNZZOGdMaRlIfUbBmuziXkpW4JijPI.kbVsq', '2013xile@gmail.com'),
(42, 'test20171', '$2y$10$f0rxPO4Yu8QQmWubH.Jf4.pA7QG2237s1dLW3wwLevHyJhDdYUGiK', '2013xile@gmail.com'),
(43, 'test201711', '$2y$10$VHo5/sX4tfjwm8xc4F0W/.rF2ux8hJUXtb7Voz1iTEdD22Q63PK1e', '2013xile@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `msg`
--
ALTER TABLE `msg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- 使用表AUTO_INCREMENT `msg`
--
ALTER TABLE `msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
