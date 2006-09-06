-------------------------------------------
write by hightman@hightman.net   2002.08.08
$Id: Readme.txt,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $
--------------------------------------------
http://cn-bbs.org/ 免费域名转信系统
--------------------------------------------

简单安装说明：

1. 修改 config.php 按内置说明改。

2. 导入数据文件: struct.sql 
   (已经包含所有 news server, news group需要再添加)

3. http://cn-bbs.org/index.php  测试...]

4. update_dns.c 
   刷新 named 用的，和 config.php 里的一些选项配合一起用。:)
   
   必要时修改 update_dns.c 里的

#define NAMED_RC        "/etc/rc.d/init.d/named"
#define COPY_PATH       "/bin/cp"

   编译： gcc -o update_dns update_dns.c

   注明：需要根据named的属主和用户或root.root, (uid,gid在config.php有设)
         将 update_dns 设为 +s 属性或 4755 ... 

   ............

5. 需要把 $config['fpath'] 这个文件属性设为 666


                                       hightman 草于 2002.8.8 23:54

change:
=1.joininn.php
=2.admin_func.php
=3.signup.php
=4.admin.php
=5.innconf.php
+6.join_passive.php
