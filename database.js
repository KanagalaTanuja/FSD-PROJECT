const path = require('path');
const fs = require('fs');
const bcrypt = require('bcryptjs');

const DB_PATH = path.join(__dirname, 'campus_events.db');

let db = null;
let SqlJs = null;

// Save DB to disk after every write
function saveDb() {
  if (db) {
    const data = db.export();
    fs.writeFileSync(DB_PATH, Buffer.from(data));
  }
}

// Thin compatibility wrapper so the rest of the code works exactly as before
function makeWrapper(sqljs_db) {
  return {
    prepare(sql) {
      return {
        run(...args) {
          const params = args.flat();
          sqljs_db.run(sql, params);
          saveDb();
          return { changes: sqljs_db.getRowsModified() };
        },
        get(...args) {
          const params = args.flat();
          const stmt = sqljs_db.prepare(sql);
          try {
            stmt.bind(params);
            if (stmt.step()) {
              return stmt.getAsObject();
            }
            return undefined;
          } finally {
            stmt.free();
          }
        },
        all(...args) {
          const params = args.flat();
          const stmt = sqljs_db.prepare(sql);
          const rows = [];
          try {
            stmt.bind(params);
            while (stmt.step()) {
              rows.push(stmt.getAsObject());
            }
          } finally {
            stmt.free();
          }
          return rows;
        }
      };
    },
    exec(sql) {
      sqljs_db.run(sql);
      saveDb();
    },
    pragma(str) {
      try { sqljs_db.run(`PRAGMA ${str}`); } catch (e) {}
    }
  };
}

