# 🏗️ Architecture Documentation

This document provides a detailed overview of the Student Management System architecture, including system design, data flow, and component interactions.

---

## 📑 Table of Contents

- [System Overview](#system-overview)
- [Architecture Patterns](#architecture-patterns)
- [Detailed Architecture Diagrams](#detailed-architecture-diagrams)
- [Data Flow](#data-flow)
- [Authentication Flow](#authentication-flow)
- [Authorization Model](#authorization-model)
- [Database Design](#database-design)
- [Security Architecture](#security-architecture)

---

## System Overview

The Student Management System follows a **Model-View-Controller (MVC)** architecture pattern, implemented using Laravel's framework conventions. The system is designed with separation of concerns, making it maintainable and scalable.

### Architecture Layers

```
┌─────────────────────────────────────────────────────────┐
│                    Presentation Layer                    │
│              (Blade Templates / Views)                   │
└─────────────────────────────────────────────────────────┘
                          ↕
┌─────────────────────────────────────────────────────────┐
│                    Controller Layer                     │
│         (Business Logic / Request Handling)             │
└─────────────────────────────────────────────────────────┘
                          ↕
┌─────────────────────────────────────────────────────────┐
│                      Model Layer                         │
│            (Data Access / Eloquent ORM)                  │
└─────────────────────────────────────────────────────────┘
                          ↕
┌─────────────────────────────────────────────────────────┐
│                    Database Layer                        │
│              (MySQL / PostgreSQL)                        │
└─────────────────────────────────────────────────────────┘
```

---

## Architecture Patterns

### 1. MVC Pattern

The application follows Laravel's MVC pattern:

- **Models**: Represent data structures (`User`, `Student`)
- **Views**: Blade templates for presentation
- **Controllers**: Handle HTTP requests and business logic

### 2. Repository Pattern (Implicit)

Data access is abstracted through Eloquent models, providing a clean interface to the database.

### 3. Middleware Pattern

Request filtering and authentication handled through middleware stack.

---

## Detailed Architecture Diagrams

### Complete System Architecture

```mermaid
graph TB
    subgraph "Client Tier"
        Browser[Web Browser<br/>Bootstrap UI]
    end
    
    subgraph "Web Server Tier"
        Server[Laravel Application Server<br/>Port 8000]
    end
    
    subgraph "Application Tier"
        subgraph "Routing Layer"
            Routes[Route Service Provider<br/>web.php]
        end
        
        subgraph "Middleware Stack"
            CSRF[CSRF Protection]
            AuthMW[Authentication Middleware]
            SessionMW[Session Middleware]
        end
        
        subgraph "Controller Layer"
            AuthCtrl[AuthController<br/>Login/Logout]
            StudentCtrl[ResourceController<br/>Student CRUD]
            UserCtrl[UserController<br/>Registration]
            DashCtrl[DashboardController<br/>Role Dashboards]
            EmailCtrl[EmailController<br/>Email Service]
        end
        
        subgraph "Service Layer"
            AuthService[Authentication Service]
            MailService[Mail Service]
            SessionService[Session Service]
        end
        
        subgraph "Model Layer"
            UserModel[User Model<br/>Eloquent ORM]
            StudentModel[Student Model<br/>Eloquent ORM]
        end
    end
    
    subgraph "Data Tier"
        DB[(MySQL Database<br/>Users & Students)]
        SessionStore[(Session Storage<br/>File/Cache)]
        CacheStore[(Cache Store<br/>Redis/Memcached)]
    end
    
    Browser -->|HTTP/HTTPS| Server
    Server --> Routes
    Routes --> CSRF
    CSRF --> SessionMW
    SessionMW --> AuthMW
    AuthMW --> AuthCtrl
    AuthMW --> StudentCtrl
    AuthMW --> UserCtrl
    AuthMW --> DashCtrl
    AuthMW --> EmailCtrl
    
    AuthCtrl --> AuthService
    StudentCtrl --> StudentModel
    UserCtrl --> UserModel
    EmailCtrl --> MailService
    
    AuthService --> UserModel
    StudentModel --> UserModel
    UserModel --> DB
    StudentModel --> DB
    
    AuthService --> SessionService
    SessionService --> SessionStore
    MailService --> CacheStore
```

### Request-Response Cycle

```mermaid
sequenceDiagram
    participant U as User
    participant B as Browser
    participant R as Routes
    participant MW as Middleware
    participant C as Controller
    participant M as Model
    participant DB as Database
    participant V as View
    participant S as Session
    
    U->>B: Enter URL / Submit Form
    B->>R: HTTP Request
    R->>MW: Route Matched
    MW->>MW: CSRF Check
    MW->>MW: Auth Check
    MW->>S: Get Session Data
    S-->>MW: Session Data
    MW->>C: Request Passed
    C->>M: Query/Update Data
    M->>DB: SQL Query
    DB-->>M: Result Set
    M-->>C: Model Instance
    C->>C: Business Logic
    C->>S: Update Session
    C->>V: Render View
    V-->>C: HTML Response
    C-->>R: HTTP Response
    R-->>B: HTML/JSON
    B-->>U: Display Page
```

### Authentication Flow (Detailed)

```mermaid
sequenceDiagram
    participant U as User
    participant L as Login Form
    participant AC as AuthController
    participant AS as Auth Service
    participant UM as User Model
    participant DB as Database
    participant S as Session
    participant D as Dashboard
    
    U->>L: Enter Credentials
    L->>AC: POST /login
    AC->>AC: Validate Input
    AC->>AS: Auth::attempt()
    AS->>UM: Query User
    UM->>DB: SELECT * FROM users
    DB-->>UM: User Record
    UM-->>AS: User Model
    AS->>AS: Verify Password
    alt Password Valid
        AS->>S: Create Session
        AS->>AC: Auth Success
        AC->>UM: Get User Role
        alt Role = Admin
            AC->>D: Redirect /admin/dashboard
        else Role = Teacher
            AC->>S: Store teacher_id
            AC->>D: Redirect /teacher/dashboard
        else Role = Student
            AC->>D: Redirect /student/dashboard
        end
        D-->>U: Show Dashboard
    else Password Invalid
        AS-->>AC: Auth Failed
        AC-->>L: Show Error
        L-->>U: Error Message
    end
```

### Student CRUD Operations Flow

```mermaid
graph TB
    subgraph "Create Student"
        C1[Teacher clicks<br/>Add Student] --> C2[GET /students/create]
        C2 --> C3[Show Form]
        C3 --> C4[POST /students]
        C4 --> C5[Validate Input]
        C5 --> C6[Create Student Model]
        C6 --> C7[Save to Database]
        C7 --> C8[Redirect to List]
    end
    
    subgraph "Read Students"
        R1[Teacher clicks<br/>View Students] --> R2[GET /students]
        R2 --> R3[Get teacher_id from Session]
        R3 --> R4[Query Students WHERE teacher_id]
        R4 --> R5[Return View with Data]
    end
    
    subgraph "Update Student"
        U1[Teacher clicks<br/>Edit] --> U2[GET /students/edit?id=X]
        U2 --> U3[Load Student]
        U3 --> U4[Verify Ownership]
        U4 --> U5[Show Edit Form]
        U5 --> U6[PATCH /students/X]
        U6 --> U7[Validate & Update]
        U7 --> U8[Save Changes]
    end
    
    subgraph "Delete Student"
        D1[Teacher clicks<br/>Delete] --> D2[DELETE /students/X]
        D2 --> D3[Verify Ownership]
        D3 --> D4[Delete from DB]
        D4 --> D5[Redirect to List]
    end
```

### Email System Architecture

```mermaid
graph LR
    User[User] --> Form[Email Form<br/>/sendMail]
    Form --> Submit[POST /send-mail]
    Submit --> EC[EmailController]
    EC --> Validate[Validate Input]
    Validate --> Mail[Mail::to]
    Mail --> WE[WelcomeEmail<br/>Mailable]
    WE --> Config[Mail Configuration<br/>SMTP/Sendmail]
    Config --> Queue[Queue System<br/>Optional]
    Queue --> SMTP[SMTP Server]
    SMTP --> Recipient[Recipient Email]
```

---

## Data Flow

### Student Creation Flow

```mermaid
flowchart TD
    Start([Teacher wants to add student]) --> Form[Access Create Form]
    Form --> Fill[Fill Student Details]
    Fill --> Submit[Submit Form POST /students]
    Submit --> Validate{Validate Input}
    Validate -->|Invalid| Error[Show Validation Errors]
    Error --> Fill
    Validate -->|Valid| GetSession[Get teacher_id from Session]
    GetSession --> CreateModel[Create Student Model Instance]
    CreateModel --> SetFields[Set: name, class, phone, state, teacher_id]
    SetFields --> Save[Save to Database]
    Save --> Success[Redirect to /students]
    Success --> List[Display Student List]
```

### Student Access Control Flow

```mermaid
flowchart TD
    Request[Request to View/Edit Student] --> CheckAuth{Authenticated?}
    CheckAuth -->|No| RedirectLogin[Redirect to Login]
    CheckAuth -->|Yes| GetStudent[Get Student from DB]
    GetStudent --> GetTeacherID[Get teacher_id from Session]
    GetTeacherID --> Compare{Session teacher_id ==<br/>Student teacher_id?}
    Compare -->|No| Deny[Access Denied<br/>Redirect to /students]
    Compare -->|Yes| Allow[Allow Access<br/>Show Student Data]
```

---

## Authentication Flow

### Login Process

```
┌─────────────────────────────────────────────────────────┐
│                   1. User Access                        │
│              GET /login → Login Form                     │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│             2. Submit Credentials                       │
│         POST /login (email, password)                   │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│             3. Input Validation                         │
│    Validate email format, password required              │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│           4. Authentication Check                       │
│    Auth::attempt() → Query DB → Verify Password         │
└─────────────────────────────────────────────────────────┘
                          ↓
                    ┌─────┴─────┐
                    │           │
              ┌─────▼─────┐ ┌──▼──────┐
              │  Success  │ │  Fail   │
              └─────┬─────┘ └──┬──────┘
                    │           │
        ┌───────────┼───────────┼───────────┐
        │           │           │           │
┌───────▼───┐ ┌─────▼────┐ ┌───▼────┐ ┌───▼────┐
│   Admin   │ │ Teacher  │ │Student │ │ Error  │
└───────┬───┘ └─────┬────┘ └───┬────┘ └───┬────┘
        │           │           │         │
        └───────────┴───────────┴─────────┘
                    │
        ┌───────────▼───────────┐
        │  Create Session       │
        │  Store user data      │
        └───────────┬───────────┘
                    │
        ┌───────────▼───────────┐
        │  Role-Based Redirect  │
        │  /admin/dashboard     │
        │  /teacher/dashboard   │
        │  /student/dashboard   │
        └───────────────────────┘
```

### Session Management

```mermaid
graph TB
    Login[User Logs In] --> CreateSession[Create Session]
    CreateSession --> StoreData[Store in Session:<br/>- teacher_id<br/>- teacher_name<br/>- user object]
    StoreData --> UseSession[Use Session Data<br/>in Controllers]
    UseSession --> CheckExpiry{Session Valid?}
    CheckExpiry -->|Yes| Continue[Continue Request]
    CheckExpiry -->|No| Redirect[Redirect to Login]
    Continue --> Logout[User Logs Out]
    Logout --> DestroySession[Destroy Session]
    DestroySession --> ClearData[Clear All Session Data]
```

---

## Authorization Model

### Role-Based Access Control (RBAC)

```
┌─────────────────────────────────────────────────────────┐
│                      User Roles                          │
└─────────────────────────────────────────────────────────┘
         │              │              │
    ┌────▼────┐    ┌────▼────┐    ┌────▼────┐
    │  Admin  │    │ Teacher │    │ Student │
    └────┬────┘    └────┬────┘    └────┬────┘
         │              │              │
    ┌────▼────────────────────────────────────┐
    │         Permission Matrix               │
    ├─────────────────────────────────────────┤
    │ Feature        │ Admin │ Teacher │ Student│
    ├────────────────┼───────┼─────────┼────────┤
    │ View All Users │   ✓   │    ✗    │   ✗    │
    │ Create User    │   ✓   │    ✗    │   ✗    │
    │ View Students  │   ✓   │   ✓*   │   ✗    │
    │ Create Student │   ✓   │   ✓    │   ✗    │
    │ Edit Student   │   ✓   │   ✓*   │   ✗    │
    │ Delete Student │   ✓   │   ✓*   │   ✗    │
    │ View Dashboard │   ✓   │   ✓    │   ✓    │
    │ Send Email     │   ✓   │   ✓    │   ✗    │
    └────────────────┴───────┴─────────┴────────┘
    
    * = Only own students
```

### Access Control Implementation

```mermaid
graph TB
    Request[Incoming Request] --> CheckRoute{Route Protected?}
    CheckRoute -->|No| Allow[Allow Access]
    CheckRoute -->|Yes| CheckAuth{User Authenticated?}
    CheckAuth -->|No| RedirectLogin[Redirect to Login]
    CheckAuth -->|Yes| CheckRole{Check User Role}
    CheckRole -->|Admin| AdminAccess[Full Access]
    CheckRole -->|Teacher| TeacherAccess[Teacher Access]
    CheckRole -->|Student| StudentAccess[Student Access]
    
    TeacherAccess --> CheckOwnership{Own Resource?}
    CheckOwnership -->|Yes| Allow
    CheckOwnership -->|No| Deny[Access Denied]
    
    AdminAccess --> Allow
    StudentAccess --> Allow
```

---

## Database Design

### Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        bigint teacher_id PK "Primary Key"
        string name "User Name"
        string email UK "Unique Email"
        string password "Hashed Password"
        string role "admin|teacher|student"
        timestamp created_at
        timestamp updated_at
    }
    
    STUDENTS {
        bigint id PK "Primary Key"
        bigint teacher_id FK "Foreign Key"
        string name "Student Name"
        string class "Class/Grade"
        string phonenumber "10 digits"
        string state "State"
        timestamp created_at
        timestamp updated_at
    }
    
    SESSIONS {
        string id PK "Session ID"
        bigint user_id FK "User ID"
        text payload "Session Data"
        int last_activity "Timestamp"
    }
    
    USERS ||--o{ STUDENTS : "has many"
    USERS ||--o{ SESSIONS : "has many"
```

### Database Schema Details

#### Users Table Structure
```sql
CREATE TABLE users (
    teacher_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'teacher',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### Students Table Structure
```sql
CREATE TABLE student (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    teacher_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(255) NOT NULL,
    phonenumber VARCHAR(10) NOT NULL,
    state VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(teacher_id) ON DELETE CASCADE
);
```

### Indexes and Constraints

- **Primary Keys**: `users.teacher_id`, `student.id`
- **Foreign Keys**: `student.teacher_id` → `users.teacher_id`
- **Unique Constraints**: `users.email`
- **Indexes**: Email (for fast lookups), teacher_id (for joins)

---

## Security Architecture

### Security Layers

```mermaid
graph TB
    subgraph "Layer 1: Network Security"
        HTTPS[HTTPS/SSL]
        Firewall[Firewall Rules]
    end
    
    subgraph "Layer 2: Application Security"
        CSRF[CSRF Protection]
        XSS[XSS Prevention]
        SQLInjection[SQL Injection Prevention]
    end
    
    subgraph "Layer 3: Authentication"
        PasswordHash[Password Hashing]
        SessionMgmt[Session Management]
        AuthMW[Authentication Middleware]
    end
    
    subgraph "Layer 4: Authorization"
        RBAC[Role-Based Access Control]
        Ownership[Ownership Verification]
        SignedURLs[Signed URLs]
    end
    
    subgraph "Layer 5: Data Protection"
        Validation[Input Validation]
        Sanitization[Data Sanitization]
        Encryption[Data Encryption]
    end
    
    HTTPS --> CSRF
    Firewall --> XSS
    CSRF --> PasswordHash
    XSS --> SessionMgmt
    SQLInjection --> AuthMW
    PasswordHash --> RBAC
    SessionMgmt --> Ownership
    AuthMW --> SignedURLs
    RBAC --> Validation
    Ownership --> Sanitization
    SignedURLs --> Encryption
```

### Security Flow for Sensitive Operations

```mermaid
sequenceDiagram
    participant U as User
    participant C as Controller
    participant M as Middleware
    participant S as Session
    participant DB as Database
    
    U->>C: Request Sensitive Operation
    C->>M: Check Authentication
    M->>S: Verify Session
    S-->>M: Session Valid
    M->>C: Auth Passed
    C->>C: Verify Ownership
    C->>DB: Check Resource Ownership
    DB-->>C: Ownership Confirmed
    C->>C: Execute Operation
    C-->>U: Success Response
```

---

## Component Interactions

### Controller Dependencies

```mermaid
graph LR
    subgraph "Controllers"
        AC[AuthController]
        RC[ResourceController]
        UC[UserController]
        DC[DashboardController]
        EC[EmailController]
    end
    
    subgraph "Models"
        UM[User Model]
        SM[Student Model]
    end
    
    subgraph "Services"
        AS[Auth Service]
        MS[Mail Service]
        SS[Session Service]
    end
    
    AC --> AS
    AC --> UM
    RC --> SM
    RC --> SS
    UC --> UM
    DC --> UM
    EC --> MS
    
    SM --> UM
    AS --> SS
```

---

## Deployment Architecture

### Production Environment

```mermaid
graph TB
    subgraph "Load Balancer"
        LB[Load Balancer<br/>Nginx/HAProxy]
    end
    
    subgraph "Application Servers"
        App1[Laravel App Server 1]
        App2[Laravel App Server 2]
        AppN[Laravel App Server N]
    end
    
    subgraph "Database Cluster"
        DBMaster[(Master DB)]
        DBSlave[(Slave DB)]
    end
    
    subgraph "Cache Layer"
        Redis[(Redis Cache)]
    end
    
    subgraph "Session Storage"
        SessionDB[(Session Database)]
    end
    
    subgraph "File Storage"
        Storage[File Storage<br/>S3/Local]
    end
    
    LB --> App1
    LB --> App2
    LB --> AppN
    App1 --> DBMaster
    App2 --> DBMaster
    AppN --> DBMaster
    DBMaster --> DBSlave
    App1 --> Redis
    App2 --> Redis
    AppN --> Redis
    App1 --> SessionDB
    App2 --> SessionDB
    AppN --> SessionDB
    App1 --> Storage
    App2 --> Storage
    AppN --> Storage
```

---

## Performance Considerations

### Caching Strategy

- **Session Caching**: Store sessions in Redis for faster access
- **Query Caching**: Cache frequently accessed data
- **View Caching**: Cache rendered Blade templates
- **Route Caching**: Cache route definitions in production

### Database Optimization

- **Indexes**: Proper indexing on foreign keys and frequently queried columns
- **Eager Loading**: Use Eloquent eager loading to prevent N+1 queries
- **Query Optimization**: Optimize complex queries with proper joins

---

## Future Enhancements

### Potential Architecture Improvements

1. **API Layer**: Add RESTful API with token authentication
2. **Queue System**: Implement job queues for email sending
3. **Event System**: Use Laravel events for decoupled components
4. **Repository Pattern**: Abstract data access layer
5. **Service Layer**: Extract business logic from controllers
6. **Caching Layer**: Implement Redis for session and data caching
7. **File Storage**: Migrate to cloud storage (S3)
8. **Real-time Features**: Add WebSocket support for notifications

---

## Conclusion

This architecture provides a solid foundation for the Student Management System with clear separation of concerns, security measures, and scalability considerations. The MVC pattern ensures maintainability, while the role-based access control provides security and proper user management.

