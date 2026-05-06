# 🎓 Campus Event Management System

A full-stack web application for managing campus events, registrations, and student engagement. Built with Node.js/Express backend and React frontend with a polished, professional UI.

---

## ✨ Features

### Student Features
- 🔍 **Browse Events** — Search and filter by category, status, keyword
- 📋 **Event Details** — Full event info, capacity tracking, venue details
- ✅ **Registration** — One-click register/unregister with real-time capacity updates
- 📅 **My Events** — View and manage all your registrations
- 👤 **Profile** — Edit name, department, academic year

### Admin Features
- 📊 **Dashboard** — Stats overview: events, registrations, students, category breakdowns
- ➕ **Create Events** — Full event creation with categories, times, capacity, tags, featured flag
- ✏️ **Edit / Delete Events** — Full CRUD management
- 👥 **Registrations Table** — View all student registrations per event

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | React 18, React Router v6, Vite |
| **Styling** | Custom CSS with CSS Variables (no Tailwind) |
| **HTTP Client** | Axios |
| **Backend** | Node.js, Express 4 |
| **Database** | SQLite via better-sqlite3 |
| **Auth** | JWT (jsonwebtoken), bcryptjs |
| **Notifications** | react-hot-toast |
| **Icons** | lucide-react |
| **Date Formatting** | date-fns |

---

## 🚀 Getting Started

### Prerequisites
- **Node.js** v18 or later
- **npm** v8 or later

### Installation

1. **Clone / Extract** the project:
   ```bash
   cd campus-event-management
   ```

2. **Run setup script** (installs all dependencies):
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

   Or manually:
   ```bash
   cd backend && npm install && cd ..
   cd frontend && npm install && cd ..
   ```

### Development

Open **two terminals**:

**Terminal 1 — Backend:**
```bash
cd backend
npm run dev
# Server runs at http://localhost:5000
```

**Terminal 2 — Frontend:**
```bash
cd frontend
npm run dev
# App runs at http://localhost:5173
```

Then open: **http://localhost:5173**

---

## 🔑 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@campus.edu` | `Admin@123` |

You can register as a student from the signup page.

---

## 📁 Project Structure

```
campus-event-management/
├── backend/
│   ├── db/
│   │   └── database.js        # SQLite init & seeding
│   ├── middleware/
│   │   └── auth.js            # JWT middleware
│   ├── routes/
│   │   ├── auth.js            # Login, Register, Profile
│   │   ├── events.js          # CRUD events
│   │   ├── registrations.js   # Register/unregister
│   │   └── categories.js      # Categories list
│   └── server.js              # Express app entry
│
├── frontend/
│   └── src/
│       ├── components/
│       │   ├── Navbar.jsx
│       │   ├── EventCard.jsx
│       │   ├── Modal.jsx
│       │   ├── EventFormModal.jsx
│       │   └── ProtectedRoute.jsx
│       ├── context/
│       │   └── AuthContext.jsx
│       ├── pages/
│       │   ├── Home.jsx
│       │   ├── Events.jsx
│       │   ├── EventDetail.jsx
│       │   ├── Auth.jsx         # Login & Register
│       │   ├── MyEvents.jsx
│       │   ├── Profile.jsx
│       │   └── AdminDashboard.jsx
│       ├── services/
│       │   └── api.js           # Axios API service
│       ├── App.jsx
│       ├── main.jsx
│       └── index.css
│
├── setup.sh
├── package.json
└── README.md
```

---

## 🌐 API Endpoints

### Auth
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/register` | Register new student |
| POST | `/api/auth/login` | Login |
| GET | `/api/auth/me` | Get current user |
| PUT | `/api/auth/profile` | Update profile |

### Events
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/events` | List with filters (search, category, status, page) |
| GET | `/api/events/:id` | Get single event |
| GET | `/api/events/stats` | Admin stats |
| POST | `/api/events` | Create event (admin) |
| PUT | `/api/events/:id` | Update event (admin) |
| DELETE | `/api/events/:id` | Delete event (admin) |

### Registrations
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/registrations/register/:eventId` | Register for event |
| DELETE | `/api/registrations/unregister/:eventId` | Cancel registration |
| GET | `/api/registrations/my-events` | Student's registered events |
| GET | `/api/registrations/check/:eventId` | Check if registered |
| GET | `/api/registrations/event/:eventId` | All registrations for event (admin) |
| GET | `/api/registrations/all` | All registrations (admin) |

### Categories
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/categories` | List all categories |

---

## 🗄️ Database Schema

The SQLite database is auto-created and seeded on first run at `backend/db/campus_events.db`.

**Tables:** `users`, `categories`, `events`, `registrations`

**Seeded data:**
- 8 categories (Academic, Sports, Cultural, Technology, Workshop, Social, Career, Health)
- 1 admin user
- 10 sample campus events

---

## 🏗️ Production Build

```bash
# Build frontend
cd frontend && npm run build

# Set NODE_ENV and start backend (serves frontend from /dist)
cd backend
NODE_ENV=production npm start
```

The backend serves the built frontend at `http://localhost:5000`.

---

## 🎨 Design Highlights

- **Typography:** Syne (display) + DM Sans (body) — distinctive, modern pairing
- **Color Palette:** Deep navy primary with coral/red accent, cohesive CSS variable system
- **Dark hero** with animated gradient blobs and grid overlay
- **Micro-interactions:** Hover lifts, smooth transitions, capacity bar animations
- **Responsive:** Mobile-first, works great on all screen sizes
- **Toast notifications** for all user actions

---

## 📄 License

MIT — Free to use and modify.
