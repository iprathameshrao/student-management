# 🚀 Quick Start Guide

A quick reference guide to get started with the Student Management System.

---

## ⚡ Quick Setup (5 Minutes)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Run migrations
php artisan migrate

# 5. Start server
php artisan serve
```

Visit: `http://localhost:8000`

---

## 👤 Default User Roles

| Role | Access Level | Dashboard Route |
|------|-------------|-----------------|
| **Admin** | Full system access | `/admin/dashboard` |
| **Teacher** | Manage own students | `/teacher/dashboard` |
| **Student** | View own info | `/student/dashboard` |

---

## 🔑 Key Routes

### Public
- `/` - Welcome page
- `/login` - Login page
- `/createUser` - Registration

### Protected (Requires Login)
- `/teacher/dashboard` - Teacher dashboard
- `/students` - List students
- `/students/create` - Add student
- `/sendMail` - Send email

---

## 📝 Common Tasks

### Create a User
1. Go to `/createUser`
2. Fill form (name, email, password, role)
3. Submit → Redirected to login

### Login
1. Go to `/login`
2. Enter email & password
3. Auto-redirect based on role

### Add Student (Teacher)
1. Login as teacher
2. Click "Add New Student"
3. Fill form → Submit

### View Students
1. Login as teacher
2. Click "View All Students"
3. See filtered list (only your students)

---

## 🗄️ Database Quick Reference

### Users Table
- `teacher_id` (PK)
- `email` (unique)
- `role` (admin/teacher/student)

### Students Table
- `id` (PK)
- `teacher_id` (FK → users.teacher_id)
- `name`, `class`, `phonenumber`, `state`

---

## 🔧 Common Commands

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Create storage link
php artisan storage:link

# Run tests
php artisan test
```

---

## 🐛 Troubleshooting

### Issue: "Class not found"
```bash
composer dump-autoload
```

### Issue: "Route not found"
```bash
php artisan route:clear
php artisan config:clear
```

### Issue: "Database connection error"
- Check `.env` file
- Verify database credentials
- Ensure database exists

### Issue: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📚 Documentation Files

- **README.md** - Complete project documentation
- **ARCHITECTURE.md** - Detailed architecture diagrams
- **QUICK_START.md** - This file

---

## 💡 Tips

1. **Session Data**: Teacher ID is stored in session after login
2. **Ownership**: Teachers can only see/edit their own students
3. **Validation**: Phone numbers must be exactly 10 digits
4. **Security**: All forms have CSRF protection

---

## 🆘 Need Help?

- Check the main [README.md](README.md)
- Review [ARCHITECTURE.md](ARCHITECTURE.md) for system design
- Check Laravel documentation: https://laravel.com/docs

---

**Happy Coding! 🎉**

