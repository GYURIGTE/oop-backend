# Deploying the BALIGYA backend

Recommended stack:

| Part      | Service                          |
| --------- | -------------------------------- |
| Frontend  | Vercel                           |
| Backend   | Render (Docker web service)      |
| Database  | Neon Postgres (free, no expiry)  |

The repo already contains a `Dockerfile`, `.dockerignore`, and
`docker/start.sh`, so Render can build it without extra configuration.

---

## 1. Create the database (Neon)

1. Sign in at <https://neon.tech> and create a project.
2. Copy the **direct connection** string (not the pooled one), which looks like:
   ```
   postgresql://neondb_owner:PASSWORD@ep-xxxx.region.aws.neon.tech/neondb?sslmode=require
   ```
3. Split it into the values below:
   - `DB_HOST` → `ep-xxxx.region.aws.neon.tech`
   - `DB_PORT` → `5432`
   - `DB_DATABASE` → `neondb`
   - `DB_USERNAME` → `neondb_owner`
   - `DB_PASSWORD` → the password from the URL

> Prefer MySQL? Use **Aiven for MySQL** instead, keep `DB_CONNECTION=mysql`,
> and set `DB_PORT=3306`. Everything else below stays the same.

---

## 2. Create the Render web service

1. Go to <https://dashboard.render.com> → **New** → **Web Service**.
2. Connect the `oop-backend` GitHub repo.
3. Settings:
   - **Language / Runtime:** Docker
   - **Branch:** `main`
   - **Dockerfile path:** `Dockerfile`
   - **Instance type:** Free
   - **Health check path:** `/up`
4. Add these environment variables:

   | Key | Value |
   | --- | --- |
   | `APP_NAME` | `BALIGYA` |
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | output of `php artisan key:generate --show` |
   | `APP_URL` | `https://<your-service>.onrender.com` |
   | `FRONTEND_URL` | `https://<your-app>.vercel.app` |
   | `LOG_CHANNEL` | `stderr` |
   | `DB_CONNECTION` | `pgsql` |
   | `DB_HOST` | from Neon |
   | `DB_PORT` | `5432` |
   | `DB_DATABASE` | `neondb` |
   | `DB_USERNAME` | from Neon |
   | `DB_PASSWORD` | from Neon |
   | `DB_SSLMODE` | `require` |
   | `SESSION_DRIVER` | `database` |
   | `CACHE_STORE` | `database` |
   | `QUEUE_CONNECTION` | `sync` |
   | `FILESYSTEM_DISK` | `public` |
   | `SEED_DATABASE` | `true` (first deploy only) |

5. Click **Create Web Service**. The first build takes a few minutes.

`docker/start.sh` automatically:
- binds Apache to Render's `PORT`
- runs `php artisan config:cache`
- runs `php artisan migrate --force`
- runs `php artisan db:seed --force` when `SEED_DATABASE=true`

6. Once the deploy is live and seeded, change `SEED_DATABASE` to `false`
   (or delete it) so future restarts don't reset the demo data.

### Check it
```
https://<your-service>.onrender.com/up
```
should return HTTP 200.

---

## 3. Point the frontend at it

In Vercel → your frontend project → **Settings** → **Environment Variables**:

```
VITE_API_URL = https://<your-service>.onrender.com/api
```

Then **redeploy** (Vite bakes this value at build time).

`FRONTEND_URL` on Render feeds `config/cors.php`, so the Vercel domain is
allowed automatically. If you also use Vercel preview URLs, add each preview
domain or allow the pattern in `config/cors.php`.

---

## Notes

- **Free Render services sleep** after inactivity. The first request after a
  pause can take 30–60 seconds — open the API URL once before a demo.
- **Uploaded photos are ephemeral** on Render's free filesystem. Seeded demo
  photos live in the frontend (`public/images`), so they are fine. For real
  uploads, use Cloudinary or S3.
- **Seeded image URLs** use `FRONTEND_URL`, so set it before the first seed.
- To deploy backend changes: `git add . && git commit -m "..." && git push`.
  Render redeploys automatically.
