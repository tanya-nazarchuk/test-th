
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1510553556),
('m171109_204210_create_user_table', 1510553558),
('m171110_102531_create_transaction_table', 1510553560);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `transaction` (
`id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `transaction` (`id`, `receiver_id`, `sender_id`, `amount`, `created_at`) VALUES
(1, 2, 1, 20.00, 1510553815),
(2, 5, 1, 10.00, 1510553842),
(3, 1, 4, 10.00, 1510553862),
(4, 6, 4, 50.00, 1510553921),
(5, 7, 3, 60.00, 1510553941),
(6, 5, 2, 30.00, 1510553979),
(7, 4, 7, 10.00, 1510554017),
(8, 1, 6, 10.00, 1510554037),
(9, 3, 1, 30.00, 1510554101),
(10, 1, 2, 10.00, 1510554154);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `auth_key`, `access_token`, `balance`, `created_at`) VALUES
(1, 'tanya', '-RX2wHaLgvNtQjYCaKu3BjXp08BwcUiF', NULL, 70.00, 1510553577),
(2, 'alex', 'QzYNdmgBrsUHYImzzGH2jE0kc9cVPBCj', NULL, -20.00, 1510553596),
(3, 'kate', '7r4nJyynFJUMSMcJl2jbzPuDOH4Yz2hj', NULL, 20.00, 1510553604),
(4, 'ivan', '-yg0WpDvDT8ddTe__F4Q0vGLTzob9ns0', NULL, 50.00, 1510553638),
(5, 'john', 'TpD5EpKfGN90mXm5O1FnRJv-WHoMT5sA', NULL, 40.00, 1510553658),
(6, 'egor', 'jHIrbcd-M0HfLQmTNvsho8kdOVacEQ13', NULL, 40.00, 1510553682),
(7, 'artem', 'i7Q6w6ToVL4_EsgMhCnbycWDF6SAhghG', NULL, 50.00, 1510553691);

-- --------------------------------------------------------

ALTER TABLE `migration`
 ADD PRIMARY KEY (`version`);


ALTER TABLE `transaction`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_transaction_receiver_user` (`receiver_id`), ADD KEY `fk_transaction_sender_user` (`sender_id`);

ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `access_token` (`access_token`);

ALTER TABLE `transaction`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;

ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;

ALTER TABLE `transaction`
ADD CONSTRAINT `fk_transaction_receiver_user` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
ADD CONSTRAINT `fk_transaction_sender_user` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);
