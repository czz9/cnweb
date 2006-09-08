////////////////////////////////////////////////////////////
// update_dns.c
// author: hightman@hightman.net
// target: auto refresh the named config data...
// usage : ./prog <fromfile> <tofile> [uid] [gid] [restart];
// notice: please use absolute path for file.
// $Id: update_dns.c,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $
////////////////////////////////////////////////////////////

#include <sys/types.h>
#include <unistd.h>
#include <stdio.h>

#define NAMED_RC        "/etc/rc.d/init.d/named"
#define COPY_PATH       "/bin/cp"

int
main (argc, argv)
     int argc;
     char *argv[];
{
  char *ptr;
  uid_t uid;
  gid_t gid;
  int restart, err;
  pid_t pid;


  uid = gid = restart = 0;

  if (argc < 3)
    {
      printf ("Usage: %s <fromfile> <distfile> [uid] [gid] [restart]\n",
	      argv[0]);
      return -1;
    }

  ptr = argv[1];
  if (*ptr != '/')
    {
      printf ("Error: Invalid path of fromfile!!\n");
      return -1;
    }
  ptr = argv[2];
  if (*ptr != '/')
    {
      printf ("Error: Invalid path of distfile!!\n");
      return -1;
    }

  if (argc > 3)
    uid = atol (argv[3]);
  if (argc > 4)
    gid = atol (argv[4]);
  if (argc > 5)
    restart = atoi (argv[5]);

  err = setuid ((uid_t) uid);
  if (err == -1)
    {
      printf ("Error: Failed to set uid to %d\n", uid);
      return -1;
    }
  err = setgid ((gid_t) gid);
  if (err == -1)
    {
      printf ("Error: Failed to set gid to %d\n", gid);
      return -1;
    }

  if (pid = fork () && pid != 1)
    waitpid (pid, &err, 0);
  else
    execl (COPY_PATH, COPY_PATH, (char *) argv[1], (char *) argv[2], NULL);

  if (err == -1)
    {
      printf ("Error: Failed to copy file.\n");
      return -1;
    }

  if (restart == 1)
    {
      if (pid = fork () && pid != 1)
	waitpid (pid, &err, 0);
      else
	execl (NAMED_RC, NAMED_RC, "restart", NULL);
    }

  if (err == -1)
    printf ("Warning: Failed to restart the named.\n");

  printf ("[+OK] Done!\n");
  return 0;
}

/* End */
