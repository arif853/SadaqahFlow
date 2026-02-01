# SadaqahFlow Database Schema Documentation

## Overview

SadaqahFlow uses MySQL as its primary database with Laravel's Eloquent ORM for data access. This document provides a comprehensive overview of all database tables, their relationships, and schema details.

---

## Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATABASE RELATIONSHIPS                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   ┌──────────┐    1:N    ┌───────────────┐    N:1    ┌──────────────┐       │
│   │  users   │◄─────────►│ member_assigns│◄─────────►│   members    │       │
│   └────┬─────┘           └───────────────┘           └──────┬───────┘       │
│        │                                                     │               │
│        │ 1:N                                            1:N  │               │
│        ▼                                                     ▼               │
│   ┌────────────────────────────────────────────────────────────┐            │
│   │                        khedmots                             │            │
│   │  (Central donation tracking - links users, members,         │            │
│   │   programs, and collection status)                          │            │
│   └────────────────────────────────────────────────────────────┘            │
│        │                          │                                          │
│        │ N:1                      │ N:1                                      │
│        ▼                          ▼                                          │
│   ┌──────────┐              ┌──────────────┐                                │
│   │ receives │              │ program_types│                                │
│   └──────────┘              └──────────────┘                                │
│        │                                                                     │
│   ┌────┴────┐                                                               │
│   │  pays   │                                                               │
│   └─────────┘                                                               │
│                                                                              │
│   ┌──────────┐              ┌──────────────┐      ┌───────────────┐         │
│   │user_logs │              │    roles     │◄────►│  permissions  │         │
│   └──────────┘              └──────────────┘      └───────────────┘         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Core Tables

### users

System users including administrators, staff, and collectors.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| name | varchar(255) | No | - | Full name |
| username | varchar(255) | Yes | NULL | Unique username |
| email | varchar(255) | No | - | Unique email |
| phone | varchar(20) | Yes | NULL | Phone number |
| bloodType | varchar(10) | Yes | NULL | Blood type |
| status | tinyint(1) | No | 1 | Active status |
| email_verified_at | timestamp | Yes | NULL | Email verification |
| password | varchar(255) | No | - | Hashed password |
| remember_token | varchar(100) | Yes | NULL | Remember me token |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE (`email`)
- UNIQUE (`username`)

**Relationships:**
- Has many `khedmots` (as collector)
- Has many `member_assigns`
- Has many `user_logs`
- Belongs to many `roles` (Spatie Permission)

---

### members

Organization members (devotees/Zakers) who make donations.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| name | varchar(255) | No | - | Full name |
| nickName | varchar(255) | Yes | NULL | Nickname/title |
| father_name | varchar(255) | Yes | NULL | Father's name |
| phone | varchar(20) | Yes | NULL | Phone number |
| spouse_name | varchar(255) | Yes | NULL | Spouse's name |
| bloodType | varchar(10) | Yes | NULL | Blood type |
| kollan_id | int unsigned | No | - | Unique member ID |
| image | varchar(255) | Yes | NULL | Profile image path |
| status | tinyint(1) | No | 1 | Active status |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE (`kollan_id`)

**Relationships:**
- Has many `khedmots`
- Belongs to many `users` through `member_assigns`

---

### khedmots

Individual donation records linking members, programs, and collectors.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| date | date | No | - | Donation date |
| member_id | bigint unsigned | No | - | FK to members |
| program_id | bigint unsigned | No | - | FK to program_types |
| user_id | bigint unsigned | No | - | FK to users (collector) |
| khedmot_amount | int | No | 0 | Khedmot donation amount |
| manat_amount | int | No | 0 | Manat (vow) amount |
| kalyan_amount | int | No | 0 | Kalyan (welfare) amount |
| rent_amount | int | No | 0 | Rent amount |
| is_collected | tinyint(1) | No | 0 | Collection status |
| receive_id | bigint unsigned | Yes | NULL | FK to receives |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`member_id`)
- INDEX (`program_id`)
- INDEX (`user_id`)
- UNIQUE (`member_id`, `program_id`) - Prevents duplicates

**Relationships:**
- Belongs to `member`
- Belongs to `program_type`
- Belongs to `user` (collector)
- Belongs to `receive` (when collected)

---

### program_types

Religious programs and events for which donations are collected.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| name | varchar(255) | No | - | Program name |
| slug | varchar(255) | No | - | URL-friendly slug |
| date | date | Yes | NULL | Program date |
| status | tinyint(1) | No | 1 | Active status |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE (`slug`)

**Relationships:**
- Has many `khedmots`

---

### receives

