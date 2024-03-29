DROP TABLE IF EXISTS Countries;
CREATE TABLE Countries (
  ID SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  bDeleted TINYINT(1) NOT NULL DEFAULT 0,
  intOrder DOUBLE DEFAULT 999,
  chrCountry VARCHAR(80) default NULL,
  chrCountryShort VARCHAR(30) default NULL
) ENGINE=MyISAM CHARSET=utf8;

INSERT INTO Countries (ID, intOrder, chrCountry, chrCountryShort) VALUES 
(1,999,'Afghanistan','AF'),
(2,999,'Albania','AL'),
(3,999,'Algeria','DZ'),
(4,999,'American Samoa','AS'),
(5,999,'Andorra','AD'),
(6,999,'Angola','AO'),
(7,999,'Anguilla','AI'),
(8,999,'Antarctica','AQ'),
(9,999,'Antigua and Barbuda','AG'),
(10,999,'Argentina','AR'),
(11,999,'Armenia','AM'),
(12,999,'Aruba','AW'),
(13,999,'Australia','AU'),
(14,999,'Austria','AT'),
(15,999,'Azerbaijan','AZ'),
(16,999,'Bahamas','BS'),
(17,999,'Bahrain','BH'),
(18,999,'Bangladesh','BD'),
(19,999,'Barbados','BB'),
(20,999,'Belarus','BY'),
(21,999,'Belgium','BE'),
(22,999,'Belize','BZ'),
(23,999,'Benin','BJ'),
(24,999,'Bermuda','BM'),
(25,999,'Bhutan','BT'),
(26,999,'Bolivia','BO'),
(27,999,'Bosnia and Herzegowina','BA'),
(28,999,'Botswana','BW'),
(29,999,'Bouvet Island','BV'),
(30,999,'Brazil','BR'),
(31,999,'British Indian Ocean Territory','IO'),
(32,999,'Brunei Darussalam','BN'),
(33,999,'Bulgaria','BG'),
(34,999,'Burkina Faso','BF'),
(35,999,'Burundi','BI'),
(36,999,'Cambodia','KH'),
(37,999,'Cameroon','CM'),
(38,2,'Canada','CA'),
(39,999,'Cape Verde','CV'),
(40,999,'Cayman Islands','KY'),
(41,999,'Central African Republic','CF'),
(42,999,'Chad','TD'),
(43,999,'Chile','CL'),
(44,999,'China','CN'),
(45,999,'Christmas Island','CX'),
(46,999,'Cocoa (Keeling) Islands','CC'),
(47,999,'Colombia','CO'),
(48,999,'Comoros','KM'),
(49,999,'Congo','CG'),
(50,999,'Cook Islands','CK'),
(51,999,'Costa Rica','CR'),
(52,999,'Cote Divoire','CI'),
(53,999,'Croatia (Hrvatska)','CT'),
(54,999,'Cuba','CU'),
(55,999,'Cyprus','CY'),
(56,999,'Czech Republic','CZ'),
(57,999,'Denmark','DK'),
(58,999,'Djibouti','DJ'),
(59,999,'Dominica','DM'),
(60,999,'Dominican Republic','DO'),
(61,999,'East Timor','TP'),
(62,999,'Ecuador','EC'),
(63,999,'Egypt','EG'),
(64,999,'El Salvador','SV'),
(65,999,'Equatorial Guinea','GQ'),
(66,999,'Eritrea','ER'),
(67,999,'Estonia','EE'),
(68,999,'Ethiopia','ET'),
(69,999,'Falkland Islands (Malvinas)','FK'),
(70,999,'Faroe Islands','FO'),
(71,999,'Fiji','FJ'),
(72,999,'Finland','FI'),
(73,999,'France','FR'),
(74,999,'Gabon','GA'),
(75,999,'Gambia','GM'),
(76,999,'Georgia','GE'),
(77,999,'Germany','DE'),
(78,999,'Ghana','GH'),
(79,999,'Gibraltar','GI'),
(80,999,'Greece','GR'),
(81,999,'Greenland','GL'),
(82,999,'Grenada','GD'),
(83,999,'Guam','GU'),
(84,999,'Guatemala','GT'),
(85,999,'Guinea','GN'),
(86,999,'Guinea-Bissau','GW'),
(87,999,'Guyana','GY'),
(88,999,'Haiti','HT'),
(89,999,'Heard and Mc Donald Islands','HM'),
(90,999,'Honduras','HN'),
(91,999,'Hong Kong','HK'),
(92,999,'Hungary','HU'),
(93,999,'Iceland','IS'),
(94,999,'India','IN'),
(95,999,'Indonesia','ID'),
(96,999,'Iran (Islamic Republic of)','IR'),
(97,999,'Iraq','IQ'),
(98,999,'Ireland','IE'),
(99,999,'Israel','IL'),
(100,999,'Italy','IT'),
(101,999,'Jamaica','JM'),
(102,999,'Japan','JP'),
(103,999,'Jordan','JO'),
(104,999,'Kazakhstan','KZ'),
(105,999,'Kenya','KE'),
(106,999,'Kiribati','KI'),
(107,999,'Korea, Democratic Peoples Republic of','KP'),
(108,999,'Korea, Republic of','KR'),
(109,999,'Kuwait','KW'),
(110,999,'Kyrgyzstan','KG'),
(111,999,'Lao Peoples Democratic Republic','LA'),
(112,999,'Latvia','LV'),
(113,999,'Lebanon','LB'),
(114,999,'Lesotho','LS'),
(115,999,'Liberia','LR'),
(116,999,'Libyan Arab Jamahiriya','LY'),
(117,999,'Liechtenstein','LI'),
(118,999,'Lithuania','LT'),
(119,999,'Luxembourg','LU'),
(120,999,'Macau','MO'),
(121,999,'Macedonia, The Former Yugoslav Republic of','MK'),
(122,999,'Madagascar','MG'),
(123,999,'Malawi','MW'),
(124,999,'Malaysia','MY'),
(125,999,'Maldives','MV'),
(126,999,'Mali','ML'),
(127,999,'Malta','MT'),
(128,999,'Marshall Islands','MH'),
(129,999,'Mauritania','MR'),
(130,999,'Mauritius','MU'),
(131,999,'Mexico','MX'),
(132,999,'Micronesia, Federated States of','FM'),
(133,999,'Moldova, Republic of','MD'),
(134,999,'Monaco','MC'),
(135,999,'Mongolia','MN'),
(136,999,'Montserrat','MS'),
(137,999,'Morocco','MA'),
(138,999,'Mozambique','MZ'),
(139,999,'Myanmar','MM'),
(140,999,'Namibia','NA'),
(141,999,'Nauru','NR'),
(142,999,'Nepal','NP'),
(143,999,'Netherlands','NL'),
(144,999,'Netherlands Antilles','AN'),
(145,999,'New Zealand','NZ'),
(146,999,'Nicaragua','NI'),
(147,999,'Niger','NE'),
(148,999,'Nigeria','NG'),
(149,999,'Niue','NU'),
(150,999,'Norfolk Island','NF'),
(151,999,'Northern Mariana Islands','MP'),
(152,999,'Norway','NO'),
(153,999,'Oman','OM'),
(154,999,'Pakistan','PK'),
(155,999,'Palau','PW'),
(156,999,'Panama','PA'),
(157,999,'Papua New Guinea','PG'),
(158,999,'Paraguay','PY'),
(159,999,'Peru','PE'),
(160,999,'Philippines','PH'),
(161,999,'Pitcairn','PN'),
(162,999,'Poland','PL'),
(163,999,'Portugal','PT'),
(164,999,'Puerto Rico','PR'),
(165,999,'Qatar','QA'),
(166,999,'Romania','RO'),
(167,999,'Russian Federation','RU'),
(168,999,'Rwanda','RW'),
(169,999,'Saint Kitts and Nevis','KN'),
(170,999,'Saint Lucia','LC'),
(171,999,'Saint Vincent and the Grenadines','VC'),
(172,999,'Samoa','WS'),
(173,999,'San Marino','SM'),
(174,999,'Sao Tome and Principe','ST'),
(175,999,'Saudi Arabia','SA'),
(176,999,'Senegal','SN'),
(177,999,'Seychelles','SC'),
(178,999,'Sierra Leone','SL'),
(179,999,'Singapore','SG'),
(180,999,'Slovakia (Slovak Republic)','SK'),
(181,999,'Slovenia','SI'),
(182,999,'Solomon Islands','Sb'),
(183,999,'Somalia','SO'),
(184,999,'South Africa','ZA'),
(185,999,'South Georgia and the South Sandwich Islands','GS'),
(186,999,'Spain','ES'),
(187,999,'Sri Lanka','LK'),
(188,999,'St. Helena','SH'),
(189,999,'Sudan','SD'),
(190,999,'Suriname','SR'),
(191,999,'Svalbard and Jan Mayen Islands','SJ'),
(192,999,'Swaziland','SZ'),
(193,999,'Sweden','SE'),
(194,999,'Switzerland','CH'),
(195,999,'Syrian ArabRepublic','SY'),
(196,999,'Taiwan','TW'),
(197,999,'Tajikistan','TJ'),
(198,999,'Tanzania, United Republic of','TZ'),
(199,999,'Thailand','TH'),
(200,999,'Togo','TG'),
(201,999,'Tokelau','TK'),
(202,999,'Tonga','TO'),
(203,999,'Trinidad and Tobago','TT'),
(204,999,'Tunisia','TN'),
(205,999,'Turkey','TR'),
(206,999,'Turkmenistan','TM'),
(207,999,'Turks and Caicos Islands','TC'),
(208,999,'Tuvalu','TV'),
(209,999,'Uganda','UG'),
(210,999,'Ukraine','UA'),
(211,999,'United Arab Emirates','AE'),
(212,999,'United Kingdom','UK'),
(213,1,'United States','US'),
(214,999,'United States Minor Outlying Islands','UM'),
(215,999,'Uruguay','UY'),
(216,999,'Uzbekistan','UZ'),
(217,999,'Vanuatu','VU'),
(218,999,'Vatican City State(Holy See)','VA'),
(219,999,'Venezuela','VE'),
(220,999,'Viet Nam','VN'),
(221,999,'Virgin Islands (British)','VG'),
(222,999,'Virgin Islands (U.S.)','VI'),
(223,999,'Western Sahara','EH'),
(224,999,'Yeman','YE'),
(225,999,'Yugoslavia','YU'),
(226,999,'Zaire','ZR'),
(227,999,'Zambia','ZM'),
(228,999,'Zimbabwe','ZW');



