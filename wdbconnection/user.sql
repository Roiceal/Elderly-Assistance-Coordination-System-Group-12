CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `age` int(4) NOT NULL,
  `dob` date NOT NULL,
  `image` varchar(255) NOT NULL
) 