# github_api_project
A lightweight web app that uses the GitHub API to fetch and display repository files in a clean, dynamic file-tree interface. Includes real-time data loading, responsive UI, and quick action buttons like Download ZIP and View on GitHub. Perfect for learning or showcasing GitHub API usage.


---

## 🔗 Live Links

### 🚀 Live Demo  
User + Repositories preview  
`https://conceptualcode.rf.gd/en/projects/github_api_project/live_demo`

### 📄 Code Viewer  
Interactive folder explorer + modal + iframe preview  
`https://conceptualcode.rf.gd/en/projects/github_api_project/code_preview`

---

## 📁 Project Structure

/github_api_project
│
├── live_demo
│   ├── index.html
│   ├── /api
│   │     ├── db.php
│   │     ├── getUser.php
│   │     └── getRepos.php
│   ├── /assets
│   │     └── loader.svg
│   ├── /css
│   │     └── style.css
│   └── /js
│         ├── app.js
│         └── jquery-3.7.1.min.js
│
├── code-viewer
│   └── index.html
│
├── project.json
└── README.md


---

## ⭐ Features

- GitHub API using PHP + cURL  
- Clean JSON output  
- User + Repositories preview  
- Modern UI with loader  
- Fully responsive  
- Interactive Code Viewer  
- Directory tree with dropdown + arrows  
- Modal preview with iframe for `.html` files  
- Smooth UX and fast loading  

---

## 🧩 API Endpoints

### `/api/getUser.php`  
Returns GitHub user profile data  
- username  
- bio  
- avatar  
- followers  
- following  
- repo count  

### `/api/getRepos.php`  
Returns public repositories  
- repo name  
- description  
- stars  
- forks  
- URL  

### `/api/db.php`  
Optional: Database connection using secure PDO.

---

## 🛠 Technologies Used

**Frontend:**  
HTML, CSS, JavaScript (fetch)

**Backend:**  
PHP, cURL, PDO

**Extra:**  
Iframe preview, modal viewer, directory tree UI

---

## 📘 project.json (Metadata)

```json
{
  "name": "GitHub API Project",
  "slug": "github-api-project",
  "description": "A GitHub user & repository explorer with a clean UI and code viewer.",
  "tags": ["API", "GitHub", "JavaScript", "PHP", "Portfolio"],
  "preview": "live-demo/index.html",
  "code_view": "code-viewer/index.html"
}
