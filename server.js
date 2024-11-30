const express = require('express');
const mongoose = require('mongoose');
const app = express();
const port = 3002;

// Middleware pour parser les données JSON
app.use(express.json());

// Connexion à MongoDB
mongoose.connect('mongodb+srv://wahi2024:wahi2024@cluster0.3les9.mongodb.net/gestionnaire', {
  
})
  .then(() => console.log('MongoDB connecté'))
  .catch((error) => console.error('Erreur de connexion à MongoDB:', error));

// Schéma Mongoose pour les notifications
const notificationSchema = new mongoose.Schema({
  message: { type: String, required: true },
  timestamp: { type: Date, default: Date.now },
});

const Notification = mongoose.model('Notification', notificationSchema);



// API pour ajouter une notification
app.post('/dd-notification', async (req, res) => {
  const { message } = req.body;

  if (!message) {
    return res.status(400).send('Le champ "message" est requis.');
  }

  const notification = new Notification({ message });

  try {
    await notification.save();
    res.status(201).send('Notification ajoutée avec succès.');
  } catch (error) {
    console.error('Erreur lors de l\'ajout de la notification:', error);
    res.status(500).send('Erreur d\'ajout de notification.');
  }
});

// API pour récupérer les notifications
app.get('/get-notifications', async (req, res) => {
  try {
    const notifications = await Notification.find().sort({ timestamp: -1 }).limit(5);
    res.json(notifications);
  } catch (error) {
    console.error('Erreur lors de la récupération des notifications:', error);
    res.status(500).send('Erreur lors de la récupération des notifications.');
  }
});

// Lancer le serveur
app.listen(port, () => {
  console.log(`Serveur Node.js démarré sur le port ${port}`);
});




