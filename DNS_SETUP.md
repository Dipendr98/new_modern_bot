<<<<<<< HEAD
﻿# DNS Setup Guide for ethnix.net â†’ Railway
=======
# DNS Setup Guide for babachecker.com → Railway
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

## Step 1: Deploy to Railway First

Before configuring DNS, you MUST deploy your app to Railway:

1. Go to [Railway.app](https://railway.app)
2. Create new project from GitHub: `Dipendr98/new_modern_bot`
3. Add MySQL database
4. Configure environment variables
5. Wait for deployment to complete

**You'll get a Railway URL like**: `your-app-xyz123.up.railway.app`

---

## Step 2: Add Custom Domain in Railway

1. In Railway, go to your **App Service**
<<<<<<< HEAD
2. Click **Settings** â†’ **Domains**
3. Click **"+ Custom Domain"**
4. Enter: `ethnix.net`
=======
2. Click **Settings** → **Domains**
3. Click **"+ Custom Domain"**
4. Enter: `babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
5. Railway will show you the **CNAME target** (something like: `your-app.up.railway.app`)

**Copy this CNAME value** - you'll need it for the next step!

---

## Step 3: Configure DNS in Landingsite

Based on your screenshot, here's what to do:

<<<<<<< HEAD
### For Root Domain (ethnix.net):

**Current A Record:**
- Type: `A`
- Name: `ethnix.net`
=======
### For Root Domain (babachecker.com):

**Current A Record:**
- Type: `A`
- Name: `babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
- Value: `76.76.21.21`
- **Action**: Click **"Remove"** (delete this record)

**Add New A Record** (if Railway provides an IP):
- Type: `A`
- Name: `@` or leave blank
- Value: `[Railway's IP address]`
- TTL: `3600`

**OR Add CNAME** (if Railway provides CNAME):
- Type: `CNAME`
- Name: `@`
- Points To: `your-app.up.railway.app` (from Railway)
- TTL: `3600`

### For WWW Subdomain:

**Current CNAME:**
- Type: `CNAME`
- Name: `www`
- Points To: (empty in screenshot)

**Update to:**
- Type: `CNAME`
- Name: `www`
- Points To: `your-app.up.railway.app` (from Railway)
- TTL: `3600`

Click **"Save"**

---

## Step 4: Verify DNS Propagation

After saving DNS records:

1. Wait **5-30 minutes** for DNS propagation
2. Check status: https://dnschecker.org
<<<<<<< HEAD
3. Enter: `ethnix.net`
=======
3. Enter: `babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
4. Should show Railway's IP/CNAME globally

---

## Step 5: Test Your Site

Visit:
<<<<<<< HEAD
- `https://ethnix.net`
- `https://www.ethnix.net`
=======
- `https://babachecker.com`
- `https://www.babachecker.com`
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

Both should load your Railway app!

---

<<<<<<< HEAD
## ðŸ”§ Troubleshooting
=======
## 🔧 Troubleshooting
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

### "DNS not resolving"
- Wait longer (can take up to 48 hours, usually 5-30 mins)
- Clear browser cache: Ctrl+Shift+Delete
- Try incognito mode

### "SSL Certificate Error"
- Railway auto-provisions SSL (takes 5-10 minutes)
- Wait and refresh

### "Site not loading"
- Verify Railway app is deployed and running
- Check Railway logs for errors
- Ensure environment variables are set

---

<<<<<<< HEAD
## ðŸ“‹ Quick Checklist
=======
## 📋 Quick Checklist
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

- [ ] Deploy app to Railway
- [ ] Get Railway's CNAME/IP from domain settings
- [ ] Remove old A record (76.76.21.21) from Landingsite
- [ ] Add new CNAME for root domain
- [ ] Update www CNAME to Railway
- [ ] Save DNS changes
- [ ] Wait for propagation
<<<<<<< HEAD
- [ ] Test both ethnix.net and www.ethnix.net

---

## ðŸŽ¯ What Railway Will Give You

When you add `ethnix.net` in Railway, you'll see:
=======
- [ ] Test both babachecker.com and www.babachecker.com

---

## 🎯 What Railway Will Give You

When you add `babachecker.com` in Railway, you'll see:
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b

**Option 1: CNAME Record**
```
CNAME: your-app-abc123.up.railway.app
```

**Option 2: A Record (less common)**
```
A: 123.456.789.012
```

Use whichever Railway provides!
<<<<<<< HEAD

=======
>>>>>>> f0e10c4ddeefca130962ae1ec2a89d1fe968e85b
