import sys
import smtplib
from email.message import EmailMessage

# Get the data from PHP
if len(sys.argv) >= 3:
    raw_message = sys.argv[1]
    email_receiver = sys.argv[2]
else:
    print("❌ Missing arguments from PHP.")
    sys.exit(1)
    
message_body = raw_message.replace("|||", "\n")


# Email setup
email_sender = 'issimo181@gmail.com'
email_password = 'nqkt xysf nhov viuf'  # Use Gmail App Password


msg = EmailMessage()
msg.set_content(message_body)
msg['Subject'] = 'LASD - New Message'
msg['From'] = email_sender
msg['To'] = email_receiver

# Send the email
try:
    with smtplib.SMTP('smtp.gmail.com', 587) as smtp:
        smtp.starttls()
        smtp.login(email_sender, email_password)
        smtp.send_message(msg)
    print("✅ Email sent successfully.")
except Exception as e:
    print(f"❌ Failed to send email: {e}")
