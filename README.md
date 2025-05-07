# Campus Critique
A system that allows students to rate their faculty for their various courses. A dedicated web-based Course Review & Rating System will bridge this gap by allowing students to share feedback, rate courses, and make informed decisions based on peer experiences.

# 📚 Campus Critique, Rate Your Ashesi Professors

**Campus Critique** is a dynamic and interactive review platform designed for the Ashesi University community. It allows students to review professors and courses, and provides analytics and moderation features for administrators and professors.

---

## 👥 Team Members
- Jemima Kukua Arhin  
- Isabella Abena Tsikata  
- Emmanuella Oteng Frimpong
- Alan Safo Ofori
- Nana Kwabena Aseda Bioh

---

## 🚀 Features
- ✍️ Students can rate and review professors and courses.
- 🧠 AI-Powered Sentiment Analysis and Content Moderation.
- 🧑‍🏫 Professors can view, edit their profile and see review stats.
- 🛡️ Admins can manage users, professors, courses, and moderate reviews.
- 📊 Charts and statistics to analyze review trends and user engagement.

---

## 📂 Folder Structure
```/RateMyProfessorAshesi/
│
├── config/ # Database connection config
├── php_files/ # PHP backend scripts
│ ├── login.php
│ ├── logout.php
│ ├── signup.php
│ ├── submit_review.php
│ ├── professor_dashboard.php
│ └── admin_dashboard.php
│ └── ...
├── public/ # Static assets (CSS, images, scripts)
│ ├── styles/
│ └── assets/
├── analyze_sentiment.py # Flask sentiment API (port 5000)
├── moderate_content.py # Flask moderation API (port 5001)
└── README.md # This file
```

---

## 🧭 User Navigation & Role-Based Access

### 1. 👤 **Students**
- **Signup/Login** through the homepage modal.
- **Dashboard**:
  - Submit reviews for courses or professors.
  - View and edit their reviews.
- **Review Feed**:
  - See what others are saying.
  - Vote on helpfulness of reviews.
  - Filter reviews by department/course.

### 2. 🧑‍🏫 **Professors**
- **Signup/Login** through the “Professor Signup” modal.
- **Dashboard**:
  - View statistics on their reviews.
  - Edit their profile (name, department).
  

### 3. 🛡️ **Admins**
- Login as an admin (using pre-defined admin account).
- **Admin Dashboard**:
  - View user, review, professor, and course statistics.
  - Manage users: Promote, demote, or delete users.
  - Manage reviews: View recent reviews, delete or edit.
  - Manage professors & courses.
  - Visual analytics (review trends, role distribution).
  - Flagged review detection (based on negative sentiment).

---

## 🧠 AI Integrations

- **Sentiment Analysis**: `analyze_sentiment.py` (Flask server on `localhost:5000`)
- **Content Moderation**: `moderate_content.py` (Flask server on `localhost:5001`)

> These Flask servers must be running in the background when submitting reviews.

---

## 🛠️ Tech Stack
- **Frontend**: HTML, CSS
- **Backend**: PHP (procedural), MySQL
- **ML & AI**: Python (Flask + VADER Sentiment)
- **Charting**: Chart.js

---

## 📦 Setup Instructions (Locally)

1. Install XAMPP and start Apache & MySQL.
2. Import the provided SQL schema into phpMyAdmin.
3. Run `analyze_sentiment.py` and `moderate_content.py`:
   ```bash
   python analyze_sentiment.py
   python moderate_content.py

## Launch the project in your browser via:
http://localhost/RateMyProfessorAshesi/home.php

Linked to the deployed web app: https://campuscritique.onrender.com 
