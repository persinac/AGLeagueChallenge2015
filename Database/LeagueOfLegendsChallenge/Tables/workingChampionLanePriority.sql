/*
workingChampionLanePriority
This table will hold counts per each champion based 
on the lane in which they played. Also keeps season into
account to later attempt to produce lane popularity
per season
*/
CREATE TABLE `workingChampionLanePriority` (
  `championid` int(11) NOT NULL DEFAULT '0',
  `season` varchar(25) NOT NULL DEFAULT '-',
  `top_priority` double NOT NULL DEFAULT '0',
  `tp_count` int(11) NOT NULL DEFAULT '0',
  `jungle_priority` double NOT NULL DEFAULT '0',
  `jp_count` int(11) NOT NULL DEFAULT '0',
  `mid_priority` double NOT NULL DEFAULT '0',
  `mp_count` int(11) NOT NULL DEFAULT '0',
  `adc_priority` double NOT NULL DEFAULT '0',
  `ap_count` int(11) NOT NULL DEFAULT '0',
  `supp_priority` double NOT NULL DEFAULT '0',
  `sp_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
