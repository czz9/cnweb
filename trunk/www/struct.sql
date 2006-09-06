# ���ݽṹ, add by hightman 08.07
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
  bbsonline varchar(32) NOT NULL default 'Ŀǰ��վ����',
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

INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin', 'cn.bbs.*�����뷢չ', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.announce', 'cn.bbs.*��Ҫ���� (Moderated)', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.installbbs', 'BBS��װ����ά���뿪��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.lists', 'cn.bbs.*����ͳ���б����¼ (Moderated)', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.lists.weather', 'cn.bbs.*����Ԥ�� (Moderated)', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.admin.test', 'cn.bbs.*����', '0');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.hust', '���пƼ���ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.job', '��ְ���ҵ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.pku', '������ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.sjtu', '�Ϻ���ͨ��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.study-abroad', '������ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tju', '����ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tsinghua', '�廪��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.tsinghua.building1', '�廪��ѧ1#', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.ysu', '��ɽ��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.zju', '�㽭��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.campus.zsu', '��ɽ��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.book', '����ͼ������', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.database', '���ݿ�', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.embedded', 'Ƕ��ʽϵͳ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.graphics', 'ͼ��ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.hardware', '����Ӳ��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang', '�������', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang.delphi', 'Delphi����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.lang.java', 'Java����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.linux', 'Linux����ϵͳ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.ms-windows', '΢��Windows����ϵͳ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.multimedia', '��ý�弼��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.network', '���缼��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.security', 'ϵͳ��ȫ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.software', '�������', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development', 'Unix�������', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development.kernel', 'Unix�ں˱��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.comp.unix.development.network', 'Unix������', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.lang.chinese', '����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.lang.english', 'Ӣ��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.ascii-art', 'ASCII����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.novel.emprise', '����С˵', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.novel.sci-fiction', '�ƻ�С˵', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.literal.original', 'ԭ����ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.comic', '������ͨ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.drama', 'Ϸ��Ϸ��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.game', '������Ϸ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.game.quake', '����֮��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.movie', '��Ӱ����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.music', '���ָ���', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.photo', '��Ӱ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.riddle', '����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.rec.travel', '����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sci.astrology', '����ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sci.electronics', '����ѧ', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.soc.market', '�����г�', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.basketball', '����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.soccer', '����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.sports.table-tennis', 'ƹ����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.girl', 'Ů����', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.joke', 'Ц��', '1');
INSERT INTO `_news_grp` (`id`, `name`, `title`, `type`) VALUES ('', 'cn.bbs.talk.love', '̸��˵��', '1');

# end
