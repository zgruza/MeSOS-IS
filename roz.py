# coding: UTF-8
import os
import urllib.request
import urllib.parse
import re
import json
from bs4 import BeautifulSoup
from datetime import datetime
import classes_config
import subjects_config
import teachers_config
import bakalari_actual_week_config
import bakalari_next_week_config
JSON = "{"
class_counter = 0
dt = datetime.now()
day = dt.strftime('%A')
#day = "Friday"
#day = "Thursday"

# Convert day name right now
weekend = False
if day == "Monday":
    day = "po"
elif day == "Tuesday":
    day = "út"
elif day == "Wednesday":
    day = "st"
elif day == "Thursday":
    day = "čt"
elif day == "Friday":
    day = "pá"
elif day == "Saturday" or day == "Sunday":
    day = "po"
    weekend = True
# Loaded from Generated Config
classes = classes_config.classes
subject_array = subjects_config.subject_array
#subject_array["Ovocnářství^ zelinářství a vinařství"] = "Ovocnářství, zelinářství a vinařství"
teachers = teachers_config.teachers

classes_url = bakalari_actual_week_config.classes_url
classes_url_nw = bakalari_next_week_config.classes_url_nw
first_class_json = True
if weekend:
    classes_url = classes_url_nw
for i in classes_url:
    class_counter += 1
    if first_class_json:
        JSON += '"' + classes[class_counter] + '": ['
    else:
        JSON += ',"' + classes[class_counter] + '": ['
    fp = urllib.request.urlopen(i)
    mybytes = fp.read()
    html_doc = mybytes.decode("utf8") # Load HTML text
    fp.close()
    # html_doc is a string containing the HTML document
    soup = BeautifulSoup(html_doc, 'html.parser')
    
    # Find all elements with the class 'day-item-hover'
    #elements = soup.find_all(class_='d-flex flex-row bk-timetable-row') # Get 
    day_items = soup.find_all(class_='day-item-hover') # Get 
    # Print the text content of each element
    first_subject_json = True
    LAST_INSERTED_HOUR = 0 # This value is for empty cells purpose (Lunch break, etc...) [EMPTY CELLS]
    for items in day_items:
        if  " " + day + " " in json.loads(items.get("data-detail"))["subjecttext"].split("|")[1].rstrip() and "Vyjmuto" not in items.get("data-detail"): # Bug Fix 16/01/2023: Pokud v Topicu existuje "po" (př. Pěstování rostlin až po Fenologii), tak si Bot myslí, že jde o den.
            y = json.loads(items.get("data-detail"))
            if y["subjecttext"].split("|")[0].rstrip() in subject_array:
                subject_text = subject_array[y["subjecttext"].split("|")[0].rstrip()]
            else:
                t_req = urllib.request.urlopen("http://127.0.0.1/admin/API/subject_error.php?subject="+urllib.parse.quote(y["subjecttext"].split("|")[0].rstrip()))
                subject_text = y["subjecttext"].split("|")[0].rstrip()
            if y["teacher"].split(",")[0].rstrip() in teachers:
                teacher = teachers[y["teacher"].split(",")[0]]
            else:
                print("http://127.0.0.1/admin/API/teacher_error.php?teacher="+urllib.parse.quote(y["teacher"].split(",")[0]))
                t_req = urllib.request.urlopen("http://127.0.0.1/admin/API/teacher_error.php?teacher="+urllib.parse.quote(y["teacher"].split(",")[0]))
                teacher = y["teacher"].split(",")[0]
            hour = (y["subjecttext"].split("|")[2])
            hour = hour.split(" ")[1]
            if y["changeinfo"] == "":
                changed = "False"
            else:
                changed = "True"
            if first_subject_json:
                JSON += '{"hour": "'+hour+'", "subject": "'+subject_text+'", "teacher": "'+teacher+'", "room": "'+y["room"]+'", "changed": "'+changed+'"}'
                LAST_INSERTED_HOUR = int(hour)
                first_subject_json = False
            else:
                LAST_INSERTED_HOUR_if = LAST_INSERTED_HOUR+1 
                if int(LAST_INSERTED_HOUR_if) == int(hour) or int(LAST_INSERTED_HOUR) == int(hour):
                    LAST_INSERTED_HOUR = int(hour)
                    JSON += ',{"hour": "'+str(hour)+'", "subject": "'+subject_text+'", "teacher": "'+teacher+'", "room": "'+y["room"]+'", "changed": "'+changed+'"}'
                else:
                    LAST_INSERTED_HOUR = int(hour)
                    fake_u = int(hour)-1
                    JSON += ',{"hour": "'+str(fake_u)+'", "subject": "", "teacher": "", "room": "", "changed": ""}' # Empty hour
                    JSON += ',{"hour": "'+str(hour)+'", "subject": "'+subject_text+'", "teacher": "'+teacher+'", "room": "'+y["room"]+'", "changed": "'+changed+'"}'
        else:
            if "Vyjmuto" in items.get("data-detail"): # If Removed subject was found
                if  day + " " in items.get("data-detail"):
                    y = json.loads(items.get("data-detail"))
                    hour = (y["subjecttext"].split("|")[1].rstrip())
                    hour = hour.split(" ")[1]
                    if first_subject_json:
                        LAST_INSERTED_HOUR = int(hour)
                        first_subject_json = False
                        JSON += '{"hour": "'+str(hour)+'", "subject": "REMOVED", "teacher": "", "room": "", "changed": ""}'
                    else:
                        LAST_INSERTED_HOUR_if = LAST_INSERTED_HOUR+1 
                        if int(LAST_INSERTED_HOUR_if) == int(hour) or int(LAST_INSERTED_HOUR) == int(hour):
                            LAST_INSERTED_HOUR = int(hour)
                            JSON += ',{"hour": "'+str(hour)+'", "subject": "REMOVED", "teacher": "", "room": "", "changed": ""}'
                        else:
                            LAST_INSERTED_HOUR = int(hour)
                            fake_u = int(hour)-1
                            JSON += ',{"hour": "'+str(fake_u)+'", "subject": "", "teacher": "", "room": "", "changed": ""}' # Empty hour
                            JSON += ',{"hour": "'+str(hour)+'", "subject": "REMOVED", "teacher": "", "room": "", "changed": ""}'

                
    JSON += "],"
JSON += "}"
JSON = JSON.replace("],}", "]}")
#print(JSON)
if os.path.exists('rozvrh.json'):
    os.remove("rozvrh.json") 
with open("rozvrh.json", "w") as f:
    f.write(JSON)
