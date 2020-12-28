from selenium import webdriver
import argparse
import time
from selenium.webdriver.common.by import By
parser = argparse.ArgumentParser(description='Port IP')
parser.add_argument('-p', dest='port', default=8001,
                    help='port where the application is running')

args = parser.parse_args()
port = args.port
browser = webdriver.Firefox()
browser.get(f"http://localhost:{port}/user/login?from=http://dasfernandes.com")
browser.set_window_size(827, 707)
time.sleep(1)
browser.find_element(By.ID, "username").click()
browser.find_element(By.ID, "username").send_keys("test")
browser.find_element(By.ID, "password").click()
browser.find_element(By.ID, "password").send_keys("test")
browser.find_element(By.ID, "Login").click()
time.sleep(1)
browser.switch_to.frame(0)
browser.find_element(By.CSS_SELECTOR, "button").click()

