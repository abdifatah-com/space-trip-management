const express = require('express');
const http = require('http');
const { init } = require('./socketManager'); // Import init
const authRoutes = require('./routes/auth');
const messageRoutes = require('./routes/messages');
require('dotenv').config(); // To load environment variables from .env file

const app = express();
const server = http.createServer(app);
const io = init(server); // Initialize Socket.io using the manager
const path = require('path'); // Import path module

const port = process.env.PORT || 3000;

// Middleware
app.use(express.json()); // To parse JSON bodies
app.use(express.static(path.join(__dirname, 'public'))); // Serve static files from public directory

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/messages', messageRoutes);

// Serve the test client HTML file
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

io.on('connection', (socket) => {
  console.log(`User connected: ${socket.id}`);

  // Join a room based on user ID (e.g., after authentication)
  // This allows direct messaging to a user's socket ID or a room they are in.
  socket.on('join_room', (userId) => {
    socket.join(`user_${userId}`);
    console.log(`User ${socket.id} joined room user_${userId}`);
  });

  socket.on('disconnect', () => {
    console.log(`User disconnected: ${socket.id}`);
  });

  // Example: Listen for a chat message (can also be handled by API and then emitted)
  socket.on('chat_message', (msg) => {
    // For direct messages, you might emit to a specific room
    // io.to(`user_${msg.receiverId}`).emit('new_message', msg);
    console.log('message: ' + msg);
    // Broadcast to everyone else (for general chat rooms)
    // socket.broadcast.emit('new_message', msg);
  });
});

server.listen(port, () => {
  console.log(`Server listening on port ${port}`);
});
