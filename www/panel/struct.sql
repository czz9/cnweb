-- 
-- 数据库: `cnbbs`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `_keep_dns`
-- 

CREATE TABLE `_keep_dns` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `target` varchar(80) NOT NULL default '',
  `type` varchar(12) NOT NULL default 'A',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

-- 
-- 表的结构 `_my_dns`
-- 

CREATE TABLE `_my_dns` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(12) NOT NULL default '',
  `pass` varchar(40) NOT NULL default '',
  `host` varchar(32) NOT NULL default '',
  `mx1` varchar(32) NOT NULL default '',
  `mx2` varchar(32) NOT NULL default '',
  `ns1` varchar(32) NOT NULL default '',
  `ns2` varchar(32) NOT NULL default '',
  `xmode` int(11) NOT NULL default '0',
  `bbsname` varchar(32) NOT NULL default '',
  `bbsport` smallint(6) unsigned NOT NULL default '23',
  `bbsdept` varchar(32) NOT NULL default '',
  `bbsonline` varchar(32) NOT NULL default '线上人数',
  `bbslogin` varchar(8) NOT NULL default '',
  `innhost` varchar(32) NOT NULL default '',
  `innport` smallint(6) NOT NULL default '7777',
  `innsrv` smallint(6) NOT NULL default '0',
  `bbsid` varchar(16) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `authcode` varchar(40) NOT NULL default '',
  `authtime` int(10) unsigned NOT NULL default '0',
  `groups` text NOT NULL,
  `introduce` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `authcode` (`authcode`,`name`,`xmode`),
  KEY `innsrv` (`innsrv`,`name`,`pass`),
  KEY `xmode` (`xmode`,`innsrv`,`name`,`pass`),
  KEY `pass` (`pass`,`name`,`email`),
  KEY `authtime` (`authtime`,`xmode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

-- 
-- 表的结构 `_news_grp`
-- 

CREATE TABLE `_news_grp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `type` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;

-- 
-- 导出表中的数据 `_news_grp`
-- 

INSERT INTO `_news_grp` VALUES (2, 'cn.bbs.admin', 'cn.bbs.*管理与发展', 0);
INSERT INTO `_news_grp` VALUES (3, 'cn.bbs.admin.announce', 'cn.bbs.*重要公告 (Moderated)', 0);
INSERT INTO `_news_grp` VALUES (4, 'cn.bbs.admin.installbbs', 'BBS安装管理维护与开发', 1);
INSERT INTO `_news_grp` VALUES (5, 'cn.bbs.admin.lists', 'cn.bbs.*各类统计列表与记录 (Moderated)', 0);
INSERT INTO `_news_grp` VALUES (6, 'cn.bbs.admin.lists.weather', 'cn.bbs.*天气预报 (Moderated)', 1);
INSERT INTO `_news_grp` VALUES (7, 'cn.bbs.admin.test', 'cn.bbs.*测试', 0);
INSERT INTO `_news_grp` VALUES (8, 'cn.bbs.campus.hust', '华中科技大学', 1);
INSERT INTO `_news_grp` VALUES (9, 'cn.bbs.campus.job', '兼职与就业', 1);
INSERT INTO `_news_grp` VALUES (10, 'cn.bbs.campus.pku', '北京大学', 1);
INSERT INTO `_news_grp` VALUES (11, 'cn.bbs.campus.sjtu', '上海交通大学', 1);
INSERT INTO `_news_grp` VALUES (12, 'cn.bbs.campus.study-abroad', '出国留学', 1);
INSERT INTO `_news_grp` VALUES (13, 'cn.bbs.campus.tju', '天津大学', 1);
INSERT INTO `_news_grp` VALUES (14, 'cn.bbs.campus.tsinghua', '清华大学', 1);
INSERT INTO `_news_grp` VALUES (15, 'cn.bbs.arts.calligraphy', '书画', 1);
INSERT INTO `_news_grp` VALUES (16, 'cn.bbs.campus.ysu', '燕山大学', 1);
INSERT INTO `_news_grp` VALUES (17, 'cn.bbs.campus.zju', '浙江大学', 1);
INSERT INTO `_news_grp` VALUES (18, 'cn.bbs.campus.zsu', '中山大学', 1);
INSERT INTO `_news_grp` VALUES (19, 'cn.bbs.comp.book', '电脑图书资料', 1);
INSERT INTO `_news_grp` VALUES (20, 'cn.bbs.comp.database', '数据库', 1);
INSERT INTO `_news_grp` VALUES (21, 'cn.bbs.comp.embedded', '嵌入式系统', 1);
INSERT INTO `_news_grp` VALUES (22, 'cn.bbs.comp.graphics', '图形学', 1);
INSERT INTO `_news_grp` VALUES (23, 'cn.bbs.comp.hardware', '电脑硬件', 1);
INSERT INTO `_news_grp` VALUES (24, 'cn.bbs.comp.lang', '程序设计', 1);
INSERT INTO `_news_grp` VALUES (25, 'cn.bbs.comp.lang.delphi', 'Delphi语言', 1);
INSERT INTO `_news_grp` VALUES (26, 'cn.bbs.comp.lang.java', 'Java语言', 1);
INSERT INTO `_news_grp` VALUES (27, 'cn.bbs.comp.linux', 'Linux操作系统', 1);
INSERT INTO `_news_grp` VALUES (28, 'cn.bbs.comp.ms-windows', '微软Windows操作系统', 1);
INSERT INTO `_news_grp` VALUES (29, 'cn.bbs.comp.multimedia', '多媒体技术', 1);
INSERT INTO `_news_grp` VALUES (30, 'cn.bbs.comp.network', '网络技术', 1);
INSERT INTO `_news_grp` VALUES (31, 'cn.bbs.comp.virus', '计算机病毒', 1);
INSERT INTO `_news_grp` VALUES (32, 'cn.bbs.comp.software', '电脑软件', 1);
INSERT INTO `_news_grp` VALUES (33, 'cn.bbs.comp.unix.development', 'Unix环境编程', 1);
INSERT INTO `_news_grp` VALUES (34, 'cn.bbs.comp.unix.development.kernel', 'Unix内核编程', 1);
INSERT INTO `_news_grp` VALUES (35, 'cn.bbs.comp.network.programming', '网络编程', 1);
INSERT INTO `_news_grp` VALUES (36, 'cn.bbs.lang.chinese', '汉语', 1);
INSERT INTO `_news_grp` VALUES (37, 'cn.bbs.lang.english', '英语', 1);
INSERT INTO `_news_grp` VALUES (38, 'cn.bbs.arts.ascii', 'ASCII艺术', 1);
INSERT INTO `_news_grp` VALUES (39, 'cn.bbs.lit.novel.emprise', '武侠小说', 1);
INSERT INTO `_news_grp` VALUES (40, 'cn.bbs.culture.sci-fi', '科幻', 1);
INSERT INTO `_news_grp` VALUES (41, 'cn.bbs.lit', '文学', 1);
INSERT INTO `_news_grp` VALUES (42, 'cn.bbs.culture.animation', '动漫卡通', 1);
INSERT INTO `_news_grp` VALUES (43, 'cn.bbs.arts.drama', '戏剧', 1);
INSERT INTO `_news_grp` VALUES (44, 'cn.bbs.game', '游戏', 1);
INSERT INTO `_news_grp` VALUES (45, 'cn.bbs.game.quake', '雷神之锤', 1);
INSERT INTO `_news_grp` VALUES (46, 'cn.bbs.arts.movie', '电影', 1);
INSERT INTO `_news_grp` VALUES (47, 'cn.bbs.music', '音乐', 1);
INSERT INTO `_news_grp` VALUES (48, 'cn.bbs.arts.photo', '摄影', 1);
INSERT INTO `_news_grp` VALUES (49, 'cn.bbs.rec.riddle', '猜谜', 1);
INSERT INTO `_news_grp` VALUES (50, 'cn.bbs.rec.travel', '旅游', 1);
INSERT INTO `_news_grp` VALUES (51, 'cn.bbs.culture.astrology', '星座', 1);
INSERT INTO `_news_grp` VALUES (52, 'cn.bbs.sci.electronics', '电子学', 1);
INSERT INTO `_news_grp` VALUES (53, 'cn.bbs.soc.market', '交易市场', 1);
INSERT INTO `_news_grp` VALUES (54, 'cn.bbs.sport.basketball', '篮球', 1);
INSERT INTO `_news_grp` VALUES (55, 'cn.bbs.sport.football', '足球', 1);
INSERT INTO `_news_grp` VALUES (56, 'cn.bbs.sport.table-tennis', '乒乓球', 1);
INSERT INTO `_news_grp` VALUES (57, 'cn.bbs.emotion.girl', '女孩子', 1);
INSERT INTO `_news_grp` VALUES (58, 'cn.bbs.rec.joke', '笑话', 1);
INSERT INTO `_news_grp` VALUES (59, 'cn.bbs.emotion.love', '谈情说爱', 1);
INSERT INTO `_news_grp` VALUES (60, 'cn.bbs.comp.lang.perl', 'Perl语言', 1);
INSERT INTO `_news_grp` VALUES (61, 'cn.bbs.comp.unix.bsd', 'BSD操作系统', 1);
INSERT INTO `_news_grp` VALUES (62, 'cn.bbs.comp.lang.visual-basic', 'Visual Basic语言', 1);
INSERT INTO `_news_grp` VALUES (63, 'cn.bbs.comp.lang.python', 'Python语言', 1);
INSERT INTO `_news_grp` VALUES (64, 'cn.bbs.sci.nano', '纳米科技', 1);
INSERT INTO `_news_grp` VALUES (65, 'cn.bbs.campus.csu', '中南大学', 1);
INSERT INTO `_news_grp` VALUES (66, 'cn.bbs.campus.neu', '东北大学', 1);
INSERT INTO `_news_grp` VALUES (67, 'cn.bbs.campus.cqupt', '重庆邮电学院', 1);
INSERT INTO `_news_grp` VALUES (68, 'cn.bbs.comp.xml', '可扩展置标语言', 1);
INSERT INTO `_news_grp` VALUES (69, 'cn.bbs.comp.unix', 'Unix操作系统', 1);
INSERT INTO `_news_grp` VALUES (70, 'cn.bbs.culture.fantasy', '奇幻', 1);
INSERT INTO `_news_grp` VALUES (71, 'cnbbs.admin.manager', 'cn.bbs.*版(组)务交流', 0);
INSERT INTO `_news_grp` VALUES (72, 'cn.bbs.campus.tsinghua.news', '清华大学新闻 (Moderated)', 1);
INSERT INTO `_news_grp` VALUES (73, 'cn.bbs.culture.mythology', '神话传说', 1);
INSERT INTO `_news_grp` VALUES (74, 'cn.bbs.lit.novel.stone-story', '红楼梦', 1);
INSERT INTO `_news_grp` VALUES (75, 'cn.bbs.lit.novel.sanguo', '三国演义', 1);
INSERT INTO `_news_grp` VALUES (76, 'cn.bbs.lang.deutsch', '德语', 1);
INSERT INTO `_news_grp` VALUES (77, 'cn.bbs.sci.medicine', '医学', 1);
INSERT INTO `_news_grp` VALUES (78, 'cn.bbs.comp.security', '系统安全', 1);
INSERT INTO `_news_grp` VALUES (79, 'cn.bbs.sci.management', '管理学', 1);
INSERT INTO `_news_grp` VALUES (80, 'cn.bbs.game.diablo', '暗黑破坏神', 1);
INSERT INTO `_news_grp` VALUES (81, 'cn.bbs.campus.nankai', '南开大学', 1);
INSERT INTO `_news_grp` VALUES (83, 'cn.bbs.campus.job.east', '兼职与就业(华东地区)', 1);
INSERT INTO `_news_grp` VALUES (84, 'cn.bbs.campus.lzu', '兰州大学', 1);
INSERT INTO `_news_grp` VALUES (85, 'cn.bbs.comp.web', '万维网技术', 1);
INSERT INTO `_news_grp` VALUES (86, 'cn.bbs.comp.network.research', '网络研究', 1);
INSERT INTO `_news_grp` VALUES (87, 'cn.bbs.sport.cycling', '自行车', 1);
INSERT INTO `_news_grp` VALUES (88, 'cn.bbs.sport.weiqi', '围棋', 1);
INSERT INTO `_news_grp` VALUES (89, 'cn.bbs.lang.francais', '法语', 1);
INSERT INTO `_news_grp` VALUES (90, 'cn.bbs.lang.japanese', '日语', 1);
INSERT INTO `_news_grp` VALUES (91, 'cn.bbs.campus.job.north', '兼职与就业(华北地区)', 1);
INSERT INTO `_news_grp` VALUES (92, 'cn.bbs.soc.taiwan', '台湾', 1);
INSERT INTO `_news_grp` VALUES (93, 'cn.bbs.campus.job.south', '兼职与就业(华南地区)', 1);
INSERT INTO `_news_grp` VALUES (94, 'cn.bbs.sci.chemistry', '化学', 1);
INSERT INTO `_news_grp` VALUES (95, 'cn.bbs.lang.foreign', '外语', 1);
INSERT INTO `_news_grp` VALUES (96, 'cn.bbs.campus.job.southeast', '兼职与就业(东南地区)', 1);
INSERT INTO `_news_grp` VALUES (97, 'cn.bbs.comp.flash', 'Flash技术', 1);
INSERT INTO `_news_grp` VALUES (98, 'cn.bbs.lang', '语言', 1);
INSERT INTO `_news_grp` VALUES (99, 'cn.bbs.campus.job.northeast', '兼职与就业(东北地区)', 1);
INSERT INTO `_news_grp` VALUES (100, 'cn.bbs.music.classical', '古典音乐', 1);
INSERT INTO `_news_grp` VALUES (101, 'cn.bbs.sci.dsp', '数字信号处理', 1);
INSERT INTO `_news_grp` VALUES (102, 'cn.bbs.rec.mobile', '移动通信', 1);
INSERT INTO `_news_grp` VALUES (103, 'cn.bbs.culture.mystery', '推理', 1);
INSERT INTO `_news_grp` VALUES (104, 'cn.bbs.soc.house', '房屋租赁', 1);
INSERT INTO `_news_grp` VALUES (105, 'cn.bbs.comp.dotnet', '.Net技术', 1);
INSERT INTO `_news_grp` VALUES (106, 'cn.bbs.sci.astronomy', '天文学', 1);
INSERT INTO `_news_grp` VALUES (107, 'cn.bbs.comp.software-eng', '软件工程', 1);
INSERT INTO `_news_grp` VALUES (108, 'cn.bbs.lit.couplet', '对联', 1);
INSERT INTO `_news_grp` VALUES (109, 'cn.bbs.admin.lists.tv', '电视节目预告 (Moderated)', 1);
INSERT INTO `_news_grp` VALUES (110, 'cn.bbs.sci.military', '军事', 1);
INSERT INTO `_news_grp` VALUES (111, 'cn.bbs.soc.market.book', '交易市场(图书)', 1);
INSERT INTO `_news_grp` VALUES (112, 'cn.bbs.sci.agriculture', '农业', 1);
INSERT INTO `_news_grp` VALUES (113, 'cn.bbs.campus.job.part-time', '兼职', 1);
INSERT INTO `_news_grp` VALUES (114, 'cn.bbs.comp.hacker', '加密与解密', 1);
INSERT INTO `_news_grp` VALUES (115, 'cn.bbs.sci.chemistry.quantum', '量子化学', 1);
INSERT INTO `_news_grp` VALUES (116, 'cn.bbs.campus.swufe', '西南财经大学', 1);
INSERT INTO `_news_grp` VALUES (117, 'cn.bbs.sci.math', '数学', 1);
INSERT INTO `_news_grp` VALUES (118, 'cn.bbs.game.music', '音乐游戏', 1);
INSERT INTO `_news_grp` VALUES (119, 'cn.bbs.soc.traffic', '交通', 1);
INSERT INTO `_news_grp` VALUES (120, 'cn.bbs.sci.mechanics', '力学', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `_news_srv`
-- 

CREATE TABLE `_news_srv` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `host` varchar(40) NOT NULL default '',
  `url` varchar(40) NOT NULL default '',
  `comment` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- 
-- 导出表中的数据 `_news_srv`
-- 

INSERT INTO `_news_srv` VALUES (1, 'news.newsmth.org', '61.182.213.237', 'http://news.newsmth.org/', 'NEWSMTH专用');
INSERT INTO `_news_srv` VALUES (2, 'news.happynet.org', 'th116080.ip.tsinghua.edu.cn', 'http://news.happynet.org/', '北京');
INSERT INTO `_news_srv` VALUES (3, 'news.zixia.net', 'news.zixia.net', 'http://news.zixia.net/', '测试成员专用');
INSERT INTO `_news_srv` VALUES (4, 'bbsnews.sdu.edu.cn', '202.194.15.150', 'http://bbsnews.sdu.edu.cn/', '江苏、安徽、山东、上海、浙江、福建、江西');
INSERT INTO `_news_srv` VALUES (5, 'news.cn-bbs.org', 'th117221.ip.tsinghua.edu.cn', 'http://news.cn-bbs.org/', 'Feeling专用');
INSERT INTO `_news_srv` VALUES (7, 'news.neu.edu.cn', 'news.neu.edu.cn', 'http://news.neu.edu.cn/', '辽宁、吉林、黑龙江');
INSERT INTO `_news_srv` VALUES (8, 'news.xjtu.edu.cn', 'irc.xjtu.edu.cn', 'http://news.xjtu.edu.cn/', '陕西、甘肃、新疆、宁夏、青海');
INSERT INTO `_news_srv` VALUES (9, 'news.uestc.edu.cn', '202.112.14.175', 'http://news.uestc.edu.cn/~news/', '四川、重庆、云南、贵州、西藏');
INSERT INTO `_news_srv` VALUES (10, 'news.bylinux.net', '202.64.181.18', 'http://news.bylinux.net/', '大陆之外');
INSERT INTO `_news_srv` VALUES (12, 'news.szu.edu.cn', 'BBS.szu.edu.cn', 'http://210.39.3.50/~news/', '广东、广西、海南');
INSERT INTO `_news_srv` VALUES (13, 'whnet.dhs.org', '202.114.14.228', 'http://whnet.3322.org/', '湖北、湖南、河南');
INSERT INTO `_news_srv` VALUES (14, 'news2.happynet.org', 'th116088.ip.tsinghua.edu.cn', 'http://news2.happynet.org/', '天津、河北、山西、内蒙古');