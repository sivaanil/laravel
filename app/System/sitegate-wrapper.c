#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
#include <stdio.h>
#include <string.h>

/**
 * Run as root and pass a command line argument to a php file.
 **/
int main (int argc, char *argv[])
{
    // run as root
    setuid(0);

    /* WARNING: Only use an absolute path to the script to execute,
     *          a malicious user might fool the binary and execute
     *          arbitary commands if not.
     **/

// limit length of argument to prevent buffer overflow below
// the longest command is an IPv6 addroute
    if (argc != 2 || strlen(argv[1]) < 5 || strlen(argv[1]) > 110) {
        puts("Bad command\n");
        return 1;
    }

// the argument must be alphanumeric or -.:/ only.
// this prevents command injection by using ; or &&
// we use dash as a delimiter
    int i = 0;
    int ok = 1;
    char c;

    while (argv[1][i])
    {
        c = argv[1][i];
        
        if (!isalnum(c) && c != '-' && c != '.' && c != ':' && c != '/') {
            ok = 0;
            break;
        }

        i++;
    }

    if (!ok) {
        puts("Bad command\n");
        return 1;
    }

    char cmd[200];

    strcpy(cmd, "/usr/bin/php /usr/local/bin/sitegate/sitegate-wrapper.php '");
    strcat(cmd, argv[1]);
    strcat(cmd, "'");

    system(cmd);

    return 0;
}
