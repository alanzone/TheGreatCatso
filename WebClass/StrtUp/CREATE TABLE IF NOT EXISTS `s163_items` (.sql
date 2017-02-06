CREATE TABLE IF NOT EXISTS `s163_items` (
02
`id` int(10) unsigned NOT NULL auto_increment,
03
`title` varchar(255) default '',
04
`description` text NOT NULL,
05
`when` int(11) NOT NULL default '0',
06
`comments_count` int(11) NOT NULL,
07
PRIMARY KEY  (`id`)
08
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
09
INSERT INTO `s163_items` (`title`, `description`, `when`, `comments_count`) VALUES
10
('Item #1', 'Here are you can put description of Item #1', UNIX_TIMESTAMP(), '0'),
11
('Item #2', 'Here are you can put description of Item #2', UNIX_TIMESTAMP()+1, '0'),
12
('Item #3', 'Here are you can put description of Item #3', UNIX_TIMESTAMP()+2, '0'),
13
('Item #4', 'Here are you can put description of Item #4', UNIX_TIMESTAMP()+3, '0'),
14
('Item #5', 'Here are you can put description of Item #5', UNIX_TIMESTAMP()+4, '0');
15
CREATE TABLE IF NOT EXISTS `s163_items_cmts` (
16
`c_id` int(11) NOT NULL AUTO_INCREMENT ,
17
`c_item_id` int(12) NOT NULL default '0',
18
`c_ip` varchar(20) default NULL,
19
`c_name` varchar(64) default '',
20
`c_text` text NOT NULL ,
21
`c_when` int(11) NOT NULL default '0',
22
PRIMARY KEY (`c_id`),
23
KEY `c_item_id` (`c_item_id`)
24
) ENGINE=MYISAM DEFAULT CHARSET=utf8;
