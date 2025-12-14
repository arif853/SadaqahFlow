# Fix for Login/Logout & Page Expired Issues

## 🐛 Issues Identified:

1. **Service Worker Caching Auth Pages** - Cached stale CSRF tokens
2. **Session expire_on_close = true** - Sessions ending unexpectedly
3. **Aggressive caching strategy** - Login/logout routes cached
4. **CSP headers** - Potentially blocking form submissions

---

## ✅ Fixes Applied:

### 1. **Service Worker Fixes (sw.js)**
- ❌ **NEVER** cache `/login`, `/logout`, `/dashboard`, or any POST requests
- ✅ Network-first strategy for authentication routes
- ✅ Cache-first only for static assets (`/assets/`, `/storage/`)
- ✅ Incremented cache version to `v2` (forces refresh)
- ✅ Added explicit never-cache list

### 2. **Session Configuration (config/session.php)**
```php
'expire_on_close' => false,  // Changed from true
'secure' => env('SESSION_SECURE_COOKIE', false),  // Configurable via .env
'same_site' => 'lax',  // Allows redirects
```

### 3. **Environment Configuration (.env)**
```env
SESSION_SECURE_COOKIE=true  # Set to true in production with HTTPS
SESSION_DOMAIN=.cpds-dk-bs.org  # Match your domain
```

### 4. **CSP Headers Relaxed**
- Added `form-action 'self'` - allows form submissions
- Added `blob:` to img-src - for uploaded images

### 5. **Auto-Clear Old Cache**
- Added script in `admin.blade.php` to clear v1 caches
- Forces service worker update on page load

---

## 🚀 Deployment Steps:

### **Step 1: Update .env on Production Server**
```bash
# SSH into your server
ssh user@cpds-dk-bs.org

# Edit .env file
nano /path/to/your/project/.env

# Add/Update these lines:
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.cpds-dk-bs.org
APP_URL=https://cpds-dk-bs.org
```

### **Step 2: Clear All Caches**
```bash
# On server, run:
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Clear session files
rm -rf storage/framework/sessions/*

# Restart PHP-FPM (or your server)
sudo systemctl restart php8.1-fpm
# OR
sudo service php-fpm restart
```

### **Step 3: Clear Browser Cache (Important!)**

**For Users:**
- Press `Ctrl + Shift + Delete` (Chrome/Firefox)
- Select "Cookies and site data" + "Cached images and files"
- Click "Clear data"

**Or:**
- Go to `chrome://serviceworker-internals/` (Chrome)
- Find your site and click "Unregister"
- Reload page

### **Step 4: Verify Service Worker Update**
```javascript
// Open browser console (F12) and run:
navigator.serviceWorker.getRegistrations().then(r => {
    r.forEach(reg => reg.unregister());
    location.reload();
});
```

---

## 🔧 Testing:

### **Test 1: Login**
1. Clear browser cache
2. Go to `https://cpds-dk-bs.org/login`
3. Login with credentials
4. Should redirect to dashboard ✅

### **Test 2: Logout**
1. Click logout button
2. Should redirect to `/` (root page)
3. Should NOT show "Page Expired" ✅

### **Test 3: CSRF Token**
1. Login to dashboard
2. Wait 5 minutes
3. Submit a form (create member, etc.)
4. Should work without "419 Page Expired" ✅

### **Test 4: Service Worker Cache**
```javascript
// Open console and check:
caches.keys().then(console.log);
// Should show: ['cpds-static-v2', 'cpds-dynamic-v2']
// NOT v1
```

---

## 🆘 If Issues Persist:

### **Issue: Still showing "Page Expired"**
**Cause:** CSRF token mismatch or session timeout

**Fix:**
```bash
# 1. Check session driver
php artisan config:clear

# 2. Check storage permissions
chmod -R 775 storage/
chown -R www-data:www-data storage/

# 3. Check session file location
ls -la storage/framework/sessions/
```

### **Issue: "This site can't be reached" ERR_FAILED**
**Cause:** Incorrect redirect or HTTPS misconfiguration