Fund collection submissions from collectors awaiting approval.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| date | date | No | - | Submission date |
| program_id | bigint unsigned | No | - | FK to program_types |
| total_amount | decimal(10,2) | No | - | Total collected amount |
| status | enum | No | 'pending' | pending/collected/canceled |
| submitted_by | bigint unsigned | No | - | FK to users (submitter) |
| collected_by | bigint unsigned | Yes | NULL | FK to users (approver) |
| canceled_by | bigint unsigned | Yes | NULL | FK to users (canceler) |
| collected_at | timestamp | Yes | NULL | Approval timestamp |
| canceled_at | timestamp | Yes | NULL | Cancellation timestamp |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`program_id`)
- INDEX (`submitted_by`)
- INDEX (`status`)

**Relationships:**
- Belongs to `program_type`
- Belongs to `user` as submitted_by
- Belongs to `user` as collected_by
- Belongs to `user` as canceled_by
- Has many `khedmots`

---

### pays

Fund disbursement/expense records.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| date | date | No | - | Payment date |
| pay_to | varchar(255) | No | - | Recipient name |
| total_paid | decimal(10,2) | No | - | Amount paid |
| notes | text | Yes | NULL | Optional notes |
| paid_by | bigint unsigned | No | - | FK to users |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`paid_by`)

**Relationships:**
- Belongs to `user` as paid_by

---

### member_assigns

Junction table for many-to-many relationship between users and members.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| user_id | bigint unsigned | No | - | FK to users |
| member_id | bigint unsigned | No | - | FK to members |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE (`user_id`, `member_id`)

**Relationships:**
- Belongs to `user`
- Belongs to `member`

---

### user_logs

Login activity tracking for audit purposes.

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | bigint unsigned | No | AUTO_INCREMENT | Primary key |
| user_id | bigint unsigned | No | - | FK to users |
| last_login_ip | varchar(45) | Yes | NULL | IP address |
| last_login_at | timestamp | Yes | NULL | Login timestamp |
| created_at | timestamp | Yes | NULL | Created timestamp |
| updated_at | timestamp | Yes | NULL | Updated timestamp |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`user_id`)

**Relationships:**
- Belongs to `user`

---

## Spatie Permission Tables

These tables are created by the Spatie Laravel Permission package.

### roles

| Column | Type | Description |
|--------|------|-------------|
| id | bigint unsigned | Primary key |
| name | varchar(255) | Role name |
| guard_name | varchar(255) | Auth guard |
| created_at | timestamp | Created timestamp |
| updated_at | timestamp | Updated timestamp |

### permissions

| Column | Type | Description |
|--------|------|-------------|
| id | bigint unsigned | Primary key |
| name | varchar(255) | Permission name |
| guard_name | varchar(255) | Auth guard |
| created_at | timestamp | Created timestamp |
| updated_at | timestamp | Updated timestamp |

### model_has_roles

| Column | Type | Description |
|--------|------|-------------|
| role_id | bigint unsigned | FK to roles |
| model_type | varchar(255) | Model class |
| model_id | bigint unsigned | Model ID |

### model_has_permissions

| Column | Type | Description |
|--------|------|-------------|
| permission_id | bigint unsigned | FK to permissions |
| model_type | varchar(255) | Model class |
| model_id | bigint unsigned | Model ID |

### role_has_permissions

| Column | Type | Description |
|--------|------|-------------|
| permission_id | bigint unsigned | FK to permissions |
| role_id | bigint unsigned | FK to roles |

---

## Default Data

### Roles

| Role | Description |
|------|-------------|
| Super Admin | Full system access |
| Admin | Management access |
| Staff | Limited collector access |

### Permissions

```
view member, create member, update member, delete member
view khedmot, create khedmot, update khedmot, delete khedmot
view user, create user, update user, delete user
view role, create role, update role, delete role
view permission
view fund-collection, create fund-collection, update fund-collection
view report
view setting
view rent
view kollyan
view program
```

---

## Migration Order

1. `2014_10_12_000000_create_users_table.php`
2. `2014_10_12_100000_create_password_reset_tokens_table.php`
3. `create_permission_tables.php` (Spatie)
4. `create_members_table.php`
5. `create_program_types_table.php`
6. `create_khedmots_table.php`
7. `create_receives_table.php`
8. `create_pays_table.php`
9. `create_member_assigns_table.php`
10. `create_user_logs_table.php`

---

## Backup Strategy

SQL backup files are stored in the `database/` directory:
- `cpds_dk_bs_backup_10-01-25.sql`
- `cpds_dk_bs_backup_20-12-24.sql`
- `cpds_dk_bs_backup_31-12-24.sql`

**Recommended backup schedule:** Daily automated backups with 30-day retention.