async function initializeDatabase() {
  // Load sql.js
  const initSqlJs = require('sql.js');
  SqlJs = await initSqlJs();

  // Load existing DB from disk or create new
  let sqljs_db;
  if (fs.existsSync(DB_PATH)) {
    const fileBuffer = fs.readFileSync(DB_PATH);
    sqljs_db = new SqlJs.Database(fileBuffer);
  } else {
    sqljs_db = new SqlJs.Database();
  }

  db = makeWrapper(sqljs_db);

  // Schema
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id TEXT PRIMARY KEY,
      name TEXT NOT NULL,
      email TEXT UNIQUE NOT NULL,
      password TEXT NOT NULL,
      role TEXT NOT NULL DEFAULT 'student',
      department TEXT,
      year TEXT,
      avatar_color TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  `);

  db.exec(`
    CREATE TABLE IF NOT EXISTS categories (
      id TEXT PRIMARY KEY,
      name TEXT UNIQUE NOT NULL,
      color TEXT NOT NULL,
      icon TEXT NOT NULL
    )
  `);

  db.exec(`
    CREATE TABLE IF NOT EXISTS events (
      id TEXT PRIMARY KEY,
      title TEXT NOT NULL,
      description TEXT NOT NULL,
      category_id TEXT NOT NULL,
      date TEXT NOT NULL,
      time TEXT NOT NULL,
      end_time TEXT,
      venue TEXT NOT NULL,
      capacity INTEGER NOT NULL DEFAULT 100,
      registered_count INTEGER NOT NULL DEFAULT 0,
      image_url TEXT,
      organizer_id TEXT NOT NULL,
      organizer_name TEXT NOT NULL,
      tags TEXT,
      status TEXT NOT NULL DEFAULT 'upcoming',
      is_featured INTEGER NOT NULL DEFAULT 0,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  `);

  db.exec(`
    CREATE TABLE IF NOT EXISTS registrations (
      id TEXT PRIMARY KEY,
      event_id TEXT NOT NULL,
      user_id TEXT NOT NULL,
      status TEXT NOT NULL DEFAULT 'confirmed',
      registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      UNIQUE(event_id, user_id)
    )
  `);

  // Seed categories
  const catCount = db.prepare('SELECT COUNT(*) as cnt FROM categories').get();
  if (!catCount || catCount.cnt == 0) {
    const ins = db.prepare('INSERT INTO categories (id, name, color, icon) VALUES (?, ?, ?, ?)');
    [
      ['cat-1', 'Academic',    '#4F46E5', '🎓'],
      ['cat-2', 'Sports',      '#059669', '⚽'],
      ['cat-3', 'Cultural',    '#D97706', '🎭'],
      ['cat-4', 'Technology',  '#0891B2', '💻'],
      ['cat-5', 'Workshop',    '#7C3AED', '🔧'],
      ['cat-6', 'Social',      '#DB2777', '🎉'],
      ['cat-7', 'Career',      '#B45309', '💼'],
      ['cat-8', 'Health',      '#16A34A', '🏃'],
    ].forEach(c => ins.run(...c));
  }

  // Seed admin
  const adminExists = db.prepare("SELECT id FROM users WHERE email = 'admin@campus.edu'").get();
  if (!adminExists) {
    const { v4: uuidv4 } = require('uuid');
    const hashed = bcrypt.hashSync('Admin@123', 10);
    db.prepare(`INSERT INTO users (id, name, email, password, role, department, avatar_color)
                VALUES (?, ?, ?, ?, ?, ?, ?)`)
      .run(uuidv4(), 'Campus Admin', 'admin@campus.edu', hashed, 'admin', 'Administration', '#4F46E5');
  }

  // Seed events
  const evCount = db.prepare('SELECT COUNT(*) as cnt FROM events').get();
  if (!evCount || evCount.cnt == 0) {
    const { v4: uuidv4 } = require('uuid');
    const admin = db.prepare("SELECT id FROM users WHERE email = 'admin@campus.edu'").get();
    const ins = db.prepare(`
      INSERT INTO events (id, title, description, category_id, date, time, end_time,
        venue, capacity, organizer_id, organizer_name, tags, status, is_featured)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `);
    [
      [uuidv4(),'Annual Tech Symposium 2025','A grand gathering of technology enthusiasts, industry experts, and students. Features keynote speeches, panel discussions, hackathons, and cutting-edge project showcases.','cat-4','2025-03-15','09:00','18:00','Main Auditorium, Block A',500,admin.id,'Campus Admin','technology,innovation,networking','upcoming',1],
      [uuidv4(),'Inter-Department Football Championship','The biggest football tournament of the year! Departments compete head-to-head in an exciting knockout format. Prizes worth ₹50,000 up for grabs.','cat-2','2025-03-20','08:00','17:00','Main Sports Ground',1000,admin.id,'Campus Admin','sports,football,championship','upcoming',1],
      [uuidv4(),'Cultural Fest - Utsav 2025','Three days of music, dance, drama, and art. Features competitions, celebrity performances, food stalls, and much more!','cat-3','2025-04-05','10:00','22:00','Open Air Theatre',2000,admin.id,'Campus Admin','cultural,music,dance,fest','upcoming',1],
      [uuidv4(),'Machine Learning Workshop','Hands-on workshop covering supervised learning, neural networks, and model deployment. Bring your laptop!','cat-5','2025-03-10','10:00','16:00','Computer Lab 3, Block B',60,admin.id,'Campus Admin','ml,ai,python,workshop','upcoming',0],
      [uuidv4(),'Campus Career Fair 2025','Over 50 top companies recruiting! Bring your resume, get on-spot interviews, and land your dream job or internship.','cat-7','2025-03-25','09:00','17:00','Convention Hall, Admin Block',800,admin.id,'Campus Admin','career,placement,jobs,internship','upcoming',1],
      [uuidv4(),'Research Paper Presentation','Present your research to a panel of professors and industry experts. Best papers will be published in the campus journal.','cat-1','2025-03-18','10:00','15:00','Seminar Hall 1',150,admin.id,'Campus Admin','research,academic,paper','upcoming',0],
      [uuidv4(),'Yoga & Wellness Camp','Start your day with a guided yoga session followed by a talk on mental health and nutrition.','cat-8','2025-03-12','06:30','08:30','Indoor Sports Complex',200,admin.id,'Campus Admin','yoga,wellness,health,mindfulness','upcoming',0],
      [uuidv4(),'Entrepreneurship Bootcamp','2-day intensive bootcamp with startup mentors, VCs, and successful founders. Pitch your idea and win seed funding!','cat-7','2025-04-10','09:00','18:00','Innovation Hub, Block C',100,admin.id,'Campus Admin','startup,entrepreneurship,funding','upcoming',0],
      [uuidv4(),'Photography Exhibition','Annual student photography exhibition showcasing the best captures from across campus.','cat-3','2025-03-08','11:00','19:00','Art Gallery, Admin Block',300,admin.id,'Campus Admin','photography,art,exhibition','completed',0],
      [uuidv4(),'Web Development Hackathon','24-hour hackathon to build full-stack web applications. Teams of 2-4. Prizes for top 3 teams.','cat-4','2025-04-15','09:00','09:00','Computer Lab Complex',120,admin.id,'Campus Admin','hackathon,webdev,coding','upcoming',1],
    ].forEach(ev => ins.run(...ev));
  }

  console.log('✅ Database initialized successfully');
  return db;
}

function getDb() {
  if (!db) throw new Error('Database not initialized. Call initializeDatabase() first.');
  return db;
}

module.exports = { getDb, initializeDatabase, saveDb };
