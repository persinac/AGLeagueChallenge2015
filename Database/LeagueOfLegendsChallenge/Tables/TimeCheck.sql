CREATE TABLE `TimeCheck` (
  `hour` int(11) NOT NULL,
  `firstHalf` int(11) DEFAULT '0',
  `secondHalf` int(11) DEFAULT '0',
  PRIMARY KEY (`hour`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
