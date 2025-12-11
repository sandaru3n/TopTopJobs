# Google Indexing API Setup Guide

This guide explains how to set up Google Indexing API for automatic job posting notifications.

## Prerequisites

1. A Google Cloud Project
2. Google Indexing API enabled
3. A service account with Indexing API permissions

## Setup Steps

### 1. Enable Google Indexing API

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Select your project (or create a new one)
3. Navigate to **APIs & Services** > **Library**
4. Search for "Indexing API"
5. Click **Enable**

### 2. Create a Service Account

1. Go to **APIs & Services** > **Credentials**
2. Click **Create Credentials** > **Service Account**
3. Fill in the service account details
4. Click **Create and Continue**
5. Grant the service account the **Indexing API Service Agent** role
6. Click **Done**

### 3. Create and Download Service Account Key

1. Click on the service account you just created
2. Go to the **Keys** tab
3. Click **Add Key** > **Create new key**
4. Select **JSON** format
5. Download the JSON file

### 4. Configure the Service Account File

1. Rename the downloaded JSON file to `google-service-account.json`
2. Place it in: `jobportal/app/Config/google-service-account.json`
3. **IMPORTANT**: Make sure this file is NOT accessible via web (it's already in app/Config which should be protected)

### 5. Enable Google Indexing API in Application

Add to your `.env` file:

```
GOOGLE_INDEXING_ENABLED=true
```

### 6. Install Google PHP Client Library (Recommended)

For production use, install Google's official PHP client library:

```bash
composer require google/apiclient
```

Then update `GoogleIndexingService.php` to use the library instead of manual JWT signing.

### 7. Verify Ownership in Google Search Console

1. Go to [Google Search Console](https://search.google.com/search-console)
2. Add your property (website)
3. Verify ownership
4. The Indexing API will only work for verified properties

## How It Works

- When a job is **created**: The system automatically sends a `URL_UPDATED` notification to Google
- When a job is **deleted**: The system automatically sends a `URL_DELETED` notification to Google

## Testing

1. Post a new job
2. Check the logs: `writable/logs/log-*.php`
3. Look for messages like: "Successfully notified Google Indexing API for URL: ..."

## Troubleshooting

### "Cannot notify Google Indexing API: No access token"
- Check that `google-service-account.json` exists in `app/Config/`
- Verify the JSON file is valid
- Check that `GOOGLE_INDEXING_ENABLED=true` in `.env`

### "Google Indexing API error"
- Verify the service account has the correct permissions
- Check that Indexing API is enabled in Google Cloud Console
- Verify the URL is accessible and returns 200 status
- Ensure the page has valid JobPosting structured data

### API Quota Limits
- Free tier: 200 URLs per day
- For higher limits, request quota increase in Google Cloud Console

## Security Notes

- Never commit `google-service-account.json` to version control
- Add it to `.gitignore`
- Keep the file permissions restricted (chmod 600)
- Rotate keys periodically

## Alternative: Manual Token Setup

If you prefer to use a manually obtained access token:

1. Update `GoogleIndexingService::getAccessToken()` to return your token
2. Or set it in cache: `$cache->save('google_indexing_token', 'your-token', 3600);`

