import MySQLdb
import sys
import subprocess
import shlex
import os
import time

#############
# IMPORTANT #
#############
# This file has been deprecated in favour of
# out_watcher.py. Keep it running.

if len(sys.argv) != 5:
    print """
# This file has been deprecated in favour of
# out_watcher.py. Keep it running.
    jobid = sys.argv[1]
    problem = sys.argv[2]
    id = sys.argv[3]
    user = sys.argv[4]
    """
    exit()

jobid = sys.argv[1]
problem = sys.argv[2]
id = sys.argv[3]
user = sys.argv[4]

while(not os.path.exists("/home/external/iitk/ankmahato/out_" + jobid + ".yc9.en.yuva.param")):
    print("Output file did not exist for " + jobid + ". Checking again. Cur: " + time.ctime())
    time.sleep(30)

print("Job has finished! :)")
print("Evaluating now")

args = ["php", "check.php", jobid, problem, id, user]
print("Checking now with these args")
print (args)
subprocess.Popen(args)
