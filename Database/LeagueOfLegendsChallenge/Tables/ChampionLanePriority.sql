/*
ChampionLanePriority
This table will be populated with all the champions and their
respctive lanes that are most popular. The table: workingChampionLane will 
populate this table with values. This table is meant only to be a view table...
So I should probably just make it a view, but oh well.
*/
CREATE TABLE `ChampionLanePriority` (
  `championid` int(11) NOT NULL,
  `champname` varchar(60) NOT NULL,
  `top_priority` double NOT NULL,
  `jungle_priority` double NOT NULL,
  `mid_priority` double NOT NULL,
  `adc_priority` double NOT NULL,
  `supp_priority` double NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`championid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