**Fix:**
```php
// In .env, ensure:
APP_URL=https://cpds-dk-bs.org  // NOT http://

// In config/app.php:
'url' => env('APP_URL', 'https://cpds-dk-bs.org'),

// Then:
php artisan config:cache
```

### **Issue: Logout redirects to blank page**
**Cause:** Root route (`/`) not defined or redirect loop

**Fix:**
```php
// In routes/web.php, verify:
Route::get('/', function () {
    return redirect()->route('login');
});
```

### **Issue: Users keep getting logged out**
**Cause:** Session lifetime too short or secure cookie on HTTP

**Fix:**
```env
# Increase session lifetime in .env:
SESSION_LIFETIME=1440  # 24 hours (in minutes)

# If using HTTP (not HTTPS):
SESSION_SECURE_COOKIE=false
```

---

## 📊 Monitoring:

### **Check Service Worker Status:**
```javascript
// In browser console:
navigator.serviceWorker.getRegistrations().then(regs => {
    regs.forEach(reg => {
        console.log('SW URL:', reg.active?.scriptURL);
        console.log('SW State:', reg.active?.state);
    });
});
```

### **Check Session Status:**
```php
// Add to a test route:
Route::get('/test-session', function() {
    return [
        'session_id' => session()->getId(),
        'is_started' => session()->isStarted(),
        'csrf_token' => csrf_token(),
        'auth_user' => auth()->check(),
    ];
});
```

---

## 🔒 Security Notes:

- ✅ CSRF protection still enabled
- ✅ Secure cookies for HTTPS
- ✅ Same-site cookie protection
- ✅ Service worker doesn't cache sensitive data
- ✅ Network-first for authentication

---

## 📝 What Changed:

### **Before:**
- Service worker cached everything (including `/login`, `/logout`)
- Cache-first strategy caused stale CSRF tokens
- `expire_on_close = true` killed sessions on browser close
- Aggressive caching led to "Page Expired" errors

### **After:**
- Auth routes NEVER cached
- Network-first for dynamic content
- Sessions persist until timeout
- Old cache automatically cleared
- Proper error handling for offline state

---

## ✅ Success Criteria:

- ✅ Login works consistently
- ✅ Logout redirects properly
- ✅ No "Page Expired" errors
- ✅ No "This site can't be reached"
- ✅ Forms submit successfully
- ✅ PWA still works offline for static content
- ✅ Service worker updates automatically

---

## 🎯 Production Checklist:

Before marking as resolved:
- [ ] Deployed updated code to server
- [ ] Updated .env with correct settings
- [ ] Cleared all server caches
- [ ] Restarted PHP-FPM/web server
- [ ] Tested login from different browsers
- [ ] Tested logout functionality
- [ ] Verified no CSRF errors
- [ ] Checked browser console for errors
- [ ] Confirmed service worker updated to v2
- [ ] Tested on mobile devices

---

## 💡 Additional Recommendations:

### **1. Monitor Error Logs**
```bash
tail -f storage/logs/laravel.log
```

### **2. Add Session Timeout Warning**
Add a JavaScript timer to warn users before session expires.

### **3. Implement Remember Me**
Already available in Laravel Breeze - ensure checkbox works.

### **4. Add AJAX CSRF Token Refresh**
```javascript
// Refresh CSRF token every 10 minutes
setInterval(() => {
    fetch('/sanctum/csrf-cookie').then(() => {
        $('meta[name="csrf-token"]').attr('content',
            document.querySelector('meta[name="csrf-token"]').content
        );
    });
}, 600000);
```

### **5. Setup Health Monitoring**
Use Laravel Telescope or a monitoring service to track:
- Failed login attempts
- CSRF token mismatches
- Session timeouts
- 419 errors

---

## 📞 Support:

If issues persist after implementing these fixes:
1. Check browser console for errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check web server logs: `/var/log/nginx/error.log` or Apache logs
4. Verify .env settings match production environment

**Common Gotchas:**
- Mixed HTTP/HTTPS content
- Incorrect APP_URL in .env
- Session storage permission issues
- Browser cache not cleared
- Service worker not updating
