# SadaqahFlow API Documentation

## Overview

SadaqahFlow provides a RESTful API for managing religious organization fund management operations. This document covers all available endpoints, request/response formats, and authentication requirements.

---

## Authentication

All API endpoints require authentication via Laravel Sanctum session-based authentication.

### Login

```http
POST /login
Content-Type: application/x-www-form-urlencoded
```

**Request Body:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| email | string | Yes | User email address |
| password | string | Yes | User password |
| _token | string | Yes | CSRF token |

**Response:** Redirect to dashboard on success

---

## Members API

### List All Members

```http
GET /members
Authorization: Session Cookie
```

**Response:** HTML page with member listing

### Create Member

```http
POST /members
Content-Type: multipart/form-data
Authorization: Session Cookie
```

**Request Body:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| name | string | Yes | Member full name (max 255) |
| nickName | string | No | Member nickname |
| father_name | string | No | Father's name |
| phone | string | No | Phone number (BD format) |
| spouse_name | string | No | Spouse name |
| bloodType | string | No | Blood type (A+, B+, etc.) |
| kollan_id | integer | Yes | Unique Kollan ID |
| image | file | No | Profile image (jpg, png, webp, max 5MB) |

**Response:**
```json
{
  "success": true,
  "message": "সফলভাবে সদস্য তৈরি হয়েছে।",
  "member": { ... }
}
```

### Update Member

```http
PUT /members/{id}
Content-Type: multipart/form-data
Authorization: Session Cookie
```

### Delete Member

```http
DELETE /members/{id}
Authorization: Session Cookie
```

**Required Permission:** `delete member`

### Search Members

```http
POST /members/search
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "search_text": "member name or ID"
}
```

**Rate Limit:** 30 requests per minute

---

## Khedmots (Donations) API

### List Donations

```http
GET /khedmots
Authorization: Session Cookie
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| date | date | Filter by date |
| search_text | string | Search by member name |

### Create Donation

```http
POST /khedmots
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "date": "2025-01-15",
  "member_id": 1,
  "program_id": 1,
  "khedmot_amount": 500,
  "manat_amount": 100,
  "kalyan_amount": 50,
  "rent_amount": 0
}
```

**Validation Rules:**
- Cannot create duplicate entry for same member + program
- All amounts must be non-negative integers

### Update Donation

```http
PUT /khedmots/{id}
Authorization: Session Cookie
```

### Delete Donation

```http
DELETE /khedmots/{id}
Authorization: Session Cookie
```

**Required Permission:** `delete khedmot`

---

## Fund Collection API

### Receive - List Collections

```http
GET /fund_collections/receive
Authorization: Session Cookie
```

### Receive - Submit Collection

```http
POST /fund_collections/receive/store
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "date": "2025-01-15",
  "program_id": 1,
  "khedmot_ids": [1, 2, 3, 4, 5],
  "total_amount": 2500
}
```

### Receive - Approve Collection

```http
POST /fund_collections/receive/collect/{id}
Authorization: Session Cookie
```

**Required Role:** Admin or Super Admin

### Pay - List Disbursements

```http
GET /fund_collections/pay
Authorization: Session Cookie
```

### Pay - Create Disbursement

```http
POST /fund_collections/pay/store
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "date": "2025-01-15",
  "pay_to": "Recipient Name",
  "total_paid": 1000,
  "notes": "Optional notes"
}
```

---

## Users API

### List Users

```http
GET /dashboard/users
Authorization: Session Cookie
```

**Required Permission:** `view user`

### Create User

```http
POST /dashboard/users
Content-Type: application/json
Authorization: Session Cookie
```

**Required Permission:** `create user`

**Request Body:**
```json
{
  "name": "User Name",
  "username": "username",
  "email": "user@example.com",
  "phone": "01700000000",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "Staff"
}
```

### Assign Members to User

```http
POST /dashboard/users/{id}/assign-members
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "member_ids": [1, 2, 3, 4, 5]
}
```

---

## Program Types API

### List Program Types

```http
GET /dashboard/program-types
Authorization: Session Cookie
```

### Create Program Type

```http
POST /dashboard/program-types
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "name": "মাসিক সভা",
  "slug": "monthly-meeting",
  "date": "2025-01-15",
  "status": 1
}
```

---

## Reports API

### Generate User-wise Report

```http
POST /reports/user-wise-report
Content-Type: application/json
Authorization: Session Cookie
```

**Request Body:**
```json
{
  "user_id": 1,
  "program_id": 1,
  "start_date": "2025-01-01",
  "end_date": "2025-01-31",
  "format": "pdf"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Resource not found."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message in Bengali"]
  }
}
```

### 429 Too Many Requests
```json
{
  "message": "Too many requests. Please slow down."
}
```

---

## Rate Limiting

| Endpoint Type | Limit |
|---------------|-------|
| Store/Update Operations | 60 requests/minute |
| Search Operations | 30 requests/minute |
| General API | 60 requests/minute |

---

## Bengali Error Messages

All validation error messages are provided in Bengali for user-friendly experience:

| Field | English | Bengali |
|-------|---------|---------|
| name | Name is required | নাম আবশ্যক |
| phone | Invalid phone format | সঠিক ফোন নম্বর দিন |
| email | Email already taken | এই ইমেইল ইতিমধ্যে ব্যবহৃত হয়েছে |
| kollan_id | Must be unique | এই কল্যাণ আইডি ইতিমধ্যে ব্যবহৃত হয়েছে |
