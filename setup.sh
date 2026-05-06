#!/bin/bash
set -e

echo ""
echo "🎓 Campus Event Management — Setup"
echo "===================================="
echo ""

echo "📦 Installing backend dependencies..."
cd backend
npm install
cd ..

echo ""
echo "📦 Installing frontend dependencies..."
cd frontend
npm install
cd ..

echo ""
echo "✅ Setup complete!"
echo ""
echo "🚀 To start development:"
echo ""
echo "   Terminal 1 (Backend):"
echo "   cd backend && npm run dev"
echo ""
echo "   Terminal 2 (Frontend):"
echo "   cd frontend && npm run dev"
echo ""
echo "🌐 Frontend: http://localhost:5173"
echo "🔌 Backend:  http://localhost:5000"
echo ""
echo "👤 Default admin credentials:"
echo "   Email:    admin@campus.edu"
echo "   Password: Admin@123"
echo ""
