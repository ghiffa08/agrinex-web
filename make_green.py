import os
import random
import subprocess
from datetime import datetime, timedelta

def run_git_commit(date_str, commit_msg):
    # Set the author and committer dates to the backdated date
    env = os.environ.copy()
    env["GIT_AUTHOR_DATE"] = date_str
    env["GIT_COMMITTER_DATE"] = date_str
    
    # Write to a file to create a modification
    with open("github_activity.txt", "a") as f:
        f.write(f"Telemetry contribution on {date_str}\n")
        
    # Stage the file
    subprocess.run(["git", "add", "github_activity.txt"], check=True, capture_output=True)
    
    # Commit with backdated env variables
    subprocess.run(["git", "commit", "-m", commit_msg], env=env, check=True, capture_output=True)

def generate_greenery():
    # Start date is January 1st of the current year (2026)
    start_date = datetime(2026, 1, 1)
    end_date = datetime.now()
    
    current_date = start_date
    commits_count = 0
    
    print(f"Starting GitHub greenery generator from {start_date.date()} to {end_date.date()}...")
    
    # Make sure we are in a git repository
    if not os.path.exists(".git"):
        print("Error: This directory is not a git repository. Please initialize git first.")
        return
        
    while current_date <= end_date:
        # Organic behavior: Lower chance of commits on weekends (30%) compared to weekdays (75%)
        is_weekend = current_date.weekday() >= 5
        activity_chance = 0.3 if is_weekend else 0.75
        
        if random.random() < activity_chance:
            # 1 to 5 random commits per active day
            num_commits = random.randint(1, 5)
            for _ in range(num_commits):
                # Generate random time of day (working hours 09:00 to 21:00)
                hour = random.randint(9, 21)
                minute = random.randint(0, 59)
                second = random.randint(0, 59)
                
                commit_time = current_date.replace(hour=hour, minute=minute, second=second)
                date_str = commit_time.isoformat()
                
                # Dynamic message to look natural
                messages = [
                    f"chore: optimize telemetry log sync at {commit_time.strftime('%H:%M:%S')}",
                    f"refactor: clean up MQTT telemetry buffer layout",
                    f"fix: resolve connection timeout recovery in ESP32 driver",
                    f"docs: update API endpoints definitions schema",
                    f"perf: enhance data payload serialization efficiency"
                ]
                commit_msg = random.choice(messages)
                
                run_git_commit(date_str, commit_msg)
                commits_count += 1
                
        current_date += timedelta(days=1)
        
    print(f"\nSuccessfully generated {commits_count} backdated commits in git history!")
    print("Action Required: Please run 'git push origin main' (or your branch name) to push the commits to GitHub and update your contribution graph.")

if __name__ == "__main__":
    generate_greenery()
