-- =========================================================
-- DATABASE: github_api_project
-- Tables:
--   1. github_users
--   2. github_repos
-- =========================================================

CREATE DATABASE IF NOT EXISTS github_api_project;
USE github_api_project;

-- =========================================================
-- TABLE 1: github_users
-- Stores detailed GitHub user profile data
-- =========================================================

CREATE TABLE IF NOT EXISTS github_users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    login VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255),
    avatar_url TEXT,
    bio TEXT,

    followers INT DEFAULT 0,
    following INT DEFAULT 0,
    public_repos INT DEFAULT 0,
    public_gists INT DEFAULT 0,

    type VARCHAR(100),

    company VARCHAR(255),
    blog TEXT,
    location VARCHAR(255),
    email VARCHAR(255),
    hireable VARCHAR(50),
    twitter_username VARCHAR(255),

    created_at DATETIME,
    updated_at DATETIME,
    
    last_fetched TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_login (login)
);


-- =========================================================
-- TABLE 2: github_repos
-- Stores repository data for each GitHub user
-- =========================================================

CREATE TABLE IF NOT EXISTS github_repos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_login VARCHAR(255) NOT NULL,

    repo_id BIGINT NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    html_url TEXT,
    description TEXT,

    fork BOOLEAN DEFAULT FALSE,
    stargazers_count INT DEFAULT 0,
    watchers_count INT DEFAULT 0,
    language VARCHAR(100),

    forks_count INT DEFAULT 0,
    open_issues_count INT DEFAULT 0,

    default_branch VARCHAR(100),
    updated_at DATETIME,

    fetched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_user_login (user_login)
);