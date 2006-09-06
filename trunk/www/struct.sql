# 数据结构, add by hightman 08.07
# MySQL DataBase.
# $Id: struct.sql,v 1.2 2003/12/06 11:51:30 czz Exp $

CREATE TABLE _my_dns (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(12) NOT NULL default '',
  pass varchar(40) NOT NULL default '',
  host varchar(32) NOT NULL default '',
  mx1 varchar(32) NOT NULL default '',
  mx2 varchar(32) NOT NULL default '',
  ns1 varchar(32) NOT NULL default '',
  ns2 varchar(32) NOT NULL default '',
  xmode int(11) NOT NULL default '0',
  bbsname varchar(32) NOT NULL default '',
  bbsport smallint(6) unsigned NOT NULL default '23',
  bbsdept varchar(32) NOT NULL default '',
  bbsonline varchar(32) NOT NULL default '目前上站人数',
  bbslogin varchar(8) NOT NULL default '',
  innhost varchar(32) NOT NULL default '',
  innport smallint(6) NOT NULL default '7777',
  innsrv smallint(6) NOT NULL default '0',
  bbsid varchar(16) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  authcode varchar(40) NOT NULL default '',
  authtime int(10) unsigned NOT NULL default '0',
  groups text NOT NULL,
  introduce text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY name (name),
  KEY name_2 (name)
) TYPE=MyISAM;


CREATE TABLE _keep_dns (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  target varchar(80) NOT NULL default '',
  type varchar(12) NOT NULL default 'A',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE _news_grp (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  title varchar(60) NOT NULL default '',
  type tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE _news_srv (
  id int(10) unsigned  NOT NULL auto_increment,
  name varchar(40) NOT NULL default '',
  host varchar(40) NOT NULL default '', # host[:port]
  url varchar(40) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;


INSERT INTO _news_srv VALUES ('', 'maily.cic.tsinghua.edu.cn', '166.111.4.19' ,'');
INSERT INTO _news_srv VALUES ('', 'news.happynet.org', '166.111.160.80', '');
INSERT INTO _news_srv VALUES ('', 'zixia.net', '202.112.80.1', '');
INSERT INTO _news_srv VALUES ('', 'news2.happynet.org', '166.111.160.7', '');
INSERT INTO _news_srv VALUES ('', 'news.feeling.smth.org', '166.111.160.221', '');
INSERT INTO _news_srv VALUES ('', 'jnunews.dhs.org', '211.66.114.93', '');
INSERT INTO _news_srv VALUES ('', 'news.neu.edu.cn', '202.118.1.82', '');
INSERT INTO _news_srv VALUES ('', 'news.tiaozhan.com', '202.117.0.201', '');
INSERT INTO _news_srv VALUES ('', 'cn.thebbs.org', '211.91.88.132', '');

INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin', 'cn.bbs.*管理与发展', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.announce', 'cn.bbs.*重要公告 (Moderated)', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.installbbs', 'BBS安装管理维护与开发', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.lists', 'cn.bbs.*各类统计列表与记录 (Moderated)', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.lists.weather', 'cn.bbs.*天气预报 (Moderated)', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.test', 'cn.bbs.*测试', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.hust', '华中科技大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.job', '兼职与就业', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.pku', '北京大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.sjtu', '上海交通大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.study-abroad', '出国留学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tju', '天津大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tsinghua', '清华大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tsinghua.building1', '清华大学1#', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.ysu', '燕山大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.zju', '浙江大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.zsu', '中山大学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.book', '电脑图书资料', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.database', '数据库', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.embedded', '嵌入式系统', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.graphics', '图形学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.hardware', '电脑硬件', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang', '程序设计', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang.delphi', 'Delphi语言', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang.java', 'Java语言', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.linux', 'Linux操作系统', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.ms-windows', '微软Windows操作系统', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.multimedia', '多媒体技术', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.network', '网络技术', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.security', '系统安全', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.software', '电脑软件', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development', 'Unix环境编程', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development.kernel', 'Unix内核编程', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development.network', 'Unix网络编程', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.lang.chinese', '汉语', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.lang.english', '英语', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.ascii-art', 'ASCII艺术', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.novel.emprise', '武侠小说', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.novel.sci-fiction', '科幻小说', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.original', '原创文学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.comic', '动漫卡通', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.drama', '戏剧戏曲', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.game', '电脑游戏', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.game.quake', '雷神之锤', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.movie', '电影电视', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.music', '音乐歌曲', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.photo', '摄影', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.riddle', '猜谜', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.travel', '旅游', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sci.astrology', '星相学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sci.electronics', '电子学', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.soc.market', '交易市场', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.basketball', '篮球', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.soccer', '足球', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.table-tennis', '乒乓球', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.girl', '女孩子', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.joke', '笑话', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.love', '谈情说爱', '1');

# end
