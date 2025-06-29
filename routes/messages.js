const express = require('express');
const router = express.Router();
const auth = require('../middleware/auth');
const db = require('../config/db');
const { getIO } = require('../socketManager'); // We'll create this helper

// Send message
router.post('/send-message', auth, async (req, res) => {
  const { receiver_id, message } = req.body;
  const sender_id = req.user.id;

  if (!receiver_id || !message) {
    return res.status(400).json({ msg: 'Please provide receiver_id and message' });
  }

  try {
    // Optional: Check if receiver_id is a valid user
    const [users] = await db.execute('SELECT id FROM users WHERE id = ?', [receiver_id]);
    if (users.length === 0) {
        return res.status(400).json({ msg: 'Receiver not found' });
    }

    const [result] = await db.execute(
      'INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)',
      [sender_id, receiver_id, message]
    );
    const messageId = result.insertId;

    const [rows] = await db.execute('SELECT * FROM messages WHERE id = ?', [messageId]);
    const newMessage = rows[0];

    // Emit message via Socket.io
    const io = getIO();
    // Emit to a room specific to the receiver or a general event
    // For one-on-one, you might have users join rooms based on their user ID
    io.to(`user_${receiver_id}`).emit('new_message', newMessage);
    // Also send to sender to confirm message is sent and display in their UI
    io.to(`user_${sender_id}`).emit('new_message', newMessage);


    res.status(201).json(newMessage);
  } catch (err) {
    console.error(err.message);
    res.status(500).send('Server error');
  }
});

// Get messages between two users
router.get('/get-messages/:otherUserId', auth, async (req, res) => {
  const currentUserId = req.user.id;
  const otherUserId = req.params.otherUserId;

  try {
    const [messages] = await db.execute(
      `SELECT * FROM messages
       WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
       ORDER BY created_at ASC`,
      [currentUserId, otherUserId, otherUserId, currentUserId]
    );
    res.json(messages);
  } catch (err) {
    console.error(err.message);
    res.status(500).send('Server error');
  }
});

module.exports = router;
