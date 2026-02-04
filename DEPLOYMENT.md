<<<<<<< HEAD
﻿# ðŸš€ Railway Deployment Guide
=======
# 🚀 Railway Deployment Guide
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

## Prerequisites
- GitHub account with repository: `https://github.com/Dipendr98/new_modern_bot`
- Railway account (sign up at [railway.app](https://railway.app))
<<<<<<< HEAD
- Domain: `ethnix.net`
=======
- Domain: `babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

---

## Step 1: Push Code to GitHub

**Important**: Remove hardcoded secrets before pushing:
- Stripe API keys are now using environment variables
- Never commit `.env` file to GitHub

```bash
git add .
git commit -m "Initial commit"
git push -u origin main --force
```

---

## Step 2: Create Railway Project

1. Go to [Railway.app](https://railway.app)
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Choose: `Dipendr98/new_modern_bot`
5. Railway auto-detects `Dockerfile` and builds

---

## Step 3: Add MySQL Database

1. In Railway dashboard, click **"+ New"**
<<<<<<< HEAD
2. Select **"Database"** â†’ **"Add MySQL"**
=======
2. Select **"Database"** → **"Add MySQL"**
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
3. Railway creates managed MySQL instance
4. Note the connection details for next step

---

## Step 4: Configure Environment Variables

<<<<<<< HEAD
Go to **App Service** â†’ **Variables**, add:
=======
Go to **App Service** → **Variables**, add:
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

```bash
# Database (Railway MySQL)
DB_DSN=mysql:host=${{MySQL.MYSQLHOST}};port=${{MySQL.MYSQLPORT}};dbname=${{MySQL.MYSQLDATABASE}};charset=utf8mb4
DB_USER=${{MySQL.MYSQLUSER}}
DB_PASS=${{MySQL.MYSQLPASSWORD}}

# Telegram Bot
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_BOT_USERNAME=YourBotUsername
TELEGRAM_ADMIN_USERNAME=your_telegram_username
TELEGRAM_ANNOUNCE_CHAT_ID=-100...
TELEGRAM_ALLOWED_IDS=123456,789012

# Stripe (Optional)
STRIPE_SECRET_KEY=sk_live_...

# Payment (Optional - can set via Admin Panel)
PAYMENT_UPI_ID=your@upi
PAYMENT_QR_LINK=https://...
```

---

## Step 5: Add Persistent Volume

**Critical for profile pictures!**

<<<<<<< HEAD
1. Go to **App Service** â†’ **Volumes**
=======
1. Go to **App Service** → **Volumes**
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
2. Click **"+ New Volume"**
3. **Mount Path**: `/var/www/html/assets/profiles`
4. Click **"Add"**

---

## Step 6: Initialize Database

1. Wait for deployment to complete
2. Visit: `https://your-app.up.railway.app/setup_db.php`
3. Creates all database tables
4. **Delete `setup_db.php` after running** (security)

---

## Step 7: Custom Domain Setup

<<<<<<< HEAD
1. **App Service** â†’ **Settings** â†’ **Domains**
2. Click **"+ Custom Domain"**
3. Enter: `ethnix.net`
=======
1. **App Service** → **Settings** → **Domains**
2. Click **"+ Custom Domain"**
3. Enter: `babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
4. Railway provides DNS records
5. Add records to your domain registrar
6. Wait 5-30 minutes for DNS propagation

---

<<<<<<< HEAD
## ðŸ”’ Post-Deployment Security

- [ ] Change admin password via Admin Panel â†’ System tab
=======
## 🔒 Post-Deployment Security

- [ ] Change admin password via Admin Panel → System tab
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
- [ ] Delete `setup_db.php`
- [ ] Verify `.env` not publicly accessible
- [ ] Test all payment gateways
- [ ] Enable HTTPS (automatic on Railway)

---

<<<<<<< HEAD
## ðŸ“Š Monitoring

- **Logs**: Railway Dashboard â†’ Deployments tab
=======
## 📊 Monitoring

- **Logs**: Railway Dashboard → Deployments tab
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
- **Restart**: Click "Restart" button
- **Database Backup**: Automatic on Railway MySQL

---

<<<<<<< HEAD
## ðŸ’¡ Tips
=======
## 💡 Tips
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

- **Free Tier**: $5/month credit (good for testing)
- **Scaling**: Upgrade to Pro for production
- **Storage**: Monitor MySQL usage in dashboard

---

<<<<<<< HEAD
## ðŸ› Troubleshooting
=======
## 🐛 Troubleshooting
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

### Push Blocked by GitHub
- Ensure no hardcoded secrets in code
- Use environment variables for API keys

### Database Connection Failed
- Check environment variables match Railway MySQL
- Verify `DB_DSN` format is correct

### Profile Pictures Not Saving
- Ensure Volume is mounted at `/var/www/html/assets/profiles`
- Check Volume is attached to correct service

---

<<<<<<< HEAD
## ðŸ“ž Support
=======
## 📞 Support
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

Need help? Check:
- Railway Docs: https://docs.railway.app
- GitHub Issues: https://github.com/Dipendr98/new_modern_bot/issues
<<<<<<< HEAD

=======
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
