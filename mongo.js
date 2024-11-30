const mongoose = require('mongoose');

// Connexion à MongoDB avec Mongoose
const connectDB = async () => {
  try {
    await mongoose.connect('mongodb+srv://wahi2024:wahi2024cluster0.3les9.mongodb.net/cali_bebe', {
   
    });
    console.log('MongoDB connecté');
  } catch (error) {
    console.error('Erreur de connexion à MongoDB:', error);
    process.exit(1); // Quitter l'application en cas d'erreur de connexion
  }
};

// Schéma Mongoose pour les notifications
const notificationSchema = new mongoose.Schema({
  message: { type: String, required: true },
  timestamp: { type: Date, default: Date.now } // Utilisation de la date actuelle
});

const Notification = mongoose.model('Notification', notificationSchema);

// Exporter la connexion et le modèle
module.exports = { connectDB, Notification };

