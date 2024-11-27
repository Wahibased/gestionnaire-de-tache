const express = require('express');
const mongoose = require('mongoose');
require('dotenv').config();  // Charger les variables d'environnement
const app = express();

// Connexion à MongoDB
mongoose.connect(process.env.MONGO_URI)
  .then(() => console.log('MongoDB connecté'))
  .catch((error) => console.error('Erreur de connexion à MongoDB:', error));

// Schéma de notification
const notificationSchema = new mongoose.Schema({
  message: { type: String, required: true },
  timestamp: { type: Date, default: Date.now },
});
const Notification = mongoose.model('Notification', notificationSchema);

// Middleware pour servir les requêtes en JSON
app.use(express.json());

// API pour récupérer les notifications
app.get('/api/notification', async (req, res) => {
  try {
    const notifications = await Notification.find().sort({ timestamp: -1 }).limit(5);
    res.json(notifications);
  } catch (error) {
    res.status(500).send('Erreur lors de la récupération des notifications');
  }
});

// Lancer le serveur sur le port 3000
app.listen(3009, () => {
  console.log('Serveur démarré sur le port 3000');
});
