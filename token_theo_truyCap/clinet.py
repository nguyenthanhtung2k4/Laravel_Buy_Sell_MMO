# File: client.py
# Client Python để gửi yêu cầu truy cập, nhận link dữ liệu và tải dữ liệu về.
import requests
import json
import time

# --- THÔNG TIN TRUY CẬP (CẦN THAY ĐỔI) ---
# Đảm bảo Token này đã được tạo trong trang management.php
MY_TOKEN = "e1e51ffa-04f1-4bcc-897c-e5e480426102" 
# Đảm bảo Signature này đã được tạo trong trang management.php
MY_SIGNATURE = "B7qdNDP1W08UrhjY8J1r" 

# URL của API Endpoint (Kiểm tra lại BASE_URL trong config.php)
# API Handler mới sử dụng api.php với action=access
API_ACCESS_URL = "http://localhost/nt/api/api.php?action=access" 
# ------------------------------------------

def get_data_and_execute():
    """
    Gửi Token và Chữ ký số lên Server để nhận link dữ liệu độc nhất.
    """
    print("--- BƯỚC 1: Gửi yêu cầu xác thực lên Server ---")
    payload = {
        'token': MY_TOKEN,
        'signature': MY_SIGNATURE
    }

    try:
        # Gửi yêu cầu POST đến API xác thực
        # Sử dụng POST để gửi thông tin bảo mật
        response = requests.post(API_ACCESS_URL, data=payload)
        response_data = response.json()
        
        # Kiểm tra phản hồi từ Server
        if response_data.get('status') == 'success':
            data_url = response_data.get('data_url')
            print(f"\n[SERVER] Xác thực thành công: {response_data.get('message')}")
            print(f"[SERVER] Nhận được Link Dữ liệu Độc nhất: {data_url}")
            
            # --- BƯỚC 2: Truy cập Link Dữ liệu Độc nhất ---
            print("\n--- BƯỚC 2: Truy cập Link Dữ liệu để nhận Payload (LƯU Ý: Link chỉ dùng được 1 lần) ---")
            
            # Tải dữ liệu từ link độc nhất (chỉ dùng được 1 lần)
            # Link này sẽ trỏ đến api/api.php?action=data&id=...
            data_response = requests.get(data_url)
            
            if data_response.status_code == 200:
                final_payload = data_response.json()
                print("[SUCCESS] Đã tải về Payload Dữ liệu thành công:")
                print(json.dumps(final_payload, indent=4))
                
                # --- BƯỚC 3: Thực thi Logic ---
                print("\n--- BƯỚC 3: Bắt đầu thực thi logic dựa trên Payload ---")
                
                user_config = final_payload.get('config', {}) # Lấy từ khóa 'config' trong Payload
                timeout = user_config.get('timeout', 60)
                mode = user_config.get('mode', 'default')

                print(f"Cấu hình đã tải: [Timeout: {timeout}s, Mode: {mode}]")
                
                # Ví dụ: thực thi một đoạn code Python dựa trên Payload
                if mode == 'secure':
                    print("--> Logic: Đang chạy tác vụ bảo mật. Chờ 2 giây...")
                    time.sleep(2)
                    print("--> Hoàn thành tác vụ bảo mật.")
                else:
                    print("--> Logic: Đang chạy ở chế độ Mặc định...")
                
                print("\n[CLIENT] Hoàn tất quá trình.")
                
            else:
                # Báo lỗi nếu link dữ liệu đã bị xóa (đã dùng) hoặc không tồn tại
                print(f"[FAILED] Link dữ liệu không hợp lệ hoặc đã hết hạn. Status: {data_response.status_code}")
                print(data_response.text)
                
        else:
            # Báo lỗi nếu xác thực thất bại
            print(f"\n[FAILED] Xác thực thất bại: {response_data.get('message')}")

    except requests.exceptions.RequestException as e:
        print(f"\n[NETWORK ERROR] Lỗi kết nối đến Server: {e}")
    except json.JSONDecodeError:
        print("\n[API ERROR] Phản hồi từ Server không phải là JSON hợp lệ.")

if __name__ == "__main__":
    get_data_and_execute()
    
# Hướng dẫn sử dụng:
# 1. Chạy Server PHP (dùng XAMPP/WAMP và đã tạo CSDL)
# 2. Đảm bảo thư mục 'cache' đã được tạo trong thư mục gốc của dự án web.
# 3. Tạo Chữ ký số (Signature) và Token trong trang management.php.
# 4. Thay thế MY_TOKEN và MY_SIGNATURE bằng giá trị đã tạo.
# 5. Chạy file này: python client.py
