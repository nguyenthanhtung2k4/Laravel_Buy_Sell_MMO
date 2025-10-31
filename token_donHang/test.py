# client.py
import requests
import hashlib
import hmac
import json
import platform
import os
import sys
import time

# CONFIG
SERVER_API = 'http://localhost/model/check-key'  # ví dụ
# SERVER_API = 'http://dev.c25tool.net/model/check-key' 

LICENSE_KEY = 'CT25-SFUNV1761896730'
LICENSE_SECRET = '50b0f2a431c7fdd8bd98989fa3ce3da82d676eedd838c9f65c38b9b086bf202e'
VERIFY_SSL = True
TOKEN_FILE = 'token.txt'

def device_id():
    s = platform.node() + platform.system() + platform.machine()
    return hashlib.sha256(s.encode('utf-8')).hexdigest()

def build_payload(event, license_key):
    return {
        'token': license_key,
        'device_id': device_id(),
        'event': event,
        'app_version': '1.0.0',
        'os_info': platform.platform(),
        'device_name': platform.node(),
    }

def canonical_json(payload):
    # produce JSON with sorted keys and separators like Python's json.dumps(..., sort_keys=True, separators=(',',':'))
    return json.dumps(payload, sort_keys=True, separators=(',', ':'), ensure_ascii=False)

def create_signature(payload_json, secret):
    key = secret.encode('utf-8')
    data = payload_json.encode('utf-8')
    return hmac.new(key, data, hashlib.sha256).hexdigest()

def post_event(event, license_key):
    payload = build_payload(event, license_key)
    payload_json = canonical_json(payload)
    signature = create_signature(payload_json, LICENSE_SECRET)
    headers = {
        'Content-Type': 'application/json',
        'X-Signature': signature,
        'X-App-Version': payload.get('app_version', '1.0.0')
    }
    try:
        r = requests.post(SERVER_API, data=payload_json.encode('utf-8'), headers=headers, verify=VERIFY_SSL, timeout=10)
        return r
    except Exception as e:
        print("Network error:", e)
        return None

def save_license_key(k):
    with open(TOKEN_FILE, 'w') as f:
        f.write(k)

def load_license_key():
    if os.path.exists(TOKEN_FILE):
        with open(TOKEN_FILE, 'r') as f:
            return f.read().strip()
    return None


code= None;
def main():
    current_key = load_license_key()
    # nếu đã có license local, request get_link -> server sẽ trả về redirect mới
    if current_key:
        event = 'get_link'
    else:
        current_key = LICENSE_KEY
        event = 'register'

    resp = post_event(event, current_key)
    if resp is None:
        print("No response from server.")
        return

    try:
        j = resp.json()
    except Exception as e:
        print("Invalid JSON from server:", resp.text[:200])
        return

    print("Server response:", j)
    if j.get('status') == 'ok':
        if event == 'register':
            save_license_key(current_key)
        redirect = j.get('redirect_url')
        if redirect:
            print("Received one-time redirect:", redirect)
            try:
                r2 = requests.get(redirect, verify=VERIFY_SSL, timeout=10)
                if r2.status_code == 200:
                    code_text = r2.text
                    print("Fetched code (first 200 chars):")
                    print(code_text[:200].replace('\n','\\n'))
                    # --- EXECUTE ---
                    print("Executing received code (only do this if you trust the server!).")
                    try:
                        exec(code_text, {'__name__':'__main__'})
                        print("Execution finished.")
                    except Exception as e:
                        print("Error executing code:", e)
                else:
                    print("Redirect fetch failed:", r2.status_code, r2.text[:200])
            except Exception as e:
                print("Error fetching redirect:", e)
    else:
        print("Server returned error:", j.get('message'))



if __name__ == '__main__':
    main()
