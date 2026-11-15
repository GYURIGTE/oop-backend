# BALIGYA — API (Backend)

Laravel 12 JSON API for **BALIGYA**, a local buy-and-sell marketplace. It powers
authentication, seller listings, the public market, buyer/seller conversations,
messages, meet-ups, and profile management for the React front end in
`../frontend-baligya`.

There is no payments, orders, or checkout module — the platform is only about
**chatting and meeting up**.

---

## Requirements

- PHP 8.2+
- Composer 2.x
- MySQL / MariaDB (XAMPP MariaDB 10.4 works)

---

## Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Create the environment file

```bash
copy .env.example .env
php artisan key:generate
```

Adjust the database block in `.env` if your MySQL credentials differ:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=baligya
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Create the database

```sql
CREATE DATABASE baligya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Migrate and seed

```bash
php artisan migrate --seed
```

### 5. Link storage (uploaded listing photos and avatars)

```bash
php artisan storage:link
```

### 6. Serve the API

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

The API is then available at <http://127.0.0.1:8000/api>.

---

## Configuration notes

| Variable        | Purpose                                                  |
| --------------- | -------------------------------------------------------- |
| `APP_URL`       | Base URL used to build stored image URLs                 |
| `FRONTEND_URL`  | Allowed CORS origin and the host used for seeded images   |
| `FILESYSTEM_DISK` | Set to `public` so uploads land in `storage/app/public` |

CORS allows `FRONTEND_URL`, `http://localhost:5173`, and
`http://127.0.0.1:5173` (see `config/cors.php`).

---

## Demo data

`php artisan migrate --seed` creates 1 user, 13 listings, and 13 listing images.
The account owns every listing and uses the password `password`.

| Email | Username |
| ------------------------------- | ---------------- |
| `chrislawrencedegamo182@gmail.com` | `chrislawrence` |

Login accepts an email **or** a username.

Register a second account to demo chatting and meet-ups.

---

## API overview

All authenticated routes use Sanctum bearer tokens:

```
Authorization: Bearer <token>
```

Responses are wrapped as `{ "data": ... }`; paginated collections also include
`links` and `meta`.

### Auth

| Method | Endpoint             | Auth | Description                      |
| ------ | -------------------- | ---- | -------------------------------- |
| POST   | `/api/auth/register` | —    | Create an account, returns token |
| POST   | `/api/auth/login`    | —    | Email or username + password     |
| GET    | `/api/auth/me`       | ✓    | Current user                     |
| POST   | `/api/auth/logout`   | ✓    | Revoke current token             |

### Market (public)

| Method | Endpoint             | Description                        |
| ------ | -------------------- | ---------------------------------- |
| GET    | `/api/market`        | Browse active listings (paginated) |
| GET    | `/api/market/{id}`   | Listing detail                     |

### Listings (owner)

| Method | Endpoint              | Description                                  |
| ------ | --------------------- | -------------------------------------------- |
| GET    | `/api/listings`       | Current seller's listings (paginated)        |
| POST   | `/api/listings`       | Create listing (multipart, `images[]`)       |
| GET    | `/api/listings/{id}`  | Show listing (includes `imageItems`)         |
| PUT    | `/api/listings/{id}`  | Update listing (`POST` + `_method=PUT`)      |
| DELETE | `/api/listings/{id}`  | Delete listing                               |

`imageItems` is `[{ "id": 1, "url": "..." }]`, used by the edit form to send
`removed_image_ids[]`.

### Conversations & messages

| Method | Endpoint                                   | Description                   |
| ------ | ------------------------------------------ | ----------------------------- |
| GET    | `/api/conversations`                       | List conversations            |
| POST   | `/api/conversations`                       | Start one for a listing       |
| GET    | `/api/conversations/{id}`                  | Conversation + messages       |
| DELETE | `/api/conversations/{id}`                  | Delete conversation           |
| GET    | `/api/conversations/{id}/messages`         | Messages (`?after_id=`)       |
| POST   | `/api/conversations/{id}/messages`         | Send a message                |

### Meet-ups

| Method | Endpoint                              | Description                                  |
| ------ | ------------------------------------- | -------------------------------------------- |
| GET    | `/api/conversations/{id}/meetups`     | List meet-ups for a conversation             |
| POST   | `/api/conversations/{id}/meetups`     | Propose a meet-up                            |
| PATCH  | `/api/meetups/{id}`                   | Update status (Accepted/Declined/Completed/Cancelled) |

Meet-up statuses: `Proposed`, `Accepted`, `Declined`, `Completed`, `Cancelled`.

### Profile

| Method | Endpoint                  | Description                                  |
| ------ | ------------------------- | -------------------------------------------- |
| GET    | `/api/profile`            | Current profile                              |
| PATCH  | `/api/profile`            | Update profile (`POST` + `_method=PATCH` for avatar upload) |
| POST   | `/api/profile/password`   | Change password                              |
| DELETE | `/api/profile`            | Delete account (requires password)           |

---

## Structure

```
app/
  Http/
    Controllers/Api/   Auth, Market, Listing, Conversation, Message,
                       Meetup, Profile
    Requests/          Form requests (validation)
    Resources/         API resources (JSON shaping)
  Models/              User, Listing, ListingImage, Conversation, Message, Meetup
  Policies/            Ownership rules for listings/conversations
database/
  migrations/          Schema
  seeders/             Demo data
routes/api.php         All API routes
```

---

## Quick smoke test

```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"chrislawrencedegamo182@gmail.com\",\"password\":\"password\"}"
```

Use the returned `token` as `Authorization: Bearer <token>` for protected
routes.
