# Google Sign-In Setup Guide

This guide explains how to set up Google Sign-In (OAuth) for your TopTopJobs application.

## Prerequisites

1. A Google Cloud Project
2. Google Identity Services enabled
3. OAuth 2.0 credentials created

## Setup Steps

### 1. Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one

### 2. Enable Google Identity Services

1. Navigate to **APIs & Services** > **Library**
2. Search for "Google Identity Services" or "Google+ API"
3. Click **Enable**

### 3. Create OAuth 2.0 Credentials

1. Go to **APIs & Services** > **Credentials**
2. Click **Create Credentials** > **OAuth client ID**
3. If prompted, configure the OAuth consent screen first:
   - Choose **External** (unless you have a Google Workspace)
   - Fill in required fields (App name, User support email, Developer contact)
   - Add scopes: `email` and `profile`
   - Add test users if needed
   - Save and continue

### 4. Configure OAuth Client ID

When creating the OAuth 2.0 Client ID:

1. **Application type**: Select **Web application**
2. **Name**: Enter a name (e.g., "TopTopJobs Web Client")

3. **Authorized JavaScript origins** (Where your app runs):
   ```
   http://localhost
   http://toptopjobs.local
   http://toptopjobs.local/
   https://yourdomain.com
   https://www.yourdomain.com
   ```
   Add all domains where your application will be accessed.

4. **Authorized redirect URIs** (Callback URLs):
   ```
   http://localhost/auth/google
   http://toptopjobs.local/auth/google
   https://yourdomain.com/auth/google
   https://www.yourdomain.com/auth/google
   ```
   Note: For Google Identity Services (One Tap), redirect URIs may not be strictly required, but it's good practice to add them.

5. Click **Create**

### 5. Copy Credentials

After creating the OAuth client:

1. You'll see a popup with your **Client ID** and **Client Secret**
2. Copy both values
3. **Important**: Save the Client Secret now - you won't be able to see it again!

### 6. Configure Environment Variables

Add the credentials to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
```

Replace `your-client-id-here` and `your-client-secret-here` with your actual values.

### 7. Test the Integration

1. Clear your browser cache
2. Go to your login or signup page
3. Click "Continue with Google"
4. You should see the Google Sign-In popup

## Important Notes

- **Authorized JavaScript origins** must match exactly (including protocol http/https and trailing slashes)
- **Redirect URIs** are used if you implement the authorization code flow (not required for current implementation)
- Keep your **Client Secret** secure - never commit it to version control
- The `.env` file should be in `.gitignore` to protect your secrets

## Troubleshooting

### "Error 400: redirect_uri_mismatch"
- Make sure your redirect URI is added in Google Cloud Console
- Check that the protocol (http/https) matches exactly
- Verify no trailing slash differences

### "Invalid Client ID"
- Verify `GOOGLE_CLIENT_ID` is set correctly in `.env`
- Make sure there are no extra spaces or quotes
- Restart your server after changing `.env`

### Button doesn't appear
- Check that `GOOGLE_CLIENT_ID` is set in `.env`
- Verify the JavaScript console for errors
- Make sure Google Identity Services script loads correctly

## Security Best Practices

1. Never expose Client Secret in client-side code
2. Use HTTPS in production
3. Verify JWT tokens server-side (already implemented)
4. Regularly rotate your Client Secret
5. Monitor OAuth usage in Google Cloud Console

