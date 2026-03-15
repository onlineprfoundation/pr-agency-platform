# GitHub Setup Guide

Quick steps to push this project to GitHub.

## Prerequisites

- Git installed (`git --version`)
- GitHub account
- (Optional) [GitHub CLI](https://cli.github.com/) for `gh` commands

---

## Method 1: Terminal + GitHub Website

### 1. Initialize Git

```bash
cd /Users/apple/online\ pr/agency-platform
git init
```

### 2. Stage and Commit

```bash
git add .
git commit -m "Initial commit: Online.PR agency platform"
```

### 3. Create Repo on GitHub

- Go to [github.com/new](https://github.com/new)
- Repository name: `online-pr-agency-platform` (or your choice)
- Description: `Self-hosted PR agency platform – clients, projects, invoicing, client portal`
- Choose Public or Private
- **Do not** add README, .gitignore, or license (we have them)
- Click **Create repository**

### 4. Push from Terminal

Replace `YOUR_USERNAME` and `YOUR_REPO_NAME` with your values:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git branch -M main
git push -u origin main
```

When prompted for credentials, use:
- **Username**: your GitHub username
- **Password**: a [Personal Access Token](https://github.com/settings/tokens) (not your GitHub password)

---

## Method 2: GitHub CLI

### 1. Install and Login

```bash
# Install (macOS)
brew install gh

# Login
gh auth login
```

Follow the prompts (browser or token).

### 2. Create Repo and Push

```bash
cd /Users/apple/online\ pr/agency-platform
git init
git add .
git commit -m "Initial commit: Online.PR agency platform"
gh repo create online-pr-agency-platform --public --source=. --push --description "Self-hosted PR agency platform – clients, projects, invoicing, client portal"
```

---

## Suggested GitHub Repo Settings

When creating the repo, you can use:

| Field | Value |
|-------|-------|
| **Name** | `online-pr-agency-platform` |
| **Description** | Self-hosted PR agency platform – clients, projects, invoicing, client portal |
| **Topics** | `laravel`, `php`, `pr-agency`, `stripe`, `self-hosted` |

---

## Troubleshooting

**"Permission denied" or "Authentication failed"**
- Use a [Personal Access Token](https://github.com/settings/tokens) instead of password
- Or switch to SSH: `git remote set-url origin git@github.com:USER/REPO.git`

**"Repository not found"**
- Check the remote: `git remote -v`
- Ensure the repo exists on GitHub and you have access
