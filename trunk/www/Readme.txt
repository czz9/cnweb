-------------------------------------------
write by hightman@hightman.net   2002.08.08
$Id: Readme.txt,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $
--------------------------------------------
http://cn-bbs.org/ �������ת��ϵͳ
--------------------------------------------

�򵥰�װ˵����

1. �޸� config.php ������˵���ġ�

2. ���������ļ�: struct.sql 
   (�Ѿ��������� news server, news group��Ҫ�����)

3. http://cn-bbs.org/index.php  ����...]

4. update_dns.c 
   ˢ�� named �õģ��� config.php ���һЩѡ�����һ���á�:)
   
   ��Ҫʱ�޸� update_dns.c ���

#define NAMED_RC        "/etc/rc.d/init.d/named"
#define COPY_PATH       "/bin/cp"

   ���룺 gcc -o update_dns update_dns.c

   ע������Ҫ����named���������û���root.root, (uid,gid��config.php����)
         �� update_dns ��Ϊ +s ���Ի� 4755 ... 

   ............

5. ��Ҫ�� $config['fpath'] ����ļ�������Ϊ 666


                                       hightman ���� 2002.8.8 23:54

change:
=1.joininn.php
=2.admin_func.php
=3.signup.php
=4.admin.php
=5.innconf.php
+6.join_passive.php