DROP TABLE IF EXISTS Locales;
CREATE TABLE Locales (
  ID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  chrKEY VARCHAR(40) NOT NULL,
  bDeleted TINYINT(1) NOT NULL DEFAULT 0,
  intOrder DOUBLE DEFAULT 999,
  idCountry SMALLINT(3) NOT NULL,
  chrLocale VARCHAR(80) NOT NULL,
  chrLocaleShort VARCHAR(5) NOT NULL
) ENGINE=MyISAM CHARSET=utf8;

INSERT INTO Locales VALUES 
(1,213,'Alabama','AL'),
(2,38,'Alberta','AB'),
(3,213,'Alaska','AK'),
(4,213,'Arizona','AZ'),
(5,213,'Arkansas','AR'),
(6,38,'British Columbia','BC'),
(7,213,'California','CA'),
(8,213,'Colorado','CO'),
(9,213,'Connecticut','CT'),
(10,213,'Delaware','DE'),
(11,213,'District Of Columbia','DC'),
(12,213,'Florida','FL'),
(13,213,'Georgia','GA'),
(14,213,'Hawaii','HI'),
(15,213,'Idaho','ID'),
(16,213,'Illinois','IL'),
(17,213,'Indiana','IN'),
(18,213,'Iowa','IA'),
(19,213,'Kansas','KS'),
(20,213,'Kentucky','KY'),
(21,213,'Louisiana','LA'),
(22,213,'Maine','ME'),
(23,38,'Manitoba','MB'),
(24,213,'Maryland','MD'),
(25,213,'Massachusetts','MA'),
(26,213,'Michigan','MI'),
(27,213,'Minnesota','MN'),
(28,213,'Mississippi','MS'),
(29,213,'Missouri','MO'),
(30,213,'Montana','MT'),
(31,38,'Nunavut','NU'),
(32,213,'Nebraska','NE'),
(33,213,'Nevada','NV'),
(34,38,'New Brunswick','NB'),
(35,38,'Newfoundland and Labrador','NL'),
(36,213,'New Hampshire','NH'),
(37,213,'New Jersey','NJ'),
(38,213,'New Mexico','NM'),
(39,213,'New York','NY'),
(40,213,'North Carolina','NC'),
(41,213,'North Dakota','ND'),
(42,38,'Northwest Territories','NT'),
(43,38,'Nova Scotia','NS'),
(44,213,'Ohio','OH'),
(45,213,'Oklahoma','OK'),
(46,38,'Ontario','ON'),
(47,213,'Oregon','OR'),
(48,213,'Pennsylvania','PA'),
(49,38,'Prince Edward Island','PE'),
(50,38,'Quebec','QC'),
(51,213,'Rhode Island','RI'),
(52,38,'Saskatchewan','SK'),
(53,213,'South Carolina','SC'),
(54,213,'South Dakota','SD'),
(55,213,'Tennessee','TN'),
(56,213,'Texas','TX'),
(57,213,'Utah','UT'),
(58,213,'Vermont','VT'),
(59,213,'Virginia','VA'),
(60,213,'Washington','WA'),
(61,213,'West Virginia','WV'),
(62,213,'Wisconsin','WI'),
(63,213,'Wyoming','WY'),
(64,38,'Yukon','YT');
