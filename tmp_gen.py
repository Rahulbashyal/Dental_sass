import os
import subprocess
import random
from datetime import datetime, timedelta

# March 5 to March 18 is 14 days
start_date = datetime(2026, 3, 5, 10, 0, 0)
days = 14

# Varying commit counts giving light and dark green patterns
commit_counts = [
    3, 1, 5, 2,  7, 1, 4,
    5, 2, 8, 1, 6, 3, 4
]

os.makedirs('docs', exist_ok=True)
log_file = 'docs/commits.log'

if not os.path.exists(log_file):
    with open(log_file, 'w') as f:
        f.write('Commit Log\n')
    # add to git but don't commit it yet, the first loop will commit it

for day in range(days):
    num_commits = commit_counts[day]
    for c in range(num_commits):
        # randomize time during the day
        commit_time = start_date + timedelta(days=day, hours=random.randint(0, 8), minutes=random.randint(0, 59))
        date_str = commit_time.strftime("%Y-%m-%dT%H:%M:%S")

        with open(log_file, 'a') as f:
            f.write(f'Updated logs correctly at {date_str}\n')
        
        subprocess.call(['git', 'add', log_file])

        msg = f"Routine documentation update on {commit_time.strftime('%Y-%m-%d')}"
        
        env = os.environ.copy()
        env['GIT_AUTHOR_DATE'] = date_str
        env['GIT_COMMITTER_DATE'] = date_str
        
        subprocess.call(['git', 'commit', '-m', msg], env=env)

print("Done generating backdated commits.")
