from selenium import webdriver
import argparse
import time
from selenium.webdriver.common.by import By
from selenium.webdriver.common.alert import Alert 
from selenium.webdriver.common.keys import Keys

parser = argparse.ArgumentParser(description='Port IP')
parser.add_argument('-p', dest='port', default=8001,
                    help='port where the application is running')

args = parser.parse_args()
port = args.port
browser = webdriver.Firefox()
browser.get(f"http://localhost:{port}/user/login?from/")

for i in range(15):
    browser.set_window_size(827, 707)
    browser.find_element(By.ID, "username").click()
    browser.find_element(By.ID,"username").clear()
    browser.find_element(By.ID, "username").send_keys("test")
    browser.find_element(By.ID, "password").send_keys(f"{i}")
    browser.find_element(By.ID, "password").send_keys(Keys.ENTER)
    time.sleep(0.5)
