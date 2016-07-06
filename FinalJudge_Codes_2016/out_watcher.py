import MySQLdb
import sys
import subprocess
import shlex
import os
import time

while(1):

    sys.stdout.flush()

    for file in os.listdir("/home/external/iitk/ankmahato"):
        if file.startswith("out_"):
            print("Job " + file + " has finished. Checking.")
            filepath = "/home/external/iitk/ankmahato/" + file
            f = open(filepath, 'r')
            problem = f.readline().strip()
            id = f.readline().strip()
            user = f.readline().strip()
            f.close()
            print("Prob: %s\nid:%s\nuser: %s" % (problem, id, user))
            jobid = file.split('_')[1].split('.')[0]

            args = ["php", "check.php", jobid, problem, id, user]
            print("Checking now with these args")
            print (args)
            subprocess.Popen(args)

            os.rename(filepath, "/home/external/iitk/ankmahato/outs/" + file)

    time.sleep(20)
