#!/usr/bin/python
import praw
import time
import pymysql
import re
from string import Template
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from pprint import pprint
import time

####################################
#### Config - edit this section ####
####################################

# Who am I?
bot_name = "Reddit Keyword"

# What subreddit?
subreddit = "Funny"

# Email settings
email_address = "reddit-keyword@email.com"
email_password = "password"
smtp_host = "smtp.gmail.com"
smtp_port = 587

# Database settings
username = "username"
password = "password"
hostname = "localhost"
database = "database"

# Web interface
home_url = "http://reddit-keyword-bot.test/www/home.php"

## Be sure to edit praw.ini ##

####################
#### End config ####
####################

reddit = praw.Reddit('Reddit-Keyword')

sub = reddit.subreddit(subreddit)

last_title = ""

def update_phrases():
    db = pymysql.connect(user=username, password=password, host=hostname, database=database)
    query = db.cursor()

    query.execute("SELECT * FROM watching")
    result = query.fetchall()

    phrases = []

    for x in result:
        phrases.append(x[1])

    query.close()
    db.close()

    return phrases

def email_matches(phrase, post):
    email_fail = False
    emails = []
    db = pymysql.connect(user=username, password=password, host=hostname, database=database)
    query = db.cursor();
    try:
        query.execute("SELECT `id` FROM `watching` WHERE `lower_phrase` = %s", (phrase.lower(),))
        phrase_id = query.fetchone()
    except:
        print("Error looking for phrase ID")

    try:
        query.execute("SELECT * FROM `watchers` WHERE `watching` = %s", (phrase_id[0],))
        result = query.fetchall()
    except:
        print("Error looking for emails")
        email_fail = True
    if(email_fail == False):
        for email in result:
            emails.append({"email": email[1], "unsub": email[3]})


        s = smtplib.SMTP(host=smtp_host, port=smtp_port)
        s.starttls()
        s.login(email_address, email_password)
        message_template = read_template("message.txt")
        for email in emails:
            msg = MIMEMultipart()

            message = message_template.substitute(PHRASE=phrase,
                                                  URL=post.permalink,
                                                  CODE=email['unsub'],
                                                  SUBREDDIT=subreddit,
                                                  HOME=home_url)

            msg['From'] = "{bot_name} <{email}>".format(email=email_address,bot_name=bot_name)
            msg['To'] = email['email']
            msg['Subject'] = "'{phrase}' was posted in /r/{subreddit}!".format(phrase=phrase,subreddit=subreddit)

            msg.attach(MIMEText(message, 'plain'))

            s.send_message(msg)

            del msg

    query.close()
    db.close()

def read_template(filename):
    with open(filename, 'r', encoding='utf-8') as template_file:
        template_file_content = template_file.read()
    return Template(template_file_content)
last_updated = int(time.time())
phrases = update_phrases()
while(True):
    if (last_updated+1800) < int(time.time()):
        phrases = update_phrases()
        last_updated = time.time()

    for post in sub.new(limit=1):
        if(last_title != post.title):
            last_title = post.title
            print("New post: " + post.title)

            for phrase in phrases:
                regex = r"({phrase})".format(phrase=phrase).lower()
                match = re.search(regex, post.title.lower())

                if(match):
                    print("Match found: " + match.group())
                    email_matches(match.group(), post)

    time.sleep(2)