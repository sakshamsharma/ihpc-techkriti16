import os
import sys
import subprocess
import shlex

if(len(sys.argv) != 8):
    print ("Wrong number of arguments.")
    print r"""
    program = sys.argv[1]   (The question name, CRICKET for example)
    user = sys.argv[2]      (User's tech id)
    library = sys.argv[3]   (MPI or anything else. MPI matches OpenMP for some weird reason)
    timestamp = sys.argv[4] (Time stamp of submission of file)
    id = sys.argv[5]        (Submission id from DB)
    timelimit = sys.argv[6]
    contact = sys.argv[7]
    Also, the binary to be run is assumed to be at:
    (binaries/{user_id}_{program/question_name}_{submission_id_from_db})
    """
    exit()


program = sys.argv[1]
user = sys.argv[2]
library = sys.argv[3]
timestamp = sys.argv[4]
id = sys.argv[5]
timelimit = sys.argv[6]
contact = sys.argv[7]

print("Program is %s" % program)
print("User is %s" % user)
print("Library is %s" % library)
print("Timestamp is %s" % timestamp)
print("Id is %s" % id)
print("I am "),
sys.stdout.flush()
subprocess.call("whoami")

compile_args = []

if library == "MPI":
    # Actually this is OpenMP. Why? Ask the previous managers, not me :P
    compile_command = "g++ /var/www/html/Judge/codefiles/%s/%s_%s.cc -fopenmp -o /home/external/iitk/ankmahato/Judge_2016/binaries/%s_%s_%s" % (program, user, timestamp, user, program, id)

else:
    compile_command = "/home/external/iitk/ankmahato/openmpi/bin/mpic++ /var/www/html/Judge/codefiles/%s/%s_%s.cc -o /home/external/iitk/ankmahato/Judge_2016/binaries/%s_%s_%s -lstdc++ -lm" % (program, user, timestamp, user, program, id)

# Use shlex to split into list to pass to Popen
compile_args = shlex.split(compile_command)

c_proc = subprocess.Popen(compile_args)
c_proc.wait()

if c_proc.returncode != 0:
    print ("There were compilation errors. Return code %d" % c_proc.returncode)
    print ("Exiting.")
    sys.exit(103)
else:
    print ("Compilation successful.")
#    print ("Calling job submitter")
#    print ("With time %s" % str(timelimit))
#    args = shlex.split("python /home/external/iitk/ankmahato/Judge_2016/job_submitter.py " + str(program) + " " + str(user) + " " + str(library) + " " + str(timelimit) + " " + str(id) + " " + str(contact))
#    p = subprocess.Popen(args)
#    p.wait()
    sys.exit(0)

